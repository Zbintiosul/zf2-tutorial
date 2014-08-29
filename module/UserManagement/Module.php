<?php

namespace UserManagement;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use UserManagement\Model\User;
use UserManagement\Model\UserTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),

            'factories' => array(
                'UserManagement\Model\UserTable' =>  function($sm) {
                        $tableGateway = $sm->get('UserTableGateway');
                        $table = new UserTable($tableGateway);
                        return $table;
                    },
                'UserTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new ResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new User());
                        return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    },
            ),
        );
    }
}