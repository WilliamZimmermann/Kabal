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
use Customer\Model\Customer;
use Customer\Model\CustomerPerson;
use Customer\Model\CustomerCompany;

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
            $customer = new Customer();
            $customerPerson = new CustomerPerson();
            $customerCompany = new CustomerCompany();
            
            $message = $this->getServiceLocator()->get('customerMessages');
            $request = $this->getRequest();
           
            if($request->isPost()){
                $data = $request->getPost();
                $customer->exchangeArray($data);
                $customer->company_id = $logedUser["idCompany"];
                $customer->addedBy = $logedUser["idUser"];
                if($customer->validation()){ //Verify to check if all data is ok
                    $flag = true;
                    //Check what kind of customer is the client (person or comapany)
                    if($customer->customerType==1){
                        $customerPerson->exchangeArray($data);
                        if($customerPerson->validation()){ //Standard validations are ok
                            //If necessary, make a validation for document_1
                            if($customerPerson->document_1){
                                //Check country id from customer
                                if($customer->country_id == 33){ //33 is Brazil
                                    //So, must to validate the CPF
                                    if(!$customerPerson->cpfValidator($customerPerson->document_1)){
                                        $flag = false;
                                        $message->setCode("CUSTOMER003");
                                    }
                                }
                            }
                        }else{
                            $message->setCode("CUSTOMER003");
                        }
                    }else{
                        $customerCompany->exchangeArray($data);
                        if($customerCompany->validation()){ //Standard validations are ok
                            //If necessary, make a validation for document_1
                            if($customerCompany->document_1){
                                //Check country id for customer
                                if($customer->country_id == 33){ //33 is Brazil
                                    //So, must to validate the CNPJ
                                    if(!$customerCompany->cnpjValidator($customerCompany->document_1)){
                                        $flag = false;
                                        $message->setCode("CUSTOMER003");
                                    }
                                }
                            }
                        }else{
                            $message->setCode("CUSTOMER003");
                        }
                    }
                    die("chegou");
                    /**
                     * TODO - há algum erro daqui para baixo
                     */
                    //If all validations are ok
                    if($flag){
                        $result = $this->customerTable->saveCustomer($customer);
                        if($result["code"=="CUSTOMER001"]){ //If saved
                            //Must to save person or company
                            if($customer->customerType<>2){//Person
                                $customerPerson->customer_id = $result["id"];
                                if($this->getCustomerPersonTable()->saveCustomer($customerPerson)){
                                    $flag = true;
                                }else{
                                    $this->getServiceLocator()->get('systemLog')->addLog(0, "Failed when tried to save Customer Person.", 3);
                                    $flag = false;
                                }
                            }else{//Company
                                $customerCompany->customer_id = $result["id"];
                                if($this->getCustomerCompanyTable()->saveCustomer($customerCompany)){
                                    $flag = true;
                                }else{
                                    $this->getServiceLocator()->get('systemLog')->addLog(0, "Failed when tried to save Customer Company.", 3);
                                    $flag = false;
                                }
                            }
                            if($flag){ //All inserts are ok
                                /**
                                 * TODO
                                 * Redirect for update page
                                 */
                            }else{
                                /*
                                 * If there was a problem when tried to insert in Customer Person or Company
                                 * delete customer from database
                                 */
                                $this->customerTable->deleteCustomer($result["id"]);
                                $result["code"] = "CUSTOMER003";
                            }
                        }
                        $message->setCode($result["code"]);
                    }
                }else{
                    $message->setCode("CUSTOMER003");
                }
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array(
                "message"=>$message->getMessage(), 
                "countries"=>$countries, 
                "customer"=>$customer, 
                "customerPerson"=>$customerPerson,
                "customerCompany"=>$customerCompany
            );
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
