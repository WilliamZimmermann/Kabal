<?php
namespace ImagesDatabase\Model;

use Zend\Db\TableGateway\TableGateway;

class ImageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all images that are in our records
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($websiteId){
        $resultSet = $this->tableGateway->select(array("website_id"=>$websiteId));
        return $resultSet;
    }
    
    /**
     * This function get a specific company registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getImage($id){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('idImage' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * This function insert or edit a image in the database
     * @param Image $image (if $image->idImage have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveImage(Image $image){
        $data = array('website_id'=>$image->website_id, 'name'=>$image->name, 'extension'=>$image->extension, 'creationDate'=>$image->creationDate);
        
        $id = (int)$image->idImage;
        //If there is no Id, so, it's a new image
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                return "DBIMAGE001";
            }else{
                return "DBIMAGE002";
            }
        }else{
            //If this image already exists
            if($this->getImage($id)){
                if($this->tableGateway->update($data, array('idImage'=>$id))){
                    return "DBIMAGE003";
                }else{
                    return "DBIMAGE004";
                }
            }else{ //This id was not found at the system, image does not exist
                throw new \Exception('Image does not exist');
                return "DBIMAGE005";
            }
        }
    }
    
    /**
     * This function save image standard label and standard alternative text
     * @param Image $image
     * @return string
     */
    public function saveStandardInfo(Image $image){
        $data = array("label"=>$image->label, "alt"=>$image->alt);
        if($this->tableGateway->update($data, array('idImage'=>$image->idImage))){
            return "DBIMAGE003";
        }else{
            return "DBIMAGE004";
        }
    }
    
    /**
     * This function will delete a specific image
     * @param int $id
     * @return number
     */
    public function deleteImage($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idImage'=>(int)$id))){
            return "DBIMAGE006";
        }else{
            return "DBIMAGE007";
        }
    }
}

