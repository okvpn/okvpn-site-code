<?php

use Phinx\Migration\AbstractMigration;

class TestVpnRegi extends AbstractMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute('DROP function IF EXISTS testVpnRegi(integer, integer)');
        $this->execute(
            "CREATE function testVpnRegi(idd integer, vpnId integer) RETURNS 
                varchar(256) AS $$
                DECLARE
                roles integer;
                c1 integer;
                c2 integer;
                c3 real;
                c4 real;
                    BEGIN
                    -- check free places
                    select (T1.free - COALESCE(T2.cnt,0)) as free into c1
                    from (
                        select id, free_places as free from vpn_hosts where id = vpnId
                    ) T1 
                    left join (
                        select count(E1.id) as cnt, MAX(E1.id) as id from (
                            select count(vpn_id) AS cnt, max(vpn_id) as id
                        from vpn_user where vpn_id = vpnId and active = true group by user_id 
                        ) E1
                    ) T2 on T1.id = T2.id;
                    if c1 <= 0 or c1 is null then
                        return 'vpnPlacesOut';
                    end if;

                    select count(*) into c1 from vpn_user where user_id = idd and active = true;
                    -- traffic 
                    select sum(count) into c4 from traffic where uid = idd 
                        and date > (select now() - interval '1 month');
                    -- amount
                    select COALESCE(sum(amount), 0) into c3 from billing where uid = idd;

                    if c1 > (select t2.hosts_limit from users t1, roles t2 where t1.id = idd and t2.id = t1.role) then
                        return 'vpnUserOut';
                    end if;

                    if c4 > (select t2.traffic_limit from users t1, roles t2 where t1.id = idd and t2.id = t1.role) then 
                        return 'fullTrafficOut';
                    end if;

                    if c3 < (select t2.min_balance from users t1, roles t2 where t1.id = idd and t2.id = t1.role) then 
                        return 'creditOut';
                    end if;
                    
                    return 'ok';
                    end 
                $$ LANGUAGE plpgsql;"
            );

    }

    public function down()
    {
        $this->execute('DROP function IF EXISTS testVpnRegi(integer, integer)');
    }
}
