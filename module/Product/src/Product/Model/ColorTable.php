<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ColorTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all categories that are in our records
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($websiteId=null, $currentPage = 1, $countPerPage = 20){
        if($websiteId){
            $where = array("website_id"=>$websiteId);
        }else{
            $where = array();
        }
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        $sqlSelect->order(array('language_id', 'name'));
    
        $adapter = new DbSelect($sqlSelect, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype());
    
        $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }
    
    /**
     * This function get a specific color registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idColor by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getColor($id, $param="idColor"){
        $id  = (int) $id;
        $data = array($param => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        return $row;
    }
    
    /**
     * This function insert or edit a color in the database
     * @param Category $color (if $color->idCategory have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveColor(Color $color){
        $data = array(
            'language_id'=>$color->language_id,
            'name'=>$color->name,
            'hexa'=>$color->hexa,
            'image'=>$color->image,
            );
        $id = (int)$color->idColor;
        //If there is no Id, so, it's a new category
        if($id  == 0){
            $data['website_id'] = $color->website_id;
            if($this->tableGateway->insert($data)){
                return $this->tableGateway->getLastInsertValue();
            }else{
                return "PCOL002";
            }
        }else{
            //If this image already exists
            if($this->getColor($id)){
                if($this->tableGateway->update($data, array('idColor'=>$id))){
                    return "PCOL004";
                }else{
                    return "PCOL005";
                }
            }else{ //This id was not found at the system, image does not exist
                return "PCOL007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific color
     * @param int $idColor
     * @return number
     */
    public function deleteColor($idColor){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idColor'=>(int)$idColor))){
            return "PCOL007";
        }else{
            return "PCOL008";
        }
    }
}

