<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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

    public function tokensigninAction() {
        $parameters = $this->getRequest()->getPost();
        $client = new Google_Client();

        $aud = "960450020562-bij42s8ibvlc72igdaquu8ruengaenf9.apps.googleusercontent.com"; # My application name
        $client->setApplicationName($aud);
        $client->setDeveloperKey("R_8kDi-8OHbxSkaCFEUO4eqk"); # Simple API Key

        $tokenData = $client->verifyIdToken($parameters['idtoken']);

        $this->exceptionOnNoToken($tokenData);
        $this->exceptionOnBadAud($tokenData, $aud);
        $this->exceptionOnExpiredToken($tokenData);

        return new ViewModel;
    }

    private function exceptionOnNoToken($tokenData) {
        if (!$tokenData) {
            throw new \Exception('Invalid token. No token returned.');
        }
    }

    private function exceptionOnBadAud($tokenData, $aud) {
        if ($tokenData['aud'] != $aud) {
            throw new \Exception('Invalid token. Invalid aud ' . $aud);
        }
    }

    private function exceptionOnExpiredToken($tokenData) {
        if ($tokenData['exp'] < time()) {
            throw new \Exception('Invalid token. Expired token. ' . $tokenData['exp'] . ' < ' . time());
        }
    }

}
