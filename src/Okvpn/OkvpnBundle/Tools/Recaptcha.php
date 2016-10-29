<?php

namespace Okvpn\OkvpnBundle\Tools;

use Okvpn\OkvpnBundle\Core\Config;

class Recaptcha
{
    const MAX_LAZY_CHECK = 3;
    
    /** @var  Config */
    private $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $gCaptchaResponse
     * @return bool
     * @throws \Exception
     */
    public function check($gCaptchaResponse)
    {
        if (! $this->config->get('captcha:check')) {
            return true;
        }
        
        $session = \Session::instance();

        if ($session->get('captcha') == true &&
            $session->get('captchaCount') < self::MAX_LAZY_CHECK
        ) {
            $session->set('captchaCount', (int) $session->get('captchaCount') + 1);
            return true;
        }

        $ch = curl_init($this->config->get('captcha:api'));

        $form = [
            'secret'   => $this->config->get('captcha:secret'),
            'response' => $gCaptchaResponse,
        ];

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => http_build_query($form),
        ]);
        $json = curl_exec($ch);
        $json = json_decode($json);

        if (isset($json->success) && $json->success) {
            $session->set('captcha', true);
            $session->set('captchaCount', 0);
            return true;
        }
        return false;
    }
}
