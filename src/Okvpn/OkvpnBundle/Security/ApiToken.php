<?php

namespace Okvpn\OkvpnBundle\Security;

use Okvpn\OkvpnBundle\Repository\UserRepository;

class ApiToken implements TokenInterface
{
    /** @var  UserRepository */
    protected $userRepository;

    protected $name = 'oath_token';
    
    /** @var  \Request */
    protected $request;
    
    public function __construct(UserRepository $userRepository, \Request $request)
    {
        $this->userRepository = $userRepository;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        $token = $this->getTokenParam();
        if (null !== $token) {
            $token = $this->userRepository->findUserByToken(hash('sha256', $token));
        }
        return $token === null ? null : $token->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function setToken(string $token)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken()
    {
    }

    /**
     * @return string|null
     */
    private function getTokenParam()
    {
        $tokenParam = $this->request->post($this->name);
        if (null === $tokenParam) {
            return $this->request->query($this->name);
        }
        return $tokenParam;
    }
}
