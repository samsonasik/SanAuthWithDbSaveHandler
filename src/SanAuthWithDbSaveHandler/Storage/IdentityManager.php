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

    public function login($identity, $credential)
    {
        $this->authService->getAdapter()
             ->setIdentity($identity)
             ->setCredential($credential);

        $result = $this->authService->authenticate();

        if (!$result->isValid()) {
            return false;
        }

        return $this->authService->getAdapter()->getResultRowObject();
    }

    public function logout()
    {
        $this->authService->getStorage()->clear();
    }

    public function hasIdentity()
    {
        $sessionId = $this->authService->getStorage()->getSessionId();

        return $this->authService->getStorage()
                    ->getSessionManager()
                    ->getSaveHandler()
                    ->read($sessionId);
    }

    public function storeIdentity(array $identity)
    {
        $this->authService->getStorage()
             ->write($identity);
    }
}