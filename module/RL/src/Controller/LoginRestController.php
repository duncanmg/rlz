<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Google_Client;

class LoginRestController extends AbstractRestfulController
{

    public function __construct()
    {
        
    }

    public function create($parameters) {
        # $parameters = $this->getRequest()->getPost();
        $client = new Google_Client();

        $aud = "960450020562-bij42s8ibvlc72igdaquu8ruengaenf9.apps.googleusercontent.com"; # My application name
        $client->setApplicationName($aud);
        $client->setDeveloperKey("R_8kDi-8OHbxSkaCFEUO4eqk"); # Simple API Key

        $tokenData = $client->verifyIdToken($parameters['idtoken']);

        $this->exceptionOnNoToken($tokenData);
        $this->exceptionOnBadAud($tokenData, $aud);
        $this->exceptionOnExpiredToken($tokenData);
        error_log('login successful');
        $view = new JsonModel(['data' => [], 'status' => 'SUCCESS']);
        # error_log('xxx');
        return $view;
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
