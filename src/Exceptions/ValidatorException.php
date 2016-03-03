<?php


namespace Katcher\Exceptions;


class ValidatorException extends \Exception
{
    protected $errors;

    /**
     * Create ValidatorException
     *
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct();

        $this->errors = $errors;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}