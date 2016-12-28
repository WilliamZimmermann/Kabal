<?php
namespace Customer\Model;

class CustomerContact
{
    public $idContact;
    public $customer_id;
    public $principal;
    public $desc;
    public $phone;
    public $email;
    
    
    public function exchangeArray($data){
        $this->idContact = (!empty($data['idContact'])) ? (int)$data['idContact'] : null;
        $this->customer_id = (!empty($data['customer_id'])) ? (int)$data['customer_id'] : null;
        $this->principal = (!empty($data['principal'])) ? 1 : 0;
        $this->desc = (!empty($data['desc'])) ? strip_tags($data['desc']) : null;
        $this->phone = (!empty($data['phone'])) ? strip_tags($data['phone']) : null;
        $this->email = (!empty($data['email'])) ? strip_tags($data['email']) : null;
    }
    
    public function validation(){
        
        if(!$this->customer_id){
            return false;
        }
        $stringValidator = new \Zend\Validator\StringLength();
        $stringValidator->setMin(2);
        $stringValidator->setMax(255);
        
        if(!$stringValidator->isValid($this->desc)){
            return false;
        }
        
        
        return true;
    }
}

