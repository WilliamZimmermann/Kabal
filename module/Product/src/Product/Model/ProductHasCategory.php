<?php
namespace Product\Model;

class ProductHasCategory
{
    public $product_idProduct;
    public $product_idCategory;
    
    public function exchangeArray($data){
        $this->product_idProduct = (!empty($data['product_idProduct'])) ? $data['product_idProduct'] : 0;
        $this->product_idCategory = (!empty($data['product_idCategory'])) ? $data['product_idCategory'] : 0;
    }
}

