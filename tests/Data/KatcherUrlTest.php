<?php


namespace Data;


use Katcher\Data\KatcherUrl;

class KatcherUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return KatcherUrl
     */
    private function getKatcherUrl()
    {
        return KatcherUrl::createFromUrl('https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_5.ts');
    }

    public function testCreateFromUrl()
    {
        $katcherURL = KatcherUrl::createFromUrl('https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_5.ts');

        $this->assertEquals(
            'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e',
            $katcherURL->getBaseURL()
        );

        $this->assertEquals(
            'media-uwmn73350_%i.ts',
            $katcherURL->getFileFormat()
        );
    }

    public function testCreateFromUrlInvalid()
    {
        $this->setExpectedException('DomainException');

        $katcherURL = KatcherUrl::createFromUrl('https://d152nid216lr13.cloudfront.net/asdasdas/23.mp4');
    }

    public function testGetFileName()
    {
        $katcherURL = $this->getKatcherUrl();

        $this->assertEquals(
            'media-uwmn73350_23.ts',
            $katcherURL->getFileName(23)
        );
    }

    public function testGetFileUrl()
    {
        $katcherURL = $this->getKatcherUrl();

        $this->assertEquals(
            'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_27.ts',
            $katcherURL->getFileURL(27)
        );
    }

    public function testGetLastUri()
    {
        $katcherURL = $this->getKatcherUrl();

        $this->assertEquals(
            '6a155ef8-6571-38a6-8c8c-d83080d2428e',
            $katcherURL->getBaseLastUri()
        );
    }
}
