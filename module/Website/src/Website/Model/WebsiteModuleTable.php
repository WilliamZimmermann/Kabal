<?php

namespace Website\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Services\SystemLog;

class WebsiteModuleTable {

    protected $tableGateway;
    protected $log;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->log = new SystemLog();
    }

    /**
     * This function returns all websites that are in our records
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAllByWebsite($idWebsite) {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('company_website_idWebsite', 'system_module_idModule'));
        $sqlSelect->join('system_module', 'system_module.idModule = company_website_has_system_module.system_module_idModule', array("moduleName"=>"name", "moduleDescription"=>"description"), 'left');
        $sqlSelect->where(array('company_website_idWebsite'=>$idWebsite));
        $resultSet = $this->tableGateway->selectWith($sqlSelect);
        return $resultSet;
    }

}
