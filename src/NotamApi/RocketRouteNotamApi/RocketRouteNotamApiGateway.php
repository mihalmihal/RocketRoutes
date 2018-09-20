<?php
namespace App\NotamApi\RocketRouteNotamApi;
use App\Helper\DmsCoordinatesToDecimal;
use App\NotamApi\NotamResponce;
use App\NotamApi\NotamApiGatewayInterface;

class RocketRouteNotamApiGateway implements NotamApiGatewayInterface
{
    const PASSWORD = 'zqcpMNk2nmxfaVtM3PBd';
    const WSDl = 'https://apidev.rocketroute.com/notam/v1/service.wsdl';    
    const USER = 'a.mykhalchyshyn@gmail.com';

    public function findByIcao(string $icao) : ?array
    {
        $soapClient = new \SoapClient(self::WSDl);     
        $request = $this->formatRequest($icao);           
        $notam = $soapClient->getNotam($request);
        return $this->formatResponce($notam);
    }

    private function formatRequest(string $icao) : string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <REQWX>
          <USR>' . self::USER . '</USR>
          <PASSWD>' . self::PASSWORD . '</PASSWD>
          <ICAO>' . $icao . '</ICAO>
        </REQWX>';                
    }

    private function formatResponce(string $notam) : array
    {
        $result = [];
        $xml = simplexml_load_string($notam);        
        if (!$xml) {
            throw new \Exception('cannot parse xml from responce');            
        }
        if ($xml->REQNOTAM->RESULT != 0) {
            throw new \Exception('request error' . $xml->MESSAGE);            
        }
        foreach ($xml->NOTAMSET->NOTAM as $notam) {
            $notamResponce = $this->parseSingleNotam($notam);
            if ($notamResponce) {
                $result[] = $notamResponce;
            }
        }
        return $result;
    }

    private function parseSingleNotam(\SimpleXMLElement $notam) : ?NotamResponce
    {
        $id = $notam->attributes()->id;
        $itemQ = $notam->ItemQ;
        $itemQ = explode('/', $itemQ);
        $dms = end($itemQ);        
        $coordinates = DmsCoordinatesToDecimal::convert(end($itemQ));
        $notice = $notam->ItemE;
        if (!$coordinates || !$notice) {
            return null;
        }        
        return new NotamResponce($id, $dms, $coordinates['latitude'], $coordinates['longitude'], $notice);
    }
}