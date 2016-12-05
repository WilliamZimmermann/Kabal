<?php
namespace Page\Model;

use Zend\Db\TableGateway\TableGateway;

class PageLanguageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all pages in all languages that are in our records
     * @param $websiteId - Page Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($pageId=null){
        if($pageId){
            $where = array("page_id"=>$pageId);
        }else{
            $where = array();
        }
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function get a specific page registred in our data base
     * @param int $id
     * @param int $language
     * @param string $param ('page_id' by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getPage($id, $language, $param='page_id'){
        $id  = (int) $id;
        $langauge = (int) $language;
        $data = array('language_id'=>$langauge, $param => $id);
        $rowset = $this->tableGateway->select($data);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    /**
     * This function insert or edit a page language at the database
     * @param Page $page (if $page->page_id and $page->language_id have valid id's, will update, not insert)
     * @throws \Exception
     */
    public function savePage(PageLanguage $page){
        $data = array(
            'language_id'=>$page->language_id, 
            'page_id'=>$page->page_id, 
            'title'=>$page->title, 
            'description'=>$page->description,
            'slug'=>$page->slug,
            'section_title'=>$page->section_title,
            'section_description'=>$page->section_description,
            'content'=>$page->content,
            'active'=>$page->active,
            'creationDate'=>$page->creationDate
        );
        
        $results = $this->getPage($page->page_id, $page->language_id);
        //If there is no result, so, it's a new language page
        if(!$results){
            if($this->tableGateway->insert($data)){
                return "PAGEL001";
            }else{
                return "PAGEL002";
            }
        }else{
            //If this page already exists
            if($this->tableGateway->update($data, array('language_id'=>$page->language_id, 'page_id'=>$page->page_id))){
                return "PAGEL004";
            }else{
                return "PAGEL005";
            }
        }
    }
    
    
    /**
     * This function will delete a specific language page
     * @param int $languageId
     * @param int $pageId
     * @return number
     */
    public function deletePage($languageId=null, $pageId){
        //Here we must to put the recursive functions to delete all future content
        $where = ($languageId==null) ? array('page_id'=>$pageId) : array('language_id'=>$languageId, 'page_id'=>$pageId);
        
        if($this->tableGateway->delete($where)){
            return "PAGE007";
        }else{
            return "PAGE008";
        }
    }
}

