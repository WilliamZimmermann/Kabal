<?php
namespace Article\Model;

class CategoryLanguage
{
    public $language_id;
    public $page_id;
    public $title;
    public $description;
    public $slug;
    public $section_title;
    public $section_description;
    public $content;
    public $active;
    public $creationDate;
    
    public function exchangeArray($data){
        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->page_id = (!empty($data['page_id'])) ? $data['page_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->description = (!empty($data['description'])) ? strip_tags($data['description']) : null;
        $this->slug = (!empty($data['slug'])) ? strip_tags($data['slug']) : null;
        $this->section_title = (!empty($data['section_title'])) ? strip_tags($data['section_title']) : null;
        $this->section_description = (!empty($data['section_description'])) ? strip_tags($data['section_description']) : null;
        $this->content = (!empty($data['content'])) ? strip_tags($data['content']) : null;
        $this->active = (!empty($data['active'])) ? 1 : 0;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date('Y-m-d H:i:s');
    }
    
    public function validation(){
        if(!$this->language_id){
            return false;
        }
        if(!$this->page_id){
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
        
        //Validate slug
        $stringValidator->setMax(150);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->slug)) {
            return false;
        }
        
        return true;
    }
}

