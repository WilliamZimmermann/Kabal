<?php
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;

class CustomerAddressTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all address for 1 customer
     * @param $customerId - If for 1 customer to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($customerId){
        $resultSet = $this->tableGateway->select(array("customer_id"=>$customerId));
        return $resultSet;
        
    }
    
    /**
     * This function get a specific address registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idAddress by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getAddress($id, $param="idAddress"){
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
     * This function insert or edit a article in the database
     * @param Article $customer (if $customer->idArticle have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveAddress(CustomerAddress $customerAddress){
        $data = array(
            'customer_id'=>$customerAddress->customer_id,
            'name'=>$customerAddress->name,
            'street'=>$customerAddress->street,
            'houseNumber'=>$customerAddress->house_number,
            'complement'=>$customerAddress->complement,
            'neighborhood'=>$customerAddress->neighborhood,
            'zip_code'=>$customerAddress->zip_code,
            'city_id'=>$customerAddress->city_id,
            'zone_id'=>$customerAddress->zone_id,
            'country_id'=>$customerAddress->country_id,
            'principal'=>$customerAddress->principal,
            'active'=>$customerAddress->active
            );
        
        $id = (int)$customerAddress->idAddress;
        //If there is no Id, so, it's a new address
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                $id = $this->tableGateway->getLastInsertValue();
                return array("code"=>"CUSTOMER011", "id"=>$id);
            }else{
                return "CUSTOMER012";
            }
        }else{
            //If this customer already exists
            if($this->getCustomer($id)){
                if($this->tableGateway->update($data, array('idCustomer'=>$id))){
                    return "CUSTOMER014";
                }else{
                    return "CUSTOMER015";
                }
            }else{ //This id was not found at the system, address does not exist
                return "CUSTOMER016";
            }
        }
    }
    
    
    /**
     * This function delete a specific address
     * @param int $id
     * @return number
     */
    public function deleteAddress($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idAddress'=>(int)$id))){
            return "CUSTOMER017";
        }else{
            return "CUSTOMER018";
        }
    }
}

