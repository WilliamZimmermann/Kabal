<?php

namespace Website\Model;

use Zend\Db\TableGateway\TableGateway;
use Website\Model\Website;
use Application\Services\SystemLog;

class LanguageTable {

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
    public function fetchAll() {
        return $this->tableGateway->select();
    }

    /**
     * This function get a specific language registred in our data base
     * @param int $id
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getLanguage($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('idLanguage' => $id));
        
        $row = $rowset->current();
        if (!$row) {
            return false;
            //throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}
