<?php

namespace Okvpn\Bridge\Kohana\Boot;

use Okvpn\Bridge\Kohana\Kernel\AbstractBundle;
use Okvpn\Bridge\Kohana\Kernel\CumulativeResourceManager;
use Okvpn\Bridge\Kohana\Kernel\Kernel;
use Okvpn\KohanaProxy\I18n;
use Okvpn\KohanaProxy\HTTP;
use Okvpn\KohanaProxy\Kohana;
use Okvpn\KohanaProxy\Cookie;

// @codingStandardsIgnoreStart
abstract class AbstractBoot
{

    public function boot($envelopment)
    {
        $this->loadKohanaKernel($envelopment);
        $this->setDefaultTimeZone();
        $this->setLocation();

        $bundles = $this->getBundles($envelopment);
        
        
        $this->loadKohanaModules($envelopment);
        //todo: change it in 2.2
        $this->loadRouter($envelopment);

        Kernel::registrationBundles($bundles);

        CumulativeResourceManager::getInstance()
            ->setContainer(
                Kernel::getContainer()
            )
            ->setBundles(
                Kernel::getBundles()
            );
    }

    /**
     * Set the default locale.
     *
     * @link http://kohanaframework.org/guide/using.configuration
     * @link http://www.php.net/manual/function.setlocale
     */
    protected function setLocation()
    {
        setlocale(LC_ALL, 'en_US.utf-8');
    }

    /**
     * Set the default language
     */
    protected function setLanguage()
    {
        I18n::lang('ru');
    }

    /**
     * Set the default time zone.
     *
     * @link http://kohanaframework.org/guide/using.configuration
     * @link http://www.php.net/manual/timezones
     */
    protected function setDefaultTimeZone()
    {
        date_default_timezone_set('Europe/Minsk');
    }
    
    protected function loadKohanaKernel($envelopment)
    {
        require_once SYSPATH . 'classes/Kohana/Core' . EXT;

        if (is_file(APPPATH . 'classes/Kohana' . EXT)) {
            // Application extends the core
            require_once APPPATH . 'classes/Kohana' . EXT;
        } else {
            // Load empty core extension
            require_once SYSPATH . 'classes/Kohana' . EXT;
        }

        spl_autoload_register(array('Kohana', 'auto_load'));
        $this->setLanguage();

        /**
         * Enable the Kohana auto-loader for unserialization.
         *
         * @link http://www.php.net/manual/function.spl-autoload-call
         * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
         */
        ini_set('unserialize_callback_func', 'spl_autoload_call');

        /**
         * Set the mb_substitute_character to "none"
         *
         * @link http://www.php.net/manual/function.mb-substitute-character.php
         */
        mb_substitute_character('none');


        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            // Replace the default protocol.
            HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
        }

        Kohana::init(['base_url' => '/']);

        if ($envelopment == 'dev') {
            Kohana::$environment = Kohana::DEVELOPMENT;
        } else {
            Kohana::$environment = Kohana::PRODUCTION;
        }

        Kohana::$log->attach(new \Log_File(APPPATH . 'logs'));

        /**
         * Attach a file reader to config. Multiple readers are supported.
         */
        Kohana::$config->attach(new \Config_File);
        
        /**
         * Cookie Salt
         * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
         *
         * If you have not defined a cookie salt in your Cookie class then
         * uncomment the line below and define a preferrably long salt.
         */
        Cookie::$salt = 'csj1QsfhAsnAafrSDQzLDa';

        Cookie::$expiration = 3141596;
    }

    /**
     * @return AbstractBundle[]
     * 
     * @param string $envelopment
     */
    abstract protected function getBundles($envelopment);

    /**
     * Activate kohana rouging role
     *
     * @param string $envelopment
     */
    abstract protected function loadRouter($envelopment);

    /**
     * @param string $envelopment
     */
    abstract protected function loadKohanaModules($envelopment);
}
// @codingStandardsIgnoreEnd
