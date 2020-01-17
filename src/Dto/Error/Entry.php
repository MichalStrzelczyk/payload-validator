<?php

declare (strict_types=1);

namespace Miinto\PayloadValidator\Dto\Error;

class Entry
{
    /** @var int  */
    protected $code;

    /** @var string  */
    protected $message;

    /** @var string  */
    protected $key;

    /**
     * Entry constructor.
     * @param int $code
     * @param string $message
     * @param string $key
     */
    public function __construct(int $code, string $message, string $key = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->key = $key;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'key' => $this->key
        ];
    }
}