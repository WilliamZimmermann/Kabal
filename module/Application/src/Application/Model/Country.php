<?php
namespace Application\Model;

class Country
{
    public $countryId;
    public $name;
    public $name2;
    
    public function exchangeArray($data){
        $this->countryId = (!empty($data['countryId'])) ? $data['countryId'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->name2 = (!empty($data['name2'])) ? (int)$data['name2'] : null;
    }
}

