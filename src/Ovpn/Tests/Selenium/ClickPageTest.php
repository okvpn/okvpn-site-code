<?php

namespace Ovpn\Tests\Selenium;

use Ovpn\TestFramework\Selenium2TestCase;

class ClickPageTest extends Selenium2TestCase
{
    public function testLoginClick()
    {
        $this->url('');
        $this->ignorePageError();
        $this->byId('signin')->click();
        $this->waitToAjax();
        $this->byClassName('btn-green')->click();
    }
}
