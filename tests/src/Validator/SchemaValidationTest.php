<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class SchemaValidationTest extends TestCase
{
    /** @var \PayloadValidator\Validator */
    protected $validator;

    /** @var \Opis\JsonSchema\ISchema */
    protected $schema;

    public function setUp(): void
    {
        $this->validator = (new \PayloadValidator\Validator\Factory())->create();
        $schema = \json_decode(\file_get_contents(FIXTURES_PATH . '/schema.json'), true);
        $this->schema = (new \PayloadValidator\Schema\Factory())->createFromArray($schema);
    }

    /**
     * @dataProvider data
     */
    public function testSanitizeMethodWithCorrectData($testData, $expectedData): void
    {
        $this->validator->schemaValidation((object) $testData, $this->schema);
        $this->assertEquals(\serialize($expectedData), \serialize($this->validator->getErrorContainer()));
    }

    /**
     * @dataProvider incorrectData
     */
    public function testSanitizeMethodWithIncorrectData($testData, $expectedData): void
    {
        $this->validator->schemaValidation((object) $testData, $this->schema);
        $this->assertEquals(\serialize($expectedData), \serialize($this->validator->getErrorContainer()));
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            // Success scenarios
            [
                [],[]
            ],
            [
                ['age' => '18'],[]
            ],
            [
                ['age' => 18],[]
            ]

        ];
    }

    /**
     * @return array
     */
    public function incorrectData()
    {
        return [
            [
                ['age' => 17.999999999999],['2001' => "Minimum `age` is 18"]
            ],
            [
                ['age' => '-18'],['2001' => "Minimum `age` is 18"]
            ],
            [
                ['age' => '17'],['2001' => "Minimum `age` is 18"]
            ],
            [
                ['age' => '17.999999'],['2001' => "Minimum `age` is 18"]
            ],
            [
                ['age' => '100'],['2002' => "Maximum `age` is 99"]
            ]
        ];
    }
}