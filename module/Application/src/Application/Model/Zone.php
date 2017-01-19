<?php
namespace Application\Model;

class Zone
{
    public $id;
    public $name;
    public $initials;
    public $country_id;
    
    public function exchangeArray($data){
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
    }
}

