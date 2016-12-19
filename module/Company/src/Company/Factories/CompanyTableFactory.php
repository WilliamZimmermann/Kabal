<?php
namespace Company\Factories;

use Zend\ServiceManager\FactoryInterface;

class CompanyTableFactory implements FactoryInterface{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Company\Model\CompanyTable');
        
    }

    
}