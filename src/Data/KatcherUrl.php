<?php


namespace Katcher\Data;


class KatcherUrl
{
    /**
     * @var string
     */
    protected $base;

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
     * Get base
     *
     * @return string
     */
    public function base()
    {
        return $this->base;
    }

    /**
     * Set format
     *
     * @return string
     */
    public function format()
    {
        return $this->format;
    }

    public function folder()
    {
        return $this->folder;
    }

    /**
     * Get file url
     *
     * @param $filePart
     * @return string
     */
    public function fileURL($filePart)
    {
        return $this->base . $this->fileName($filePart);
    }

    /**
     * Get file name
     *
     * @param $filePart
     * @return string
     */
    public function fileName($filePart)
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
        $this->base = str_replace($file, '', $rawURL);

        /* set format */
        $this->format = preg_replace('/[0-9]+\.ts$/', '%i.ts', $file);

        /* set folder */
        $this->folder = preg_replace(['/^http(s)?:\/\/[^\/]+\//', '/\/$/'], '', $this->base);
    }
}