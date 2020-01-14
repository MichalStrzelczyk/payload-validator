<?php

declare (strict_types=1);

use PHPUnit\Framework\TestCase;

final class ToIntegerTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testToInt($dataProvider): void
    {
        $this->assertEquals(1 , \PayloadValidator\Sanitizer\ToInteger::sanitize($dataProvider));
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            [1, "1", '1',1.1, 1.6,  "true",'true',true, "notEmptyString"]
        ];
    }
}