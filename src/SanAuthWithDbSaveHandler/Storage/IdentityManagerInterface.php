<?php
namespace SanAuthWithDbSaveHandler\Storage;

interface IdentityManagerInterface
{
    public function login($identity, $credential);
    public function logout();
    public function hasIdentity();
    public function storeIdentity(array $identity);
    public function getAuthService();
}
