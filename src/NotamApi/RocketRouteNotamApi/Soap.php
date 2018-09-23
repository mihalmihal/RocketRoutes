<?php
namespace App\NotamApi\RocketRouteNotamApi;

class Soap
{
    const WSDl = 'https://apidev.rocketroute.com/notam/v1/service.wsdl'; 

    public function makeRequest(string $method, string $arguments) : string 
    {
        $soapClient = new \SoapClient(self::WSDl);                      
        return $soapClient->$method($arguments);
    }
}