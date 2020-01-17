<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class EntryErrorTest extends TestCase
{
    /** @var \Miinto\PayloadValidator\Dto\Factory */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new \Miinto\PayloadValidator\Dto\Factory();
    }

    /**
     * @dataProvider data
     */
    public function testToArray($code, $errorMessage, $key = null, $expected): void
    {
        $errorEntry = $this->factory->createErrorEntry($code, $errorMessage, $key);
        $this->assertEquals(\serialize($expected), \serialize($errorEntry->toArray()));
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            [
                100,
                'Name is required',
                'name',
                [
                    'code' => 100,
                    'message' => 'Name is required',
                    'key' => 'name'
                ]
            ],
            [
                200,
                'Name is required',
                null,
                [
                    'code' => 200,
                    'message' => 'Name is required',
                    'key' => null
                ]
            ]
        ];
    }
}