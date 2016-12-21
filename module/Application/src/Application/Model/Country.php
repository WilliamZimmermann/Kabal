<?php
namespace Application\Model;

class Country
{
    public $idCountry;
    public $name;
    public $name2;
    
    public function exchangeArray($data){
        $this->idCountry = (!empty($data['idCountry'])) ? $data['idCountry'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->name2 = (!empty($data['name2'])) ? (int)$data['name2'] : null;
    }
}

