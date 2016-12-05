<?php
namespace Website\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;

class WebsiteFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get("Zend\Db\Adapter\Adapter");
        $websiteTable = new TableGateway('company_website', $adapter);
        
        return $websiteTable;
    }

}

