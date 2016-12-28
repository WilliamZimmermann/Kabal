<?php
use Customer\Services\CustomerMessages;

return array(
    'controllers' => array(
        'invokables' => array(
            'Customer\Controller\Customer' => 'Customer\Controller\CustomerController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'customer' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/customer',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Customer\Controller',
                        'controller'    => 'Customer',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'new' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/new',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Customer\Controller',
                                'controller'    => 'Customer',
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
                                'action'=>'edit',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'address' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/address',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'address',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'address-list' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/address/list',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'addressList',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'address-data' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/address/data',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'addressData',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'address-edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/address/edit',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'addressEdit',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'address-delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/address/delete',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'addressDelete',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'contacts' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/contacts',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'contacts',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    'contact-add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]/contact/add',
                            'constraints' => array(
                                'id' => '[0-9_-]*',
                            ),
                            'defaults' => array(
                                'action'=>'contactAdd',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    /*
                    'checkEmail' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/checkEmail',
                            'defaults' => array(
                                'action'=>'checkEmail',
                                'controller'=>'Customer'
                            ),
                        ),
                    ),
                    */
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables'=>[
            'customerMessages'=>CustomerMessages::class,
        ]
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Customer' => __DIR__ . '/../view',
        ),
    ),
);
