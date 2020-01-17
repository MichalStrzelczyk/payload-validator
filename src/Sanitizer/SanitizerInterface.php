<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Sanitizer;

interface SanitizerInterface
{
    /**
     * @param mixed $value
     */
    public static function sanitize($value);
}