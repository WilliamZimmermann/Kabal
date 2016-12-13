<?php
namespace Article\Model;

class ArticleLanguage
{
    public $idArticleLanguage;
    public $language_id;
    public $article_id;
    public $title;
    public $description;
    public $slug;
    public $resume;
    public $section_title;
    public $section_description;
    public $content;
    public $lastUpdateDate;
    public $active;
    
    public function exchangeArray($data){
        $this->idArticleLanguage =  (!empty($data['idArticleLanguage'])) ? $data['idArticleLanguage'] : null;
        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->article_id = (!empty($data['article_id'])) ? $data['article_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->description = (!empty($data['description'])) ? strip_tags($data['description']) : null;
        $this->slug = (!empty($data['slug'])) ? strip_tags($data['slug']) : null;
        $this->resume = (!empty($data['resume'])) ? $data['resume'] : null;
        $this->section_title = (!empty($data['section_title'])) ? strip_tags($data['section_title']) : null;
        $this->section_description = (!empty($data['section_description'])) ? strip_tags($data['section_description']) : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
        $this->lastUpdateDate = (!empty($data['lastUpdateDate'])) ? $data['lastUpdateDate'] : date('Y-m-d H:i:s');
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation(){
        if(!$this->language_id){
            return false;
        }
        if(!$this->article_id){
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
        
        //Will validate the resume
        $stringValidator->setMax(200);
        $stringValidator->setMin(4);
        if(!$stringValidator->isValid($this->resume)) {
            return false;
        }
        
        //Validate slug
        $stringValidator->setMax(150);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->slug)) {
            return false;
        }
        
        return true;
    }
}

