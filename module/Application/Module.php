<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;
use Zend\Db\ResultSet\ResultSet;
use Application\Model\Country;
use Zend\Db\TableGateway\TableGateway;
use Application\Model\CountryTable;
use Application\Model\ZoneTable;
use Application\Model\Zone;
use Application\Model\CityTable;
use Application\Model\City;
use Application\Model\UserPermissionTable;
use Application\Model\UserPermission;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager      = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
 
        $viewModel = $e->getViewModel();
        
        $userData = $serviceManager->get('user')->getUserSession();
        /*
         * Get all websites for this user
         */
        $viewModel->userWebsites = $serviceManager->get('website_user')->fetchByUser($userData["idUser"]);
        $viewModel->permissions = $serviceManager->get('menuFactory')->fetchByUser($userData["idUser"], $userData["idWebsite"]);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
         
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, array($this, 'checkAuthentication'), 100);
    }
    
    /**
     * Method to verify the authentication
     * @param unknown $e
     */
    public function checkAuthentication($e)
    {
        // Get the actual route
        $controller = $e->getTarget();
        $route = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
         
        if ($route != 'login') { //If it is not the login page, check if this session is open
             
            $session = new Container('Auth');
            if (!$session->adm) {
                return $controller->redirect()->toRoute('login');
            }
             
        }
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\CountryTable' => function ($sm) {
                    $tableGateway = $sm->get('CountryTableGateway');
                    $table = new CountryTable($tableGateway);
                    return $table;
                },
                'CountryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Country());
                    return new TableGateway('country', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\ZoneTable' => function ($sm) {
                    $tableGateway = $sm->get('ZoneTableGateway');
                    $table = new ZoneTable($tableGateway);
                    return $table;
                },
                'ZoneTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Zone());
                    return new TableGateway('zone', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\CityTable' => function ($sm) {
                    $tableGateway = $sm->get('CityTableGateway');
                    $table = new CityTable($tableGateway);
                    return $table;
                },
                'CityTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new City());
                    return new TableGateway('city', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\UserPermission' => function ($sm) {
                    $tableGateway = $sm->get('UserPermissionTableGateway');
                    $table = new UserPermissionTable($tableGateway);
                    return $table;
                },
                'UserPermissionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserPermission());
                    return new TableGateway('company_user_permissions', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }
}
