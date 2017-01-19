<?php
namespace Application\Factories;

use Zend\ServiceManager\FactoryInterface;

class CityFactory implements FactoryInterface{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('Application\Model\CityTable'); 
    }

    
}