<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

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
    public function fetchAll($websiteId=null, $currentPage = 1, $countPerPage = 20){
        if($websiteId){
            $where = array("website_id"=>$websiteId);
        }else{
            $where = array();
        }
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        $sqlSelect->order(array('language_id', 'title'));
        
        $adapter = new DbSelect($sqlSelect, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype());
        
        $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
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
        return $row;
    }

    /**
     * This function insert or edit a product in the database
     * @param Category $product (if $product->idCategory have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveProduct(Product $product){
        $data = array(
            'language_id'=>$product->language_id,
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
            $data['website_id'] = $product->website_id;
            $data['creationDate'] = $product->creationDate;
            if($this->tableGateway->insert($data)){
                return $this->tableGateway->getLastInsertValue();
            }else{
                return "PRO002";
            }
        }else{
            //If this product already exists
            if($this->getProduct($id)){
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
    
    //Save a log of any customer changes
    public function saveLogChanges($idProduct, $message){
        $product = $this->getProduct($idProduct);
        if($product){
            $log = $product->log;
            $data = array(
              "log"=>$log."\n".$message,
              "updatedDate"=>date('Y-m-d H:i:s')
            );
            if($this->tableGateway->update($data, array('idProduct'=>$idProduct))){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
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
            return "PRO008";
        }else{
            return "PRO007";
        }
    }
}

