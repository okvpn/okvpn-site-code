<?php

use Phinx\Migration\AbstractMigration;

class TestVpnRegi extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
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
        $this->execute(
            "CREATE FUNCTION testVpnRegi(idd integer, vpnId integer) RETURNS 
                varchar(256) AS $$
                DECLARE
                roles integer;
                c1 integer;
                c2 integer;
                c3 real;
                c4 real;
                    BEGIN
                    -- check free places
                    SELECT (T1.free - COALESCE(T2.cnt,0)) AS free INTO c1
                    FROM (
                        SELECT id, free_places as free FROM vpn_hosts WHERE id = vpnId
                    ) T1 
                    LEFT JOIN (
                        SELECT count(E1.id) as cnt, MAX(E1.id) as id FROM (
                        SELECT count(vpn_id) AS cnt, MAX(vpn_id) as id
                        FROM vpn_user WHERE vpn_id = vpnId GROUP BY user_id 
                        ) E1
                    ) T2 ON T1.id = T2.id;
                    IF c1 = 0 OR c1 IS NULL THEN
                        RETURN 'На выбранном Вами сервере нет свободных мест';
                    END IF;

                    SELECT role INTO roles FROM users WHERE id = idd;
                    -- role super admin 
                    if roles = 3 then 
                        return 'ok';
                    end if;
                    -- role free users 
                    IF roles = 1 THEN
                        -- vpn 
                        SELECT count(*) INTO c1 FROM vpn_user WHERE user_id = idd and active = true;

                        -- traffic 
                        SELECT sum(count) INTO c2 FROM traffic WHERE uid = idd 
                            AND date > (SELECT now() - interval '1 month');
                        IF c2 > 4096 THEN 
                            RETURN 'Отказано. Превышен месячный лимит трафика в 4GB';
                        END IF;
                        IF c1 > 0 THEN 
                            RETURN 'Отказано. Разрешено не более одного .opvpn ключ';
                        END IF;
                    ELSE 
                        -- vpn 
                        SELECT count(*) INTO c1 FROM vpn_user WHERE user_id = idd and active = true;

                        -- traffic 
                        SELECT sum(count) INTO c4 FROM traffic WHERE uid = idd 
                            AND date > (SELECT now() - interval '1 month');
                        SELECT COALESCE(sum(amount), 0) INTO c3 FROM billing WHERE uid = idd;
                        IF c3 <= 0 THEN 
                            RETURN 'Отказано. Не достаточно средств';
                        END IF;

                        IF c4 > 76800 THEN 
                            RETURN 'Отказано. Превышен месячный лимит трафика в 75GB';
                        END IF;
                        IF c1 > 6 THEN 
                            RETURN 'Отказано. Разрешено не более 7 .opvpn ключа';
                        END IF;
                    END IF;
                    RETURN 'ok';
                    END 
                $$ LANGUAGE plpgsql;"
            );
    }
}
