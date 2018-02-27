<?php

namespace RL;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

# use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\QuestionTable::class => function($container) {
                    $tableGateway = $container->get(Model\QuestionTableGateway::class);
                    return new Model\QuestionTable($tableGateway);
                },
                Model\QuestionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
                },
                Model\AnswerChecker::class => function ($container) {
                    return new Model\AnswerChecker();
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\QuestionAdminController::class => function($container) {
                    return new Controller\QuestionAdminController(
                        $container->get(Model\QuestionTable::class)
                    );
                },
                Controller\QuestionController::class => function($container) {
                    return new Controller\QuestionController(
                        $container->get(Model\QuestionTable::class),
                        $container->get(Model\AnswerChecker::class),
                        $container->get('RLContainerNamespace')
                    );
                },
            ],
        ];
    }

}
