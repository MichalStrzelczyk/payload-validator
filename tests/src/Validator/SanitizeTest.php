<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class SanitizeTest extends TestCase
{
    /** @var \Miinto\PayloadValidator\Validator */
    protected $validator;

    /** @var \Opis\JsonSchema\ISchema */
    protected $schema;

    public function setUp(): void
    {
        $this->validator = (new \Miinto\PayloadValidator\Validator\Factory())->create();
        $schema = \json_decode(\file_get_contents(FIXTURES_PATH . '/schema.json'), true);
        $this->schema = (new \Miinto\PayloadValidator\Schema\Factory())->createFromArray($schema);
    }

    /**
     * @dataProvider data
     */
    public function testSanitizeMethod($testData, $expectedData): void
    {
        $dataAfterSanitizing = $this->validator->sanitize((object) $testData, $this->schema);
        $this->assertEquals(\serialize((object) $expectedData), \serialize($dataAfterSanitizing));
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            [
                [
                    'age' => '11'
                ],
                [
                    'age' => 11
                ],
            ],
            [
                [
                    'age' => '11testtest'
                ],
                [
                    'age' => false
                ],
            ],
            [
                [
                    'age' => '-14'
                ],
                [
                    'age' => -14
                ],
            ],
            [
                [
                    'age' => -12.51
                ],
                [
                    'age' => false
                ],
            ]
        ];
    }
}