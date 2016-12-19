<?php

namespace Website\Model;

use Zend\Db\TableGateway\TableGateway;
use Website\Model\Website;
use Application\Services\SystemLog;

class WebsiteTable {

    protected $tableGateway;
    protected $log;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->log = new SystemLog();
    }

    /**
     * This function returns all websites that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($idCompany=null) {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('idWebsite', 'company_id', 'name', 'status'));
        $sqlSelect->join('company', 'company.idCompany = company_website.company_id', array("company"=>"name"), 'left');
        if($idCompany){
            $sqlSelect->where(array("company_id"=>$idCompany));
        }
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }

    /**
     * This function get a specific website registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getWebsite($id=null, $apiKey=null) {
        if($id!=null){
            $id = (int) $id;
            $where = array('idWebsite'=>$id);
        }else if($apiKey!=null){
            $apiKey = strip_tags($apiKey);
            $where = array('apiKey'=>$apiKey);
        }
        $rowset = $this->tableGateway->select($where);
        
        $row = $rowset->current();
        if (!$row) {
            return false;
            //throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveApiKey($idWebsite, $apiKey){
        $data = array("apiKey"=>$apiKey);
        if ($this->tableGateway->update($data, array('idWebsite' => $idWebsite))) {
            return "WEBSITE004";
        } else {
            return "WEBSITE005";
        }
    }
    
    public function saveSettings($data){
        $idWebsite = $data["idWebsite"];
        $data = array("apiIp"=>$data["apiIp"]);
        if($this->tableGateway->update($data, array('idWebsite'=>$idWebsite))){
            return "WEBSITE004";
        } else {
            return "WEBSITE005";
        }
    }

    /**
     * This function insert or edit a Website
     * @param Website $website (if $website->id have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveWebsite(Website $website) {
        $data = array('company_id' => $website->company_id, 'name' => $website->name, 'status' => $website->status);
        $id = (int) $website->idWebsite;
        //If there is no Id, so, it's a new Website
        if ($id == 0) {
            $data["creationDate"] =$website->creationDate;
            //Fist, will check if this email already exists in the database
            if ($this->tableGateway->insert($data)) {
                //IF the website was created, will insert the modules to him
                if($website->modules){
                    $idWebsite = $this->tableGateway->getLastInsertValue();
                    $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
                    $modules = (array)$website->modules;
                    foreach($modules as $module){
                        $moduleTable->insert(array('company_website_idWebsite'=>$idWebsite, 'system_module_idModule'=>$module));
                    }
                }
                return "WEBSITE001";
            } else {
                return "WEBSITE002";
            }
        } else {
            //If this Website already exists
            if ($this->getWebsite($id)) {
                if ($this->tableGateway->update($data, array('idWebsite' => $id))) {
                    return "WEBSITE004";
                } else {
                    return "WEBSITE005";
                }                
            } else { //This id was not found at the system, Website does not exist
                return "WEBSOTE007";
            }
        }
    }
    
    public function lastInsertValue(){
        return $this->tableGateway->lastInsertValue;
    }
    
    /**
     * Will save the selected modules
     * @param array $modules
     */
    public function saveModules($modules, $idSite){
        $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
        //First, will delete all related modules to add others
        $this->deleteAllModules($idSite);
        //Now, will add all the modules selected
        foreach($modules as $module){
            $moduleTable->insert(array('company_website_idWebsite'=>$idSite, 'system_module_idModule'=>$module));
        }
        return "WEBSITE011";
    }
    
    /**
     * Save a specific relationship between one module and one website 
     * @param int $module
     * @param int $website
     * @return string
     */
    public function saveModule($module, $website){
        $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
        $moduleTable->insert(array('company_website_idWebsite'=>$website, 'system_module_idModule'=>$module));
        return "WEBSITE011";
    }
    
    /**
     * Check if this specific module and website are related
     * @param int $module
     * @param int $website
     * @return boolean
     */
    public function moduleWebsiteExist($module, $website){
        $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
        $data = $moduleTable->select(array('company_website_idWebsite'=>$website, 'system_module_idModule'=>$module));
        if($data->count()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * This function will delete a specific Website and his records
     * @param int $id
     * @return number
     */
    public function deleteWebsite($id) {
        //Here we must to put the recursive functions to delete all future content
        if($this->deleteAllModules($id)){
            if($this->tableGateway->delete(array('idWebsite' => (int) $id))){
                return "WEBSITE008";
            }else{
                return "WEBSITE009";
            }
        }else{
            return "WEBSITE010";
        }
    }
    
    /**
     * This methodo will delete all modules related with a website
     * @param int $idWebsite
     * @return number
     */
    public function deleteAllModules($idWebsite){
        $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
        $result = $moduleTable->delete(array("company_website_idWebsite"=>$idWebsite));
        return true;
    }
    
    /**
     * Delete a specific relationship between one module and one website
     * @param int $website
     * @param int $module
     * @return string
     */
    public function deleteModule($website, $module){
        //Before to delete it, delete all user permissions for this website and module
        $permissionTable = new TableGateway("company_user_permissions", $this->tableGateway->getAdapter());
        $permissionTable = $permissionTable->delete(array("website_module_idWebsite"=>$website, "website_module_idModule"=>$module));
        //If permissions delete are ok, delete module relationship
        $moduleTable = new TableGateway("company_website_has_system_module", $this->tableGateway->getAdapter());
        $result = $moduleTable->delete(array("company_website_idWebsite"=>$website, "system_module_idModule"=>$module));
        if($result){    
            return "WEBSITE011";
        }else{
            return "WEBSITE012";
        }
    }

}
