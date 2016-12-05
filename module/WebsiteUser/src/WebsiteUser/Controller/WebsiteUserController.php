<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/WebsiteUser for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace WebsiteUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class WebsiteUserController extends AbstractActionController
{
    protected $websiteUserTable;
    
    public function indexAction()
    {
        //Get the Website ID
        $id = (int) $this->params()->fromRoute('idWebsite', 0);
        
        //Get the data for this website
        $websiteService = $this->getServiceLocator()->get('website');
        $website = $websiteService->select(array("idWebsite"=>$id));
        
        $websites = $websiteService->select();

        //Get all relationships for this website
        $users = $this->getWebsiteUserTable()->fetchByWebsite($id);
        
        return array("users"=>$users, "website"=>$website, "websites"=>$websites);
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /websiteUser/website-user/foo
        return array();
    }
    
    /*
     * Database managers
     */
    
    public function getWebsiteUserTable() {
        if (!$this->websiteUserTable) {
            $sm = $this->getServiceLocator();
            $this->websiteUserTable = $sm->get('WebsiteUser\Model\WebsiteUserTable');
        }
        return $this->websiteUserTable;
    }
}
