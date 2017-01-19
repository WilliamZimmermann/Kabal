<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ZoneTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all zones that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll(){
        return $this->tableGateway->select();
    }
    
    /**
     * This function returns all zones for a specific country
     * @param unknown $idCountry
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllByCountry($idCountry){
        return $this->tableGateway->select(array("country_id"=>$idCountry));
    }
    
    /**
     * This function get a specific zone registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getZone($id){
        $id  = (int) $id;
        $data = array("id" => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}

