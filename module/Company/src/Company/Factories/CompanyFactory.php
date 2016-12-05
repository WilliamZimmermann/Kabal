<?php
namespace Company\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;

class CompanyFactory implements FactoryInterface{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        // TODO Auto-generated method stub
        $adapter = $serviceLocator->get("Zend\Db\Adapter\Adapter");
        $companyTable = new TableGateway('company', $adapter);
        
        return $companyTable;
    }

    
}