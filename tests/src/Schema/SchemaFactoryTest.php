<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class SchemaFactoryTest extends TestCase
{
    /** @var \PayloadValidator\Schema\Factory  */
    protected $factory;

    /** @var array */
    protected $schema;

    public function setUp(): void
    {
        $this->factory = new \PayloadValidator\Schema\Factory();
        $this->schema = \json_decode(\file_get_contents(FIXTURES_PATH . '/schema.json'), true);
    }

    public function testSchemaFactory(): void
    {
        $this->assertInstanceOf(\Opis\JsonSchema\ISchema::class, $this->factory->createFromArray($this->schema));
    }
}