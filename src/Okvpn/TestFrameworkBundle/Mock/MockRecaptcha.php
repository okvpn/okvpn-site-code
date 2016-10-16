<?php

namespace Okvpn\TestFrameworkBundle\Mock;

use Okvpn\OkvpnBundle\Tools\Recaptcha as BaseRecaptcha;

class MockRecaptcha extends BaseRecaptcha
{
    public static function check($gCaptchaResponse)
    {
        return $gCaptchaResponse;
    }
}
