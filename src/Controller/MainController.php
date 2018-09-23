<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    public function index()
    {        
    	$mapKey = getenv('GOOGLE_MAPS_KEY');
        return $this->render('app/index.html.twig', ['key' => $mapKey]);
    }
    
}