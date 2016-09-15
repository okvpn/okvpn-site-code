<?php

use Ovpn\TestFramework\WebTestCase;

/**
 * @dbIsolation
 */
class FirstTest extends WebTestCase
{

    public function testAction()
    {
        $this->request('GET', '/');
    }

    public function testUser()
    {
        $usr = new \Ovpn\Entity\Users(1);
        echo $usr->getEmail();
    }
}