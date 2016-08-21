<?php

namespace Ovpn\Tools;

use Ovpn\Core\Config;

class Recaptcha
{

    const MAX_LAZY_CHECK = 3;

    /**
     * @param $gCaptchaResponse
     * @return bool
     * @throws \Exception
     */
    public static function check($gCaptchaResponse)
    {
        $session = \Session::instance();
        $config = new Config();

        if ($session->get('captcha') == true &&
            $session->get('captchaCount') < self::MAX_LAZY_CHECK) {

            $session->set('captchaCount', (int) $session->get('captchaCount') + 1);
            return true;
        }

        $ch = curl_init($config->get('captcha:api'));

        $form = [
            'secret'   => $config->get('captcha:secret'),
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