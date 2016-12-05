<?php
namespace Page\Model;

use Zend\Db\TableGateway\TableGateway;

class PageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all pages that are in our records
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($websiteId=null){
        if($websiteId){
            $where = array("website_id"=>$websiteId);
        }else{
            $where = array();
        }
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function get a specific page registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idPage by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getPage($id, $param="idPage"){
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
     * This function insert or edit a page in the database
     * @param Page $page (if $page->idPage have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function savePage(Page $page){
        $data = array('website_id'=>$page->website_id, 'title'=>$page->title, 'description'=>$page->description, 'active'=>$page->active);
        
        $id = (int)$page->idPage;
        //If there is no Id, so, it's a new page
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                return "PAGE001";
            }else{
                return "PAGE002";
            }
        }else{
            //If this image already exists
            if($this->getPage($id)){
                if($this->tableGateway->update($data, array('idPage'=>$id))){
                    return "PAGE004";
                }else{
                    return "PAGE005";
                }
            }else{ //This id was not found at the system, image does not exist
                throw new \Exception('Image does not exist');
                return "PAGE007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific image
     * @param int $id
     * @return number
     */
    public function deletePage($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idPage'=>(int)$id))){
            return "PAGE007";
        }else{
            return "PAGE008";
        }
    }
}

