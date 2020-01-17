<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ValidatorFactoryTest extends TestCase
{
    /** @var \Miinto\PayloadValidator\Validator\Factory  */
    protected $factory;

    public function setUp(): void
    {
        $this->factory = new \Miinto\PayloadValidator\Validator\Factory();
    }

    public function testValidatorFactory(): void
    {
        $this->assertInstanceOf(\Miinto\PayloadValidator\Validator::class, $this->factory->create());
    }
}