<?php


class DownloadMissingTsCest
{
    public function _before(AcceptanceTester $I, \Page\DownloadMissingTsPage $downloadMissingTsPage)
    {
        $downloadMissingTsPage->downloadTsWithMissing();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function viewPage(AcceptanceTester $I)
    {
        $I->wantTo('view Download Missing Ts Page');

        $I->see('Download these file(s) again:', '.alert-danger');
        $I->seeElement('form', ['action' => '/download-ts/missing']);
        $I->seeElement('form input[type=hidden]', ['value' => \Data\SampleKatcherUrl::FOLDER]);
    }

    public function downloadMissingTs(AcceptanceTester $I)
    {
        $I->wantTo('download missing Ts');

        $folder = \Data\SampleKatcherUrl::FOLDER;
        $filePath = 'storage/' . $folder . '/files';
        $I->dontSeeFileFound('chunk_1.ts', $filePath);

        $I->submitForm('form', []);
        $I->seeCurrentUrlEquals(\Page\ConvertPage::getUrl($folder));
        $I->seeFileFound('chunk_1.ts', $filePath);
    }
}
