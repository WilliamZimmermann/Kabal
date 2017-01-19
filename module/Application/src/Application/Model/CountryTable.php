<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class CountryTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all countries that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(){
        return $this->tableGateway->select();
    }
    
    /**
     * This function get a specific country registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCountry($id){
        $id  = (int) $id;
        $data = array("idCountry" => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}

