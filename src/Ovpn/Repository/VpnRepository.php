<?php

namespace Ovpn\Repository;


class VpnRepository
{
    /**
     * Return information on availability
     *
     * @return array
     */
    public function getVpnStatus()
    {
        $info = \DB::query(\Database::SELECT,
            "select T2.id, T2.name as title, 
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
        
        return $info;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVpnInformation($id)
    {
        $data = \DB::select('network', 'specifications_link')
            ->from('vps')->where('vpn_id','=', $id)
            ->execute()->as_array();
        return $data;
    }

}