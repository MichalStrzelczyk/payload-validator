<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class StringToBoolTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testToStringToBool($dataProvider): void
    {
        $this->assertTrue(\PayloadValidator\Sanitizer\StringToBool::sanitize($dataProvider));
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            ["true",'true',true, 'notEmptyString', 1, '1', "1"]
        ];
    }
}