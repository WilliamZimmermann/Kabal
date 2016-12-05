<?php

namespace Website\Model;

class Language {

    public $idLanguage;
    public $name;
    public $code;
    
    public function exchangeArray($data) {
        $this->idLanguage = (!empty($data['idLanguage'])) ? $data['idLanguage'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
    }

    public function validation($edition=false) {
        return true;
    }

}
