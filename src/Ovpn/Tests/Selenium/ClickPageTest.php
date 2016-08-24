<?php

namespace Ovpn\Tests\Selenium;

use Ovpn\TestFramework\Selenium2TestCase;
use Ovpn\Tests\Selenium\Page\Login;

class ClickPageTest extends Selenium2TestCase
{
    public function testWaitToElementEnable()
    {
        $this->url('');
        $this->ignorePageError();
        $this->byId('signin')->click();
        $this->waitToAjax();
        $elem = $this->byClassName('btn-green');
        //$this->waitToElementEnable($elem);
        sleep(10);
        $elem->click();
    }

    /**
     * @depends testWaitToElementEnable
     */
    public function testLoginClick()
    {
        $login = new Login($this);
        $login->login()
            ->setUsername('tsykun314@gmail.com')
            ->setPassword('php123456')
            ->submit();
    }
}
