<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'UserManagement\Controller\UserManagement' => 'UserManagement\Controller\UserManagementController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'user-man' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user-man[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'UserManagement\Controller\UserManagement',
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

//    'doctrine' => array(
//        'driver' => array(
//            'album_entities' => array(
//                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => array(__DIR__ . '/../src/Album/Entity')
//            ),
//
//            'orm_default' => array(
//                'drivers' => array(
//                    'Album\Entity' => 'album_entities'
//                )
//            ))),
);