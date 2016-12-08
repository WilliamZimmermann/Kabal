<?php
namespace Article\Model;

class Category
{
    public $idCategory;
    public $website_id;
    public $title;
    public $description;
    public $active;
    
    public function exchangeArray($data){
        $this->idCategory = (!empty($data['idCategory'])) ? $data['idCategory'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation(){
        if(!$this->website_id){
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        
        //Will validate the title
        $stringValidator->setMax(60);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->title)) {
            return false;
        }
        
        return true;
    }
}

