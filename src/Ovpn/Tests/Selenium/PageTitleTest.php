<?php
namespace Ovpn\Tests\Selenium;

use Ovpn\TestFramework\Selenium2TestCase;

class PageTitleTest extends Selenium2TestCase
{

    public function testIndexTitle()
    {
        $this->url('');
        $this->assertRegExp('/VPN.+\$1\x20/', $this->title());
    }

    public function testFaqTitle()
    {
        $this->url('faq');
        $this->assertRegExp('/^OkVPN - FAQ$/', $this->title());
    }

    public function testGuideTitle()
    {
        $this->url('guide');
        $this->assertRegExp('/^OkVPN/', $this->title());
    }
}
