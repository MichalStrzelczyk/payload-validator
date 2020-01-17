<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Schema;

use \Opis\JsonSchema\ISchema;
use \Opis\JsonSchema\Schema;

class Factory
{
    /**
     * @param array $validationEntry
     *
     * @return ISchema
     */
    public function createFromArray(array $validationEntry): ISchema
    {
        return Schema::fromJsonString(\json_encode((object) $validationEntry));
    }
}