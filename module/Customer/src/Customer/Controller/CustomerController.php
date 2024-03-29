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
use Customer\Model\CustomerAddress;
use Customer\Model\CustomerContact;

class CustomerController extends AbstractActionController
{
    protected $moduleId = 9;
    protected $customerTable;
    protected $customerPersonTable;
    protected $customerCompanyTable;
    protected $customerAddressTable;
    protected $customerContactTable;
    
    public function indexAction()
    {
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            // Page number
            $currentPage = $this->params()->fromQuery('page');
            // Number of records per page
            $countPerPage = "30";
            
            $customers = $this->getCustomerTable()->fetchAll($logedUser["idCompany"], $currentPage, $countPerPage);
            return array("customers"=>$customers, "permission"=>$permission);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function newAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
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
                    //Check if this email is already in using
                    if($this->getCustomerTable()->emailExists($customer->email, $customer->company_id)>0){
                        $message->setCode("CUSTOMER010");
                    }else{
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
                        //If all validations are ok
                        if($flag){
                            $customer->log = "Cliente adicionado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
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
    
    public function viewAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            if($customerData->customerType==1){
                $customerPersonData = $this->getCustomerPersonTable()->getCustomer($id);
            }else{
                $customerCompanyData = $this->getCustomerCompanyTable()->getCustomer($id);
            }
            $customerAddressData = $this->getCustomerAddressTable()->getAddress($id, "customer_id", true);
            $customerContactData = $this->getCustomerContactTable()->getContact($id, "customer_id", true);
            
            $this->layout("layout/layout_blank");
            return array(
                //"countries"=>$countries,
                "customer"=>$customerData,
                "customerPerson"=>$customerPersonData,
                "customerCompany"=>$customerCompanyData,
                "customerAddressData"=>$customerAddressData,
                "customerContactData"=>$customerContactData
            );
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function editAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
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
                    //Check if this email is already in using
                    if($this->getCustomerTable()->emailExists($customer->email, $customer->company_id, $id)>0){
                        $message->setCode("CUSTOMER010");
                    }else{
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
    
    /**
     * This action delete a article from the system
     * @return \Zend\Http\Response|NULL[]
     */
    public function deleteAction(){
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) { //If there is no ID
                $this->getServiceLocator()->get('systemLog')->addLog(0, "Article ".$id." not found to delete.", 5);
                return $this->redirect()->toRoute('article');
            }
            //Recursive delete
            $message = $this->getServiceLocator()->get('customerMessages');
            
            //First, remove associated addresses
            $this->getCustomerAddressTable()->deleteAllAddresses($id);
            //Now, remove all associated contacts
            $this->getCustomerContactTable()->deleteAllContacts($id);
            //Remove customer Person or Company
            if($this->getCustomerPersonTable()->deleteCustomer($id) || $this->getCustomerCompanyTable()->deleteCustomer($id)){
                //Now, remove customer
                $result = $this->getCustomerTable()->deleteCustomer($id);
                $message->setCode("CUSTOMER006");
            }
            return $this->redirect()->toRoute("customer");
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    /**
     * This action show addresses options
     * @return \Zend\Http\Response|NULL[]|unknown[]|\Customer\Model\ArrayObject[]|\Customer\Model\NULL[]
     */
    public function addressAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
        
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $customerPerson = new CustomerPerson();
            $customerCompany = new CustomerCompany();
            if($customerData->customerType==1){
                $customerPersonData = $this->getCustomerPersonTable()->getCustomer($id);
            }else{
                $customerCompanyData = $this->getCustomerCompanyTable()->getCustomer($id);
            }
            
            $request = $this->getRequest();
            if ($request->isPost()) {
                $address = new \Customer\Model\CustomerAddress();
                $data = $request->getPost();
                $address->exchangeArray($data);
                
                if($address->validation()){ //Verify to check if all data is ok
                    $result = $this->getCustomerAddressTable()->saveAddress($address);
                    $message->setCode($result);
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                    
                    if($result=="CUSTOMER012"){
                        die("error");
                    }else{
                        $log = "\n Novo endereço adicionado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                        $this->getCustomerTable()->saveLogChanges($id, $log);
                        die("success");
                    }
                }else{
                    $message->setCode("CUSTOMER013");
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                    die("error");
                }
                
            }
            $addresses = $this->getCustomerAddressTable()->fetchAll($id);
            $countries = $this->getServiceLocator()->get('countryFactory')->fetchAll();
            $message = $this->getServiceLocator()->get('customerMessages');
            
            return array(
                "addresses"=>$addresses,
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
    
    /**
     * This aciton list, using Jquery, all address for some customer
     * @return \Zend\Http\Response|NULL[]|\Zend\Db\ResultSet\ResultSet[]|\Customer\Model\ArrayObject[]|\Customer\Model\NULL[]
     */
    public function addressListAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
        
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $countries = $this->getServiceLocator()->get('countryFactory')->fetchAll();
            $countries2 = $this->getServiceLocator()->get('countryFactory')->fetchAll();
            
            $addresses = $this->getCustomerAddressTable()->fetchAll($id);
            $this->layout("layout/layout_blank");
            return array(
                "countries"=>$countries,
                "countries2"=>$countries2,
                "addresses"=>$addresses,
                "message"=>$message->getMessage(),
                "customer"=>$customerData,
            );
        }
    }
    
    public function addressDeleteAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
        
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $request = $this->getRequest();
            $dados = $request->getContent();
            $idAddress = substr($dados, 3);
            $result = $this->getCustomerAddressTable()->deleteAddress($idAddress);
            $message->setCode($result);
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            if($result=="CUSTOMER017"){ //Agora deleta do banco de dados
                $log = "\n Endereço removido por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                $this->getCustomerTable()->saveLogChanges($id, $log);
                die("success");
            }else{
               die("error");
            }
        }
    }
    
    public function addressDataAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
    
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $request = $this->getRequest();
            $dados = $request->getContent();
        
            $idAddress = substr($dados, 3);
            $result = $this->getCustomerAddressTable()->getAddress($idAddress);
            die(json_encode($result));
        }
    }
    
    public function addressEditAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
        
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $request = $this->getRequest();
            $dados = $request->getPost();
            
            $address = new CustomerAddress();
            $address->exchangeArray($dados);
            if($address->validation()){
                //Agora Salva do banco de dados
                $result = $this->getCustomerAddressTable()->saveAddress($address);
                $message->setCode($result);
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                
                if($result=="CUSTOMER014"){ 
                    $log = "\n Endereço alterado por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                    $this->getCustomerTable()->saveLogChanges($id, $log);
                    die("success");
                }else{
                   die("error");
                }
            }else{
                $result = "CUSTOMER013";
                $message->setCode($result);
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                die("error");
            }
        }
    }
    
    /**
     * This action show contacts 
     * @return \Zend\Http\Response|NULL[]|unknown[]|\Customer\Model\ArrayObject[]|\Customer\Model\NULL[]
     */
    public function contactsAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
    
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $customerPerson = new CustomerPerson();
            $customerCompany = new CustomerCompany();
            if($customerData->customerType==1){
                $customerPersonData = $this->getCustomerPersonTable()->getCustomer($id);
            }else{
                $customerCompanyData = $this->getCustomerCompanyTable()->getCustomer($id);
            }
    
            $countries = $this->getServiceLocator()->get('countryFactory')->fetchAll();
            $message = $this->getServiceLocator()->get('customerMessages');
    
            $contacts = $this->getCustomerContactTable()->fetchAll($id);
            
            return array(
                "message"=>$message->getMessage(),
                "countries"=>$countries,
                "customer"=>$customerData,
                "customerPerson"=>$customerPersonData,
                "customerCompany"=>$customerCompanyData,
                "contacts"=>$contacts
            );
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function contactsListAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
    
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            
            $contacts = $this->getCustomerContactTable()->fetchAll($id);
            $this->layout("layout/layout_blank");
            
            return array(
                "customer"=>$customerData,
                "contacts"=>$contacts
            );
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function contactAddAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
        
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $request = $this->getRequest();
            $data = $request->getPost();
            $customerContact = new CustomerContact();
            $customerContact->exchangeArray($data);
            $customerContact->customer_id = $id;
            if($customerContact->validation()){
                $result = $this->getCustomerContactTable()->saveContact($customerContact);
                $message->setCode($result);
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                if($result=="CUSTOMER019"){
                    $log = "\n Dados de contato adicionados por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                    $this->getCustomerTable()->saveLogChanges($id, $log);
                    die("success");
                }else{
                    die("error");
                }
            }else{
                $result = "CUSTOMER021";
                $message->setCode($result);
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                die("error");
            }
        }else{
            return "forbiden";
        }
    }
    
    /**
     * This action delete a contact
     * @return \Zend\Http\Response|string
     */
    public function contactDeleteAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
    
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $request = $this->getRequest();
            $idContact = $request->getContent();
            $result = $this->getCustomerContactTable()->deleteContact($idContact);
            $message->setCode($result);
            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            if($result=="CUSTOMER025"){
                $log = "\n Dados de contato apagados por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                $this->getCustomerTable()->saveLogChanges($id, $log);
                die("success");
            }else{
                die("error");
            }
        }else{
            return "forbiden";
        }
    }
    
    public function contactEditAction(){
        //Check if this user can access this address
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        $message = $this->getServiceLocator()->get('customerMessages');
    
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Customer ID
            $id = (int) $this->params()->fromRoute('id', 0);
            //First of all, check if this customer is from this company
            $customerData = $this->getCustomerTable()->getCustomer($id);
            if($customerData->company_id!=$logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                return $this->redirect()->toRoute('noPermission');
            }
            $idContact = $_GET["idContact"];
            $request = $this->getRequest();
            if($request->isPost()){
                $data = $request->getPost();
                $customerContact = new CustomerContact();
                $customerContact->exchangeArray($data);
                if($customerContact->validation(true)){
                    $result = $this->getCustomerContactTable()->saveContact($customerContact);
                    $message->setCode($result);
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                    if($result=="CUSTOMER022"){
                        $log = "\n Dados de contato alterados por meio do site pelo usuário ".$logedUser["name"]." (".$logedUser["idUser"].") em ".date("d/m/Y H:i:s").".";
                        $this->getCustomerTable()->saveLogChanges($id, $log);
                        die("success");
                    }else{
                        die("error");
                    }
                }else{
                    die("error");
                }
            }
            $result = $this->getCustomerContactTable()->getContact($idContact);
            die(json_encode($result));
        }else{
            return "forbiden";
        }
    }
    
    /*
    public function checkEmailAction(){
        //Check if this user can access this article
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $logedUser["idCompany"]==1){
            // Get Customer ID
            $id = $_POST["idCustomer"];
            $idCompany = $_POST["idCompany"];
            $email = $_POST["email"];
            
            if($this->getCustomerTable()->emailExists($email, $idCompany, $id)>0){
                echo "error";
            }else{
                echo "success";
            }
        }else{
            echo "forbiden";
        }
    }
    */
    
    //Customer
    public function getCustomerTable(){
        if(!$this->customerTable){
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Customer\Model\CustomerTable');
        }
        return $this->customerTable;
    }
    
    //Cusotmer Person
    public function getCustomerPersonTable(){
        if(!$this->customerPersonTable){
            $sm = $this->getServiceLocator();
            $this->customerPersonTable = $sm->get('Customer\Model\CustomerPersonTable');
        }
        return $this->customerPersonTable;
    }
    //Customer Company
    public function getCustomerCompanyTable(){
        if(!$this->customerCompanyTable){
            $sm = $this->getServiceLocator();
            $this->customerCompanyTable = $sm->get('Customer\Model\CustomerCompanyTable');
        }
        return $this->customerCompanyTable;
    }
    //Address
    public function getCustomerAddressTable(){
        if(!$this->customerAddressTable){
            $sm = $this->getServiceLocator();
            $this->customerAddressTable = $sm->get('Customer\Model\CustomerAddressTable');
        }
        return $this->customerAddressTable;
    }
    //Contact
    public function getCustomerContactTable(){
        if(!$this->customerContactTable){
            $sm = $this->getServiceLocator();
            $this->customerContactTable = $sm->get('Customer\Model\CustomerContactTable');
        }
        return $this->customerContactTable;
    }

}
