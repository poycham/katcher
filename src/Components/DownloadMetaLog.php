<?php


namespace Katcher\Components;


class DownloadMetaLog
{
    const FILE_NAME = 'meta.json';

    /**
     * @var resource
     */
    protected $stream;

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
     * Get count of a meta value array
     *
     * @param $key
     * @return int
     */
    public function count($key)
    {
        return count($this->meta[$key]);
    }

    /**
     * Save log file
     *
     * return $this;
     */
    public function save()
    {
        rewind($this->stream);
        ftruncate($this->stream, 0);

        fwrite(
            $this->stream,
            json_encode($this->meta, JSON_PRETTY_PRINT)
        );

        return $this;
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
                $downloadStorage->getPath(static::FILE_NAME),
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
        $filePath = $downloadStorage->getPath(static::FILE_NAME);
        $fileStream = fopen($filePath, 'r+');
        $metaLog = new static($fileStream);

        $metaLog->setMeta(json_decode(
            fread($fileStream, filesize($filePath)),
            true
        ));

        return $metaLog;
    }
}