<?php
namespace App\Socket\Bind;

interface SocketInterface
{
    /**
     * принимает рессурс для чтение и записи данных 
     * в поток 
     * @param resurce $socket
     * @param mixed   $data
     * @return bool 
     */
    public function accept($socket,&$data = '');

    /**
     * обрабочик для дочернего процесса
     * @param mixed $data
     * 
     */
    public function child($data);

    /**
     * обрабочик для главного процесса
     * @param mixed $data
     */
    public function main($data);

    /**
     * 
     *
     */
    public function run();
}