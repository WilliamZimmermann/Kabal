<?php

namespace Website\Model;

class WebsiteModule {

    public $company_website_idWebsite;
    public $system_module_idModule;
    public $moduleName;
    public $moduleDescription;
    public $user_type;
    
    public function exchangeArray($data) {
        $this->company_website_idWebsite = (!empty($data['company_website_idWebsite'])) ? $data['company_website_idWebsite'] : null;
        $this->system_module_idModule = (!empty($data['system_module_idModule'])) ? $data['system_module_idModule'] : null;
        $this->moduleName = (!empty($data['moduleName'])) ? $data['moduleName'] : null;
        $this->moduleDescription = (!empty($data['moduleDescription'])) ? $data['moduleDescription'] : null;
        $this->user_type = (!empty($data['user_type'])) ? $data['user_type'] : null;
    }

    public function validation() {
       

        return true;
    }

}
