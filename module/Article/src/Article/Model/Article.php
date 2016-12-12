<?php
namespace Article\Model;

use Zend\Validator\Date;

class Article
{
    public $idPage;
    public $website_id;
    public $title;
    public $description;
    public $author;
    public $publicationDate;
    public $lastUpdateDate;
    public $socialMedias;
    public $comments;
    public $active;
    
    public function exchangeArray($data){
        $this->idPage = (!empty($data['idPage'])) ? $data['idPage'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->description = (!empty($data['description'])) ? strip_tags($data['description']) : null;
        $this->author = (!empty($data['author'])) ? strip_tags($data['author']) : null;
        $this->publicationDate = (!empty($data['publicationDate'])) ? $data['publicationDate'] : date("Y-m-d H:i:s");
        $this->lastUpdateDate = (!empty($data['lastUpdateDate'])) ? $data['lastUpdateDate'] : date("Y-m-d H:i:s");
        $this->socialMedias = (!empty($data['socialMedias'])) ? 1 : 0;
        $this->comments = (!empty($data['comments'])) ? 1 : 0;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation(){
        if(!$this->website_id){
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        
        //Will validate the title
        $stringValidator->setMax(85);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->title)) {
            return false;
        }
        
        //Will validate the description
        $stringValidator->setMax(200);
        $stringValidator->setMin(4);
        if(!$stringValidator->isValid($this->description)) {
            return false;
        }
        
        //Will validate the author
        $stringValidator->setMax(60);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->description)) {
            return false;
        }
        
        //Will validate the publication date
        $dateValidator = new Date();
        $dateValidator->setFormat("d/m/Y H:i");
        if(!$dateValidator->isValid($this->publicationDate)) {
            return false;
        }
        
        return true;
    }
}

