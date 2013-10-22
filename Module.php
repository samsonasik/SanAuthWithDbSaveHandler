<?php
//filename : SanAuthWithDbSaveHandler/Module.php
namespace SanAuthWithDbSaveHandler;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $em = $e->getApplication()->getEventManager();
        $em->attach('route', array($this, 'checkAuthenticated'));
    }

    public function isOpenRequest(MvcEvent $e)
    {
        if ($e->getRouteMatch()->getParam('controller') == 'SanAuthWithDbSaveHandler\Controller\AuthController') {
            return true;
        }

        return false;
    }

    public function checkAuthenticated(MvcEvent $e)
    {
        if (!$this->isOpenRequest($e)) {
            $sm = $e->getApplication()->getServiceManager();
            if (! $sm->get('AuthService')->getStorage()->getSessionManager()
                        ->getSaveHandler()->read($sm->get('AuthService')->getStorage()->getSessionId())) {
                $e->getRouteMatch()
                    ->setParam('controller', 'SanAuthWithDbSaveHandler\Controller\Auth')
                    ->setParam('action', 'index');
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
}
