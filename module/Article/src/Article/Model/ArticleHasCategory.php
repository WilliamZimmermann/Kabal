<?php
namespace Article\Model;

class ArticleHasCategory
{
    public $language_idArticle;
    public $language_idCategory;
    
    public function exchangeArray($data){
        $this->language_idArticle = (!empty($data['language_idArticle'])) ? $data['language_idArticle'] : 0;
        $this->language_idCategory = (!empty($data['language_idCategory'])) ? $data['language_idCategory'] : 0;
    }
}

