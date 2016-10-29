<?php

namespace Okvpn\Component\Logger;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{
    /**
     * @param $name
     * @return Logger
     */
    public static function create($name)
    {
        $logger = new Logger($name);
        $logger->pushHandler(self::getHandler());
        return $logger;
    }

    /**
     * @return HandlerInterface
     */
    protected static function getHandler()
    {
        return new StreamHandler(APPPATH . sprintf('logs/monolog/%s.log', date('Ymd')));
    }
}
