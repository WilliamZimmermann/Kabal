<?php
namespace Customer\Model;

use Zend\Validator\Date;
use Zend\Validator\EmailAddress;

class Customer
{
    public $idAddress;
    public $customer_id;
    public $name;
    public $street;
    public $house_number;
    public $complement;
    public $neighborhood;
    public $city;
    public $zip_code;
    public $zone;
    public $country_id;
    public $principal;
    public $active;
    
    public function exchangeArray($data){
        $this->idAddress = (!empty($data['idAddress'])) ? $data['idAddress'] : null;
        $this->customer_id = (!empty($data['customer_id'])) ? $data['customer_id'] : null;
        $this->addedBy = (!empty($data['addedBy'])) ? (int)$data['addedBy'] : null;
        $this->customerType = (!empty($data['customerType'])) ? (int)$data['customerType'] : null;
        $this->email = (!empty($data['email'])) ? strip_tags($data['email']) : null;
        $this->password = (!empty($data['password'])) ? md5($data['password']) : null;
        $this->password2 = (!empty($data['password2'])) ? md5($data['password2']) : null;
        $this->birthDate = (!empty($data['birthDate'])) ? $data['birthDate'] : null;
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation($validatePassword=true){
        if(!$this->company_id){
            return false;
        }
        
        $stringValidator = new \Zend\Validator\StringLength();
        $stringValidator->setMax(85);
        $stringValidator->setMin(2);
        
        $emailValidator = new EmailAddress();
        //Email validation
        if(!$emailValidator->isValid($this->email)) {
            return false;
        }
        
        if($validatePassword){
            //Validate Password
            $stringValidator->setMax(33);
            $stringValidator->setMin(6);
            if(!$stringValidator->isValid($this->password)) {
                return false;
            }
            if(!$stringValidator->isValid($this->password2)) {
                return false;
            }
            if($this->password!=$this->password2){
                return false;
            }
        }
        
        //Validate Birth Date (day)
        $dateValidator = new Date();
        $dateValidator->setFormat("Y-m-d");
        if(!$dateValidator->isValid($this->birthDate)) {
            return false;
        }
        
        return true;
    }
}

