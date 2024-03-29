<?php
use Application\Services\SystemMessages;
use Application\Services\SystemLog;
use Application\Services\SystemFunctions;
use Application\Factories\CountryFactory;
use Application\Factories\ZoneFactory;
use Application\Factories\CityFactory;
use Application\Factories\MenuFactory;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'login'=>array('type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Login',
                        'action'     => 'index',
                    ),
                ),
            ),
            'logout'=>array('type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Login',
                        'action'     => 'logOut',
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[home[/]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'noPermission'=>array('type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/no-permission',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'nopermission',
                    ),
                ),
            ),
            'website-change' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/website-change/:idWebsite',
                    'constraints' => array(
                        'idWebsite' => '[0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Index',
                        'action'        => 'websiteChange',
                    ),
                ),
            ),
            'zones'=>array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/zones',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Region',
                        'action'        => 'zones',
                    ),
                ),
            ),
            'cities'=>array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cities',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Region',
                        'action'        => 'cities',
                    ),
                ),
            ),
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
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
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'invokables'=>[
            'systemMessages'=>SystemMessages::class,
            'systemLog'=>SystemLog::class,
            'systemFunctions'=>SystemFunctions::class,
        ],
        'factories'=>[
            'countryFactory' => CountryFactory::class,
            'zoneFactory' => ZoneFactory::class,
            'cityFactory' => CityFactory::class,
            'menuFactory' => MenuFactory::class
        ],
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Login' => 'Application\Controller\LoginController',
            'Application\Controller\Region' => 'Application\Controller\RegionController'
            
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layout_blank'           => __DIR__ . '/../view/layout/layout_blank.phtml',
            'layout/images'           => __DIR__ . '/../view/layout/image.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
