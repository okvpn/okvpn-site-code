<?php defined('SYSPATH') or die('No direct script access.');

class Model_Server extends Model
{

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
        $vpns = DB::query(Database::SELECT,
            "SELECT T2.id, T2.name as title, (T2.free_places-COALESCE(T1.cnt,0)) as free,
            T2.location as country, T2.icon as img, T2.speedtest
            FROM (
                SELECT count(id) as cnt, id FROM (
                    SELECT MAX(vpn_id) as id  FROM vpn_user where active = true GROUP BY user_id
                ) as E1
                GROUP BY id
            ) as T1
            RIGHT JOIN (
                SELECT * FROM vpn_hosts where enable = true
            ) as T2 ON T1.id = T2.id order by ordernum")
            ->execute()->as_array();
        return $vpns;
    }

    /**
     * создает vpn
     *
     */
    public function vpnRegi($user, $hostId)
    {
        if ($user instanceof Model_User) {
            $user->instance();
            $client   = Text::random('hexdec', 8);
            $callback = Text::random('alnum', 12);

            $result = DB::query(Database::SELECT, "SELECT testVpnRegi(:id, :hostId) AS test")
                ->param(':id', $user->getId())
                ->param(':hostId', $hostId)
                ->execute()->get('test');
            if ($result != 'ok') {
                return $result;
            }

            $sql = DB::select('ip', 'name')
                ->from('vpn_hosts')->where('id', '=', $hostId)
                ->execute();
            $host = $sql->get('ip');
            $client = $sql->get('name') . "-$client";
            $socket = stream_socket_client("tcp://$host");
            stream_set_timeout($socket, 7);
            if ($socket === false) {
                return Kohana::message('user', 'serNotAvailable');
            }

            $nonce = time();
            $key   = $this->_config->secret;
            $dat   = serialize(array(
                'client'   => $client,
                'command'  => 'new',
                'key'      => $this->_config->mailkey,
                'email'    => $user->getEmail(),
                'callback' => $this->_config->site . "/user/callbackvpn/$callback",
            ));

            $data = array(
                'nonce' => $nonce,
                'data'  => $dat,
                'hash'  => hash_hmac('sha256', $dat, "$key$nonce"),
            );

            fwrite($socket, base64_encode(json_encode($data)));
            if (fread($socket, 1024) == "Oxi") {

                DB::insert('vpn_user', array('vpn_id', 'user_id', 'callback', 'name', 'date_delete', 'date_create', 'active'))
                    ->values(array($hostId, $user->getId(), $callback, $client, 
                        date('Y-m-d', time() + 86400), date('Y-m-d H:i:s'), false))
                    ->execute();
                return false;
            }
            return Kohana::message('user', 'serNotAnswer');
        }
        return Kohana::message('user', 'someError');
    }

    /**
     * логирует потребление трафика пользователями
     *
     */
    public function setTrafficMeters()
    {
        $ip  = $_SERVER['REMOTE_ADDR'];
        $cnt = DB::query(Database::SELECT,
            "SELECT id FROM vpn_hosts WHERE ip LIKE '$ip%'")->execute()->count();
        if ($cnt) {
            $post = Request::current()->post();
            if (array_key_exists('data', $post)) {

                $data = json_decode(base64_decode($post['data']), true);

                $name = DB::select(DB::Expr('user_id as id'), 'name')
                    ->from('vpn_user')
                    ->where('name', 'IN', array_keys($data))
                    ->execute()
                    ->as_array();
                if (empty($name)) {
                    return false;
                }
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
        $cnt = DB::query(Database::SELECT,
            "SELECT id FROM vpn_hosts WHERE ip LIKE '$ip%'")->execute()->count();
        
        if ($cnt) {
            //file_put_contents(__DIR__.'/test.log',json_encode($_POST));
            DB::query(Database::INSERT, 
                "INSERT into connection  (user_id, vpn_id, date, type)
                    select user_id, vpn_id, now(), :type as type 
                    from vpn_user where name = :name")
                ->param(':name', $name)
                ->param(':type', $type)
                ->execute();
        }

    }

    /**
     * возвращает список пользовательских vpn
     *
     */
    public function getUserVpn(Model_User $user)
    {
        if ($user instanceof Model_User) {
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
        return false;
    }

    /**
     * callback для активации vpn
     *
     */
    public function setRegiVpn($token)
    {
        DB::update('vpn_user')
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
        $sql = DB::select('network', 'specifications_link')
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
        DB::query(Database::DELETE, "select deleteSelectedVpn(:list, :id)")
            ->param(':list', $list)
            ->param(':id', $user->getId())
            ->execute();
    }

}
