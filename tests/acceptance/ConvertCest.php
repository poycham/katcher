<?php


class ConvertCest
{
    public function _before(AcceptanceTester $I, \Page\DownloadTsPage $downloadTsPage)
    {
        $downloadTsPage->downloadTs();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function viewConvertPage(AcceptanceTester $I)
    {
        $I->wantTo('view Convert Page');

        $I->seeCurrentUrlEquals(\Page\ConvertPage::getUrl(\Data\SampleKatcherUrl::FOLDER));
        $I->seeInTitle('Katcher - Convert to .mp4');
    }

    public function convert(AcceptanceTester $I)
    {
        $I->wantTo('Convert .ts files to .mp4');

        $I->see('All files were downloaded.', '.alert-success');
        $I->click('button[type=submit]');

        $I->seeCurrentUrlMatches(\Page\DownloadMp4Page::$URL);
        $I->seeFileFound(
            \Data\SampleKatcherUrl::FOLDER . '.mp4',
            'storage/' . \Data\SampleKatcherUrl::FOLDER
        );
    }
}
