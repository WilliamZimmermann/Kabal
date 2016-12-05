<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;

class UserPermissionsTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * Set USER permissions
     * @param int $idUser
     * @param int $idWebsite
     * @param int $idModule
     * @param array $permissions (insertP, editP, deleteP)
     */
    public function setUserPermissions(UserPermissions $userPermissions){
        //First, check if this user already have permissions for this module
        if($this->getUserPermissions($userPermissions->company_user_idUser, $userPermissions->website_module_idWebsite, $userPermissions->website_module_idModule)){
            $this->deleteUserPermissions($userPermissions->company_user_idUser, $userPermissions->website_module_idWebsite, $userPermissions->website_module_idModule);
        }
        $insert = new Insert();
        $insert->into("company_user_permissions");
        $data = [
            "company_user_idUser" => $userPermissions->company_user_idUser, 
            "website_module_idWebsite" =>$userPermissions->website_module_idWebsite, 
            "website_module_idModule"=>$userPermissions->website_module_idModule, 
            "insertP"=>$userPermissions->insertP, 
            "editP"=>$userPermissions->editP, 
            "deleteP"=>$userPermissions->deleteP
        ];
        if($this->tableGateway->insert($data)){
            return "USER014";
        }else{
            return "USER015";
        }
    }
    
    /**
     * Get All Permisions from a USER
     * @param int $idUser
     * @param int $idWebsite
     * @param int $idModule
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getUserPermissions($idUser, $idWebsite=null, $idModule=null){
        $where["company_user_idUser"] = $idUser;
        if($idWebsite){
            $where["website_module_idWebsite"] = $idWebsite;
        }
        if($idModule){
            $where["website_module_idModule"] = $idModule;
        }
        return $this->tableGateway->select($where);
    }
    
    /**
     * Check what permission the user have for a module and website
     * @param int $idUser
     * @param int $idWebsite
     * @param int $idModule
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function havePermission($idUser, $idWebsite, $idModule){
        $where["company_user_idUser"] = $idUser;
        $where["website_module_idWebsite"] = $idWebsite;
        $where["website_module_idModule"] = $idModule;
        
        return $this->tableGateway->select($where)->current();
    }
    
    public function deleteUserPermissions($idUser=null, $idWebsite=null, $idModule=null){
       
            //If anyone of this 3 parameters be seted, return false
        if($idUser==null && $idWebsite==null && $idModule==null){
            return false;
        }
        
        if($idUser!=null){
            $where["company_user_idUser"] = $idUser;
        }
        if($idWebsite != null){
            $where["website_module_idWebsite"] = $idWebsite;
        }
        if($idModule!=null){
            $where["website_module_idModule"] = $idModule;
        }
        if($this->tableGateway->select($where)->count()>0){ //Verifica se há algo para deletar
            if($this->tableGateway->delete($where)){
                return true;
            }else{
                return false;
            }
        }else{ //Não há um relacionamento desse tipo para deletar
            return true;
        }
        
    }
    
}
