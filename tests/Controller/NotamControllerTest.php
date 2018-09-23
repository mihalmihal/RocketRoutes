<?php
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotamControllerTest extends WebTestCase
{
    public function testEmptyIcaoString()
    {     
        $client = static::createClient();
        $client->request('GET', '/notam?icao=');
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('icao code can not be empty', $response['error']);
    }
    
    public function testWrongIcaoCode()
    {
        $client = static::createClient();
        $client->request('GET', '/notam?icao=qwe');
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('wrong icao code format', $response['error']);
    }
}