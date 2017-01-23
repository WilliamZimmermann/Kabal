<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryTable
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
    public function fetchAll($websiteId=null){
        if($websiteId){
            $where = array("website_id"=>$websiteId);
        }else{
            $where = array();
        }
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        $sqlSelect->order('title');
        return $this->tableGateway->selectWith($sqlSelect);
    }
    
    /**
     * This function get a specific category registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idCategory by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCategory($id, $param="idCategory"){
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
     * This function insert or edit a category in the database
     * @param Category $category (if $category->idCategory have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveCategory(Category $category){
        $data = array('website_id'=>$category->website_id, 'title'=>$category->title, 'active'=>$category->active);
        
        $id = (int)$category->idCategory;
        //If there is no Id, so, it's a new category
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                return "PCAT001";
            }else{
                return "PCAT002";
            }
        }else{
            //If this image already exists
            if($this->getCategory($id)){
                if($this->tableGateway->update($data, array('idCategory'=>$id))){
                    return "PCAT004";
                }else{
                    return "PCAT005";
                }
            }else{ //This id was not found at the system, image does not exist
                throw new \Exception('Image does not exist');
                return "PCAT007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific image
     * @param int $id
     * @return number
     */
    public function deleteCategory($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idCategory'=>(int)$id))){
            return "PCAT007";
        }else{
            return "PCAT008";
        }
    }
}

