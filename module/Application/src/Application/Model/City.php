<?php
namespace Application\Model;

class City
{
    public $id;
    public $name;
    public $zone_id;
    
    public function exchangeArray($data){
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->zone_id = (!empty($data['zone_id'])) ? $data['zone_id'] : null;
    }
}

