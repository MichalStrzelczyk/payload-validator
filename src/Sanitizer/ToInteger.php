<?php

declare (strict_types=1);

namespace PayloadValidator\Sanitizer;

class ToInteger implements SanitizerInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public static function sanitize($value)
    {
        return (int) $value;
    }
}