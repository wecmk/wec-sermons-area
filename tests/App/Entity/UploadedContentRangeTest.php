<?php

namespace App\Tests\App\Entity;

use PHPUnit\Framework\TestCase;

class UploadedContentRangeTest extends TestCase
{
    public function testStartsAtWithZero()
    {
        $range = new \App\Entity\UploadedContentRange("bytes 0-524287/2000000");
        $this->assertEquals(0, $range->startsAt());
        $this->assertEquals(524287, $range->endsAt());
        $this->assertEquals(2000000, $range->totalSize());
        $this->assertEquals(524288, $range->length());
    }
    
    public function testStartsAtWith100()
    {
        $range = new \App\Entity\UploadedContentRange("bytes 100-637285/4000000");
        print_r($range->startsAt());
        $this->assertEquals(100, $range->startsAt());
        $this->assertEquals(637285, $range->endsAt());
        $this->assertEquals(4000000, $range->totalSize());
        $this->assertEquals(637186, $range->length());
    }
    
    public function testStartsAtWith400Offset()
    {
        $range = new \App\Entity\UploadedContentRange("bytes 0-198/200");
        print_r($range->startsAt());
        $this->assertEquals(0, $range->startsAt());
        $this->assertEquals(198, $range->endsAt());
        $this->assertEquals(200, $range->totalSize());
        $this->assertEquals(199, $range->length());
    }
}
