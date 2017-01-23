<?php
namespace Product\Model;

class CategoryLanguage
{
    public $idCategoryLanguage;
    public $language_id;
    public $category_id;
    public $title;
    public $slug;
    public $active;
    
    public function exchangeArray($data){
        $this->idCategoryLanguage = (!empty($data['idCategoryLanguage'])) ? $data['idCategoryLanguage'] : null;
        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->slug = (!empty($data['slug'])) ? strip_tags($data['slug']) : null;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation(){
        if(!$this->language_id){
            return false;
        }
        if(!$this->category_id){
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        
        //Will validate the title
        $stringValidator->setMax(60);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->title)) {
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

