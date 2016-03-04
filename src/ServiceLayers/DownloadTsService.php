<?php


namespace Katcher\ServiceLayers;


use Katcher\Data\Input;

class DownloadTsService extends AbstractService
{
    /**
     * Get index view data
     *
     * @param array $flash
     * @return array
     */
    public function getIndexViewData(array $flash)
    {
        $input = Input::createFromKeys([
            'url',
            'first_part',
            'last_part'
        ], $flash['input']);

        $viewData = [
            'input' => $input,
            'errors' => $flash['errors']
        ];

        return $viewData;
    }
}