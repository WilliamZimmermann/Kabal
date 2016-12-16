<?php
namespace Company\Model;

class Company
{
    public $idCompany;
    public $name;
    public $max_users;
    public $status;
    public $creationDate;
    
    public function exchangeArray($data){
        $this->idCompany = (!empty($data['idCompany'])) ? $data['idCompany'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->max_users = (!empty($data['max_users'])) ? $data['max_users'] : null;
        $this->status = (!empty($data['status'])) ? 1 : 0;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date('Y-m-d H:i:s');
    }
    
    public function validation(){
        if(!$this->name){
            return false;
        }
        return true;
    }
}

