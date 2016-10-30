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
        $dir = APPPATH . 'logs/monolog';
        return new StreamHandler(sprintf('%s/%s/%s.log', $dir, date('Ym'), date('Ymd')));
    }
}
