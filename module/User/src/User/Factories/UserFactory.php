<?php
namespace User\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;

class UserFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $adapter = $serviceLocator->get("Zend\Db\Adapter\Adapter");
        $userTable = new TableGateway('company_user', $adapter);
        
        return $userTable;
    }

}

