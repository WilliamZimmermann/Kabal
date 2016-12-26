<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class RegionController extends AbstractActionController
{
    public $countryTable;
    public $zoneTable;
    public $cityTable;
    
    public function zonesAction(){
        $request = $this->getRequest();
        $dados = $request->getContent();
        $id = substr($dados, 3);
        $zones = $this->getZoneTable()->fetchAllByCountry($id);
        $zonesR = array();
        foreach ($zones as $zone){
            $zonesR[] = array("name"=>$zone->name, "id"=>$zone->id);
        }
        die(json_encode($zonesR));
    }
    
    public function citiesAction(){
        $request = $this->getRequest();
        $dados = $request->getContent();
        $id = substr($dados, 3);
        $cities = $this->getCityTable()->fetchAllByZone($id);
        $citiesR = array();
        foreach ($cities as $city){
            $citiesR[] = array("name"=>$city->name, "id"=>$city->id);
        }
        die(json_encode($citiesR));
    }
    
    
    public function getCountryTable(){
        if(!$this->countryTable){
            $sm = $this->getServiceLocator();
            $this->countryTable = $sm->get('Application\Model\CountryTable');
        }
        return $this->countryTable;
    }
    
    public function getZoneTable(){
        if(!$this->zoneTable){
            $sm = $this->getServiceLocator();
            $this->zoneTable = $sm->get('Application\Model\ZoneTable');
        }
        return $this->zoneTable;
    }
    
    public function getCityTable(){
        if(!$this->cityTable){
            $sm = $this->getServiceLocator();
            $this->cityTable = $sm->get('Application\Model\CityTable');
        }
        return $this->cityTable;
    }
}

