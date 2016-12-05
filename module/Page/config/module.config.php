<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Page\Controller\Page' => 'Page\Controller\PageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'page' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/page',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Page\Controller',
                        'controller'    => 'Page',
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
                                'controller'    => 'Page',
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
                                'controller'    => 'Page',
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
                                'controller'    => 'Page',
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
                                'controller'    => 'Page',
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
            'Page' => __DIR__ . '/../view',
        ),
    ),
);
