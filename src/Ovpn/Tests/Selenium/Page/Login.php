<?php

namespace Ovpn\Tests\Selenium\Page;

use Ovpn\TestFramework\AbstractPage;

class Login extends AbstractPage
{
    const URL = '';

    public function login()
    {
        $this->test->byId('signin')->click();

        $form = $this->test->byClassName('btn-green');
        $this->test->waitToElementEnable($form);
        return $this;
    }
    
    public function setUsername($username)
    {
        $userInput = $this->test->byId('email');
        $this->test->waitToElementEnable($userInput);
        $userInput->clear();
        $userInput->value($username);
        return $this;
    }
    
    public function setPassword($password)
    {
        $passwordInput = $this->test->byId('password');
        $this->test->waitToElementEnable($passwordInput);
        $passwordInput->clear();
        $passwordInput->value($password);
        return $this;
    }
    
    public function submit()
    {
        $this->test->byClassName('btn-green')->click();
        $this->test->ignorePageError();
        $this->test->waitToAjax();
    }
}