<?php
namespace RL\Model;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

use Google_Client;

class AuthenticationAdapter implements AdapterInterface {

    private $idToken;
    private $aud = "960450020562-bij42s8ibvlc72igdaquu8ruengaenf9.apps.googleusercontent.com"; # My application name
    private $developerKey = "R_8kDi-8OHbxSkaCFEUO4eqk";

    public function _construct() {     
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate() {
        $client = new Google_Client();

        $client->setApplicationName($this->getAud());
        $client->setDeveloperKey($this->getDeveloperKey());

        $tokenData = $client->verifyIdToken($this->getIdToken());

        try {
            $this->exceptionOnNoToken($tokenData);

            $this->exceptionOnBadAud($tokenData, $this->getAud());

            $this->exceptionOnExpiredToken($tokenData);
        } catch (\Exception $e) {
            return (new Result(Result::FAILURE, [], ['Login unsuccessful', $e->getMessage()]));
        }
        # error_log(print_r(['xx login successful', $tokenData], true));
        $identity = [ 'name' => $tokenData['name'],
            'forename' => $tokenData['given_name'],
            'surname' => $tokenData['family_name'],
            'email' => $tokenData['email'],
            'picture' => $tokenData['picture'],
            'userId' => $tokenData['sub']];
        $messages = [];
        return (new Result(Result::SUCCESS, $identity, $messages));
    }

    public function setIdToken($idToken) {
       $this->idToken = $idToken;
       return $this;
    }
    
    private function getIdToken() {
        return $this->idToken;
    }

    public function setAud($aud) {
        $this->aud = $aud;
        return $this;
    }

    private function getAud() {
        return $this->aud;
    }
    
    public function setDeveloperKey($developerKey) {
        $this->developerKey = $developerKey;
        return $this;
    }
    
    private function getDeveloperKey(){
        return $this->developerKey;
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
