<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Product for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Product;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Product\Model\CategoryTable;
use Zend\Db\TableGateway\TableGateway;
use Product\Model\Category;
use Product\Model\CategoryLanguageTable;
use Product\Model\CategoryLanguage;
use Product\Model\ProductHasCategoryTable;
use Product\Model\ProductHasCategory;
use Product\Model\Product;
use Product\Model\ProductTable;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
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
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Product\Model\ProductTable' => function ($sm) {
                    $tableGateway = $sm->get('ProductTableGateway');
                    $table = new ProductTable($tableGateway);
                    return $table;
                },
                'ProductTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Product());
                    return new TableGateway('product', $dbAdapter, null, $resultSetPrototype);
                },
                'Product\Model\CategoryTable' => function ($sm) {
                    $tableGateway = $sm->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Category());
                    return new TableGateway('product_category', $dbAdapter, null, $resultSetPrototype);
                },
                'Product\Model\CategoryLanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('CategoryLanguageTableGateway');
                    $table = new CategoryLanguageTable($tableGateway);
                    return $table;
                },
                'CategoryLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CategoryLanguage());
                    return new TableGateway('product_category_has_language', $dbAdapter, null, $resultSetPrototype);
                },
                'Product\Model\ProductHasCategoryTable' => function ($sm) {
                    $tableGateway = $sm->get('ProductHasCategoryTableGateway');
                    $table = new ProductHasCategoryTable($tableGateway);
                    return $table;
                },
                'ProductHasCategoryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new ProductHasCategory());
                    return new TableGateway('product_has_category', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
