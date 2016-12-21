<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Customer for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Customer;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Customer\Model\CustomerTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Customer\Model\Customer;
use Customer\Model\CustomerPersonTable;
use Customer\Model\CustomerPerson;
use Customer\Model\CustomerCompany;
use Customer\Model\CustomerCompanyTable;

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
                'Customer\Model\CustomerTable' => function ($sm) {
                    $tableGateway = $sm->get('CustomerTableGateway');
                    $table = new CustomerTable($tableGateway);
                    return $table;
                },
                'CustomerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Customer());
                    return new TableGateway('customer', $dbAdapter, null, $resultSetPrototype);
                },
                'Customer\Model\CustomerPersonTable' => function ($sm) {
                    $tableGateway = $sm->get('CustomerPersonTableGateway');
                    $table = new CustomerPersonTable($tableGateway);
                    return $table;
                },
                'CustomerPersonTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CustomerPerson());
                    return new TableGateway('customer_person', $dbAdapter, null, $resultSetPrototype);
                },
                'Customer\Model\CustomerCompanyTable' => function ($sm) {
                    $tableGateway = $sm->get('CustomerCompanyTableGateway');
                    $table = new CustomerCompanyTable($tableGateway);
                    return $table;
                },
                'CustomerCompanyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CustomerCompany());
                    return new TableGateway('customer_company', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }
}
