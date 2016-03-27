<?php
namespace Data;


use Codeception\Specify;
use Katcher\Data\KatcherUrl;

class KatcherUrlTest extends \Codeception\TestCase\Test
{
    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var KatcherUrl
     */
    protected $katcherUrl;

    protected function _before()
    {
        $this->katcherUrl = KatcherUrl::createFromUrl('https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_5.ts');
    }

    protected function _after()
    {
        unset($this->katcherUrl);
    }

    // tests
    public function testCreateFromUrl()
    {
        $this->specify('without exception', function() {
            $this->assertEquals(
                'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e',
                $this->katcherUrl->getBaseURL()
            );

            $this->assertEquals(
                'media-uwmn73350_%i.ts',
                $this->katcherUrl->getFileFormat()
            );
        });

        $this->specify('with exception', function() {
            $this->setExpectedException('DomainException');

            $this->katcherUrl = KatcherUrl::createFromUrl('https://d152nid216lr13.cloudfront.net/asdasdas/23.mp4');
        });
    }

    public function testGet()
    {
        $this->specify('get file name', function() {
            $this->assertEquals(
                'media-uwmn73350_23.ts',
                $this->katcherUrl->getFileName(23)
            );
        });

        $this->specify('get file url', function() {
            $this->assertEquals(
                'https://d152nid216lr13.cloudfront.net/6a155ef8-6571-38a6-8c8c-d83080d2428e/media-uwmn73350_27.ts',
                $this->katcherUrl->getFileURL(27)
            );
        });

        $this->specify('get base last uri', function() {
            $this->assertEquals(
                '6a155ef8-6571-38a6-8c8c-d83080d2428e',
                $this->katcherUrl->getBaseLastUri()
            );
        });
    }
}