<?php

namespace Ovpn\Tests\Selenium;

use Ovpn\TestFramework\Selenium2TestCase;
use Ovpn\Tests\Selenium\Page\Login;

class ClickPageTest extends Selenium2TestCase
{

    public function testLoginClick()
    {
        $login = new Login($this);
        $login->login()
            ->setUsername('tsykun314@gmail.com')
            ->setPassword('php123456')
            ->submit();

        var_dump($this->url());
    }
}
