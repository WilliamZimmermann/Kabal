<?php
namespace ImagesDatabase\Factories;

use Zend\ServiceManager\FactoryInterface;

class ModuleImageFactory implements FactoryInterface{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('ImagesDatabase\Model\ModuleImageTable');
        
    }

    
}