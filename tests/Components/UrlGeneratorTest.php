<?php


namespace Tests\Components;


use Katcher\Components\UrlGenerator;

class UrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlWithSlash()
    {
        $urlGenerator = new UrlGenerator('http://some_base_url/');

        $this->assertEquals('http://some_base_url/uri1/uri2', $urlGenerator->url('uri1/uri2'));
    }

    public function testUrlWithoutSlash()
    {
        $urlGenerator = new UrlGenerator('http://some_base_url');

        $this->assertEquals('http://some_base_url/uri1/uri2', $urlGenerator->url('uri1/uri2'));
    }
}
