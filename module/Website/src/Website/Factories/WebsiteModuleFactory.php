<?php
namespace Website\Factories;

use Zend\ServiceManager\FactoryInterface;

class WebsiteModuleFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get("Website\Model\WebsiteModuleTable");
    }

}

