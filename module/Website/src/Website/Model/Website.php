<?php

namespace Website\Model;

class Website {

    public $idWebsite;
    public $company_id;
    public $company;
    public $name;
    public $status;
    public $creationDate;
    public $apiKey;
    public $apiIp;
    public $modules = array();
    
    public function exchangeArray($data) {
        $this->idWebsite = (!empty($data['idWebsite'])) ? $data['idWebsite'] : null;
        $this->company_id = (!empty($data['company_id'])) ? $data['company_id'] : null;
        $this->company = (!empty($data['company'])) ? $data['company'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->status = (!empty($data['status'])) ? 1 : 0;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date('Y-m-d H:i:s');
        $this->apiKey = (!empty($data['apiKey'])) ? $data['apiKey'] : null;
        $this->apiIp = (!empty($data['apiIp'])) ? $data['apiIp'] : null;
        $this->modules = (!empty($data['modules'])) ? $data['modules'] : null;
    }

    public function validation($edition=false) {
        
        $stringValidator = new \Zend\Validator\StringLength();

        //Will validate the name
        $stringValidator->setMax(120);
        $stringValidator->setMin(4);
        if(!$stringValidator->isValid($this->name)) {
            return false;
        }

        return true;
    }

}
