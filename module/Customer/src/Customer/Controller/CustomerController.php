<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Customer for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Customer\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class CustomerController extends AbstractActionController
{
    protected $moduleId = 9;
    protected $customerTable;
    protected $customerPersonTable;
    protected $customerCompanyTable;
    
    public function indexAction()
    {
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete") || $logedUser["idCompany"]==1){
            $permissions = array(
                "insert"=>$this->getServiceLocator()->get('user')->checkPermission($permission, "insert"),
                "edit"=>$this->getServiceLocator()->get('user')->checkPermission($permission, "edit"),
                "delete"=>$this->getServiceLocator()->get('user')->checkPermission($permission, "delete")
            );
            $customers = $this->getCustomerTable()->fetchAll($logedUser["idCompany"]);
            return array("customers"=>$customers, "permissions"=>$permissions);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function newAction(){
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $logedUser["idCompany"]==1){
            $countries = $this->getServiceLocator()->get('countryFactory')->fetchAll();
            return array("countries"=>$countries);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function getCustomerTable(){
        if(!$this->customerTable){
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Customer\Model\CustomerTable');
        }
        return $this->customerTable;
    }
    
    public function getCustomerPersonTable(){
        if(!$this->customerPersonTable){
            $sm = $this->getServiceLocator();
            $this->customerPersonTable = $sm->get('Customer\Model\CustomerPersonTable');
        }
        return $this->customerPersonTable;
    }
    
    public function getCustomerCompanyTable(){
        if(!$this->customerCompanyTable){
            $sm = $this->getServiceLocator();
            $this->customerCompanyTable = $sm->get('Customer\Model\CustomerCompanyTable');
        }
        return $this->customerCompanyTable;
    }

}
