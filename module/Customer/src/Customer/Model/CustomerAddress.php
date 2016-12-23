<?php
namespace Customer\Model;

class CustomerAddress
{
    public $idAddress;
    public $customer_id;
    public $name;
    public $street;
    public $house_number;
    public $complement;
    public $neighborhood;
    public $zip_code;
    public $city_id;
    public $zone_id;
    public $country_id;
    public $principal;
    public $active;
    
    public function exchangeArray($data){
        $this->idAddress = (!empty($data['idAddress'])) ? (int)$data['idAddress'] : null;
        $this->customer_id = (!empty($data['customer_id'])) ? (int)$data['customer_id'] : null;
        $this->name = (!empty($data['name'])) ? strip_tags($data['name']) : null;
        $this->street = (!empty($data['street'])) ? strip_tags($data['street']) : null;
        $this->house_number = (!empty($data['house_number'])) ? strip_tags($data['house_number']) : null;
        $this->complement = (!empty($data['complement'])) ? strip_tags($data['complement']) : null;
        $this->neighborhood = (!empty($data['neighborhood'])) ? strip_tags($data['neighborhood']) : null;
        $this->zip_code = (!empty($data['zip_code'])) ? strip_tags($data['zip_code']) : null;
        $this->city_id = (!empty($data['city_id'])) ? $data['city_id'] : null;
        $this->zone_id = (!empty($data['zone_id'])) ? $data['zone_id'] : null;
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
        $this->principal = (!empty($data['principal'])) ? 1 : 0;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation($validatePassword=true){
        if(!$this->customer_id){
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        $stringValidator->setMin(2);
        $stringValidator->setMax(45);
        
        if(!$stringValidator->isValid($this->name)){
            return false;
        }
        $stringValidator->setMax(60);
        if(!$stringValidator->isValid($this->street)){
            return false;
        }
        $stringValidator->setMin(0);
        $stringValidator->setMax(15);
        if(!$stringValidator->isValid($this->house_number)){
            return false;
        }
        $stringValidator->setMax(60);
        if(!$stringValidator->isValid($this->complement)){
            return false;
        }
        $stringValidator->setMax(60);
        if(!$stringValidator->isValid($this->neighborhood)){
            return false;
        }
        $stringValidator->setMin(1);
        $stringValidator->setMax(45);
        if(!$stringValidator->isValid($this->zip_code)){
            return false;
        }
        
        if(!$this->city_id){
            return false;
        }
        if(!$this->zone_id){
            return false;
        }
        if(!$this->country_id){
            return false;
        }
        
        
        return true;
    }
}

