<?php
namespace Customer\Model;

use Zend\Validator\Date;
use Zend\Validator\EmailAddress;

class Customer
{
    public $idCustomer;
    public $company_id;
    public $addedBy;
    public $customerType;
    public $email;
    public $password;    
    public $password2;
    public $birthDate;
    public $country_id;
    public $comments;
    public $log;
    public $dateCreated;
    public $dateUpdated;
    public $active;
    //For Joins
    public $name;
    public $name2;
    public $name3;
    public $name4;
    
    public function exchangeArray($data){
        $this->idCustomer = (!empty($data['idCustomer'])) ? $data['idCustomer'] : null;
        $this->company_id = (!empty($data['company_id'])) ? $data['company_id'] : null;
        $this->addedBy = (!empty($data['addedBy'])) ? (int)$data['addedBy'] : null;
        $this->customerType = (!empty($data['customerType'])) ? (int)$data['customerType'] : null;
        $this->email = (!empty($data['email'])) ? strip_tags($data['email']) : null;
        $this->password = (!empty($data['password'])) ? md5($data['password']) : null;
        $this->password2 = (!empty($data['password2'])) ? md5($data['password2']) : null;
        $this->birthDate = (!empty($data['birthDate'])) ? $data['birthDate'] : null;
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
        $this->comments = (!empty($data['comments'])) ? $data['comments'] : null;
        $this->log = (!empty($data['log'])) ? $data['log'] : null;
        $this->dateCreated = (!empty($data['dateCreated'])) ? $data['dateCreated'] : date("Y-m-d H:i:s");
        $this->dateUpdated = (!empty($data['dateUpdated'])) ? $data['dateUpdated'] : date("Y-m-d H:i:s");
        $this->active = (!empty($data['active'])) ? 1 : 0;
        //For Joins
        $this->name = (!empty($data['name'])) ? strip_tags($data['name']) : null;
        $this->name2 = (!empty($data['name2'])) ? strip_tags($data['name2']) : null;
        $this->name3 = (!empty($data['name3'])) ? strip_tags($data['name3']) : null;
        $this->name4 = (!empty($data['name4'])) ? strip_tags($data['name4']) : null;
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

