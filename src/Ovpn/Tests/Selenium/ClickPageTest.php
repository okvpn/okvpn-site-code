<?php

namespace Ovpn\Tests\Selenium;

use Ovpn\TestFramework\Selenium2TestCase;
use Ovpn\Tests\Selenium\Page\HostInfoJson;
use Ovpn\Tests\Selenium\Page\ProfileViewList;
use Ovpn\Tests\Selenium\Page\Settings;

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
