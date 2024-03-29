<?php
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Services\SystemFunctions;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

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
    public function fetchAll($companyId, $currentPage = 1, $countPerPage = 20){
        
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('customer_person', 'customer.idCustomer = customer_person.customer_id', array("name"=>"name", "name2"=>last_name), 'left');
        $sqlSelect->join('customer_company', 'customer.idCustomer = customer_company.customer_id', array("name3"=>"social_name", "name4"=>"fantasy_name"), 'left');
        $sqlSelect->where(array("company_id"=>$companyId));
        $sqlSelect->order("customer_person.name, customer_company.social_name");     
        $adapter = new DbSelect($sqlSelect, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype());
        
        $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
        
    }
    
    /**
     * Check if some email is already inserted in our database for the same company
     * @param string $email
     * @param int $company_id
     * @param int $idCustomer
     * @return number
     */
    public function emailExists($email, $company_id, $idCustomer=null){
        $sqlSelect = $this->tableGateway->getSql()->select();
        if($idCustomer){ //It's a update
            $sqlSelect->where(array("company_id"=>$company_id, "email"=>$email));
            $sqlSelect->where->notEqualTo('idCustomer', $idCustomer);
        }else{
            //It's a insert
            $sqlSelect->where(array("company_id"=>$company_id, "email"=>$email));
        }
        return $this->tableGateway->selectWith($sqlSelect)->count();
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
            'customerType'=>$customer->customerType,
            'email'=>$customer->email,
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
            $data['password'] = $customer->password;
            if($this->tableGateway->insert($data)){
                $id = $this->tableGateway->getLastInsertValue();
                return array("code"=>"CUSTOMER001", "id"=>$id);
            }else{
                return "CUSTOMER002";
            }
        }else{
            //If this customer already exists
            if($this->getCustomer($id)){
                if($this->tableGateway->update($data, array('idCustomer'=>$id))){
                    return "CUSTOMER004";
                }else{
                    return "CUSTOMER005";
                }
            }else{ //This id was not found at the system, customer not exist
                return "CUSTOMER007";
            }
        }
    }
    
    //Save a log of any customer changes
    public function saveLogChanges($idCustomer, $message){
        $customer = $this->getCustomer($idCustomer);
        if($customer){
            $log = $customer->log;
            $data = array(
              "log"=>$log."<br>".$message,
              "dateUpdated"=>date('Y-m-d H:i:s')
            );
            if($this->tableGateway->update($data, array('idCustomer'=>$idCustomer))){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
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

