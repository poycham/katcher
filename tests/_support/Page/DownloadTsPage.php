<?php
namespace Page;

use Data\SampleKatcherUrl;

class DownloadTsPage
{
    // include url of current page
    public static $URL = '/';
    public static $Title = 'Katcher - Download .ts Videos from katch.me';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    /**
     * Create DownloadTsPage
     *
     * @param \AcceptanceTester $tester
     */
    public function __construct(\AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Download Ts
     *
     * @param int $firstPart
     * @param int $lastPart
     * @return $this
     */
    public function downloadTs($firstPart = 0, $lastPart = 0)
    {
        $I = $this->tester;
        $I->amOnPage(static::$URL);

        $I->submitForm('form', [
            'url' => SampleKatcherUrl::URL,
            'first_part' => $firstPart,
            'last_part' => $lastPart
        ]);
        $I->seeCurrentUrlEquals(ConvertPage::getUrl(SampleKatcherUrl::FOLDER));


        return $this;
    }

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }
}
