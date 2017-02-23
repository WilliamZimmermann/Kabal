<?php
namespace Product\Model;

class Color
{
    public $idColor, $website_id, $language_id, $name, $hexa, $image;
    
    public function exchangeArray($data){
        $this->idColor = (!empty($data['idColor'])) ? $data['idColor'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->name = (!empty($data["name"])) ? $data["name"] : null;
        $this->hexa = (!empty($data["hexa"])) ? $data["hexa"] : null;
        $this->image = (!empty($data["image"])) ? $data["image"] : null;
    }
    
    public function validation(){
        if($this->website_id==null){
            return false;
        }
        if($this->language_id==null){
            return false;
        }
        if($this->name==null){
            return false;
        }
        return true;
    }
}

