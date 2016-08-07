<?php 
namespace App\Socket;

use App\Socket\Bind\Socket as AbsrSocket;
use Mailgun\Mailgun;
/**
*  
*/
class Socket extends AbsrSocket
{
    
    public $_key = "key-8cX7ieq4Zdl";

    public $_nonce;

    public $_esayrsa = "bash /var/www/src/okvpn.sh";

    public $ovpnFile = "/var/www/crt/";

    function __construct($host = '') 
    {
        $this->_nonce = time();
        if ($host == '') {
            parent::__construct();
        } else {
            parent::__construct($host);
        }
    }

    /**
     *
     *
     */
    public function accept($socket,&$data = '')
    {
        fwrite($socket, "Oxi");
        $time = date('Y-m-d H:i:s');
        $key  = $this->_key;
        $content = base64_decode(fgets($socket));
        echo "Connect:[$time] \n";
        //
        if ($content !== false) {
            $obj   = json_decode($content);
            
            if (isset($obj->nonce) && $obj->nonce > $this->_nonce) {

                $this->_nonce = $obj->nonce;
                $data = $obj->data;
                if (hash_hmac('sha256',$data,"$key$this->_nonce") == $obj->hash) {
                    $data = unserialize($obj->data);
                    $data['sign'] = $key;       
                    echo "Accept\n";
                    return true;
                } else {
                    
                    echo "Access denied: error hash is not matches\n";   
                }
            } else{

                echo "Access denied: error nonce is empty\n";
            }
        }
        echo "\n";

        return false;
    }

    public function main($data){}

    public function child($data)
    {
        if (isset($data['command'], $data['client'])){
            $client  = $data['client']; 
            switch ($data['command']) {

                case 'new':
                    exec("$this->_esayrsa new $client");

                    if (isset($data['email'], $data['key']) 
                        && $this->sendMail($data['email'], $client, $data['key'])) {
                        
                        $ch = curl_init($data['callback']);
                        curl_setopt_array($ch, array(
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_USERAGENT => "okvpn.org callback agent v 0.2.1",
                            CURLOPT_HTTPHEADER => array('sign' 
                                => hash_hmac('sha256',$data['callback'], $data['sign'])),
                            ));
                        var_dump(curl_exec($ch));
                    }
                    break;

                case 'remove': 
                    if (file_exists("$this->ovpnFile$client.ovpn")) {
                        exec("$this->_esayrsa remove $client");
                        unlink("$this->_esayrsa/$client");

                        $ch = curl_init($data['callback']);
                        curl_setopt_array($ch, array(
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_USERAGENT => "okvpn.org callback agent v 0.2.1",
                            CURLOPT_HTTPHEADER => array('sign' 
                                => hash_hmac('sha256', $data['callback'], $data['sign'])),
                            ));
                        var_dump(curl_exec($ch));

                    }

                    break;

                case 'resend':
                    if (isset($data['email'], $data['key'])) {
                        $this->sendMail($data['email'], $client, $data['key']);
                    }
                    break;
                default:
                    break;
            }
            

            
        }
    }

    public function sendMail($to,$client,$mgKey) 
    {
        if (file_exists("$this->ovpnFile$client.ovpn")) {

            $mgClient = new Mailgun($mgKey);

            $domain  = "okvpn.org";
            $message = "Здравствуйте! \n\n".
                "Ваш VPN доступ успешно активирован.".
                "Инструкцию по подключению вы можете посмотреть на нашем сайте в разделе \"Руководство\".".
                "Если вы хотите прикратить использование VPN, вы можете его удалить в разделе \"Настройки\",".
                "и деньги за пользование сервисом больше не будут списываться с вашего счета.".
                "Если у вас возникли проблемы, не стесняйтесь обращаться в тех.поддержку.".
                "Это письмо сгенерировано автоматически. На него не нужно отвечать.\n\n".
                "С уважением, команда OkVPN.org";

            $result = $mgClient->sendMessage($domain, array(
                'from'    => 'OkVPN <noreply@okvpn.org>',
                'to'      => $to,
                'subject' => 'Активация VPN доступа',
                'text'    => $message
            ), array(
                'attachment' => array("$this->ovpnFile$client.ovpn")
            ));
            var_dump($result);
            return true;
        }

        return false;
    }

}

 ?>