<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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

}
