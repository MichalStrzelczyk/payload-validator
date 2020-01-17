<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Sanitizer;

class ToInteger implements SanitizerInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public static function sanitize($value)
    {
        return \filter_var($value, FILTER_VALIDATE_INT);
    }
}