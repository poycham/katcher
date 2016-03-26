<?php
namespace Page;

class ConvertPage
{
    // include url of current page
    public static $URL = '/convert/{folder}';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

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
