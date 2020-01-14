<?php

declare (strict_types=1);

namespace PayloadValidator\Validator;

use \PayloadValidator\Validator;

class Factory
{
    /**
     * @return Validator
     */
    public function create(): Validator
    {
        return new Validator();
    }
}