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
    protected $meta = [];

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
        $this->setPath();
        /*$this->setMeta();*/
    }

    /**
     * Set meta
     *
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
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
     * Get meta data including nested
     *
     * @param string ...$keys
     * @return mixed
     */
    public function get(...$keys)
    {
        $value = $this->meta[array_shift($keys)];

        foreach ($keys as $key) {
            $value = $value[$key];
        }

        return $value;
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
     * Push value to a meta value array
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function push($key, $value)
    {
        $this->meta[$key][] = $value;

        return $this;
    }

    /**
     * Increment a meta value
     *
     * @param $key
     * @return $this
     */
    public function increment($key)
    {
        $this->meta[$key]++;

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
     * Close file
     */
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    /**
     * Set path
     */
    private function setPath()
    {
        $this->path = stream_get_meta_data($this->stream)['uri'];
    }

    /**
     * Create initial instance
     *
     * @param array $meta
     * @param DownloadStorage $downloadStorage
     * @return static
     */
    public static function create(array $meta, DownloadStorage $downloadStorage)
    {
        $metaLog = new static(
            fopen(
                $downloadStorage->path('meta.json'),
                'w+'
            )
        );

        $metaLog->setMeta($meta)->save();

        return $metaLog;
    }

    /**
     * Create through read
     *
     * @param DownloadStorage $downloadStorage
     * @return static
     */
    public static function read(DownloadStorage $downloadStorage)
    {
        /*$this->meta = json_decode(
            fread($this->stream, filesize($this->path)),
            true
        );*/

        return new static(
            fopen(
                $downloadStorage->path('meta.json'),
                'r+'
            )
        );
    }
}