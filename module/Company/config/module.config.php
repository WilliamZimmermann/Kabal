<?php
use Company\Factories\CompanyFactory;

return array(
    'controllers' => array(
        'invokables' => array(
            'Company\Controller\Company' => 'Company\Controller\CompanyController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'company' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/company',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Company\Controller',
                        'controller'    => 'Company',
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
                                'controller'=>'Company'
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
                                'controller'=>'Company'
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
                                'controller'=>'Company'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Company' => __DIR__ . '/../view',
        ),
    ),
    'service_manager'=>[
        'factories'=>[
            'companies' => CompanyFactory::class
        ]
    ]
);
