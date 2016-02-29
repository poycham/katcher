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
    protected $format;

    /**
     * @var string
     */
    protected $folder;

    public function __construct($rawURL)
    {
        $this->setProperties($rawURL);
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
     * Set format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Get file url
     *
     * @param $filePart
     * @return string
     */
    public function getFileURL($filePart)
    {
        return $this->baseURL . $this->getFileName($filePart);
    }

    /**
     * Get file name
     *
     * @param $filePart
     * @return string
     */
    public function getFileName($filePart)
    {
        return str_replace('%i', $filePart, $this->format);
    }

    /**
     * Set properties
     *
     * @param $rawURL
     */
    private function setProperties($rawURL)
    {
        /* extract file part */
        preg_match('/\/([^\/]+)$/', $rawURL, $matches);

        $file = $matches[1];

        /* set base */
        $this->baseURL = str_replace($file, '', $rawURL);

        /* set format */
        $this->format = preg_replace('/[0-9]+\.ts$/', '%i.ts', $file);

        /* set folder */
        $this->folder = preg_replace(['/^http(s)?:\/\/[^\/]+\//', '/\/$/'], '', $this->baseURL);
    }
}