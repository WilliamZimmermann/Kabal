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
     * @param int $id - null
     * @param string $code - null
     * @throws \Exception
     * @return ArrayObject|NULL
     */
    public function getLanguage($id=null, $code=null) {
        if($id!=null){
            $id = (int) $id;
            $where = array("idLanguage"=>$id);
        }else{
            $code = (string)strip_tags($code);
            $where = array("code"=>$code);
        }
        $rowset = $this->tableGateway->select($where);
        
        $row = $rowset->current();
        if (!$row) {
            return false;
            //throw new \Exception("Could not find row $id");
        }
        return $row;
    }
}
