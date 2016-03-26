<?php


class DownloadTsCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function viewIndex(AcceptanceTester $I)
    {
        $I->amOnPage(\Page\DownloadTsPage::$URL);
        $I->wantTo('view Download Ts Page');

        $I->seeInTitle('Katcher - Download .ts Videos from katch.me');
    }

    public function downloadTs(AcceptanceTester $I)
    {
        $I->amOnPage(\Page\DownloadTsPage::$URL);
        $I->wantTo('download TS files from Katcher');

        $I->fillField('url', \Data\SampleKatcherUrl::URL);
        $I->fillField('first_part', 0);
        $I->fillField('last_part', 1);
        $I->click('Download Files');

        $I->seeCurrentUrlEquals(\Page\ConvertPage::getUrl(\Data\SampleKatcherUrl::FOLDER));
        $folder = 'storage/' . \Data\SampleKatcherUrl::FOLDER . '/files';
        $I->seeFileFound('chunk_0.ts', $folder);
        $I->seeFileFound('chunk_1.ts', $folder);
    }
}
