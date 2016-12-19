<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/User for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Model\User;
use Zend\Db\Sql\Select;
use User\Model\UserPermissions;

class UserController extends AbstractActionController {

    protected $moduleId = 3; //This is this module identity, please, don't change it
    protected $userTable;
    protected $userPermissionsTable;
    protected $companyTable;

    public function indexAction() {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert") || $this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            if($logedUser["idCompany"]==1){
                $companyId = null;
                $ableToInsert = true;
                $companyData = $this->getServiceLocator()->get('companyTable')->getCompany(1);
            }else{
                $companyId = $logedUser["idCompany"];
                //Check if this company can insert more users
                $companyData = $this->getServiceLocator()->get('companyTable')->getCompany($companyId);
                $users = $this->getUserTable()->fetchAll($companyId);
                if($companyData->max_users<=count($users)){
                    //This company already arrived to the users limit, can't insert more
                    $ableToInsert = false;
                }else{
                    $ableToInsert = true;
                }
            }
            
            return array("users" => $this->getUserTable()->fetchAll($companyId), "logedUserPermission"=>$permission, "ableToInsert"=>$ableToInsert, "companyData"=>$companyData);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function newAction() {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "insert")){
            $user = new User();
            
            //Get Message Service
            $message = $this->getServiceLocator()->get('systemMessages');
            
            if($logedUser["idCompany"]==1){
                $companies = $this->getServiceLocator()->get('companies')->select();
            }else{
                //Check if this company can insert more users
                $companyData = $this->getServiceLocator()->get('companyTable')->getCompany($logedUser["idCompany"]);
                $users = $this->getUserTable()->fetchAll($logedUser["idCompany"]);
                if($companyData->max_users<=count($users)){
                    //This company already arrived to the users limit, can't insert more
                    $this->redirect()->toRoute("user");
                }
                $companies = null;
            }
                    
            $request = $this->getRequest();
            if ($request->isPost()) {
                $user->exchangeArray($request->getPost());
                if ($user->validation()) { //Validate all data if are ok
                    //After populate the object, will save in Database
                    if($logedUser["idCompany"]!=1){
                        $user->company_id = $logedUser["idCompany"];
                    }
                    if($user->type==1){
                        /* For security reasons, if type is 1 (tecnician), 
                        * check if the user that is inserting is also a tecnician
                        * */
                        $userLogedData = $this->getServiceLocator()->get('userDb')->getUser($logedUser["logedUser"]);
                        if($userLogedData->type!=1){
                           //If he is not a tecnician, so, put the new user type 2 - Administrator.
                            $user->type = 2;
                        }
                    }
                    $result = $this->getUserTable()->saveUser($user);
                    $message->setCode($result);
                } else {
                    $message->setCode("USER004");    
                }
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
    
            return array("message" => $message->getMessage(), "user"=>$user, "companies" => $companies);
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
     public function editAction(){
         //Check if this user can access this page
         $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
         $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
         if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            //Get the User ID
            $id = (int) $this->params()->fromRoute('id', 0);
            
            //Get companies for the select if it's the Manager Account
            if($logedUser["idCompany"]==1){
                $companies = $this->getServiceLocator()->get('companies')->select();
            }else{
                $companies = null;
            }
            
            //Check if this id exists at the database
            $user = $this->getUserTable()->getUser($id);
            if($user){
                //Check if this user is a user of this company
                if(($user->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                $user = new User();
                
                //If is a POST
                $message = $this->getServiceLocator()->get('systemMessages');
                $request = $this->getRequest();
                if($request->isPost()){
                    //Will put the new data at the Company object
                    $user->exchangeArray($request->getPost());
                    if($user->validation(true)){
                        //Before to save, will check again if this user is from this company
                        if($logedUser["idCompany"]!=1){
                            $user->company_id = $logedUser["idCompany"];
                        }
                        if($user->type==1){
                            /* For security reasons, if type is 1 (tecnician),
                             * check if the user that is inserting is also a tecnician
                             * */
                            $userLogedData = $this->getServiceLocator()->get('userDb')->getUser($logedUser["idUser"]);
                            
                            if($userLogedData->type!=1){
                                //If he is not a tecnician, so, put the new user type 2 - Administrator.
                                $user->type = 2;
                            }
                        }
                        
                        //After populate the object, will save in Database
                        $result = $this->getUserTable()->saveUser($user);
                        $message->setCode($result);
                    }else{
                        $message->setCode("USER009");
                    }
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                //Get from the Database the Company data
                $data = $this->getUserTable()->getUser($id);
            
                return array("message" => $message->getMessage(), "user"=>$data, "companies" => $companies);
            }else{ //If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()->get('systemLog')->addLog(0, "This user ".$id." was not found. Will redirect to index page.", 5);
                return $this->redirect()->toRoute('user');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function editPasswordAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        //Get the User ID
        $id = (int) $this->params()->fromRoute('id', 0);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit") || $id==$logedUser["idUser"]){
            //Check if this id exists at the database
            $user = $this->getUserTable()->getUser($id);
            if($user){
                //Check if this user is a user of this company
                if($user->company_id != $logedUser["idCompany"] && $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                $user = new User();
            
                //If is a POST
                $message = $this->getServiceLocator()->get('systemMessages');
                $request = $this->getRequest();
                if($request->isPost()){
                    //Put the new data at the Company object
                    $user->exchangeArray($request->getPost());
                    if($user->validPassword()){
                        //Before to save, will check again if this user is from this company
                        $userCheck = $this->getUserTable()->getUser($user->idUser);
                        if($userCheck->company_id != $logedUser["idCompany"]){
                            return $this->redirect()->toRoute('noPermission');
                        }
                        //After populate the object, will save in Database
                        $result = $this->getUserTable()->savePassword($user);
                        $message->setCode($result, array("id"=>$id));
                    }else{
                        $message->setCode("USER013", array("id"=>$id));
                    }
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                //Get from the Database the Company data
                $data = $this->getUserTable()->getUser($id);
            
                return array("message" => $message->getMessage(), "user"=>$data);
            }else{ //If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()->get('systemLog')->addLog(0, "This user ".$id." was not found. Will redirect to index page.", 5);
                return $this->redirect()->toRoute('user');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function editPermissionsAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            //Get the User ID
            $id = (int) $this->params()->fromRoute('id', 0);
            
            //Check if this id exists at the database
            $user = $this->getUserTable()->getUser($id);
            if($user){
                //Check if this user is a user of this company
                if(($user->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                
                $user = new User();
                $message = $this->getServiceLocator()->get('systemMessages');
                
                $request = $this->getRequest();
                if($request->isPost()){
                    //Remove all permissions for the user, after that, will save the new permissions
                    $this->getUserPermissionsTable()->deleteUserPermissions($id);
                    
                    $data = $request->getPost();
                    $insert = $data["insert"];
                    $edit = $data["edit"];
                    $delete = $data["delete"];
                    //var_dump($insert);
                    $userPermissions = new UserPermissions();
                    $data["company_user_idUser"] = $id;
                    
                    foreach(array_keys($insert) as $info){
                        $permissions = explode("-", $info);
                        $permissionsArray[$permissions[0]][$permissions[1]]["insert"] = 1;
                    }
                    foreach(array_keys($edit) as $info){
                        $permissions = explode("-", $info);
                        $permissionsArray[$permissions[0]][$permissions[1]]["edit"] = 1;
                    }
                    foreach(array_keys($delete) as $info){
                        $permissions = explode("-", $info);
                        $permissionsArray[$permissions[0]][$permissions[1]]["delete"] = 1;
                    }
                    $permissionsArray2 = array_keys($permissionsArray);
                    $l=0;
                    foreach($permissionsArray as $permissao){
                        $data["website_module_idWebsite"] = $permissionsArray2[$l];
                        $modules = array_keys($permissao);
                        $c=0;
                        foreach($permissao as $perm){
                            $data["website_module_idModule"]  = $modules[$c];
                            $data["insertP"] = $perm["insert"];
                            $data["editP"] = $perm["edit"];
                            $data["deleteP"] = $perm["delete"];
                            $userPermissions->exchangeArray($data);
                            $result = $this->getUserPermissionsTable()->setUserPermissions($userPermissions);
                            //Save message and LOG
                            $message->setCode($result, array("id"=>$id));
                            $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                            $c++;
                        }
                        $l++;
                    }
                    //A Mensagem vai aqui
                    
                }
                
                //Get from the Database, User data
                $data = $this->getUserTable()->getUser($id);
                
                //First, get all websites that this user is registred
                //Get all websites for this user
                $websites = $this->getServiceLocator()->get("website_user")->fetchByUser($id);
                
                $websiteSuite = array();
                foreach($websites as $website){
                    //For each website, will get his modules
                    $idWebsite = $website->company_website_idWebsite;
                   
                    $modules = $this->getServiceLocator()->get('website_module')->fetchAllByWebsite($idWebsite);
                    $permissions = $this->getUserPermissionsTable()->getUserPermissions($id, $idWebsite);
                    
                    $websiteSuite[] = array(
                        "website"=>$website, 
                        "modules"=>$modules,
                        "permissions"=>$permissions
                    );
                }
                
                return array("message" => $message->getMessage(), "user"=>$data, "websites"=>$websiteSuite);
            }else{ //If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()->get('systemLog')->addLog(0, "This user ".$id." was not found. Will redirect to index page.", 5);
                return $this->redirect()->toRoute('user');
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
            if (!$id || $id==1 || $id==$logedUser["idUser"]) { //If there is no ID or if the user try to delete himself
                $this->getServiceLocator()->get('systemLog')->addLog(0, "This user ".$id." was not found. Will redirect to index page.", 5);
                
                return $this->redirect()->toRoute('user');
            }
            
            //Check if this user is a user of this company
            $user = $this->getUserTable()->getUser($id);
            if(($user->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1) {
                return $this->redirect()->toRoute('noPermission');
            }
            
            $message = $this->getServiceLocator()->get('systemMessages');
            
            //Delete all user permissions
            if($this->getUserPermissionsTable()->deleteUserPermissions($id)){
                //Delete all user relationships with websites
                $relationship = $this->getServiceLocator()->get('website_user')->deleteAllRelationships(null, $id, "boolean");
                if($relationship){
                    if($this->getUserTable()->deleteUser($id)){
                        $message->setCode("USER010");
                    }else{
                        $message->setCode("USER011");
                    }
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }else{ //Error when it tried to remove the website relationships
                    $message->setCode("USER018");
                    $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
            }else{ //Error when it tried to remove user permissions
                $message->setCode("USER017");
                $this->getServiceLocator()->get('systemLog')->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            
            return array("message"=>$message->getMessage());
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    /*
     * Database managers
     */

    public function getUserTable() {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
    
    public function getUserPermissionsTable() {
        if (!$this->userPermissionsTable) {
            $sm = $this->getServiceLocator();
            $this->userPermissionsTable = $sm->get('User\Model\UserPermissionsTable');
        }
        return $this->userPermissionsTable;
    }

}
