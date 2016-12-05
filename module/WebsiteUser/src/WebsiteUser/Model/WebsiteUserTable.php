<?php
namespace WebsiteUser\Model;

use Zend\Db\TableGateway\TableGateway;
use WebsiteUser\Model\WebsiteUser;
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
    
    public function fetchByWebsite($idWebsite){
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('company_user_idUser', 'company_website_idWebsite'));
        $sqlSelect->join('company_user', 'company_user.idUser = company_user_has_company_website.company_user_idUser', array("userName"=>"name"), 'left');
        $sqlSelect->join('company_website', 'company_website.idWebsite = company_user_has_company_website.company_website_idWebsite', array("websiteName"=>"name"), 'left');
        $sqlSelect->where(array('company_website.idWebsite'=>$idWebsite));
        
        
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }
    
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
        $data = array('company_user_idUser'=>$websiteUser->company_user_idUser, 'company_website_idWebsite'=>$websiteUser->company_website_idWebsite);
        
        if($this->tableGateway->insert($data)){
            return "WEBUSER001";
        }else{
            return "WEBUSER002";
        }
    }
    
    /**
     * This function will delete a specific relationship between a user and a website
     * @param int $id
     * @return number
     */
    public function deleteRelationship($idWebsite, $idUser){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('company_website_idWebsite'=>(int)$idWebsite, 'company_user_idUser'=>(int)$idUser))){
            return "WEBUSER003";
        }else{
            return "WEBUSER004";
        }
    }
}

