<?php
namespace App\NotamApi;
use App\NotamApi\NotamResponce;

interface NotamApiGatewayInterface 
{
    //should return array of NotamResponce objects
    public function findByIcao(string $icao) : ?array;    
}