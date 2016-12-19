<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;

class UserTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    /**
     * This function returns all companies that are in our records
     * @param $companyId 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($companyId=null) {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('idUser', 'company_id', 'type', 'name', 'email', 'status'));
        $sqlSelect->join('company', 'company.idCompany = company_user.company_id', array("company"=>"name"), 'left');
        if($companyId){
            $sqlSelect->where(array('company_id'=>$companyId));
        }
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }

    /**
     * This function get a specific user registred in our data base
     * @param int $id
     * @param string $email
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getUser($id = null, $email = null) {
        if ($id) {
            $id = (int) $id;
            $rowset = $this->tableGateway->select(array('idUser' => $id));
        } else {
            $email = (string) $email;
            $rowset = $this->tableGateway->select(array('email' => $email));
        }

        $row = $rowset->current();
        if (!$row) {
            return false;
            //throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /**
     * Make the login (if user is active) and return user data
     * @param string $email
     * @param string $password
     * @return boolean|ArrayObject|NULL
     */
    public function login($email = null, $password=null) {
        $rowset = $this->tableGateway->select(array('email'=>$email, 'password'=>$password, 'status'=>1));
        
        $row = $rowset->current();
        if (!$row) {
            return false;
            //throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /**
     * Check the database to verify if a user with the same email but with another id already exists
     * @param string $email
     * @param int $id
     * @return number of ocourences
     */
    public function userExists($email, $id){
        $where = new Where();
        $where->notEqualTo("idUser", $id);
        $where->equalTo('email', $email);
        return $this->tableGateway->select($where)->count();
    }

    /**
     * This function insert or edit a user
     * @param User $user (if $user->id have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveUser(User $user) {
        
        $data = array('company_id' => $user->company_id, 'type'=>$user->type, 'name' => $user->name, 'email' => $user->email, 'status' => $user->status);
        if($user->password){
            $data['password'] = $user->password;
        }
        
        $id = (int) $user->idUser;
        //If there is no Id, so, it's a new user
        if ($id == 0) {
            $data["creationDate"] =$user->creationDate;
            //Fist, will check if this email already exists in the database
            if ($this->getUser(null, $user->email)) {
                return "USER003";
            } else { //If this emaisl is not in the database, will try to insert it
                if ($this->tableGateway->insert($data)) {
                    return "USER001";
                } else {
                    return "USER002";
                }
            }
        } else {
            //If this user already exists
            if ($this->getUser($id)) {
                if($this->userExists($user->email, $user->idUser)>0){
                    return "USER007";
                }else{
                    if ($this->tableGateway->update($data, array('idUser' => $id))) {
                        return "USER005";
                    } else {
                        return "USER006";
                    }
                }
            } else { //This id was not found at the system, user does not exist
                return "USER008";
            }
        }
    }
    
    
    
    public function savePassword(User $user){
       if($this->tableGateway->update(array("password"=>$user->password), array("idUser"=>$user->idUser))){
           return "USER012";
       }else{
           return "USER013";
       }
    }

    /**
     * This function will delete a specific user and his records
     * @param int $id
     * @return number
     */
    public function deleteUser($id) {
        return $this->tableGateway->delete(array('idUser' => (int) $id));
    }

}
