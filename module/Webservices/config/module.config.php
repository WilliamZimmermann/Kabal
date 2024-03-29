<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Webservices\Controller\Page' => 'Webservices\Controller\PageController',
            'Webservices\Controller\Product' => 'Webservices\Controller\ProductController',
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'webservices' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/webservice/[:apiKey]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Webservices\Controller',
                        'controller'    => 'Page',
                        'action'        => 'index',
                    ),
                    'constraints' => array(
                        'apiKey' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'page-get' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/page/get[/:language[/:param/:value]]',
                            'constraints' => array(
                                'language' => '[a-zA-Z0-9_-]*',
                                'param' => '[a-zA-Z0-9_-]*',
                                'value' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Page',
                                'action'        => 'get',
                            ),
                        ),
                    ),
                    'product' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/product/:language',
                            'constraints' => array(
                                'language' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Product',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'get' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/get[/:param/:value]',
                                    'constraints' => array(
                                        'param' => '[a-zA-Z0-9_-]*',
                                        'value' => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'Product',
                                        'action'        => 'get',
                                    ),
                                ),
                            ),
                            'categories' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/categories/:method[/:param/:value]',
                                    'constraints' => array(
                                        'method' => '[a-zA-Z0-9_-]*',
                                        'param' => '[a-zA-Z0-9_-]*',
                                        'value' => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller'    => 'Product',
                                        'action'        => 'categories',
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
            'Webservices' => __DIR__ . '/../view',
        ),
    ),
);
