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
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('country', 'customer_address.country_id = country.countryId', array("country"=>"name"), 'left');
        $sqlSelect->join('zone', 'customer_address.zone_id = zone.id', array("zone"=>"name", "initials"=>"initials"), 'left');
        $sqlSelect->join('city', 'customer_address.city_id = city.id', array("city"=>"name"), 'left');
        $sqlSelect->where(array("customer_id"=>$customerId));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        
        return $resultSet;        
    }
    
    /**
     * This function get a specific address registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idAddress by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getAddress($id, $param="idAddress", $justPrincipal=false){
        $id  = (int) $id;
        if($justPrincipal){
            $data = array($param => $id, "principal"=>"true");
        }else{
            $data = array($param => $id);
        }
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('country', 'customer_address.country_id = country.countryId', array("country"=>"name"), 'left');
        $sqlSelect->join('zone', 'customer_address.zone_id = zone.id', array("zone"=>"name", "initials"=>"initials"), 'left');
        $sqlSelect->join('city', 'customer_address.city_id = city.id', array("city"=>"name"), 'left');
        $sqlSelect->where($data);
        $sqlSelect->limit(1);
        $row = $this->tableGateway->selectWith($sqlSelect);
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
            'house_number'=>$customerAddress->house_number,
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
                return "CUSTOMER011";
            }else{
                return "CUSTOMER012";
            }
        }else{
            //If this address already exists
            if($this->getAddress($id)){
                if($this->tableGateway->update($data, array('idAddress'=>$id))){
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
    
    /**
     * Delete all address for a specific customer
     * @param int $idCustomer
     * @return boolean
     */
    public function deleteAllAddresses($idCustomer){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('customer_id'=>(int)$idCustomer))){
            return true;
        }else{
            return false;
        }
    }
}

