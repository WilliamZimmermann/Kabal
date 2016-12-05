<?php
namespace ImagesDatabase\Model;

class Image
{
    public $idImage;
    public $website_id;
    public $name;
    public $extension;
    public $label;
    public $alt;
    public $creationDate;
    
    public function exchangeArray($data){
        $this->idImage = (!empty($data['idImage'])) ? $data['idImage'] : null;
        $this->website_id = (!empty($data['website_id'])) ? $data['website_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->extension = (!empty($data['extension'])) ? $data['extension'] : null;
        $this->label = (!empty($data['label'])) ? strip_tags($data['label']) : null;
        $this->alt = (!empty($data['alt'])) ? strip_tags($data['alt']) : null;
        $this->creationDate = (!empty($data['creationDate'])) ? $data['creationDate'] : date('Y-m-d H:i:s');
    }
    
    public function validation(){
        if($this->extension){
            if($this->extension!=("jpg" || "JPG" || "JPEG" || "jpeg" || "png" || "PNG" || "gif" || "GIF")){
                return false;
            }
        }
        return true;
    }
}

