<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Website for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Website;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Website\Model\Website;
use Zend\Db\TableGateway\TableGateway;
use Website\Model\WebsiteTable;
use Website\Model\WebsiteUserTable;
use Website\Model\WebsiteUser;
use Website\Model\WebsiteModule;
use Website\Model\WebsiteModuleTable;
use Website\Model\LanguageTable;
use Website\Model\Language;
use Website\Model\WebsiteLanguageTable;
use Website\Model\WebsiteLanguage;

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
                'Website\Model\WebsiteTable' => function ($sm) {
                    $tableGateway = $sm->get('WebsiteTableGateway');
                    $table = new WebsiteTable($tableGateway);
                    return $table;
                },
                'WebsiteTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Website());
                    return new TableGateway('company_website', $dbAdapter, null, $resultSetPrototype);
                },
                'Website\Model\WebsiteModuleTable' => function ($sm) {
                    $tableGateway = $sm->get('WebsiteModuleTableGateway');
                    $table = new WebsiteModuleTable($tableGateway);
                    return $table;
                },
                'WebsiteModuleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new WebsiteModule());
                    return new TableGateway('company_website_has_system_module', $dbAdapter, null, $resultSetPrototype);
                },
                'Website\Model\WebsiteUserTable' => function ($sm) {
                    $tableGateway = $sm->get('WebsiteUserTableGateway');
                    $table = new WebsiteUserTable($tableGateway);
                    return $table;
                },
                'WebsiteUserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new WebsiteUser());
                    return new TableGateway('company_user_has_company_website', $dbAdapter, null, $resultSetPrototype);
                },
                'Website\Model\LanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('LanguageTableGateway');
                    $table = new LanguageTable($tableGateway);
                    return $table;
                },
                'LanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Language());
                    return new TableGateway('language', $dbAdapter, null, $resultSetPrototype);
                },
                'Website\Model\WebsiteLanguageTable' => function ($sm) {
                    $tableGateway = $sm->get('WebsiteLanguageTableGateway');
                    $table = new WebsiteLanguageTable($tableGateway);
                    return $table;
                },
                'WebsiteLanguageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new WebsiteLanguage());
                    return new TableGateway('company_website_has_language', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }
}
