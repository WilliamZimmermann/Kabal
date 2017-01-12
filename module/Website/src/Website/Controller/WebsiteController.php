<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Website for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Website\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Website\Model\Website;
use Zend\Db\Sql\Select;
use Application\Services\SystemLog;
use Website\Model\WebsiteUser;
use Website\Model\WebsiteLanguage;

class WebsiteController extends AbstractActionController
{

    protected $websiteTable;

    protected $websiteUserTable;
    
    protected $languageTable;
    
    protected $websiteLanguageTable;

    protected $moduleId = 2;
 // This is this module identity, please, don't change it
    public function indexAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "insert")) {
                if($logedUser["idCompany"]==1){
                    $websites = $this->getWebsiteTable()->fetchAll(null);
                }else{
                    $websites = $this->getWebsiteTable()->fetchAll($logedUser["idCompany"]);
                }
                return array(
                "websites" => $websites, "logedUser"=>$logedUser,
                    "permission"=>$permission
            );
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function newAction()
    {
        // Check if this user can access this page
        $logedUser = $this->getServiceLocator()
            ->get('user')
            ->getUserSession();
        $permission = $this->getServiceLocator()
            ->get('permissions')
            ->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if ($this->getServiceLocator()
            ->get('user')
            ->checkPermission($permission, "edit")) {
            $website = new Website();
            
            // Get Message Service
            $message = $this->getServiceLocator()->get('systemMessages');
            
            // If was a POST
            $companies = $this->getServiceLocator()
                ->get('companies')
                ->select();
            
            $request = $this->getRequest();
            if ($request->isPost()) {
                $website->exchangeArray($request->getPost());
                if ($website->validation()) { // Validate all data is ok
                                              // After populate the object, will save in Database
                    $result = $this->getWebsiteTable()->saveWebsite($website);
                    if($result=="WEBSITE001"){
                        $lastId = $this->getWebsiteTable()->lastInsertValue();
                        mkdir("public/files_database/".$lastId, 0777);
                    }
                    //Now, will create a new folder to put the files
                    mkdir("public/files_database");
                    $message->setCode($result);
                } else {
                    $message->setCode("WEBSITE004");
                }
                // Log
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, $message->getMessage(), $message->getLogPriority());
            }
            
            // Get Modules avaliables
            $modules = $this->getServiceLocator()
                ->get('modules')
                ->select(function (Select $select) {
                $select->order('name ASC');
            });
            
            return array(
                "message" => $message->getMessage(),
                "website" => $website,
                "companies" => $companies,
                "modules" => $modules
            );
        } else {
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function editAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get Website ID
            $id = (int) $this->params()->fromRoute('id', 0);
            
            // Get companies for the select
            $companies = $this->getServiceLocator()
                ->get('companies')
                ->select();
            
            // Will check if this id exists at the database
            $website = $this->getWebsiteTable()->getWebsite($id);
            if ($website) {
                //Check if this website is a website of this company
                if(($website->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1) {
                    return $this->redirect()->toRoute('noPermission');
                }
                
                $website = new Website();
                
                // If is a POST
                $message = $this->getServiceLocator()->get('systemMessages');
                $request = $this->getRequest();
                if ($request->isPost()) {
                    // Will put the new data at the Company object
                    $website->exchangeArray($request->getPost());
                    if($logedUser["idCompany"]!=1){ //Se for um usuário que não seja do Sistema, só pode editar os sites para a própria empresa
                        $website->company_id = $logedUser["idCompany"];
                    }
                    if ($website->validation(true)) {
                        // After populate the object, will save in Database
                        $result = $this->getWebsiteTable()->saveWebsite($website);
                        $message->setCode($result);
                    } else {
                        $message->setCode("WEBSITE008");
                    }
                    $this->getServiceLocator()
                        ->get('systemLog')
                        ->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                // Get from the Database the Company data
                $data = $this->getWebsiteTable()->getWebsite($id);
                
                return array(
                    "message" => $message->getMessage(),
                    "website" => $data,
                    "companies" => $companies,
                    "logedUser"=>$logedUser
                );
            } else { // If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "This website ID " . $id . " was not found at our system", 5);
                return $this->redirect()->toRoute('website');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    /**
     * List all modules for a website
     */
    public function modulesAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get the Website ID
            $id = (int) $this->params()->fromRoute('id', 0);
            // Will check if this id exists at the database
            $website = $this->getWebsiteTable()->getWebsite($id);
            if ($website) {
                //Check if this user are not trying to get another ID, from another company
                if(($website->id_company!=$logedUser["idCompany"])&& $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                $website = new Website();
                
                // If is a POST
                $message = $this->getServiceLocator()->get('systemMessages');
                $request = $this->getRequest();
                if ($this->params()->fromRoute('idModule', 0)) {
                    $idModule = $this->params()->fromRoute('idModule', 0);
                    
                    //TODO - funções já criadas, é só implantar
                    //Check if the module is already associated with this website
                    if($this->getWebsiteTable()->moduleWebsiteExist($idModule, $id)){
                        //If it exists, just remove relationship
                        $result = $this->getWebsiteTable()->deleteModule($id, $idModule);
                    }else{
                        $result = $this->getWebsiteTable()->saveModule($idModule, $id);
                    }
                    $message->setCode($result);
                    $this->getServiceLocator()
                        ->get('systemLog')
                        ->addLog(0, $message->getMessage(), $message->getLogPriority());
                }
                // Get from the Database the Company data
                $data = $this->getWebsiteTable()->getWebsite($id);
                
                $modulesAlreadySelected = $this->getServiceLocator()
                    ->get('websiteModules')
                    ->select(array(
                    "company_website_idWebsite" => $id
                ));
                
                // Get Modules avaliables
                $modules = $this->getServiceLocator()
                    ->get('modules')
                    ->select(function (Select $select) {
                    $select->order('name ASC');
                });
                
                return array(
                    "message" => $message->getMessage(),
                    "website" => $data,
                    "modules" => $modules,
                    "modulesSelected" => $modulesAlreadySelected
                );
            } else { // If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "This website ID " . $id . " was not found at our system", 5);
                return $this->redirect()->toRoute('website');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    /**
     * List and add users for a website
     */
    public function usersAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            // Check if this id exists at the database
            $website = $this->getWebsiteTable()->getWebsite($id);
            if ($website) {
                //Check if this user are not trying to get another ID, from another company
                if(($website->company_id!=$logedUser["idCompany"]) && $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                $website = new Website();
                
                // If is a POST
                $message = $this->getServiceLocator()->get('systemMessages');
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $websiteUser = new WebsiteUser();
                    
                    // Get the data from the post
                    $data = $request->getPost();
                    
                    // Populate object
                    $websiteUser->exchangeArray($data);
                    
                    // Validate object
                    if ($websiteUser->validation()) {
                        $result = $this->getWebsiteUserTable()->includeUser($websiteUser);
                        $message->setCode($result);
                    } else {
                        $message->setCode("WEBSITE15");
                    }
                } else 
                    if ($request->isGet()) {
                        if ($this->params()->fromRoute('idUser')) { // So it's a exclusion
                            $idWebsite = $id;
                            $idUser = (int) $this->params()->fromRoute('idUser', 0);
                            
                            $message = $this->getServiceLocator()->get('systemMessages');
                            //Delete his permissions before to delete the relationship
                            if($this->getUserPermissionsTable()->deleteUserPermissions($idUser, $idWebsite)){
                                $result = $this->getWebsiteUserTable()->deleteRelationship($idWebsite, $idUser);
                                $message->setCode($result);
                            }else{
                                $message->setCode("WEBSITE017");
                            }
                            $this->getServiceLocator()
                            ->get('systemLog')
                            ->addLog(0, $message->getMessage(), $message->getLogPriority());
                        }
                    }
                // Get website data
                $data = $this->getWebsiteTable()->getWebsite($id);
                
                // Get all users relationships for this website
                $usersRelationship = $this->getWebsiteUserTable()->fetchByWebsite($id);
                
                $idCompany = $data->company_id;
                // Get users for this company
                if($logedUser["idCompany"]==1){
                    $where = array();
                }else{
                    $where = array("company_id" => $idCompany);
                }
                $users = $this->getServiceLocator()
                    ->get("users")
                    ->select($where);                
                return array(
                    "message" => $message->getMessage(),
                    "website" => $data,
                    "users" => $users,
                    "usersRelationship" => $usersRelationship,
                    "logedUser"=>$logedUser
                );
            } else { // If we not found this ID at the Database, will return to the list
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "This website ID " . $id . " was not found at our system", 5);
                return $this->redirect()->toRoute('website');
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function settingsAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            // Get the Company ID
            $id = (int) $this->params()->fromRoute('id', 0);
            // Check if this id exists at the database
            $website = $this->getWebsiteTable()->getWebsite($id);
            if ($website) { //Check if this website exist
                //Check if this user are not trying to get another ID, from another company
                if(($website->company_id!=$logedUser["idCompany"]) && $logedUser["idCompany"]!=1){
                    return $this->redirect()->toRoute('noPermission');
                }
                $languages = $this->getLanguageTable()->fetchAll();
            
                $message = $this->getServiceLocator()->get('systemMessages');
                
                $request = $this->getRequest();
                
                if($request->isPost()){
                    $languagesSelected = $request->getPost();
                    $languagesSelected = $languagesSelected["language"];
                    $websiteLanguage = new WebsiteLanguage();
                    
                    //Delete all relationships between this website and languages
                    $this->getWebsiteLanguageTable()->deleteLanguagePair(0, $id);
                    //Save all new relationships
                    foreach($languagesSelected as $language){
                        $websiteLanguage->exchangeArray(array('company_website_id'=>$id, 'language_id'=>$language));
                        $result = $this->getWebsiteLanguageTable()->saveLanguagePair($websiteLanguage);
                        
                        $message->setCode($result);
                        $this->getServiceLocator()
                        ->get('systemLog')
                        ->addLog(0, $message->getMessage(), $message->getLogPriority());
                    }
                    //This function will save another settings like IP, name, etc...
                    $data["idWebsite"] = $id;
                    $data["apiIp"] = strip_tags($_POST["apiIp"]);
                    $saveSettings = $this->websiteTable->saveSettings($data);
                    $message->setCode($saveSettings);
                    $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, $message->getMessage(), $message->getLogPriority());
                    //Load again the website informations
                    $website = $this->getWebsiteTable()->getWebsite($id);
                }
                //Get alll languages related with this website
                $languagesSelectedDb = $this->getWebsiteLanguageTable()->fetchAll($id);
                $languagesSelected = array();
                $l=1;
                foreach($languagesSelectedDb as $lSelected){
                    $languagesSelected[$l] = $lSelected->language_id;
                    $l++;
                }
                
                return array("website"=>$website, "languages"=>$languages, "languagesSelected"=>$languagesSelected, "logedUser"=>$logedUser, "idWebsite"=>$id, "message"=>$message->getMessage());
            }else{
                return $this->redirect()->toRoute("noPermission");
            }
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }

    public function deleteAction()
    {
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "delete")){
            $id = (int) $this->params()->fromRoute('id', 0);
            if (! $id) { // If there is no ID
                $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "This Website " . $id . " was not found.", 5);
                return $this->redirect()->toRoute('website');
            }
            $message = $this->getServiceLocator()->get('systemMessages');
            //Check if this website is a website of this company
            $website = $this->getWebsiteTable()->getWebsite($id);
            if(($website->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1) {
                return $this->redirect()->toRoute('noPermission');
            }
            
            $result = $this->getWebsiteTable()->deleteWebsite($id);
            if($result=="WEBSITE008"){
                if($this->delTree("public/files_database/".$id)){
                    $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "Files directory for website ".$id." was succeful removed.", 5);
                }else{
                    $this->getServiceLocator()
                    ->get('systemLog')
                    ->addLog(0, "Failed when tried to remove files directory for website ".$id.".", 2);
                }
            }
            $message->setCode($result);
            // Log
            $this->getServiceLocator()
                ->get('systemLog')
                ->addLog(0, $message->getMessage(), $message->getLogPriority());
            
            return array(
                "message" => $message->getMessage()
            );
        }else{
            return $this->redirect()->toRoute("noPermission");
        }
    }
    
    public function generateApiKeyAction(){
        //Check if this user can access this page
        $logedUser = $this->getServiceLocator()->get('user')->getUserSession();
        $permission = $this->getServiceLocator()->get('permissions')->havePermission($logedUser["idUser"], $logedUser["idWebsite"], $this->moduleId);
       
        if($this->getServiceLocator()->get('user')->checkPermission($permission, "edit")){
            $id = (int)$_POST["id"];
            if (! $id) { // If there is no ID
                $this->getServiceLocator()
                ->get('systemLog')
                ->addLog(0, "This Website " . $id . " was not found.", 5);
                die("forbiden - id");
            }
            $message = $this->getServiceLocator()->get('systemMessages');
            //Check if this website is a website of this company
            $website = $this->getWebsiteTable()->getWebsite($id);
            if(($website->company_id != $logedUser["idCompany"]) && $logedUser["idCompany"]!=1) {
                die("there is no permission");
            }
            if($_POST["id"]){
               $length = 32;
               $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
               $len = strlen($salt);
               $pass = '';
               mt_srand(10000000*(double)microtime());
               for ($i = 0; $i < $length; $i++){
                    $pass .= $salt[mt_rand(0,$len - 1)];
               }
               //Check if this pass is already used
               if($this->getWebsiteTable()->getWebsite(null, $pass)){
                   $this->generateApiKeyAction(); //Recursive function, generate another API key
               }else{
                   $this->getWebsiteTable()->saveApiKey($id, $pass);
                   echo $pass;
                   die();
               }
            }else{
                die("forbiden");
            }
        }else{
            die("forbiden");
        }
    }
    
    /**
     * This function will remove all files from a directory making a recursive delete
     * @param string $dir
     * @return boolean
     */
    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function getWebsiteTable()
    {
        if (! $this->websiteTable) {
            $sm = $this->getServiceLocator();
            $this->websiteTable = $sm->get('Website\Model\WebsiteTable');
        }
        return $this->websiteTable;
    }

    public function getWebsiteUserTable()
    {
        if (! $this->websiteUserTable) {
            $sm = $this->getServiceLocator();
            $this->websiteUserTable = $sm->get('Website\Model\WebsiteUserTable');
        }
        return $this->websiteUserTable;
    }
    
    public function getUserPermissionsTable() {
        if (!$this->userPermissionsTable) {
            $sm = $this->getServiceLocator();
            $this->userPermissionsTable = $sm->get('User\Model\UserPermissionsTable');
        }
        return $this->userPermissionsTable;
    }
    
    public function getLanguageTable() {
        if (!$this->languageTable) {
            $sm = $this->getServiceLocator();
            $this->languageTable = $sm->get('Website\Model\LanguageTable');
        }
        return $this->languageTable;
    }
    
    public function getWebsiteLanguageTable() {
        if (!$this->websiteLanguageTable) {
            $sm = $this->getServiceLocator();
            $this->websiteLanguageTable = $sm->get('Website\Model\WebsiteLanguageTable');
        }
        return $this->websiteLanguageTable;
    }
}
