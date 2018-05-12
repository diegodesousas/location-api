<?php

namespace App\Validation;

/**
 * Class Rule
 *
 * @package App\Validation
 */
abstract class Rule
{
    /**
     * @var string
     */
    private $message;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Build validation laravel format
     *
     * @return string
     */
    abstract function build(): string;
}