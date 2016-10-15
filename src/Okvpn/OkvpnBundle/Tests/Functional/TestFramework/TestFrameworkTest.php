<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\OkvpnBundle\TestFramework\WebTestCase;
use Okvpn\OkvpnBundle\Entity\Users;

/**
 * @dbIsolation
 * @keepCookie
 */
class TestFrameworkTest extends WebTestCase
{

    /**
     * @var \Database
     */
    protected $databaseManager;

    public function setUp()
    {
        $this->databaseManager = \Database::instance();
        parent::setUp();
    }

    public function testPrepare()
    {
        $user = new Users(1);
        $this->assertNotSame($user->getEmail(), '123456');
    }

    /**
     * @depends testPrepare
     */
    public function testUpdateEntity()
    {
        $user = new Users(1);
        $user->setEmail('123456');
        $user->save();

        $this->assertSame($user->getEmail(), '123456');
    }

    /**
     * @depends testUpdateEntity
     */
    public function testEnableUpdateEntity()
    {
        $user = new Users(1);
        $this->assertSame($user->getEmail(), '123456');
    }

    /**
     * @depends testUpdateEntity
     */
    public function testTransaction()
    {
        $this->assertSame($this->databaseManager->getTransactionNestingLevel(), 1);
    }

    /**
     * @depends testTransaction
     */
    public function testRollbackTransaction()
    {
        $this->databaseManager->rollback();
        $user = new Users(1);

        $this->assertNotSame($user->getEmail(), '123456');
        $this->assertSame($this->databaseManager->getTransactionNestingLevel(), 0);
    }


    public function testLoginClient()
    {
        $this->loginClient();

        $response = $this->request('GET', '/profile');
        $this->assertStatusCode($response, 200);
        $this->assertRedirectResponse($response, null);
    }

    /**
     * @depends testLoginClient
     */
    public function testClearCookieAndSession()
    {
        $this->clearSession();
        $this->clearCookie();

        $response = $this->request('GET', '/profile');
        $this->assertStatusCode($response, 200);
        $this->assertRedirectResponse($response, '/');
    }
}
