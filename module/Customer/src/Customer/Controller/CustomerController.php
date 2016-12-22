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
                        $data["document_1"] = $data["p_document_1"];
                        $data["document_2"] = $data["p_document_2"];
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
                        $data["document_1"] = $data["c_document_1"];
                        $data["document_2"] = $data["c_document_2"];
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
                    /**
                     * TODO - há algum erro daqui para baixo
                     */
                    //If all validations are ok
                    if($flag){
                        $customer->log = "Cliente adicionado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d-m-Y H:i:s").".";
                        $result = $this->getCustomerTable()->saveCustomer($customer);
                        if($result["code"]=="CUSTOMER001"){ //If saved
                            //Must to save person or company
                            if($customer->customerType==1){//Person
                                $customerPerson->customer_id = $result["id"];
                                if($this->getCustomerPersonTable()->saveCustomer($customerPerson, 1)){
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
                                $this->redirect()->toRoute("customer/edit", array("id"=>$result["id"], "code"=>$result["code"]));
                            }else{
                                /*
                                 * If there was a problem when tried to insert in Customer Person or Company
                                 * delete customer from database
                                 */
                                $this->getCustomerTable()->deleteCustomer($result["id"]);
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
    
    public function editAction(){
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $logedUser["idCompany"]==1){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $countries = $this->getServiceLocator()->get('countryFactory')->fetchAll();

            $customerPerson = new CustomerPerson();
            $customerCompany = new CustomerCompany();
            if($customerData->customerType==1){
                $customerPersonData = $this->getCustomerPersonTable()->getCustomer($id);
            }else{
                $customerCompanyData = $this->getCustomerCompanyTable()->getCustomer($id);
            }
    
            $message = $this->getServiceLocator()->get('customerMessages');
            if($this->params()->fromRoute('code')){
                $message->setCode($this->params()->fromRoute('code'));
            }
            $request = $this->getRequest();
             
            if($request->isPost()){
                $customer = new Customer();
                $data = $request->getPost();
                $customer->exchangeArray($data);
                $customer->idCustomer = $customerData->idCustomer;
                $customer->company_id = $customerData->company_id;
                $customer->addedBy = $customerData->addedBy;
                if($customer->validation(false)){ //Verify to check if all data is ok
                    $flag = true;
                    //Check what kind of customer is the client (person or comapany)
                    if($customer->customerType==1){
                        $data["document_1"] = $data["p_document_1"];
                        $data["document_2"] = $data["p_document_2"];
                        $customerPerson->exchangeArray($data);
                        if($customerPerson->validation()){ //Standard validations are ok
                            //If necessary, make a validation for document_1
                            if($customerPerson->document_1){
                                //Check country id from customer
                                if($customer->country_id == 33){ //33 is Brazil
                                    //So, must to validate the CPF
                                    if(!$customerPerson->cpfValidator($customerPerson->document_1)){
                                        $flag = false;
                                        $message->setCode("CUSTOMER006");
                                    }
                                }
                            }
                        }else{
                            $message->setCode("CUSTOMER006");
                        }
                    }else{
                        $data["document_1"] = $data["c_document_1"];
                        $data["document_2"] = $data["c_document_2"];
                        $customerCompany->exchangeArray($data);
                        if($customerCompany->validation()){ //Standard validations are ok
                            //If necessary, make a validation for document_1
                            if($customerCompany->document_1){
                                //Check country id for customer
                                if($customer->country_id == 33){ //33 is Brazil
                                    //So, must to validate the CNPJ
                                    if(!$customerCompany->cnpjValidator($customerCompany->document_1)){
                                        $flag = false;
                                        $message->setCode("CUSTOMER006");
                                    }
                                }
                            }
                        }else{
                            $message->setCode("CUSTOMER006");
                        }
                    }
                    /**
                     * TODO - há algum erro daqui para baixo
                     */
                    //If all validations are ok
                    if($flag){
                        $customer->dateCreated = $customerData->dateCreated;
                        $customer->log = $customerData->log."<br>"."Cliente alterado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                        $result = $this->getCustomerTable()->saveCustomer($customer);
                        if($result=="CUSTOMER004"){ //If saved
                            //Must to save person or company
                            if($customer->customerType==1){//Person
                                $customerPerson->customer_id = $customerData->idCustomer;
                                if($this->getCustomerPersonTable()->saveCustomer($customerPerson, 2)){
                                    $flag = true;
                                }
                            }else{//Company
                                
                                $customerCompany->customer_id = $customerData->idCustomer;
                                if($this->getCustomerCompanyTable()->saveCustomer($customerCompany, 2)){
                                    $flag = true;
                                }
                            }
                            if($flag){ //All updates are ok
                                //Load new data again
                                $customerData = $this->getCustomerTable()->getCustomer($id);
                                if($customerData->customerType==1){
                                    $customerPersonData = $this->getCustomerPersonTable()->getCustomer($id);
                                }else{
                                    $customerCompanyData = $this->getCustomerCompanyTable()->getCustomer($id);
                                }
                            }else{
                                /*
                                 * If there was a problem when tried to update in Customer Person or Company
                                 */
                                $result = "CUSTOMER005";
                            }
                        }
                        $message->setCode($result);
                    }
                }else{
                    $message->setCode("CUSTOMER006");
                }
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            return array(
                "message"=>$message->getMessage(),
                "countries"=>$countries,
                "customer"=>$customerData,
                "customerPerson"=>$customerPersonData,
                "customerCompany"=>$customerCompanyData
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
