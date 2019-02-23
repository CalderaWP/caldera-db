<?php

namespace calderawp\DB\Tests\Unit;

use calderawp\DB\Time;

class TimeTest extends TestCase
{

    public function testNormalizeMysql()
    {
        $timestamp = '2017-06-30 11:37:06';
        $result = Time::normalizeMysql($timestamp);
        $this->assertEquals('2017-06-30 11:37:06', $result);
    }

    public function testDateTimeFromMysql()
    {
        $timestamp = '2017-06-30 11:37:06';
        $result = Time::dateTimeFromMysql($timestamp);
        $this->assertEquals($timestamp, $result->format(Time::FORMAT));
    }
}
