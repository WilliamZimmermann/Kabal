<?php
namespace Product\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoryLanguageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all categories in all languages that are in our records
     * @param $categoryId - Page Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($categoryId=null){
        if($categoryId){
            $where = array("category_id"=>$categoryId);
        }else{
            $where = array();
        }
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function returns all categories for a specific website and language
     * @param $language - Language ID to filter
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllCategories($language, $websiteId){
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('product_category', 'product_category.idCategory = product_category_has_language.category_id', array());
        $sqlSelect->where(array("website_id"=>$websiteId, "language_id"=>$language));
        $sqlSelect->order("title");
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        //die(var_dump($resultSet));
        return $resultSet;
    }
    
    /**
     * This function get a specific category registred in our data base
     * @param int $id
     * @param int $language
     * @param string $param ('category_id' by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getCategory($value, $language, $param='category_id'){
        $langauge = (int) $language;
        $data = array('language_id'=>$langauge, $param => $value);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    /**
     * This function insert or edit a category language at the database
     * @param Page $category (if $category->category_id and $category->language_id have valid id's, will update, not insert)
     * @throws \Exception
     */
    public function saveCategory(CategoryLanguage $category){
        $data = array(
            'language_id'=>$category->language_id, 
            'category_id'=>$category->category_id, 
            'title'=>$category->title, 
            'slug'=>$category->slug,
            'active'=>$category->active,
        );
        
        $results = $this->getCategory($category->category_id, $category->language_id);
        //If there is no result, so, it's a new language category
        if(!$results){
            if($this->tableGateway->insert($data)){
                return "PCATL001";
            }else{
                return "PCATL002";
            }
        }else{
            //If this category already exists
            if($this->tableGateway->update($data, array('language_id'=>$category->language_id, 'category_id'=>$category->category_id))){
                return "PCATL004";
            }else{
                return "PCATL005";
            }
        }
    }
    
    
    /**
     * This function will delete a specific language category
     * @param int $languageId
     * @param int $categoryId
     * @return number
     */
    public function deleteCategory($languageId=null, $categoryId){
        //Here we must to put the recursive functions to delete all future content
        $where = ($languageId==null) ? array('category_id'=>$categoryId) : array('language_id'=>$languageId, 'category_id'=>$categoryId);
        
        if($this->tableGateway->delete($where)){
            return "PCATL007";
        }else{
            return "PCATL008";
        }
    }
}

