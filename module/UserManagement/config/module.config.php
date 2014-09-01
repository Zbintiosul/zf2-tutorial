<?php

namespace UserManagement;

return array(
    'controllers' => array(
        'invokables' => array(
            'UserManagement\Controller\UserManagement' => 'UserManagement\Controller\UserManagementController',
            'UserManagement\Controller\User' => 'UserManagement\Controller\UserController',
            'UserManagement\Controller\Role' => 'UserManagement\Controller\RoleController',
            'UserManagement\Controller\Right' => 'UserManagement\Controller\RightController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserManagement\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'role' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/role[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserManagement\Controller\Role',
                        'action'     => 'index',
                    ),
                ),
            ),
            'right' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/right[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserManagement\Controller\Right',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'user-man' => __DIR__ . '/../view',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),

);