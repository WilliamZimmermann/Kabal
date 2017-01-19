<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UserPermissionTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all zones that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(){
        return $this->tableGateway->select();
    }
    
    /**
     * This function returns all permissions for a specific user and website
     * @param unknown $idCountry
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByUser($idUser, $idWebsite){
        return $this->tableGateway->select(array("company_user_idUser"=>$idUser, "website_module_idWebsite"=>$idWebsite));
    }
    
    /**
     * This function get a specific permission registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getPermission($idUser, $idWebsite, $idModule){
        $id  = (int) $id;
        $data = array("company_user_idUser" => $idUser, "website_module_idWebsite"=>$idWebsite, "website_module_idModule"=>$idModule);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}

