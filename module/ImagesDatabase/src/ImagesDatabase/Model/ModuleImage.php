<?php
namespace ImagesDatabase\Model;

class ModuleImage
{
    public $system_module_idModule;
    public $image_idImage;
    public $id_item;
    public $name; //Come from a inner join with images table
    public $extension; //Come from a inner join with images table
    public $label;
    public $alt;
    public $active;
    
    public function exchangeArray($data){
        $this->system_module_idModule = (!empty($data['system_module_idModule'])) ? $data['system_module_idModule'] : null;
        $this->image_idImage = (!empty($data['image_idImage'])) ? $data['image_idImage'] : null;
        $this->id_item = (!empty($data['id_item'])) ? $data['id_item'] : null;
        $this->name = (!empty($data['name'])) ? strip_tags($data['name']) : null;
        $this->extension = (!empty($data['extension'])) ? strip_tags($data['extension']) : null;
        $this->label = (!empty($data['label'])) ? strip_tags($data['label']) : null;
        $this->alt = (!empty($data['alt'])) ? strip_tags($data['alt']) : null;
        $this->active = (!empty($data['active'])) ? 1 : 0;
    }
    
    public function validation(){
        return true;
    }
}

