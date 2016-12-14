<?php
namespace Article\Model;

use Zend\Db\TableGateway\TableGateway;

class ArticleHasCategoryTable
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
    public function fetchAll($id, $column="language_idArticle"){
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
    public function saveCategory(ArticleHasCategory $articleCategory){
        $data = array('language_idArticle'=>$articleCategory->language_idArticle, 'language_idCategory'=>$articleCategory->language_idCategory);
        
        if($this->tableGateway->insert($data)){
            return true;
        }else{
            return false;
        }
    }
    
    
    /**
     * This function will delete a specific relationship
     * @param ArticleHasCategory $articleCategory
     * @return string
     */
    public function deleteCategory(ArticleHasCategory $articleCategory){
        if($articleCategory->language_idArticle && $articleCategory->language_idCategory){
            $where = array(
                'language_idArticle'=>$articleCategory->language_idArticle, 
                'language_idCategory'=>$articleCategory->language_idCategory
            );
        }else if($articleCategory->language_idArticle){
            $where = array(
                'language_idArticle'=>$articleCategory->language_idArticle
            );
        }else if($articleCategory->language_idCategory){
            $where = array(
                'language_idCategory'=>$articleCategory->language_idCategory
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

