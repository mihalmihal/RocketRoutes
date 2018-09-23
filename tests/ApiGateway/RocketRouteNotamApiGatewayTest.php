<?php
namespace Tests\ApiGateway;
use PHPUnit\Framework\TestCase;
use App\NotamApi\RocketRouteNotamApi\Soap;
use App\NotamApi\RocketRouteNotamApi\RocketRouteNotamApiGateway;
use App\NotamApi\NotamResponce;

class RocketRouteNotamApiGatewayTest extends TestCase
{
    public function testInvalidXmlException()
    {
        $soapMock = $this->createMock(Soap::class);
        $wrongXml = '           
            <Example ID="1">
                <Test>test</Test>
            <Example ID="2">
                <Test>test</Test>
            </Example>';
        $soapMock->method('makeRequest')->willReturn($wrongXml);
        $this->expectExceptionMessage('cannot parse xml from responce');
        $apiGateway = new RocketRouteNotamApiGateway($soapMock);
        $apiGateway->findByIcao('test');
    }

    public function testThatExceptionIsThrownWhenStatusIsNotZero()
    {
        $soapMock = $this->createMock(Soap::class);
        $errorXml = '<?xml version="1.0" encoding="UTF-8" ?>
            <REQNOTAM version="1.0">
                <RESULT>2</RESULT>
                <MESSAGE>ERROR: UNSUPPPORTED VERSION OF API 2.5</MESSAGE>
            </REQNOTAM>';
        $soapMock->method('makeRequest')->willReturn($errorXml);
        $this->expectExceptionMessage('request error ERROR: UNSUPPPORTED VERSION OF API 2.5');
        $apiGateway = new RocketRouteNotamApiGateway($soapMock);
        $apiGateway->findByIcao('test');
    }

   public function testParsingOfSuccesfullRequest()
    {
        $soapMock = $this->createMock(Soap::class);
        $successXml = '<?xml version="1.0" encoding="utf-8"?>
            <REQNOTAM>
                <RESULT>0</RESULT>
                <NOTAMSET ICAO="EGKA">
                    <NOTAM id="C5256/18">
                        <ItemQ>EGTT/QFALC/IV/NBO /A /000/999/5050N00018W</ItemQ>
                        <ItemA>EGKA</ItemA>
                        <ItemB>1809230800</ItemB>
                        <ItemC>1809231800</ItemC>
                        <ItemD></ItemD>
                        <ItemE>AD CLSD DUE TO STAFF SHORTAGES1100-1200 AND 1500-1600</ItemE>
                    </NOTAM> 
                </NOTAMSET>
            </REQNOTAM>';
        $soapMock->method('makeRequest')->willReturn($successXml);
        $expectedNotam =  new NotamResponce(
            'C5256/18',
            '5050N00018W',
            50.83,
            -0.3,
            'AD CLSD DUE TO STAFF SHORTAGES1100-1200 AND 1500-1600'
        );
        $apiGateway = new RocketRouteNotamApiGateway($soapMock);
        $notam = $apiGateway->findByIcao('test');
        $this->assertEquals([$expectedNotam], $notam);
    }
}