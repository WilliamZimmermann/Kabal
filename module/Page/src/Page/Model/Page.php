<?php
namespace Page\Model;

class Page
{
    public $idPage;
    public $website_id;
    public $title;
    public $description;
    public $active;
    
    public function exchangeArray($data){
        $this->idPage = (!empty($data['idPage'])) ? $data['idPage'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->description = (!empty($data['description'])) ? strip_tags($data['description']) : null;
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
        
        return true;
    }
}

