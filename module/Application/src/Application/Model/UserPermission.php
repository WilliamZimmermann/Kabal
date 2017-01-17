<?php
namespace Application\Model;

class UserPermission
{
    public $company_user_idUser;
    public $website_module_idWebsite;
    public $website_module_idModule;
    public $insertP;
    public $editP;
    public $deleteP;
    
    public function exchangeArray($data){
        $this->company_user_idUser = (!empty($data['company_user_idUser'])) ? $data['company_user_idUser'] : null;
        $this->website_module_idWebsite = (!empty($data['website_module_idWebsite'])) ? $data['website_module_idWebsite'] : null;
        $this->website_module_idModule = (!empty($data['website_module_idModule'])) ? $data['website_module_idModule'] : null;
        $this->insertP = (!empty($data['insertP'])) ? $data['insertP'] : null;
        $this->editP = (!empty($data['editP'])) ? $data['editP'] : null;
        $this->deleteP = (!empty($data['deleteP'])) ? $data['deleteP'] : null;
    }
}

