<?php
use User\Factories\PermissionsFactory;
use User\Factories\UserFactory;
use User\Factories\UserDbFactory;
use User\Services\UserService;

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/user',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'User',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'new' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/new',
                            'defaults' => array(
                                'action'=>'new',
                                'controller'=>'User'
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'edit',
                                'controller'=>'User'
                            ),
                        ),
                    ),
                    'edit-password' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/password/:id',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'editPassword',
                                'controller'=>'User'
                            ),
                        ),
                    ),
                    'edit-permissions' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/permissions/:id',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'editPermissions',
                                'controller'=>'User'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'delete',
                                'controller'=>'User'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'User' => __DIR__ . '/../view',
        ),
    ),
    'service_manager'=>[
        'invokables'=>[
            'user'=>UserService::class
        ],
        'factories'=>[
            'permissions' => PermissionsFactory::class,
            'users' => UserFactory::class,
            'userDb'=>UserDbFactory::class,
        ]
    ]
);
