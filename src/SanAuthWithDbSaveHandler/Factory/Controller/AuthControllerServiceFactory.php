<?php
//filename : module/SanAuthWithDbSaveHandler/src/SanAuthWithDbSaveHandler/Factory/Controller/AuthControllerServiceFactory.php
namespace SanAuthWithDbSaveHandler\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SanAuthWithDbSaveHandler\Controller\AuthController;

class AuthControllerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authService = $serviceLocator->getServiceLocator()->get('AuthService');
        $controller = new AuthController($authService);
        
        return $controller;
    }
}