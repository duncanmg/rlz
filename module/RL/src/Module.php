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
                Model\UserQuestionTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserQuestionTableGateway::class);
                    return new Model\UserQuestionTable($tableGateway);
                },
                Model\QuestionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
                },
                Model\UserQuestionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserQuestion());
                    return new TableGateway('user_question', $dbAdapter, null, $resultSetPrototype);
                },
                Model\AnswerManager::class => function ($container) {
                    return new Model\AnswerManager($container->get(Model\QuestionTable::class));
                },
                Model\ActiveQuestion::class => function ($container) {
                    return new Model\ActiveQuestion();
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
                        $container->get(Model\AnswerManager::class),
                        $container->get('RLContainerNamespace')
                    );
                },
            ],
        ];
    }

}
