<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Google_AccessToken_Verify;
use Google_Client;

class LoginController extends AbstractActionController
{

    use CharMapTrait;

    public function __construct()
    {
        
    }

    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        return new ViewModel;
    }
    
    public function signoutAction()
    {
        return new ViewModel;
    }

    public function tokensigninAction()
    {
        //error_log('In tokensigninAction');
        $parameters = $this->getRequest()->getPost();
        //error_log(print_r($parameters,true));
        
        $client = new Google_Client();
        //error_log('Got a client');
        
        $aud = "960450020562-bij42s8ibvlc72igdaquu8ruengaenf9.apps.googleusercontent.com"; # My application name
        $client->setApplicationName($aud); 
        $client->setDeveloperKey("R_8kDi-8OHbxSkaCFEUO4eqk"); # Simple API Key

        //error_log('X0 ' . $parameters['idtoken']);
        #$verify = new  Google_AccessToken_Verify($client);
        //error_log('X1');
        $tokenData = $client->verifyIdToken($parameters['idtoken']);
        //error_log('X2');
        if ($tokenData) {
            if ($tokenData['aud'] != $aud){
                error_log('Invalid aud');
            }
            if ($tokenData['exp'] < time()){
                error_log('Token has expired: ' . $tokenData['exp'].' < ' . time());
            }
           error_log(print_r(['Token validated' , $tokenData],true));
        }
        else {
            error_log('Token invalid');
        }
        
        return new ViewModel;
    }
}
