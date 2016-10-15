<?php

namespace Okvpn\OkvpnBundle\Tests\Selenium;

use Okvpn\OkvpnBundle\TestFramework\Selenium2TestCase;
use Okvpn\OkvpnBundle\Tests\Selenium\Page\ProfileViewList;
use Okvpn\OkvpnBundle\Tests\Selenium\Page\Settings;

class ClickPageTest extends Selenium2TestCase
{

    public function testLogin()
    {
        $this->login();
    }

    /**
     * @depends testLogin
     */
    public function testSettings()
    {
        $login = $this->login();
        /** @var Settings $login */
        $page = $login->openSettings();
        $this->assertTrue($page->checkCurrentUrl());
    }

    /**
     * @depends testLogin
     */
    public function testCreate()
    {
        $this->login();
        $page = new ProfileViewList($this);
        $this->assertTrue($page->checkCurrentUrl());
    }
}
