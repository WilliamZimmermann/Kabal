<?php
use Website\Factories\WebsiteFactory;
use Website\Factories\ModuleFactory;
use Website\Factories\WebsiteModuleFactory;
use Website\Factories\WebsiteUserFactory;
use Website\Factories\WebsiteModuleDbFactory;
use Website\Factories\WebsiteLanguageFactory;
use Website\Factories\LanguageFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            'Website\Controller\Website' => 'Website\Controller\WebsiteController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'website' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/website',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Website\Controller',
                        'controller'    => 'Website',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'new' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/new',
                            'defaults' => array(
                                'action'=>'new',
                                'controller'=>'Website'
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
                                'controller'=>'Website'
                            ),
                        ),
                    ),
                    'edit-modules' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/modules/:id[/:idModule]',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                                'idModule'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'modules',
                                'controller'=>'Website'
                            ),
                        ),
                    ),
                    'edit-users' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/users/:id[/:idUser]',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                                'idUser'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'users',
                                'controller'=>'Website'
                            ),
                        ),
                    ),
                    'settings' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/settings/:id',
                            'constraints' => array(
                                'id'     => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'settings',
                                'controller'=>'Website'
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
                                'controller'=>'Website'
                            ),
                        ),
                    ),
                    'generateApiKey'=>array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/generateApiKey',
                            
                            'defaults' => array(
                                'action'=>'generateApiKey',
                                'controller'=>'Website'
                            ),
                        ),
                    )
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Website' => __DIR__ . '/../view',
        ),
    ),
    'service_manager'=>[
        'factories'=>[
            'language' => LanguageFactory::class,
            'website_language' => WebsiteLanguageFactory::class,
            'website' => WebsiteFactory::class,
            'modules' => ModuleFactory::class,
            'website_modules' => WebsiteModuleDbFactory::class,
            'website_module' => WebsiteModuleFactory::class,
            'website_user'=>WebsiteUserFactory::class
        ]
    ]
);
