<?php
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;

class CustomerPersonTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    
    /**
     * This function get a specific customer_person registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serch (customer_id by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCustomer($id, $param="customer_id"){
        $id  = (int) $id;
        $data = array($param => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row at customer person $id");
        }
        return $row;
    }

    /**
     * This function insert or edit a article in the database
     * @param Article $customer (if $customer->idArticle have a valid id, will update, not insert)
     * @param int $action - (1 Insert | 2 Update)
     * @throws \Exception
     */
    public function saveCustomer(CustomerPerson $customer, $action=1){
        $data = array(
            'customer_id'=>$customer->customer_id,
            'name'=>$customer->name, 
            'last_name'=>$customer->last_name,
            'document_1'=>$customer->document_1,
            'document_2'=>$customer->document_2,
            );
        
        
        $id = (int)$customer->customer_id;
        //If there is no Id, so, it's a new article
        if($action==1){
            if($this->tableGateway->insert($data)){
                //$id = $this->tableGateway->getLastInsertValue();
                return true;
            }else{
                return false;
            }
        }else{
            //If this customer already exists
            if($this->getCustomer($id)){
                if($this->tableGateway->update($data, array('customer_id'=>$id))){
                    return true;
                }else{
                    return false;
                }
            }else{ //This id was not found at the system, customer not exist
                return false;
            }
        }
    }
    
    
    /**
     * This function delete a specific customer_person
     * @param int $id
     * @return number
     */
    public function deleteCustomer($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('customer_id'=>(int)$id))){
            return true;
        }else{
            return false;
        }
    }
}

