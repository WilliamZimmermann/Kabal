<?php
use Product\Services\ProductMessages;

return array(
    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Product' => 'Product\Controller\ProductController',
            'Product\Controller\Category' => 'Product\Controller\CategoryController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'product' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/product',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Product\Controller',
                        'controller'    => 'Product',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'new' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/new',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Product\Controller',
                                'action'        => 'new',
                            ),
                        ),
                    ),
                    'category' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            // Change this to something specific to your module
                            'route'    => '/category',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Product\Controller',
                                'controller'    => 'Category',
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
                                        'controller'    => 'Category',
                                        'action'        => 'new',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/edit/[:id]',
                                    'constraints' => array(
                                        'id' => '[0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'Category',
                                        'action'        => 'edit',
                                    ),
                                ),
                            ),
                            'edit-language' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/edit-language/:id/:idLanguage',
                                    'constraints' => array(
                                        'id' => '[0-9_-]*',
                                        'idLanguage' => '[0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'Category',
                                        'action'        => 'editLanguage',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/delete/[:id]',
                                    'constraints' => array(
                                        'id' => '[0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'Category',
                                        'action'        => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Product' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'invokables'=>[
            'productMessages'=>ProductMessages::class,
        ]
    ),
);
