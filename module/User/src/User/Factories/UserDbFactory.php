<?php
namespace User\Factories;

use Zend\ServiceManager\FactoryInterface;

class UserDbFactory implements FactoryInterface
{    
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('User\Model\UserTable');
        
        //return $userTable;
    }

}

