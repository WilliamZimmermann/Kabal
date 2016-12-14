<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Article\Controller\Article' => 'Article\Controller\ArticleController',
            'Article\Controller\Category' => 'Article\Controller\CategoryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'category' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/category',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Article\Controller',
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
            'article' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/article',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Article\Controller',
                        'controller'    => 'Article',
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
                                'controller'    => 'Article',
                                'action'        => 'new',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id[/:code]]',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                                'code'=>'[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Article',
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
                                'controller'    => 'Article',
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
                                'controller'    => 'Article',
                                'action'        => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Article' => __DIR__ . '/../view',
        ),
    ),
);
