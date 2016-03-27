<?php
namespace Page;

use Data\SampleKatcherUrl;
use Katcher\Components\DownloadMetaLog;

class DownloadMissingTsPage extends AbstractFolderPage
{
    // include url of current page
    public static $URL = '/convert/{folder}';

    /**
     * @var int
     */
    public static $MissingFile = 1;

    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    /**
     * @var DownloadTsPage
     */
    protected $downloadTsPage;

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Create DownloadMissingTsPage
     *
     * @param \AcceptanceTester $tester
     * @param DownloadTsPage $downloadTsPage
     */
    public function __construct(\AcceptanceTester $tester, DownloadTsPage $downloadTsPage)
    {
        $this->tester = $tester;
        $this->downloadTsPage = $downloadTsPage;
    }

    public function downloadTsWithMissing()
    {
        $this->downloadTsPage->downloadTs();
        $currentUrl = static::getUrl(SampleKatcherUrl::FOLDER);
        $this->tester->seeCurrentUrlEquals($currentUrl);
        $this->updateMetaLog();
        $this->tester->amOnPage($currentUrl);

        return $this;
    }

    private function updateMetaLog()
    {
        $filePath = codecept_root_dir('storage/' . SampleKatcherUrl::FOLDER . '/meta.json');
        $fileStream = fopen($filePath, 'r+');
        $metaLog = new DownloadMetaLog($fileStream);
        $metaLog->setMeta(json_decode(
            fread($fileStream, filesize($filePath)),
            true
        ));
        $metaLog->push('missingFiles', static::$MissingFile)->save()->close();
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
