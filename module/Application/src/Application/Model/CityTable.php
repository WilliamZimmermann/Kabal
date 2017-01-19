<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class CityTable
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
     * This function returns all zones for a specific Zone
     * @param int $idZone
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllByZone($idZone){
        return $this->tableGateway->select(array("zone_id"=>$idZone));
    }
    
    /**
     * This function get a specific city registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCity($id){
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

