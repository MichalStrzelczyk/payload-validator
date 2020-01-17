<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Validator;

use \Miinto\PayloadValidator\Validator;

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