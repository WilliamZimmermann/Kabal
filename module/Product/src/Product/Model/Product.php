<?php
namespace Product\Model;

use Zend\Validator\Date;

class Product
{
    public $idProduct, $language_id, $company_id, $website_id, $creationDate, $updatedDate, $title, $slug, $reference, $description, $content, $price_original, $price_actual, $comments, $log, $show_website, $show_order, $active;

    public function exchangeArray($data){
        $this->idProduct = (!empty($data['idProduct'])) ? $data['idProduct'] : null;
        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->website_id = (!empty($data['website_id'])) ? strip_tags($data['website_id']) : null;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date("Y-m-d H:i:s");
        $this->updatedDate = (!empty($data['updatedDate'])) ? $data['updatedDate'] : date("Y-m-d H:i:s");
        $this->title = (!empty($data['title'])) ? strip_tags($data['title']) : null;
        $this->slug = (!empty($data['slug'])) ? strip_tags($data['slug']) : null;
        $this->reference = (!empty($data['reference'])) ? strip_tags($data['reference']) : null;
        $this->description = (!empty($data['description'])) ? strip_tags($data['description']) : null;
        $this->content = (!empty($data['content'])) ? strip_tags($data['content']) : null;
        $this->price_original = (!empty($data['price_original'])) ? strip_tags($data['price_original']) : null;
        $this->price_actual = (!empty($data['price_actual'])) ? strip_tags($data['price_actual']) : null;
        $this->comments = (!empty($data['comments'])) ? strip_tags($data['comments']) : null;
        $this->log = (!empty($data['log'])) ? strip_tags($data['log']) : null;
        $this->show_website = (!empty($data['show_website'])) ? 1 : 0;
        $this->show_order = (!empty($data['show_order'])) ? 1 : 0;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    } 
    
    public function validation(){
        if(!$this->website_id || !$this->language_id){
            return false;
        }
    
        $stringValidator = new \Zend\Validator\StringLength();
    
        //Will validate the title
        $stringValidator->setMax(85);
        $stringValidator->setMin(2);
        if(!$stringValidator->isValid($this->title)) {
            return false;
        }
    
        return true;
    }
}

