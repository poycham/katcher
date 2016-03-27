<?php

use Page\DownloadTsPage;

class DownloadTsCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function viewPage(AcceptanceTester $I)
    {
        $I->wantTo('view Download Ts Page');

        $I->amOnPage(\Page\DownloadTsPage::$URL);
        $I->seeInTitle(\Page\DownloadTsPage::$Title);
    }

    public function downloadTs(AcceptanceTester $I, DownloadTsPage $downloadTsPage)
    {
        $I->wantTo('download TS files from Katcher');

        $downloadTsPage->downloadTs(0, 1);

        $folder = 'storage/' . \Data\SampleKatcherUrl::FOLDER . '/files';
        $I->seeFileFound('chunk_0.ts', $folder);
        $I->seeFileFound('chunk_1.ts', $folder);
    }

    public function downloadTsValidationErrors(AcceptanceTester $I, DownloadTsPage $downloadTsPage)
    {
        $I->wantTo('view validation errors when submitting invalid download ts input');

        $downloadTsPage->downloadTs('invalid', 'input');

        $I->seeCurrentUrlEquals(\Page\DownloadTsPage::$URL);
        $I->seeElement('.alert-danger');
    }
}
