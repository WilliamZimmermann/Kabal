<?php
namespace Article\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Services\SystemFunctions;

class ArticleTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * This function returns all articles that are in our records
     * @param $websiteId - Website Id to filter
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($websiteId=null){
        if($websiteId){
            $where = array("website_id"=>$websiteId);
        }else{
            $where = array();
        }
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
    
    /**
     * This function get a specific article registred in our data base
     * @param int $id
     * @param string $param (name of some param to make the serc (idArticle by default)
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getArticle($id, $param="idArticle"){
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
     * This function insert or edit a article in the database
     * @param Article $article (if $article->idArticle have a valid id, will update, not insert)
     * @throws \Exception
     */
    public function saveArticle(Article $article){
        $data = array(
            'website_id'=>$article->website_id, 
            'title'=>$article->title, 
            'description'=>$article->description, 
            'author'=>$article->author,
            'publicationDate'=>SystemFunctions::dateInvert($article->publicationDate, "american"),
            'lastUpdateDate'=>date('Y-m-d H:i:s'),
            'socialMedias'=>$article->socialMedias,
            'comments'=>$article->comments,
            'active'=>$article->active);
        
        
        $id = (int)$article->idArticle;
        //If there is no Id, so, it's a new article
        if($id  == 0){
            if($this->tableGateway->insert($data)){
                $id = $this->tableGateway->getLastInsertValue();
                return array("code"=>"ART001", "id"=>$id);
            }else{
                return "ART002";
            }
        }else{
            //If this article already exists
            if($this->getArticle($id)){
                if($this->tableGateway->update($data, array('idArticle'=>$id))){
                    return "ART004";
                }else{
                    return "ART005";
                }
            }else{ //This id was not found at the system, article does not exist
                return "ART007";
            }
        }
    }
    
    
    /**
     * This function will delete a specific article
     * @param int $id
     * @return number
     */
    public function deleteArticle($id){
        //Here we must to put the recursive functions to delete all future content
        if($this->tableGateway->delete(array('idArticle'=>(int)$id))){
            return "ART007";
        }else{
            return "ART008";
        }
    }
}

