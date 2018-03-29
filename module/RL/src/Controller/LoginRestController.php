<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Authentication\AuthenticationService;

class LoginRestController extends AbstractRestfulController {

    private $authenticationService;
    
    public function __construct(AuthenticationService $authenticationService ) {
        error_log('xx');
        $this->authenticationService = $authenticationService;
    }

    public function create($parameters) {
        #$auth = new AuthenticationAdapter();
        error_log('Hi');
        $this->authenticationService->getAdapter()->setIdToken($parameters['idtoken']);

        $result = $this->authenticationService->authenticate();

        if ($result->isValid()) {
            error_log(print_r(['lll', $this->authenticationService->getIdentity()],true));
            return( new JsonModel(['data' => [], 'status' => 'SUCCESS', 'message' => 'Login successful']));
        } else {
            return(new JsonModel(['data' => [], 'status' => 'UNAUTHORIZED', 'message' => 'Login failed']));
        }
    }

}
