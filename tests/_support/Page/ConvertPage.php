<?php
namespace Page;

use Data\SampleKatcherUrl;

class ConvertPage extends AbstractFolderPage
{
    // include url of current page
    public static $URL = '/convert/{folder}';

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
     * Create ConvertPage
     *
     * @param \AcceptanceTester $tester
     * @param DownloadTsPage $downloadTsPage
     */
    public function __construct(
        \AcceptanceTester $tester,
        DownloadTsPage $downloadTsPage
    ) {
        $this->tester = $tester;
        $this->downloadTsPage = $downloadTsPage;
    }

    public function downloadTs()
    {
        $this->downloadTsPage->downloadTs();
    }

    public function convert()
    {
        $I = $this->tester;

        $this->downloadTs();
        $I->seeCurrentUrlEquals(static::getUrl(SampleKatcherUrl::FOLDER));
        $I->see('All files were downloaded.', '.alert-success');

        $I->click('button[type=submit]');
        $I->seeCurrentUrlEquals(DownloadMp4Page::getUrl(SampleKatcherUrl::FOLDER));

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
