<?php
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;

class CustomerContactTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all contacst for a customer
     * @param $customerId - customer id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($customerId){
        $resultSet = $this->tableGateway->select(array("customer_id"=>$customerId));
        
        return $resultSet;        
    }
    
    /**
     * This function get a specific contact registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idContact by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getContact($id, $param="idContact"){
        $id  = (int) $id;
        $data = array($param => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * This function insert or edit a contact in the database
     * @param Article $customer (if $customer->idContact have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveContact(CustomerContact $customerContact){
        $data = array(
            'customer_id'=>$customerContact->customer_id,
            'desc'=>$customerContact->desc,
            'phone'=>$customerContact->phone,
            'email'=>$customerContact->email,
            'principal'=>$customerContact->principal,
            );
        
        $id = (int)$customerContact->idContact;
        //If there is no Id, so, it's a new contact
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                $id = $this->tableGateway->getLastInsertValue();
                return "CUSTOMER019";
            }else{
                return "CUSTOMER020";
            }
        }else{
            //If this contact already exists
            if($this->getAddress($id)){
                if($this->tableGateway->update($data, array('idContact'=>$id))){
                    return "CUSTOMER022";
                }else{
                    return "CUSTOMER023";
                }
            }else{ //This id was not found at the system, contact does not exist
                return "CUSTOMER024";
            }
        }
    }
    
    
    /**
     * This function delete a specific contact
     * @param int $id
     * @return number
     */
    public function deleteContact($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idContact'=>(int)$id))){
            return "CUSTOMER025";
        }else{
            return "CUSTOMER026";
        }
    }
}

