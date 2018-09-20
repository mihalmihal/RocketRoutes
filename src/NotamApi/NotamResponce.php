<?php
namespace App\NotamApi;

class NotamResponce
{
    public $id;
    public $dms;
    public $latitude;
    public $longitude;
    public $notice;

    public function  __construct(string $id, string $dms, string $latitude, string $longitude, string $notice)
    {
        $this->id = $id;
        $this->dms = $dms;
        $this->latitude = $latitude; 
        $this->longitude = $longitude;
        $this->notice = $notice;
    }

}