<?php

use Page\ConvertPage;

class ConvertCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function viewPage(AcceptanceTester $I, \Page\ConvertPage $convertPage)
    {
        $I->wantTo('view Convert Page');

        $convertPage->downloadTs();
        $I->seeInTitle(\Page\ConvertPage::$Title);
    }

    public function convert(AcceptanceTester $I, \Page\ConvertPage $convertPage)
    {
        $I->wantTo('convert .ts files to .mp4');

        $convertPage->convert();
        $I->seeFileFound(
            \Data\SampleKatcherUrl::FOLDER . '.mp4',
            'storage/' . \Data\SampleKatcherUrl::FOLDER
        );
    }
}
