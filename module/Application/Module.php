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
}
