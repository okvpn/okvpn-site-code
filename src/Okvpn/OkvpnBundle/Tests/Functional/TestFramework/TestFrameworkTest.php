<?php

namespace Okvpn\OkvpnBundle\Tests\Functional;

use Okvpn\KohanaProxy\Database;
use Okvpn\OkvpnBundle\TestFramework\WebTestCase;
use Okvpn\OkvpnBundle\Entity\Users;

/**
 * @dbIsolation
 * @keepCookie
 */
class TestFrameworkTest extends WebTestCase
{
    const USER = 'test1.ci@okvpn.org';

    protected static $userId;

    /**
     * @var Database
     */
    protected $databaseManager;

    public function setUp()
    {
        $this->databaseManager = Database::instance();
        parent::setUp();
    }

    public function testPrepare()
    {
        $user = $this->getUser();
        $this->assertNotSame($user->getEmail(), '123456');
    }

    /**
     * @depends testPrepare
     */
    public function testUpdateEntity()
    {
        $user =$this->getUser();
        $user->setEmail('123456');
        $user->save();

        $this->assertSame($user->getEmail(), '123456');
    }

    /**
     * @depends testUpdateEntity
     */
    public function testEnableUpdateEntity()
    {
        $user = $this->getUser();
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
        $user = $this->getUser();

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

    /**
     * @return Users
     */
    protected function getUser()
    {
        if (null !== self::$userId) {
            return new Users(self::$userId);
        }

        $user = new Users();
        $user->where('email', '=', self::USER)->find();
        $userId = $user->getId();
        if (null === $userId) {
            $this->markTestIncomplete('Incomplete tests, run seeds required');
        } else {
            self::$userId = $userId;
        }

        return $user;
    }
}
