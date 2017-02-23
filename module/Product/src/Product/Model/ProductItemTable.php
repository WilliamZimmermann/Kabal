<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ProductItemTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all itens that are in our records
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($productId, $currentPage = 1, $countPerPage = 20){
        $where = array("product_id"=>$productId);
        
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        $sqlSelect->order(array('description', 'price'));
        
        $adapter = new DbSelect($sqlSelect, $this->tableGateway->getAdapter(), $this->tableGateway->getResultSetPrototype());
        
        $paginator = new Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }
    
    
    /**
     * This function get a specific item registred in our data base
     * @param int $idProductItem
     * @param string $param (name of some param to make the serc (idProduct by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getItem($id, $param="idProductItem"){
        $id  = (int) $id;
        $data = array($param => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        return $row;
    }

    /**
     * This function insert or edit a product item in the database
     * @param ProductItem $productItem
     * @throws \Exception
     */
    public function saveProductItem(ProductItem $productItem){
        $data = array(
            'description'=>$productItem->description,
            'color_id'=>$productItem->color_id,
            'size'=>$productItem->size,
            'size_kind'=>$productItem->size_kind,
            'stock'=>$productItem->stock, 
            'price'=>$productItem->price);
        $id = (int)$productItem->idProductItem;
        //If there is no Id, so, it's a new category
        if($id  == 0){
            $data['product_id'] = $productItem->product_id;
            
            if($this->tableGateway->insert($data)){
                return $this->tableGateway->getLastInsertValue();
            }else{
                return "PROI002";
            }
        }else{
            //If this item already exists
            if($this->getItem($id)){
                if($this->tableGateway->update($data, array('idProductItem'=>$id))){
                    return "PROI004";
                }else{
                    return "PROI005";
                }
            }else{ //This id was not found at the system, image does not exist
                return "PROI007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific product
     * @param int $idProduct
     * @return number
     */
    public function deleteProductItem($idProductItem){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idProductItem'=>(int)$idProductItem))){
            return "PROI007";
        }else{
            return "PROI008";
        }
    }
    
    /**
     * This function delete all itens related with one product
     * @param int $idProduct
     * @return string
     */
    public function deleteAll($idProduct){
        //Verifica primeiro se tem algum item para ser removido
        if(count($this->fetchAll($idProduct))>0){
            //Here we must to put the recursive functions to delete all future content
            if($this->tableGateway->delete(array('product_id'=>(int)$idProduct))){
                return "PROI007";
            }else{
                return "PROI008";
            }
        }else{
            return "PROI007";
        }
    }
}

