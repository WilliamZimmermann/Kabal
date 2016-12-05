<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function nopermissionAction(){
        return array();
    }
    
    public function websiteChangeAction(){
        //Get the Website ID
        $idWebsite = (int) $this->params()->fromRoute('idWebsite', 0);
        
        //Get the data for this website
        $websiteService = $this->getServiceLocator()->get('website');
        
        $userData = $this->getServiceLocator()->get('user')->getUserSession();
        
        //First, check if this user have acess to this website
        $userWebsiteService = $this->getServiceLocator()->get('website_user')->haveRelationship($userData["idUser"], $idWebsite);
       
        if($userWebsiteService>0){
            $website = $websiteService->select(array("idWebsite"=>$idWebsite))->current();
            $session = new Container('Auth');
            $session->__set("websiteName", $website->name);
            $session->__set("websiteId", $idWebsite);
            $this->redirect()->toRoute("home");
        }else{
            $this->redirect()->toRoute("noPermission");
        }
        
    }
}
