<?php
namespace Website\Factories;

use Zend\ServiceManager\FactoryInterface;

class WebsiteUserFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        // TODO Auto-generated method stub
        
        return $serviceLocator->get('Website\Model\WebsiteUserTable');
        
    }

    
}

