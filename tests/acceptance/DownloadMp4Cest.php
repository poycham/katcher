<?php


class DownloadMp4Cest
{
    public function _before(AcceptanceTester $I, \Page\ConvertPage $convertPage)
    {
        $convertPage->convert();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function viewPage(AcceptanceTester $I)
    {
        $I->wantTo('view Download Mp4 Page');

        $I->seeInTitle(\Page\DownloadMp4Page::$Title);
    }

//    public function downloadMp4(AcceptanceTester $I)
//    {
//        $I->wantTo('download the converted .mp4 file');
//
//        $I->submitForm('#download-form', []);
//
//        /* do an assertion here regarding the file */
//    }
}
