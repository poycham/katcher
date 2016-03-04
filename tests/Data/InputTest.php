<?php


namespace Data;


use Katcher\Data\Input;

class InputTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromKeysNoData()
    {
        $data = [];
        $input = Input::createFromKeys([
            'url',
            'first_part',
            'second_part'
        ], $data);

        $this->assertEquals('', $input->getValue('url'));
        $this->assertEquals('', $input->getValue('first_part'));
        $this->assertEquals('', $input->getValue('second_part'));
    }

    public function testCreateWithData()
    {
        $data = [
            'url' => 'some_url',
            'first_part' => 'some_first_part',
            'second_part' => 'some_second_part'
        ];
        $input = Input::createFromKeys([
            'url',
            'first_part',
            'second_part'
        ], $data);

        $this->assertEquals('some_url', $input->getValue('url'));
        $this->assertEquals('some_first_part', $input->getValue('first_part'));
        $this->assertEquals('some_second_part', $input->getValue('second_part'));
    }

    public function testCreateFromKeysWithDefaults()
    {
        $keys = [
            'url',
            'first_part',
            'second_part'
        ];
        $data = [];
        $defaults = [
            'url' => 'default_url',
            'second_part' => 'default_second_part'
        ];
        $input = Input::createFromKeys($keys, $data, $defaults);

        $this->assertEquals('default_url', $input->getValue('url'));
        $this->assertEquals('', $input->getValue('first_part'));
        $this->assertEquals('default_second_part', $input->getValue('second_part'));
    }
}
