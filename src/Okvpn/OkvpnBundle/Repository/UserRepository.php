<?php

namespace Okvpn\OkvpnBundle\Repository;

use Okvpn\KohanaProxy\Database;
use Okvpn\KohanaProxy\DB;
use Okvpn\OkvpnBundle\Entity\Users;
use Okvpn\OkvpnBundle\Entity\VpnUser;
use Okvpn\OkvpnBundle\Security\UserProviderInterface;

class UserRepository implements UserProviderInterface
{
    /**
     * @inheritdoc
     */
    public function findUserByEmail($email, $onlyActive = false)
    {
        /** @var Users $user */
        $user = (new Users)
            ->where('email', '=', $email)
            ->find();

        if ($onlyActive && !$user->getChecked()) {
            return null;
        }

        return null === $user->getId() ? null : $user;
    }

    public function findUserByCertName($cert)
    {
        /** @var VpnUser $vu */
        $vu = (new VpnUser())
            ->where('name', '=', $cert)
            ->find();

        return $vu->getUser();
    }
    
    
    public function findUserByToken($token)
    {
        if (null === $token) {
            return null;
        }
        /** @var Users $user */
        $user = (new Users)
            ->where('token', '=', $token)
            ->find();

        return null === $user->getId() ? null : $user;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getTrafficMeters($id)
    {
        return \DB::query(
            \Database::SELECT,
            "SELECT CAST(row_number() OVER() as integer) as id, 
                    CAST(r1.dates as character varying) as date, 
                    r1.traffic as x, r1.amount as spent,
                COALESCE((select sum(b.amount) from billing as b
                    where b.uid = :idd and b.date < (select date_trunc('day',now() - interval '1 month'))),
                0) + 
                sum(r1.amount) OVER (ORDER BY r1.dates)  as balance from (
                    select t2.dates, COALESCE(t1.traffic,0) as traffic, COALESCE(t3.amount,0) as amount from (
                        select sum(t.count) as traffic, TO_CHAR(t.date,'YYYY-MM-DD') as dates
                            from traffic as t where uid = :idd group by dates) t1
                    right join (
                        select TO_CHAR(current_date - rng,'YYYY-MM-DD') as dates
                            from generate_series(0,30,1) as rng) t2
                    on t1.dates = t2.dates
                    left join (
                        select sum(b.amount) as amount, TO_CHAR(b.date,'YYYY-MM-DD') as dates
                            from billing as b where uid = :idd group by dates) t3
                    on t3.dates = t2.dates) r1"
        )
            ->param(':idd', $id)
            ->execute()
            ->as_array();
    }

    /**
     * @param int $uid
     * @return array
     */
    public function getUserVpnList($uid)
    {
        $sql = \DB::query(
            \Database::SELECT,
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
            ) T2 ON T1.id = T2.id"
        )
            ->param(':id', $uid);

        $return = $sql->execute()->as_array();

        return is_array($return) ? $return : [];
    }

    /**
     * @param integer $uid
     */
    public function deleteAllUserVpn($uid)
    {
        $sql = \DB::query(
            \Database::UPDATE,
            "UPDATE vpn_user set active = false
                where user_id = :uid"
        )
            ->param(':uid', $uid);

        $sql->execute();
    }

    /**
     * Check status user and return true if that user allow connected to server
     *
     * @param integer $uid
     * @param string  $certName
     * @return bool
     */
    public function isAllowConnection($uid, $certName)
    {
        $sql = \DB::query(
            \Database::SELECT,
            "select
                case when count(*) > 0 then true else false end as allow_connect
            from users u
            inner join roles r on u.role = r.id
            inner join vpn_user vu on vu.user_id = u.id
            where vu.active = true
            and vu.name = :name
            and u.id = :uid
            and u.checked = true
            and r.min_balance < (
                select coalesce(sum(amount), 0) from billing
                where uid = :uid
            )
            and r.traffic_limit > (
                select coalesce(sum(
                    case when count > 0 then count else 0 end
                ), 0) from traffic
                where uid = :uid
                and date > (select date_trunc('day', now() - interval '1 month')
                )) + (
                select coalesce(sum (
                    case when count < 0 then count else 0 end
                ), 0) from traffic
                where uid = :uid)"
        )->parameters([':uid' => $uid, ':name' => $certName]);

        return $sql->execute()->get('allow_connect') == 't';
    }

    /**
     * Checks the user can create a VPN
     *
     * @param $userId
     * @param $vpnId
     */
    public function isAllowCreateVpnSelected($userId, $vpnId)
    {
        $query = "with o_free_places as 
            (
                select (T1.free - COALESCE(T2.cnt, 0)) as free
                from (
                    select id, free_places as free from vpn_hosts where id = :vpn
                ) T1 
                left join (
                    select count(vpn_id) AS cnt, max(vpn_id) as id
                    from vpn_user where vpn_id = :vpn and active = true group by user_id 
                ) T2 on T1.id = T2.id
            ), o_traffic as (
                select COALESCE(sum(count), 0) as traffic_count from traffic where uid = :user
                    and date > (select now() - interval '1 month')
            ), o_money as (
                select COALESCE(sum(amount), 0) as amount from billing where uid = :user
            ), o_host as (
                select count(*) as count from vpn_user where user_id = :user and active = true
            )
            select o_free_places.free < 0 as error, 'vpnPlacesOut' as message, 
                o_free_places.free as current_value, 0 as allow_value
            from o_free_places
            union all 
                select o_traffic.traffic_count > r.traffic_limit as error, 'trafficOut' as message, 
                    o_traffic.traffic_count as current_value, r.traffic_limit as allow_value
                from o_traffic
                cross join (
                    select r.traffic_limit from users u
                    inner join roles r on r.id = u.role
                    where u.id = :user
                ) r
            union all
                select o_money.amount < r.min_balance as error, 'creditOut' as message, 
                    o_money.amount as current_value, r.min_balance as allow_value
                from o_money
                cross join (
                    select r.min_balance from users u
                    inner join roles r on r.id = u.role
                    where u.id = :user
                ) r
            union all
                select o.count >= r.hosts_limit as error, 'vpnUserOut' as message,
                    o.count as current_value, r.hosts_limit as allow_value
                from o_host o
                cross join (
                    select r.hosts_limit from users u
                    inner join roles r on r.id = u.role
                    where u.id = :user
                ) r
            union all
                select o.count != 1 as error, 'vpnNotExist' as message,
                    o.count as current_value, 1 as allow_value
                from (
                    select count(*) as count from vpn_hosts where id = :vpn
                ) o";

        $sql = DB::query(Database::SELECT, $query)
            ->parameters(
                [
                    ':user' => $userId,
                    ':vpn' => $vpnId
                ]
            );
        
        return $sql->execute()->as_array();
    }
}
