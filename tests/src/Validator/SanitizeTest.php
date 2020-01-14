<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class SanitizeTest extends TestCase
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
    public function testSanitizeMethod($testData, $expectedData): void
    {
        $dataAfterSanitiziting = $this->validator->sanitize((object) $testData, $this->schema);
        $this->assertEquals(\serialize((object) $expectedData), \serialize($dataAfterSanitiziting));
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
                    'age' => 11
                ],
            ],
            [
                [
                    'age' => '-12'
                ],
                [
                    'age' => -12
                ],
            ],
            [
                [
                    'age' => -12.51
                ],
                [
                    'age' => -12
                ],
            ],
            [
                [
                    'age' => -12.49
                ],
                [
                    'age' => -12
                ],
            ]
        ];
    }
}