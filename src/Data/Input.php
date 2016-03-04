<?php


namespace Katcher\Data;


class Input
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get value
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->data[$key];
    }

    /**
     * Create from keys
     *
     * @param array $keys
     * @param array $data
     * @param array $defaults
     * @return static
     */
    public static function createFromKeys(array $keys, $data = [], $defaults = [])
    {
        $keyData = [];

        foreach ($keys as $key) {
            $defaultValue = (array_key_exists($key, $defaults)) ? $defaults[$key] : '';
            $keyData[$key] = (array_key_exists($key, $data)) ? $data[$key] : $defaultValue;
        }

        return new static($keyData);
    }
}