<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class DtoFactoryTest extends TestCase
{
    /** @var \Miinto\PayloadValidator\Dto\Factory */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new \Miinto\PayloadValidator\Dto\Factory();
    }

    public function testSchemaFactory(): void
    {
        $this->assertInstanceOf(
            \Miinto\PayloadValidator\Dto\Error\Entry::class,
            $this->factory->createErrorEntry(10, "Test message")
        );
    }
}