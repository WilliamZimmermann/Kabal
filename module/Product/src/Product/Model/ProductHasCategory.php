<?php
namespace Product\Model;

class ProductHasCategory
{
    public $product_idProduct;
    public $product_idCategoryLanguage;
    
    public function exchangeArray($data){
        $this->product_idProduct = (!empty($data['product_idProduct'])) ? $data['product_idProduct'] : 0;
        $this->product_idCategoryLanguage = (!empty($data['product_idCategoryLanguage'])) ? $data['product_idCategoryLanguage'] : 0;
    }
}

