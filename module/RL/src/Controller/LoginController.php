<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController
{

    use AuthenticationTrait;

    public function __construct($authenticationService)
    {
        $this->setAuthenticationService($authenticationService);
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
        $this->getAuthenticationService()->clearIdentity();
        return new ViewModel;
    }

}
