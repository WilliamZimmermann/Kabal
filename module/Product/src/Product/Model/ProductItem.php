<?php
namespace Product\Model;

class ProductItem
{
    public $idProductItem, $product_id, $description, $color_id, $size, $size_kind, $stock, $price;
    
    public function exchangeArray($data){
        $this->idProductItem = (!empty($data['idProductItem'])) ? $data['idProductItem'] : null;
        $this->product_id = (!empty($data['product_id'])) ? $data['product_id'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->color_id = (!empty($data["color_id"])) ? $data["color_id"] : null;
        $this->size = (!empty($data["size"])) ? $data["size"] : null;
        $this->size_kind = (!empty($data["size_kind"])) ? $data["size_kind"] : null;
        $this->stock = (!empty($data["stock"])) ? $data["stock"] : null;
        $this->price = (!empty($data["price"])) ? $data["price"] : null;
    }
    
    public function validation(){
        if($this->product_id==null){
            return false;
        }
        if($this->description==null){
            return false;
        }
        if($this->price==null){
            return false;
        }
        return true;
    }
}

