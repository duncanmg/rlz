<?php

namespace RL\Controller;

use Zend\Authentication\AuthenticationService;

/**
 * 
 *
 * @author duncan
 */
trait AuthenticationTrait {

    private $authenticationService;

    private function isLoggedIn() {
        # error_log('isLoggedIn');
        return $this->authentication->getIdentity() ? true : false;
    }

    private function getAuthenticationService() {
        return $this->authenticationService;
    }

    private function setAuthenticationService(AuthenticationService $a) {
        $this->authenticationService = $a;
        return $this;
    }

}
