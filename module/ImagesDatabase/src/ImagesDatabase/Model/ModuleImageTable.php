<?php
namespace ImagesDatabase\Model;

use Zend\Db\TableGateway\TableGateway;

class ModuleImageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all images that are in our records
     * @param $idModule - Module id that the image is
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($idModule, $idItem){
        $sqlSelect = $this->tableGateway->getSql()->select();
        //$sqlSelect->columns(array('company_website_idWebsite', 'system_module_idModule'));
        $sqlSelect->join('image', 'system_module_has_image.image_idImage = image.idImage', array("name"=>"name", "extension"=>"extension"), 'left');
        $sqlSelect->where(array("system_module_idModule"=>$idModule, "id_item"=>$idItem));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }
    
    /**
     * This function get a specific company registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getImageData($idModule, $idImage, $idItem){
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('system_module_idModule' => $idModule, 'image_idImage'=>$idImage, 'id_item'=>$idItem));
        $row = $rowset->current();
        return $row;
    }

    /**
     * This function insert or edit a image in the database
     * @param Image $image (if $image->idImage have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveImage(ModuleImage $image){
        $data = array('system_module_idModule'=>$image->system_module_idModule, 'image_idImage'=>$image->image_idImage, 'id_item'=>$image->id_item, 'label'=>$image->label, 'alt'=>$image->alt, 'active'=>$image->active);
        //Check if this image is already registred for this item and module
        $dataToCheck = $this->getImageData($image->system_module_idModule, $image->image_idImage, $image->id_item);
        if(!$dataToCheck){
            if($this->tableGateway->insert($data)){
                return "DBIMAGE001";
            }else{
                return "DBIMAGE002";
            }
        }else{
            //If this image already exists
            if($this->tableGateway->update($data, array('system_module_idModule' => $image->system_module_idModule, 'image_idImage'=>$image->image_idImage, 'id_item'=>$image->id_item))){
                return "DBIMAGE003";
            }else{
                return "DBIMAGE004";
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
    public function deleteImage($idModule=null, $idImage=null, $idItem=null){
        if($idModule && $idItem){
            $data = array("system_module_idModule"=>$idModule, "id_item"=>$idItem);
        }
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete($data)){
            return "DBIMAGE006";
        }else{
            return "DBIMAGE007";
        }
    }
}

