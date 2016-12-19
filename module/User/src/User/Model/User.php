<?php

namespace User\Model;

class User {

    public $idUser;
    public $company_id;
    public $company;
    public $type;
    public $name;
    public $email;
    public $password;
    public $status;
    public $creationDate;

    public function exchangeArray($data) {
        $this->idUser = (!empty($data['idUser'])) ? $data['idUser'] : null;
        $this->company_id = (!empty($data['company_id'])) ? $data['company_id'] : null;
        $this->company = (!empty($data['company'])) ? $data['company'] : null;
        $this->type = (!empty($data['type'])) ? $data['type'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->status = (!empty($data['status'])) ? 1 : 0;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date('Y-m-d H:i:s');
    }

    public function validation($edit=false) {
        
        $stringValidator = new \Zend\Validator\StringLength();

        //Will validate the name
        $stringValidator->setMax(60);
        $stringValidator->setMin(4);
        if(!$stringValidator->isValid($this->name)) {
            return false;
        }

        //Will validate the email 
        $emailValidator = new \Zend\Validator\EmailAddress();

        if (!$emailValidator->isValid($this->email)) {
            return false;
        }

        //Just validate password if it is not a edition
        if(!$edit){
            $result = $this->validPassword();
            if(!$result){ //If result is false
                return false;
            }
        }

        return true;
    }
    
    /**
     * Method to validate and encrypt password
     * @return boolean
     */
    public function validPassword(){
        $stringValidator = new \Zend\Validator\StringLength();
        
        $stringValidator->setMin(6);
        
        if($this->password){
            if ($stringValidator->isValid($this->password)) {
                //Will encrypt the password
                $this->password = md5($this->password);
            } else {
                return false;
            }
        }
        return true;
    }

}
