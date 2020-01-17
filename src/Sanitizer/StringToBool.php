<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Sanitizer;

class StringToBool implements SanitizerInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public static function sanitize($value)
    {
        return $value === 'true';
    }
}