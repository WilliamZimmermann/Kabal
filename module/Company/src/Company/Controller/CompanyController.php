<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Company for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Company\Model\Company;
use Zend\Mvc\I18n\Translator;

class CompanyController extends AbstractActionController
{
    protected $companyTable;
    protected $moduleId = 1; //This is this module identity, please, don't change it
    public $translator;
        
    /**
     * The index page show all the companies
     * {@inheritDoc}
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            return array("companies"=>$this->getCompanyTable()->fetchAll());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function newAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            $company = new Company();
            //If was a POST
            $message = $this->getServiceLocator()->get('systemMessages');
            $request = $this->getRequest();
            if($request->isPost()){
                $company->exchangeArray($request->getPost());
                if($company->validation()){ //Verify to check if all data is ok
                    //After populate the object, will save in Database
                    $result = $this->getCompanyTable()->saveCompany($company);
                    
                    $message->setCode($result);
                }else{
                    $message->setCode("COMPANY003");
               }
               $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array("message"=>$message->getMessage(), "copany"=>$company);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
   
    public function editAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            //Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Will check if this id exists at the database
            if($this->getCompanyTable()->getCompany($id)){
                $company = new Company();
                
                //If is a POST
                $request = $this->getRequest();
                if($request->isPost()){
                    //Will put the new data at the Company object
                    $company->exchangeArray($request->getPost());
                    if($company->validation()){
                        //After populate the object, will save in Database
                        $result = $this->getCompanyTable()->saveCompany($company);
                        $message->setCode($result);
                    }else{
                        $message->setCode("COMPANY006");
                    }
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());        
                }
                //Get from the Database the Company data
                $data = $this->getCompanyTable()->getCompany($id);
            
                return array("message"=>$message->getMessage(), "copany"=>$data);
            }else{ //If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()->get('systemLog')->addLog(0, "User ".$id." not found to edit.", 5);
                return $this->redirect()->toRoute('company');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function deleteAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) { //If there is no ID
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Company ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('company');
            }
                    
            $message = $this->getServiceLocator()->get('systemMessages');
            
            $result = $this->getCompanyTable()->deleteCompany($id);
            $message->setCode($result);
            
            //Save log
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            
            return array("message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /*
     * Database managers
     */
    
    public function getCompanyTable(){
        if(!$this->companyTable){
            $sm = $this->getServiceLocator();
            $this->companyTable = $sm->get('Company\Model\CompanyTable');
        }
        return $this->companyTable;
    }

    
}
