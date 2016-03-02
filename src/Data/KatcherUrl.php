<?php


namespace Katcher\Data;


class KatcherUrl
{
    /**
     * @var string
     */
    protected $baseURL;

    /**
     * @var string
     */
    protected $fileFormat;

    /**
     * @var string
     */
    protected $folder;

    /**
     * Create Katcher URL
     *
     * @param $baseURL
     * @param $fileFormat
     */
    public function __construct($baseURL, $fileFormat)
    {
        $this->baseURL = $baseURL;
        $this->fileFormat = $fileFormat;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    public function getBaseURL()
    {
        return $this->baseURL;
    }

    /**
     * Get file format
     *
     * @return string
     */
    public function getFileFormat()
    {
        return $this->fileFormat;
    }

    /**
     * Get file name
     *
     * @param $filePart
     * @return string
     */
    public function getFileName($filePart)
    {
        return str_replace('%i', $filePart, $this->fileFormat);
    }

    /**
     * Get file url
     *
     * @param $filePart
     * @return string
     */
    public function getFileURL($filePart)
    {
        return $this->baseURL . '/' . $this->getFileName($filePart);
    }

    /**
     * Get base url last uri
     *
     * @return string
     */
    public function getBaseLastUri()
    {
        if (preg_match('/[\/]([^\/]+)$/', $this->baseURL, $matches)) {
            return $matches[1];
        }
    }

    /**
     * Create from url
     *
     * @param $url
     * @return KatcherUrl
     */
    public static function createFromUrl($url)
    {
        /* extract file part */
        if (! preg_match('/\/([^\/]+\.ts)$/', $url, $matches)) {
            throw new \DomainException('The URL must end with .ts');

            return;
        }

        $file = $matches[1];

        /* set base */
        $baseURL = str_replace('/' . $file, '', $url);

        /* set format */
        $fileFormat = preg_replace('/[0-9]+\.ts$/', '%i.ts', $file);

        return new static($baseURL, $fileFormat);
    }
}