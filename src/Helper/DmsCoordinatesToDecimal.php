<?php
namespace App\Helper;

class DmsCoordinatesToDecimal
{
    public static function convert(string $geoLocation) : ?array
    {
        $originalGeoLocation = $geoLocation;
        if (!empty($geoLocation)) {
            $degree1 = mb_substr($geoLocation , 0 , 2);
            $hour1 = mb_substr($geoLocation , 2 , 2 );
            $geoLocation = substr( $geoLocation, 4 );
            if ( is_numeric( mb_substr( $geoLocation, - 3 ) ) ) {
                $geoLocation = substr( $geoLocation, 0, - 3 );
            }
            $hour2 = substr(substr( $geoLocation, - 3 ), 0 , -1);
            $geoLocation = substr(substr( $geoLocation, 0, - 3 ), 1 , 3);
            $degree2 = $geoLocation;            
            $latitude = $degree1 + $hour1 / 60;
            $longitude = $degree2 + $hour2 / 60;
            $longitude = strstr($originalGeoLocation, 'W')? '-'.$longitude : $longitude;
            $latitude =  strstr($originalGeoLocation, 'S')? '-'.$latitude : $latitude;
            $returnArray = [
                'latitude' => $latitude,
                'longitude' => $longitude,                
            ];
            return $returnArray;
        } else {
            return null;
        }
    }
}