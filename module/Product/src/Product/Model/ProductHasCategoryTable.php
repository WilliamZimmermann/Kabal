<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;

class ProductHasCategoryTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all relationships between some category or aticle
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($id, $column="product_idProduct"){
        $where = array($column=>$id);
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->where($where);
        return $this->tableGateway->selectWith($sqlSelect);
    }
    

    /**
     * This function insert or edit a category in the database
     * @param Category $category (if $category->idCategory have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveCategory(ProductHasCategory $productCategory){
        $data = array('product_idProduct'=>$productCategory->product_idProduct, 'product_idCategoryLanguage'=>$productCategory->product_idCategory);
        
        if($this->tableGateway->insert($data)){
            return true;
        }else{
            return false;
        }
    }
    
    
    /**
     * This function will delete a specific relationship
     * @param ProductHasCategory $productCategory
     * @return string
     */
    public function deleteCategory(ProductHasCategory $productCategory){
        if($productCategory->product_idProduct && $productCategory->product_idCategoryLanguage){
            $where = array(
                'product_idProduct'=>$productCategory->product_idProduct, 
                'product_idCategoryLanguage'=>$productCategory->product_idCategoryLanguage
            );
        }else if($productCategory->product_idProduct){
            $where = array(
                'product_idProduct'=>$productCategory->product_idProduct
            );
        }else if($productCategory->product_idCategoryLanguage){
            $where = array(
                'product_idCategoryLanguage'=>$productCategory->product_idCategoryLanguage
            );
        }
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete($where)){
            return true;
        }else{
            return false;
        }
    }
}

