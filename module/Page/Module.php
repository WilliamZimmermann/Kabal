<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Page for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Page;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Page\Model\PageTable;
use Zend\Db\ResultSet\ResultSet;
use Page\Model\Page;
use Zend\Db\TableGateway\TableGateway;
use Page\Model\PageLanguageTable;
use Page\Model\PageLanguage;

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
                'Page\Model\PageTable' => function ($sm) {
                    $tableGateway = $sm->get('PageTableGateway');
                    $table = new PageTable($tableGateway);
                    return $table;
                },
                'PageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Page());
                    return new TableGateway('website_page', $dbAdapter, null, $resultSetPrototype);
                },
                'Page\Model\PageLanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('PageLanguageTableGateway');
                    $table = new PageLanguageTable($tableGateway);
                    return $table;
                },
                'PageLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new PageLanguage());
                    return new TableGateway('page_has_language', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
