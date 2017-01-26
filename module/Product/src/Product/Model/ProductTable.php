<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;

class ProductTable
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
     * This function get a specific product registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idProduct by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getProduct($id, $param="idProduct"){
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
     * @param Category $product (if $product->idCategory have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveProduct(Product $product){
        $data = array(
            'language_id'=>$product->language_id,
            'website_id'=>$product->website_id,
            'creationDate'=>$product->creationDate,
            'updatedDate'=>$product->updatedDate,
            'title'=>$product->title,
            'slug'=>$product->slug,
            'reference'=>$product->reference,
            'description'=>$product->description, 
            'content'=>$product->content,
            'price_original'=>$product->price_original,
            'price_actual'=>$product->price_actual,
            'comments'=>$product->comments,
            'log'=>$product->log,
            'show_website'=>$product->show_website,
            'show_order'=>$product->show_order,
            'active'=>$product->active);
        $id = (int)$product->idProduct;
        //If there is no Id, so, it's a new category
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                return $this->tableGateway->getLastInsertValue();
            }else{
                return "PRO002";
            }
        }else{
            //If this image already exists
            if($this->getCategory($id)){
                if($this->tableGateway->update($data, array('idProduct'=>$id))){
                    return "PRO004";
                }else{
                    return "PRO005";
                }
            }else{ //This id was not found at the system, image does not exist
                return "PRO007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific product
     * @param int $idProduct
     * @return number
     */
    public function deleteProduct($idProduct){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idProduct'=>(int)$idProduct))){
            return "PRO007";
        }else{
            return "PRO008";
        }
    }
}

