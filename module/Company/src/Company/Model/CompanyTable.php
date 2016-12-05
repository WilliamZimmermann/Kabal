<?php
namespace Company\Model;

use Zend\Db\TableGateway\TableGateway;

class CompanyTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all companies that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    /**
     * This function get a specific company registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCompany($id){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('idCompany' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * This function insert or edit a company
     * @param Company $company (if $company->id have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveCompany(Company $company){
        $data = array('name'=>$company->name, 'status'=>$company->status, 'creationDate'=>$company->creationDate);
        
        $id = (int)$company->idCompany;
        //If there is no Id, so, it's a new company
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                return "COMPANY001";
            }else{
                return "COMPANY002";
            }
        }else{
            //If this company already exists
            if($this->getCompany($id)){
                if($this->tableGateway->update($data, array('idCompany'=>$id))){
                    return "COMPANY004";
                }else{
                    return "COMPANY005";
                }
            }else{ //This id was not found at the system, company does not exist
                throw new \Exception('Company does not exist');
                return "COMPANY007";
            }
        }
    }
    
    /**
     * This function will delete a specific company and his records
     * @param int $id
     * @return number
     */
    public function deleteCompany($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idCompany'=>(int)$id))){
            return "COMPANY008";
        }else{
            return "COMPANY009";
        }
    }
}

