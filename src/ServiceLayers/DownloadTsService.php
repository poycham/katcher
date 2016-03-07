<?php


namespace Katcher\ServiceLayers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\Input;
use Katcher\Data\KatcherDownload;
use Katcher\Data\KatcherUrl;
use Katcher\Exceptions\ValidatorException;

class DownloadTsService extends AbstractService
{
    /**
     * Get index view data
     *
     * @param array $flash
     * @return array
     */
    public function getIndexViewData(array $flash)
    {
        $input = Input::createFromKeys([
            'url',
            'first_part',
            'last_part'
        ], $flash['input']);

        $viewData = [
            'input' => $input,
            'errors' => $flash['errors']
        ];

        return $viewData;
    }

    /**
     * Download .ts files
     *
     * @param array $data
     * @return string
     * @throws ValidatorException
     */
    public function downloadTs(array $data)
    {
        /* validate data */
        $validator = new \Validator($data);
        $validator
            ->filter('trim')
            ->required()
            ->url()
            ->endsWith('.ts')
            ->validate('url', false, 'URL');
        $validator
            ->integer()
            ->validate('first_part', false, 'First Part');
        $validator
            ->integer()
            ->validate('last_part', false, 'Last Part');

        if ($validator->hasErrors()) {
            throw new ValidatorException($validator->getAllErrors());
        }

        /* delete duplicate directory */
        $data = $validator->getValidData();
        $katcherURL = KatcherUrl::createFromUrl($data['url']);
        $filesystem = $this->app->get('filesystem');
        $folder = $katcherURL->getBaseLastUri();


        if ($filesystem->has($folder)) {
            $filesystem->deleteDir($folder);
        }

        /* create download storage */
        $downloadStorage = DownloadStorage::create($folder, $filesystem);

        /* initialize log */
        $metaLog = DownloadMetaLog::create([
            'status' => 'downloading',
            'currentFile' => 0,
            'url' => $katcherURL->getFileURL('%i'),
            'parts' => [
                'first' => $data['first_part'],
                'last' => $data['last_part']
            ],
            'missingFiles' => [],
            'nonexistentFiles' => [],
            'downloadRetries' => 0
        ], $downloadStorage);

        /* download files */
        $guzzle = new Client();

        for ($i = $data['first_part']; $i <= $data['last_part']; $i++) {
            $metaLog->set('currentFile', $i)->save();

            $retries = 0;

            while ($retries != KatcherDownload::RETRY_LIMIT) {
                try {
                    $response =  $guzzle->request('GET', $katcherURL->getFileURL($i), [
                        'verify' => false,
                        'timeout' => KatcherDownload::CONNECTION_TIMEOUT
                    ]);
                } catch (ClientException $e) {
                    $metaLog->push('nonexistentFiles', (int) $i)->save();

                    /* download next file */
                    continue 2;
                } catch (RequestException $e) {
                    $metaLog->increment('downloadRetries')->save();

                    $retries++;

                    /* handle reaching the retry limit */
                    if ($retries == KatcherDownload::RETRY_LIMIT) {
                        $metaLog->push('missingFiles', (int) $i)->save();

                        /* download next file */
                        continue;
                    }

                    /* wait before retrying */
                    sleep(KatcherDownload::RETRY_WAIT_SECS);
                    continue;
                }

                /* save file */
                $downloadStorage->writeFilePart(
                    $katcherURL->getFileName($i),
                    $response->getBody()->getContents()
                );

                /* download next file */
                break;
            }
        }

        /* update log */
        $metaLog->set('status', 'downloaded')
            ->save()
            ->close();

        return $folder;
    }

    /**
     * @param string $folder
     */
    public function downloadMissingTs($folder)
    {
        $downloadStorage = new DownloadStorage($folder, $this->getFileSystem());
        $metaLog = DownloadMetaLog::read($downloadStorage);
        $missingFiles = $metaLog->get('missingFiles');

        $metaLog
            ->set('missingFiles', [])
            ->set('oldMissingFiles', $missingFiles)
            ->save();

        $this->downloadFileParts(
            $missingFiles,
            KatcherUrl::createFromUrl($metaLog->get('url')),
            $downloadStorage,
            $metaLog
        );
    }

    /**
     * Get filesystem
     *
     * @return \League\Flysystem\Filesystem
     */
    private function getFileSystem()
    {
        return $this->app->get('filesystem');
    }

    /**
     * @param array $fileParts
     * @param KatcherUrl $katcherURL
     * @param DownloadStorage $downloadStorage
     * @param DownloadMetaLog $metaLog
     */
    private function downloadFileParts(
        array $fileParts,
        KatcherUrl $katcherURL,
        DownloadStorage $downloadStorage,
        DownloadMetaLog $metaLog
    ) {
        $guzzle = new Client();
        $filePartsCount = count($fileParts);

        for ($i = 0; $i < $filePartsCount; $i++) {
            $curFilePart = $fileParts[$i];
            $retries = 0;

            $metaLog->set('currentFile', $curFilePart)->save();

            while ($retries != KatcherDownload::RETRY_LIMIT) {
                try {
                    $response =  $guzzle->request('GET', $katcherURL->getFileURL($curFilePart), [
                        'verify' => false,
                        'timeout' => KatcherDownload::CONNECTION_TIMEOUT
                    ]);
                } catch (ClientException $e) {
                    $metaLog->push('nonexistentFiles', $curFilePart)->save();

                    /* download next file */
                    continue 2;
                } catch (RequestException $e) {
                    $retries++;

                    $metaLog->increment('downloadRetries')->save();

                    /* handle reaching the retry limit */
                    if ($retries == KatcherDownload::RETRY_LIMIT) {
                        $metaLog->push('missingFiles', $curFilePart)->save();

                        /* download next file */
                        continue;
                    }

                    /* wait before retrying */
                    sleep(KatcherDownload::RETRY_WAIT_SECS);
                    continue;
                }

                /* save file */
                $downloadStorage->writeFilePart(
                    $katcherURL->getFileName($curFilePart),
                    $response->getBody()->getContents()
                );

                /* download next file */
                break;
            }
        }
    }
}