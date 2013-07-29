<?php

//filename : SanAuthWithDbSaveHandler/config/module.config.php
namespace SanAuthWithDbSaveHandler;

return array(

    //controllers services...
    'controllers' => array(
        'factories' => array(
            'SanAuthWithDbSaveHandler\Controller\Auth' => 'SanAuthWithDbSaveHandler\Factory\Controller\AuthControllerServiceFactory'
        ),
        'invokables' => array(
            'SanAuthWithDbSaveHandler\Controller\Success' => 'SanAuthWithDbSaveHandler\Controller\SuccessController'
        ),
    ),

    //register auth services...
    'service_manager' => array(
        'factories' => array(
            'AuthStorage' => 'SanAuthWithDbSaveHandler\Factory\Storage\AuthStorageFactory',
            'AuthService' => 'SanAuthWithDbSaveHandler\Factory\Storage\AuthenticationServiceFactory',
            'IdentityManager' => 'SanAuthWithDbSaveHandler\Factory\Storage\IdentityManagerFactory',
        ),
    ),

    //routing configuration...
    'router' => array(
        'routes' => array(

            'auth' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/auth[/:action]',
                    'defaults' => array(
                        'controller' => 'SanAuthWithDbSaveHandler\Controller\Auth',
                        'action'     => 'index',
                    ),
                ),
            ),

            'success' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/success[/:action]',
                    'defaults' => array(
                        'controller' => 'SanAuthWithDbSaveHandler\Controller\Success',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    //setting up view_manager
    'view_manager' => array(
        'template_path_stack' => array(
            'SanAuthWithDbSaveHandler' => __DIR__ . '/../view',
        ),
    ),
);
