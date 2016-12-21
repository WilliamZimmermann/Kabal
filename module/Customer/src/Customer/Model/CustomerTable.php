<?php
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Services\SystemFunctions;

class CustomerTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all customers that are in our records
     * @param $companyId - Company Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($companyId){
        if($companyId>1){
            $where = array("company_id"=>$companyId);
        }else if($companyId==1){
            $where = array();
        }
        
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function get a specific customer registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idCustomer by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCustomer($id, $param="idCustomer"){
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
    public function saveCustomer(Customer $customer){
        $data = array(
            'company_id'=>$customer->company_id,
            'addedBy'=>$customer->addedBy, 
            'customeType'=>$customer->customerType,
            'email'=>$customer->customerType,
            'password'=>$customer->password,
            'birthDate'=>SystemFunctions::dateInvert($customer->birthDate, "american"),
            'country_id'=>$customer->country_id,
            'comments'=>$customer->comments,
            'log'=>$customer->log,
            'dateCreated'=>$customer->dateCreated,
            'dateUpdated'=>$customer->dateUpdated, 
            'active'=>$customer->active
            );
        
        $id = (int)$customer->idCustomer;
        //If there is no Id, so, it's a new article
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                $id = $this->tableGateway->getLastInsertValue();
                return array("code"=>"CUSTOMER001", "id"=>$id);
            }else{
                return "CUSTOMER002";
            }
        }else{
            //If this customer already exists
            if($this->getCustomer($id)){
                if($this->tableGateway->update($data, array('company_id'=>$id))){
                    return "CUSTOMER004";
                }else{
                    return "CUSTOMER005";
                }
            }else{ //This id was not found at the system, customer not exist
                return "CUSTOMER07";
            }
        }
    }
    
    
    /**
     * This function delete a specific customer
     * @param int $id
     * @return number
     */
    public function deleteCustomer($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idCustomer'=>(int)$id))){
            return "CUSTOMER008";
        }else{
            return "CUSTOMER009";
        }
    }
}

