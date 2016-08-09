<?php 
namespace App\Socket\Bind;

/**
* cd c:\xampp\htdocs\project\tf\
*/
abstract class Socket implements SocketInterface
{
    
    public $_socket;

    /**
     * создает серверный сокет
     */
    function __construct($host = "tcp://0.0.0.0:9141")
    {   
        set_time_limit(0);
        if (function_exists("pcntl_signal")) {
            pcntl_signal(SIGCHLD,SIG_IGN);
        } else {
            echo "Warming: call to undefined function pcntl_signal\n";
        }
        //
        $socket = stream_socket_server($host, $errno, $errstr);

        if (!$socket) {
            die("$errstr ($errno)\n");
        }
        $this->_socket = $socket;
    }

    public function run()
    {
        $socket = $this->_socket;
        while (true) {

            $read   = $write = array();
            $read[] = $socket;

            $except = null;
            if (!stream_select($read, $write, $except, null)) {//ожидаем сокеты доступные для чтения (без таймаута)
                
                break;
            }

            if (($connect = stream_socket_accept($socket, -1))) {
                $data = null;
                if ($this->accept($connect,$data)) {
                    
                    if (function_exists("pcntl_fork")) {
                        $pid = pcntl_fork();
                    } else {
                        $pid = 1;
                    }

                    if ($pid == -1) {
                        exit;
                    }

                    if ($pid == 0) {
                        $this->child($data);
                        exit;

                    } elseif ($pid) {

                        $this->main($data);
                    }

                }
            }  
        }
    }


}



 ?>