<?php


namespace Katcher\ServiceLayers;


use Katcher\Components\DownloadMetaLog;
use Katcher\Components\DownloadStorage;
use Katcher\Data\KatcherUrl;

class ConvertService extends AbstractService
{
    /**
     * Get show view data
     *
     * @param $folder
     * @return array
     */
    public function getShowViewData($folder)
    {
        $condViewData = [];
        $downloadStorage = new DownloadStorage($folder, $this->app->get('filesystem'));
        $metaLog = DownloadMetaLog::read($downloadStorage);
        $hasMissingFiles = ($metaLog->count('missingFiles') > 0);
        $hasNonexistentFiles = ($metaLog->count('nonexistentFiles') > 0);
        $isAllDownloaded = (! $hasNonexistentFiles && ! $hasMissingFiles);

        if (! $isAllDownloaded) {
            if ($hasMissingFiles) {
                $condViewData['missingFiles'] = $metaLog->get('missingFiles');
            }

            if ($hasNonexistentFiles) {
                $condViewData['nonexistentFiles'] = $metaLog->get('nonexistentFiles');
            }

            $condViewData['hasMissingFiles'] = $hasMissingFiles;
            $condViewData['hasNonexistentFiles'] = $hasNonexistentFiles;
            $condViewData['katcherURL'] = KatcherUrl::createFromUrl($metaLog->get('url'));
        }

        $metaLog->close();

        /* set view data */
        $viewData = array_merge(compact(
            'isAllDownloaded'
        ), $condViewData);

        return $viewData;
    }
}