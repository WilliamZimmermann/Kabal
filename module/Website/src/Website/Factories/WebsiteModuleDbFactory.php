<?php
namespace Website\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;

class WebsiteModuleDbFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get("Zend\Db\Adapter\Adapter");
        $moduleTable = new TableGateway('company_website_has_system_module', $adapter);
        
        return $moduleTable;
    }

}

