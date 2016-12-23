<?php
namespace Website\Model;

use Zend\Db\TableGateway\TableGateway;
use Website\Model\WebsiteUser;
use Zend\Db\Sql\Select;

class WebsiteUserTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all relations that exists between users and websites
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    /**
     * Fecth all users registred for a website
     * @param unknown $idWebsite
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchByWebsite($idWebsite){
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('company_user_idUser', 'company_website_idWebsite'));
        $sqlSelect->join('company_user', 'company_user.idUser = company_user_has_company_website.company_user_idUser', array("userName"=>"name"), 'left');
        $sqlSelect->join('company_website', 'company_website.idWebsite = company_user_has_company_website.company_website_idWebsite', array("websiteName"=>"name"), 'left');
        $sqlSelect->where(array('company_website.idWebsite'=>$idWebsite));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }
    
    /**
     * Fetch all websites regitred for a user
     * @param unknown $idUser
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchByUser($idUser){
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('company_user_idUser', 'company_website_idWebsite'));
        $sqlSelect->join('company_user', 'company_user.idUser = company_user_has_company_website.company_user_idUser', array("userName"=>"name"), 'left');
        $sqlSelect->join('company_website', 'company_website.idWebsite = company_user_has_company_website.company_website_idWebsite', array("websiteName"=>"name"), 'left');
        $sqlSelect->where(array('company_user.idUser'=>$idUser));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }

    /**
     * This function insert a user in a website
     * @param WebsiteUser $websiteUser
     * @throws \Exception
     */
    public function includeUser(WebsiteUser $websiteUser){
        //First, check if this user is already registred for this website
        if($this->tableGateway->select(array('company_user_idUser'=>$websiteUser->company_user_idUser, 'company_website_idWebsite'=>$websiteUser->company_website_idWebsite))->count()>0){
            return "WEBSITE014";
        }else{
            $data = array('company_user_idUser'=>$websiteUser->company_user_idUser, 'company_website_idWebsite'=>$websiteUser->company_website_idWebsite);
            
            if($this->tableGateway->insert($data)){
                return "WEBSITE013";
            }else{
                return "WEBSITE014";
            }
        }
    }
    
    /**
     * Check if this user have acess to this website
     * @param int $idUser
     * @param int $idWebsite
     * @return number
     */
    public function haveRelationship($idUser, $idWebsite){
        $result = $this->tableGateway->select(array("company_user_idUser"=>$idUser, "company_website_idWebsite"=>$idWebsite))->count();
        return $result;
    }
    
    /**
     * This function will delete a specific relationship between a user and a website
     * @param int $id
     * @return number
     */
    public function deleteRelationship($idWebsite, $idUser){
        if($this->tableGateway->delete(array('company_website_idWebsite'=>(int)$idWebsite, 'company_user_idUser'=>(int)$idUser))){
            return "WEBSITE016";
        }else{
            return "WEBSITE017";
        }
    }
    
    /**
     * This function will delete all relationships
     * @param int $idWebsite
     * @param int $idUser
     * @param string kindOfReturn - message or boolean
     */
    public function deleteAllRelationships($idWebsite=null, $idUser=null, $kindOfReturn = "message"){
        if($idWebsite){
            $where["company_website_idWebsite"] = $idWebsite;
        }
        if($idUser){
            $where["company_user_idUser"] = $idUser;
        }
        
        if($this->tableGateway->select($where)->count()>0){
            if($this->tableGateway->delete($where)){
                return ($kindOfReturn=="message") ? "WEBSITE016" : true;
            }else{
                return ($kindOfReturn=="message") ? "WEBSITE017" : false;
            }
        }else{ //Já não havia nenhum relacionamento
            return ($kindOfReturn=="message") ? "WEBSITE016" : true;
        }
    }
}

