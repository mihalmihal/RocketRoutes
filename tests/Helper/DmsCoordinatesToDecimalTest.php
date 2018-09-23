<?php
namespace Tests\Helper;
use PHPUnit\Framework\TestCase;
use App\Helper\DmsCoordinatesToDecimal;

class DmsCoordinatesToDecimalTest extends TestCase
{
    public function testConvert()
    {
        //5537N01239E
        //$this->asserEqual('3659212N8413533W', )
        $convertedCoordinates = DmsCoordinatesToDecimal::convert('5537N01239E');
        $expectedCoordinates = ['latitude' => 55.62, 'longitude' => 12.65];        
        $this->assertEquals($convertedCoordinates, $expectedCoordinates);
    }
}