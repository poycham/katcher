<?php


namespace Data;


use Katcher\Data\KatcherUrl;

class KatcherUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KatcherUrl
     */
    protected $katcherURL;

    public function setUp()
    {
        parent::setUp();

        $this->katcherURL = new KatcherUrl('https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_5.ts');
    }

    public function testProperties()
    {
        $this->assertEquals(
            'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/',
            $this->katcherURL->getBaseURL()
        );
        $this->assertEquals(
            'media-uwmn73350_%i.ts',
            $this->katcherURL->getFormat()
        );
    }

    public function testFileUrl()
    {
        $this->assertEquals(
            'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_27.ts',
            $this->katcherURL->getFileURL(27)
        );
    }

    public function testFileName()
    {
        $this->assertEquals(
            'media-uwmn73350_23.ts',
            $this->katcherURL->getFileName(23)
        );
    }

    public function testFolder()
    {
        $this->assertEquals(
            '6a155ef8-6571-38a6-8c8c-d83080d2428e',
            $this->katcherURL->getFolder()
        );
    }
}
