<?php
namespace SanAuthWithDbSaveHandler\Storage;
use Zend\Authentication\AuthenticationService;

class IdentityManager implements IdentityManagerInterface
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function getAuthService()
    {
        return $this->authService;
    }

    public function login($identity, $credential)
    {
        $this->getAuthService()->getAdapter()
             ->setIdentity($identity)
             ->setCredential($credential);

        $result = $this->getAuthService()->authenticate();

        return $result;
    }

    public function logout()
    {
        $this->getAuthService()->getStorage()->clear();
    }

    public function hasIdentity()
    {
        $sessionId = $this->getAuthService()->getStorage()->getSessionId();

        return $this->getAuthService()->getStorage()
                    ->getSessionManager()
                    ->getSaveHandler()
                    ->read($sessionId);
    }

    public function storeIdentity(array $identity)
    {
        $this->getAuthService()->getStorage()
             ->write($identity);
    }
}
