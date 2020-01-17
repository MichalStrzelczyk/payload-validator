<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Dto;

class Factory
{
    /**
     * @param int $errorCode
     * @param string $errorMessage
     * @param ?string $key
     *
     * @return Error\Entry
     */
    public function createErrorEntry(int $errorCode, string $errorMessage, string $key = null): \Miinto\PayloadValidator\Dto\Error\Entry
    {
        return new \Miinto\PayloadValidator\Dto\Error\Entry($errorCode, $errorMessage, $key);
    }
}