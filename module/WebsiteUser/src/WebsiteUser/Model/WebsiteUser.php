<?php
namespace WebsiteUser\Model;

class WebsiteUser
{
    public $company_user_idUser;
    public $userName;
    public $company_website_idWebsite;
    public $websiteName;
    
    public function exchangeArray($data){
        $this->company_user_idUser = (!empty($data['company_user_idUser'])) ? $data['company_user_idUser'] : null;
        $this->userName = (!empty($data['userName'])) ? $data['userName'] : null;
        $this->company_website_idWebsite = (!empty($data['company_website_idWebsite'])) ? $data['company_website_idWebsite'] : null;
        $this->websiteName = (!empty($data['websiteName'])) ? $data['websiteName'] : null;
    }
    
    public function validation(){
        return true;
    }
}

