<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Sanitizer;

class ToObject implements SanitizerInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public static function sanitize($value)
    {
        return (object) $value;
    }
}