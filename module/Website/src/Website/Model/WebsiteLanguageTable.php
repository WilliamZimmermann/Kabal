<?php

namespace Website\Model;

use Zend\Db\TableGateway\TableGateway;
use Website\Model\Website;
use Application\Services\SystemLog;
use Zend\Db\Sql\Select;

class WebsiteLanguageTable {

    protected $tableGateway;
    protected $log;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->log = new SystemLog();
    }

    /**
     * This function returns all languages that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($idWebsite) {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array("company_website_id", "language_id"));
        $sqlSelect->join("language", "company_website_has_language.language_id=language.idLanguage", array("name"=>"name"));
        $sqlSelect->where(array("company_website_id"=>$idWebsite));;
        
        return $this->tableGateway->selectWith($sqlSelect);
    }
    
    public function saveLanguagePair(WebsiteLanguage $websiteLanguage){
        $data = [
          'company_website_id'=>$websiteLanguage->company_website_id,
            'language_id'=>$websiteLanguage->language_id
        ];
        if($this->tableGateway->insert($data)){
            return "WEBSITE018";
        }else{
            return "WEBSITE019";
        }
    }
    
    /**
     * This function delete a determined language pair for a website
     * @param int $languageId
     * @param int $websiteId
     */
    public function deleteLanguagePair($languageId=0, $websiteId=0){
        if($languageId!=0 && $websiteId!=0){
            $where = [
                'company_website_id'=>$websiteId,
                'language_id'=>$languageId
            ];
        }else if($languageId!=0){
            $where = [
                'language_id'=>$languageId
            ];
        }else if($websiteId!=0){
            $where = [
                'company_website_id'=>$websiteId
            ];
        }else{
            die("ERROR - FORBIDEN ACTION");
        }
        if($this->tableGateway->delete($where)){
            return "WEBSITE021";
        }else{
            return "WEBSITE020";
        }
    }
    
    public function pairExist(WebsiteLanguage $websiteLanguage){
        return $this->tableGateway->select(['company_website_id'=>$websiteLanguage->company_website_id, 'language_id'=>$websiteLanguage->language_id])->current();
    }
}
