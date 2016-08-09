<?php
namespace Model;

use Bitpay\UserInterface;
use Mailgun\Mailgun;
use Guzzle\Http\Exception\CurlException;
use Kohana;
use DB;
use Database;
use View;
use Request;


class Server
{
    const EASYRSA_CERT_EXPIRE = 10368000;

    private $_config;

    public function __construct()
    {
        $mode          = MODE;
        $this->_config = Kohana::$config->load('info')->$mode;
    }

    /**
     * возвращает список доступных vpn
     */
    public function getVpns()
    {
        $vpns = DB::query(\Database::SELECT,
            "SELECT T2.id, T2.name as title, 
                case when T2.free_places < coalesce(T1.cnt,0) then 0 else T2.free_places - coalesce(T1.cnt,0) end as free,
                T2.location as country, T2.icon as img, T2.speedtest
            from (
                select count(*) as cnt, id from (
                    select count(*) as cnt, vpn_id as id from vpn_user 
                    where active = true group by vpn_id, user_id) e1
                group by id
            ) as T1
            right join vpn_hosts T2
            on T1.id = T2.id 
            where T2.enable = true
            order by ordernum")
            ->execute()->as_array();
        return $vpns;
    }


    public function createClientConfig(Users $user, Host $host)
    {
        $client   = $host->getName() . '-' . \Text::random('hexdec', 8);

        $result = DB::query(Database::SELECT, "SELECT testVpnRegi(:id, :hostId) AS test")
            ->param(':id', $user->getId())
            ->param(':hostId', $host->getId())
            ->execute()->get('test');

        if ($result != 'ok') {
            return $result;
        }

        try {
            $ca = (new OpenSSL)->genConfig($host, $client);
            file_put_contents(APPPATH . "out/$client.ovpn", $ca);
        } catch (\Exception $e) {

            if (Kohana::$environment >= Kohana::TESTING) {
                return $e->getMessage();
            }
            return Kohana::message('user', 'someError');
        }

        $message = View::factory('mail/vpnActivate');
        $subject = Kohana::message('user', 'vpnActivate');

        $mailProvider = new Mailgun($this->_config->mailkey);
        $domain  = "okvpn.org";
        try {
             $mailProvider->sendMessage($domain, array(
                'from'    => "OkVPN <noreply@okvpn.org>",
                'to'      => $user->getEmail(),
                'subject' => $subject,
                'html'    => $message,
            ), array(
                'attachment' => array(APPPATH . "out/$client.ovpn"),
            ));

        } catch (CurlException $e) {
            return $e->getMessage();
        }

        (new VpnUser())
            ->setActive(true)
            ->setCallback('')
            ->setDateCreate(date('Y-m-d H:i:s'))
            ->setDateDelete(date('Y-m-d H:i:s', time() + self::EASYRSA_CERT_EXPIRE))
            ->setUser($user)
            ->setHost($host)
            ->setName($client)
            ->save();

        return false;
    }


    /**
     * логирует потребление трафика пользователями
     *
     */
    public function setTrafficMeters()
    {
        //TODO:: must be refactored in OK-09-01
        $ip  = $_SERVER['REMOTE_ADDR'];
        $cnt = DB::query(Database::SELECT,
            "SELECT id FROM vpn_hosts WHERE ip LIKE '$ip%'")->execute()->count();

        if ($cnt) {
            $post = Request::current()->post();
            if (array_key_exists('data', $post)) {

                $data = json_decode(base64_decode($post['data']), true);

                $name = DB::select(DB::expr('user_id as id'), 'name')
                    ->from('vpn_user')
                    ->where('name', 'IN', array_keys($data))
                    ->execute()
                    ->as_array();

                if (empty($name)) {
                    return false;
                }

                $map = [];
                foreach ($name as $item) {
                    $map[$item['name']] = $item['id'];
                }

                $sql = DB::insert('traffic', array('uid', 'count', 'date'));
                foreach ($data as $name => $count) {
                    if (!array_key_exists($name, $map)) {
                        continue;
                    }

                    $sql->values(array($map[$name], $count / 1048576, date('Y-m-d H:i:s')));
                }
                if ($sql->execute()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function setUserConnect($ip, $name, $type)
    {
        /** @var VpnUser $host */
        $host = (new VpnUser())
            ->where('name', '=', $name)
            ->find();
        
        if ($host === null ||
            $host->getHost()->getIp() != $ip || 
            !$host->getActive() ||
            (new UserManager())->allowUserConnect($host->getUser()) !== true
            ) {
            return false;
        }
        
        DB::query(Database::INSERT, 
            "INSERT into connection  (user_id, vpn_id, date, type)
                select user_id, vpn_id, now(), :type as type 
                from vpn_user where name = :name")
            ->param(':name', $name)
            ->param(':type', $type)
            ->execute();

        return true;
    }

    /**
     * возвращает список пользовательских vpn
     * 
     * @var $user 
     */
    public function getUserVpn(UsersIntrface $user)
    {
        $sql = DB::query(Database::SELECT,
            "SELECT T1.uid as id, T1.name, T2.name as host, T2.location, T2.icon
            FROM (
                SELECT id as uid, vpn_id as id, name
                FROM vpn_user
                WHERE user_id = :id AND active = true) T1
            LEFT JOIN (
                SELECT id, name, location, icon
                FROM vpn_hosts
                WHERE id IN (SELECT vpn_id as id
                    FROM vpn_user
                    WHERE user_id = :id)
            ) T2 ON T1.id = T2.id")
            ->param(':id', $user->getId());

        $return = $sql->execute()->as_array();

        return is_array($return) ? $return : false;
    }

    /**
     * callback для активации vpn
     *
     */
    public function setRegiVpn($token)
    {
        \DB::update('vpn_user')
            ->set(array(
                'date_create' => date('Y-m-d H:i:s'),
                'date_delete' => date('Y-m-d H:i:s', time() + 3860000),
                'active' => true,
            ))
            ->where('callback', '=', $token)
            ->execute();
    }

    public function getVpnInfo($id)
    {
        $sql = \DB::select('network', 'specifications_link')
            ->from('vps')->where('vpn_id','=', $id)
            ->execute()->as_array();
        return $sql;
    }

    /**
     * удаляет vpn по списку их id
     * @param array $list список id
     */
    public function deleteVpnByList($list, $user)
    {
        $list = implode(',', $list);
        \DB::query(\Database::DELETE, "select deleteSelectedVpn(:list, :id)")
            ->param(':list', $list)
            ->param(':id', $user->getId())
            ->execute();
    }

}
