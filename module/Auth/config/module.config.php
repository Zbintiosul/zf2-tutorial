<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Auth' => 'Auth\Controller\AuthController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'my-profile' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/my-profile',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action'     => 'myprofile',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'auth' => __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'AuthServiceHelper' => 'Auth\View\Helper\AuthServiceHelper',
        ),
    ),
);