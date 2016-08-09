<?php defined('SYSPATH') OR die('No direct access allowed.');
use Mailgun\Mailgun;

/**
 * Всякая всячина, необходимая для работы приложения 
 * @author nobody 
 * @version 1.0.1
 */
class Kohana_Okvpn {
    
    protected static $variable = "tb_variable";

    protected static $config;

    /**
     * возвращает значение переменной сохраненой в бд
     * например это может быть текущее значение котировки BTC/USD
     * @param   имя переменной
     * @return  mixed, если переменная не была обявлена рание то null 
     */
    public static function get_var($name) 
    {
        $data = DB::select('value')
            ->from(Okvpn::$variable)
            ->where('var_name','=',$name)
            ->execute()
            ->get('value');

        if ($data === null) {

            return null;
        } else {

            return unserialize($data);
        }

    }  

    public static function set_var($name, $value) 
    {
        if (
            DB::select()
                ->from(Okvpn::$variable)
                ->where('var_name','=',$name)
                ->execute()
                ->count()
            ) {

            DB::update(Okvpn::$variable)
                ->set(array('value' => serialize($value)))
                ->execute();

        } else {

            DB::insert(Okvpn::$variable,array('var_name','value'))
                ->values(array($name,serialize($value)))
                ->execute();
        }
    }

    public static function proxyScan()
    {
        $time = time();
        while(time() - $time < 48) {
            Model::factory('Proxy')->scanFirstProxy();
        }
    }

    public static function arrayToXML($arr)
    {
        $closing = function( $data, &$xml_data ) use (&$closing){
            foreach( $data as $key => $value ) {
                if( is_array($value) ) {
                    if( is_numeric($key) ){
                        $key = 'item'.$key;
                    }
                    $subnode = $xml_data->addChild($key);
                    $closing($value, $subnode);
                } else {
                    $xml_data->addChild("$key", htmlspecialchars("$value"));
                }
             }
        };
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $closing($arr, $xml_data);
        return $xml_data;
    }

    public static function getP0fData($ip = null)
    {
        if ($ip === null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $config = Kohana::$config->load('info');
        $socket = $config->pof_path;

        if ($socket = @fsockopen("unix://$socket")) {
            
            $query = pack('Lha*@24', 0x50304601, 4, inet_pton($ip));
            fwrite($socket, $query);
            $resp = fread($socket, 233);
            fclose($socket);

            $resp = unpack( 'Lmagic_number/Lstatus/Lfirst_seen/Llast_seen'.
                            '/Ltotal_conn/Luptime_min/Lup_mod_days/Llast_nat'.
                            '/Llast_chg/cdistance/Cbad_sw/Cos_match_q'.
                            '/a32os_name/a32os_flavor/a32http_name/a32http_flavor/Clin1/Clin2'.
                            '/a32link_type/a32language', $resp);
            if (!is_array($resp)) {
                return false;
            }

            $resp = preg_replace('/\x0/','',$resp);
            switch ($resp['os_match_q']) {
                case 0:
                    $os ="Fingerprint and OS match. ";
                    break;
                case 1:
                    $os ="Fingerprint and OS not match. Probably TTL or DF difference. ";
                    break;
                case 2:
                    $os ="OS match for a generic signature. ";
                    break;
                default:
                    $os ="OS match for a generic signature. Probably TTL or DF difference. ";
                    break;
            }

            switch ($resp['bad_sw']) {
                case 0:
                    $ua ="Fingerprint and User-agent match. ";
                    break;
                case 1:
                    $ua ="Fingerprint and User-agent not match. Possibly due to proxying. ";
                    break;
                case 2:
                    $ua ="Fingerprint and User-agent are outright mismatch.The User-agent is fake. ";
                    break;
                default:
                    $ua ="Fingerprint and User-agent are outright mismatch.The User-agent is fake. ";
                    break;
            }
            return array(
                'First seen' => date('Y-m-d H:i:s',$resp['first_seen'])."UTC",
                'Last seen' => date('Y-m-d H:i:s',$resp['last_seen'])."UTC",
                'IP' => $ip,
                'Total connection' => $resp['total_conn'],
                'Distance' => $resp['distance'],
                'Network' => $resp['link_type'],
                'Detected OS' => $resp['os_name']." ".$resp['os_flavor'],
                'Devices' => $resp['http_name']." ".$resp['http_flavor'],
                'MTU' => 1280 + $resp['lin2'],
                'language' => $resp['language'],
                'Conclusion' => "$os $ua"
                );
        }
        return false;
    }

    public static function getInstace()
    {
        $mode = MODE;
        Okvpn::$config = Kohana::$config->load('info')->$mode;
    }

    public static function blockUser()
    {
        require_once  Kohana::find_file('vendor', 'autoload');
        Okvpn::getInstace();

        $user = Model::factory('User');
        foreach ($user->controlUser() as $usr) {
            $email = $usr['email'];
            $message = View::factory('mail/'.$usr['cause']);
            $subject = Kohana::message('user', $usr['cause']);

            $mailgun = new Mailgun(Okvpn::$config->mailkey);
            $domain = "okvpn.org";
            try {
                $result = $mailgun->sendMessage($domain, array(
                    'from'    => 'okvpn <noreply@okvpn.org>',
                    'to'      => "<$email>",
                    'subject' => $subject,
                    'html'    => $message
                ));            

            } catch (Guzzle\Http\Exception\CurlException $e) {
                 
            }
        }

        if (isset($e)) {
            throw $e;
        }
    }
} 
