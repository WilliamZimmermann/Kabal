<?php

namespace Website\Model;

class WebsiteLanguage {

    public $company_website_id;
    public $language_id;
    public $name;
    
    public function exchangeArray($data) {
        $this->company_website_id = (!empty($data['company_website_id'])) ? (int)$data['company_website_id'] : null;
        $this->language_id = (!empty($data['language_id'])) ? (int)$data['language_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
    }

    public function validation($edition=false) {
        return true;
    }

}
