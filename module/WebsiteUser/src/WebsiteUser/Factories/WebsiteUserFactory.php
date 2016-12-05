<?php
namespace WebsiteUser\Factories;

use Zend\ServiceManager\FactoryInterface;

class WebsiteUserFactory implements FactoryInterface
{    
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('WebsiteUser\Model\WebsiteUserTable');
        
        //return $userTable;
    }

}

