<?php

namespace Okvpn\TestFrameworkBundle\Mock;

use Okvpn\OkvpnBundle\Tools\Recaptcha as BaseRecaptcha;

class MockRecaptcha extends BaseRecaptcha
{
    use MockTrait;

    /**
     * {@inheritdoc}
     */
    public function check($gCaptchaResponse)
    {
        if (self::isEnableParentMethod('check')) {
            parent::check($gCaptchaResponse);
        }
        
        if (null === self::$mockObject) {
            return true;
        }
        
        return self::$mockObject->check($gCaptchaResponse);
    }
}
