<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ToObjectTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testToObject($data, $expected): void
    {
        $this->assertEquals($expected, \Miinto\PayloadValidator\Sanitizer\ToObject::sanitize($data));
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            [['test'], (object)['test']],
            [['name' => 'John Snow'], (object)['name' => 'John Snow']]
        ];
    }
}