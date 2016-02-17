<?php


namespace Katcher\Components;


class DownloadMetaLog
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
        $this->setPath();
        $this->setMeta();
    }

    /**
     * Create through read
     *
     * @param DownloadStorage $downloadStorage
     * @return static
     */
    public static function read(DownloadStorage $downloadStorage)
    {
        return new static(
            fopen(
                $downloadStorage->path('meta.json'),
                'r+'
            )
        );
    }

    /**
     * Get meta
     *
     * @return array
     */
    public function all()
    {
        return $this->meta;
    }

    /**
     * Get meta data
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->meta[$key];
    }


    /**
     * Set meta data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->meta[$key] = $value;

      return $this;
    }

    /**
     * Save log file
     */
    public function save()
    {
        rewind($this->stream);
        ftruncate($this->stream, 0);

        fwrite(
            $this->stream,
            json_encode($this->meta, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Set path
     */
    private function setPath()
    {
        $this->path = stream_get_meta_data($this->stream)['uri'];
    }

    /**
     * Set meta
     */
    private function setMeta()
    {
        $this->meta = json_decode(
            fread($this->stream, filesize($this->path)),
            true
        );
    }
}