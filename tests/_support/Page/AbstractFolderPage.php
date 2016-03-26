<?php


namespace Page;


abstract class AbstractFolderPage
{
    /**
     * Get convert URL
     *
     * @param $folder
     * @return string
     */
    public static function getUrl($folder)
    {
        return str_replace('{folder}', $folder, static::$URL);
    }
}