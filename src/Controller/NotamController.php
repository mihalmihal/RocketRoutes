<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\NotamApi\RocketRouteNotamApi\RocketRouteNotamApiGateway;
use Psr\Log\LoggerInterface;

class NotamController
{
    private $request;
    private $notamApiGateway;
    private $logger;

    public function __construct(RequestStack $request, RocketRouteNotamApiGateway $notamApiGateway, LoggerInterface $logger)
    {
        $this->request =  $request->getCurrentRequest();
        $this->notamApiGateway = $notamApiGateway;
        $this->logger = $logger;
    }

    public function index()
    {     
        $status = false;
        $error = null;
        $data = null;
        $icao =  $this->request->query->get('icao');
        $icao = strtoupper($icao);
        
        if(empty($icao)) {
            $error = 'icao code can not be empty';
        } else if (!preg_match('#^[A-Z]{4}$#', $icao)){
            $error = 'wrong icao code format';
        } else {
            try{
                $notams = $this->notamApiGateway->findByIcao($icao);                
                if (empty($notams)) {
                    $error = 'nothing was found for ICAO ' . $icao;
                } else {                
                    $status = true;                                    
                    $data = $notams;
                }
            } catch(\Exception $e){                
                $this->logger->error($e->getMessage());
                $error = 'Ooops, something went wrong. Try again later, or contact our suppport';
            }

        }        
        return new JsonResponse(['status' => $status, 'error' => $error, 'data' => $data]);
    }
}