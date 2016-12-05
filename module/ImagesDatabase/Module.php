<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ImagesDatabase for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ImagesDatabase;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ImagesDatabase\Model\ImageTable;
use Zend\Db\ResultSet\ResultSet;
use ImagesDatabase\Model\Image;
use Zend\Db\TableGateway\TableGateway;
use ImagesDatabase\Model\ModuleImage;
use ImagesDatabase\Model\ModuleImageTable;

class Module implements AutoloaderProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ImagesDatabase\Model\ImageTable' => function ($sm) {
                    $tableGateway = $sm->get('ImageTableGateway');
                    $table = new ImageTable($tableGateway);
                    return $table;
                },
                'ImageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Image());
                    return new TableGateway('image', $dbAdapter, null, $resultSetPrototype);
                },
                'ImagesDatabase\Model\ModuleImageTable' => function ($sm) {
                    $tableGateway = $sm->get('ModuleImageTableGateway');
                    $table = new ModuleImageTable($tableGateway);
                    return $table;
                },
                    'ModuleImageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ModuleImage());
                    return new TableGateway('system_module_has_image', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
