<?php
use ImagesDatabase\Factories\ModuleImageFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            'ImagesDatabase\Controller\ImagesDatabase' => 'ImagesDatabase\Controller\ImagesDatabaseController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'images-database' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/imagesDatabase',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'ImagesDatabase\Controller',
                        'controller'    => 'ImagesDatabase',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'new' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/new',
                            'defaults' => array(
                                'action'        => 'new',
                                'controller'    => 'ImagesDatabase',
                            ),
                        ),
                    ),
                    'upload' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/upload',
                            'defaults' => array(
                                'action'        => 'upload',
                                'controller'    => 'ImagesDatabase',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/delete/:id',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'        => 'delete',
                                'controller'    => 'ImagesDatabase',
                            ),
                        ),
                    ),
                    'images' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/images',
                            'defaults' => array(
                                'action'        => 'images',
                                'controller'    => 'ImagesDatabase',
                            ),
                        ),
                    ),
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/ttt/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ImagesDatabase' => __DIR__ . '/../view',
        ),
    ),
    'service_manager'=>[
        'factories'=>[
            'moduleImages' => ModuleImageFactory::class
        ]
    ]
);
