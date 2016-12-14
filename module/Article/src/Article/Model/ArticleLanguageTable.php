<?php
namespace Article\Model;

use Zend\Db\TableGateway\TableGateway;
use Article\Model\ArticleLanguage;

class ArticleLanguageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all articles in all languages that are in our records
     * @param $articleId - Article Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($articleId=null){
        if($articleId){
            $where = array("article_id"=>$articleId);
        }else{
            $where = array();
        }
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function returns all articles for a specific website and language
     * @param $language - Language ID to filter
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllArticles($language, $websiteId){
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->join('website_article', 'website_article.idArticle = article_has_language.article_id', array());
        $sqlSelect->where(array("website_id"=>$websiteId, "language_id"=>$language));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        //die(var_dump($resultSet));
        return $resultSet;
    }
    
    /**
     * This function get a specific article registred in our data base
     * @param int $id
     * @param int $language
     * @param string $param ('article_id' by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getArticle($value, $language, $param='article_id'){
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
     * This function insert or edit a article language at the database
     * @param Article $article (if $article->article_id and $article->language_id have valid id's, will update, not insert)
     * @throws \Exception
     */
    public function saveArticle(ArticleLanguage $article){
        $data = array(
            'language_id'=>$article->language_id, 
            'article_id'=>$article->article_id, 
            'title'=>$article->title, 
            'description'=>$article->description,
            'slug'=>$article->slug,
            'resume'=>$article->resume,
            'section_title'=>$article->section_title,
            'section_description'=>$article->section_description,
            'content'=>$article->content,
            'lastUpdateDate'=>date("Y-m-d H:i:s"),
            'active'=>$article->active,
        );
        
        $results = $this->getArticle($article->article_id, $article->language_id);
        //If there is no result, so, it's a new language article
        if(!$results){
            if($this->tableGateway->insert($data)){
                return "ARTL001";
            }else{
                return "ARTL002";
            }
        }else{
            //If this article already exists
            if($this->tableGateway->update($data, array('language_id'=>$article->language_id, 'article_id'=>$article->article_id))){
                return "ARTL004";
            }else{
                return "ARTL005";
            }
        }
    }
    
    
    /**
     * This function will delete a specific language article
     * @param int $languageId
     * @param int $articleId
     * @return number
     */
    public function deleteArticle($languageId=null, $articleId){
        //Here we must to put the recursive functions to delete all future content
        $where = ($languageId==null) ? array('article_id'=>$articleId) : array('language_id'=>$languageId, 'article_id'=>$articleId);
        
        if($this->tableGateway->delete($where)){
            return "ART007";
        }else{
            return "ART008";
        }
    }
}

