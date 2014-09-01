<?php

namespace UserManagement;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

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

            ),
        );
    }

    public function getFormElementConfig()
    {
        return array(
            'initializers' => array(
                'ObjectManagerInitializer' => function ($element, $formElements) {
                        if ($element instanceof ObjectManagerAwareInterface) {
                            $services      = $formElements->getServiceLocator();
                            $entityManager = $services->get('Doctrine\ORM\EntityManager');

                            $element->setObjectManager($entityManager);
                        }
                    },
            ),
        );
    }

}