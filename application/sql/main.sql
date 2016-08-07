SELECT T1.uid as id, T1.name, T2.host, T2.location, T2.icon
FROM ( 
    SELECT id as uid, vpn_id as id, name
    FROM `user-vpn`
    WHERE `user_id` = 20) T1
LEFT JOIN (   
    SELECT id, host, location, icon 
    FROM `vpn` 
    WHERE id IN (SELECT vpn_id as id  
        FROM `user-vpn` 
        WHERE `user_id` = 20 ) 
) T2 ON T1.id = T2.id

--
MODIFIES SQL DATA
    COMMENT 'удаляет все связаны с пользователям данные'
BEGIN
    -- insert data into queue
    INSERT INTO queue (name, host, dtime) 
    SELECT T1.name, T2.host, NOW() AS dtime FROM ( 
        SELECT name, `vpn_id` AS id FROM `user-vpn` WHERE `user_id` = idd AND active = 1
    ) T1
    LEFT JOIN (
        SELECT id, ip AS host FROM vpn WHERE id IN ( 
            SELECT `vpn_id` AS id  FROM `user-vpn` WHERE `user_id` = idd)
    ) T2 ON T1.id = T2.id;
    -- delete user table 
    DELETE FROM `billing` WHERE `uid` = idd;
    DELETE FROM `traffic` WHERE `uid` = idd;
    DELETE FROM `user-vpn` WHERE `user_id` = idd;
        SET @role = (SELECT  `role` FROM `user-data` WHERE `id` = idd LIMIT 1);
    SET @phone = (SELECT `phone` FROM `user-data` WHERE `id` = idd LIMIT 1);
    SET @email = (SELECT `email` FROM `user-data` WHERE `id` = idd LIMIT 1);
    DELETE FROM `user-data` WHERE `id` = idd LIMIT 1;
    SET @phone = SHA2(concat(@phone, salt),256);
    SET @email = SHA2(concat(@email, salt),256);
    INSERT INTO `history` (`hash`,`system`) values (@email, 'email');
    IF @role = 1 THEN 
        INSERT INTO `history` (`hash`,`system`) values (@phone, 'phone');
    END IF;

END

-- PAS
DELIMITER $$

CREATE PROCEDURE DelSelectedVpn(IN listVpn VARCHAR(1024), IN uid INT(11))
MODIFIES SQL DATA
    COMMENT 'удаляет ползовательские внп'
BEGIN
    SET @sql = CONCAT('CREATE TABLE IF NOT EXISTS tmp AS (
        SELECT name, vpn_id AS id, id as uid FROM `user-vpn` WHERE id IN (', listVpn ,') AND 
        `user_id` = ', uid ,')');    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    -- insert into 
    INSERT INTO queue (name, host, dtime) 
        SELECT T1.name, T2.host, NOW() as dtime
        FROM (
            SELECT name, id FROM tmp
        ) T1
        LEFT JOIN (
            SELECT ip as host, id FROM vpn WHERE id IN (
                SELECT id FROM tmp
            )
        ) T2 ON T1.id = T2.id;
    -- delete vpn
    UPDATE `user-vpn` SET `active` = 0, `delete` = NOW() WHERE id IN (
        SELECT uid FROM tmp);
    -- 
    DROP TABLE tmp;
END

$$
DELIMITER;

-- sql for delete
INSERT INTO queue (name, host, dtime) 
SELECT T1.name, T2.host, NOW() AS dtime FROM ( 
    SELECT name, `vpn_id` AS id FROM `user-vpn` WHERE `user_id` = 20 AND active = 1
) T1
LEFT JOIN (
    SELECT id, ip AS host FROM vpn WHERE id IN ( 
        SELECT `vpn_id` AS id  FROM `user-vpn` WHERE `user_id` = 20)
) T2 ON T1.id = T2.id 
-- end sql for delete 


DROP FUNCTION IF EXISTS testVpnRegi;
CREATE FUNCTION testVpnRegi(
    idd INT
    vpnId INT
)
RETURNS VARCHAR(255)
BEGIN
    SET @cnt = (SELECT (T1.free - IFNULL(T2.cnt,0)) AS free
        FROM (
            SELECT id, free FROM vpn WHERE `id` = vpnId
        ) T1 
        LEFT JOIN (
            SELECT E1.id, count(*) as cnt FROM (   
                SELECT vpn_id AS id, count(*) AS cnt 
                FROM `user-vpn` WHERE `vpn_id` = vpnId GROUP BY `user_id`) E1
        ) T2 ON T1.id = T2.id);
    IF @cnt < 1 THEN 
        RETURN "На выбранном Вами сервере нет свободных мест";
    END IF;

    SET @status = (SELECT role FROM `user-data` WHERE id = idd);
    IF @status == 1 THEN 
        SET @cvpn = (SELECT count(*) FROM `user-vpn` WHERE `user_id` = idd);
        SET @traf = (SELECT SUM(count) FROM traffic 
                WHERE `time` > (SELECT now()-interval 1 month) AND uid = idd);
        IF @traf > 4096 THEN
            RETURN "Отказано. Превышен месячный лимит трафика в 4GB";
        END IF;
        IF @cvpn > 1 THEN
            RETURN "Отказано. Разрешено не более одного .opvpn ключа";
        END IF;
    ELSE 
    -- real 
        SET @cvpn = (SELECT count(*) FROM `user-vpn` WHERE `user_id` = idd);
        SET @traf = (SELECT SUM(count) FROM traffic 
                WHERE `time` > (SELECT now()-interval 1 month) AND uid = idd);
        SET @amount = (SELECT SUM(amount) FROM billing WHERE uid = idd);

        IF @traf > 76800 THEN 
            RETURN "Отказано. Превышен месячный лимит трафика в 75GB";
        END IF;
        IF @cvpn > 7 THEN
            RETURN "Отказано. Разрешено не более 7 .opvpn ключей";
        END IF;
        IF @cvpn <= 0 THEN
            RETURN "Отказано. Не достаточно средств";
        END IF; 
    END IF;

    RETURN "ok";
END;



SELECT (T1.free - IFNULL(T2.cnt,0)) AS free
FROM (
    SELECT id, free FROM vpn WHERE `id` = 5
) T1 
LEFT JOIN (
    SELECT E1.id, count(*) as cnt FROM (   
        SELECT vpn_id AS id, count(*) AS cnt 
        FROM `user-vpn` WHERE `vpn_id` = 5 GROUP BY `user_id`) E1
) T2 ON T1.id = T2.id

-- удаляет впн по крону, если пользователь привысил максимально,
-- допустимый предел потрябляемого трафика или израсходовал все средства

MODIFIES SQL DATA
    COMMENT 'удаляет внп, если изррасходованы все средства аккаунта'
begin
create table if not exists tmp as (
    select r2.email, r2.cause, r2.id from (
        select name, user_id as id from `user-vpn` where active = 1
    ) r1
    inner join (
        (select t1.id, t2.email, 'freeTrafficOut' as cause from (
            select uid as id, sum(count) as traffic from traffic 
                where DATE_FORMAT(`time`,'%M') = (select DATE_FORMAT(now(),'%M'))
                group by uid
                having traffic > (select value from const where name = 'freeUserTraffic' )) t1
        inner join (
            select id, email from `user-data` where role = 1) t2
        on t1.id = t2.id )
        union
        (select t1.id, t2.email, 'fullTrafficOut' as cause from (
            select uid as id, sum(count) as traffic from traffic 
                where DATE_FORMAT(`time`,'%M') = (select DATE_FORMAT(now(),'%M'))
                group by uid
                having traffic > (select value from const where name = 'fullUserTraffic' ) ) t1
        inner join (
            select id, email from `user-data` where role = 2) t2
        on t1.id = t2.id )
        union
        (select t1.id, t2.email, 'creditOut' as cause from (
            select sum(amount) as total, uid as id from billing 
                group by uid having total <= 0
            ) t1
        inner join (
            select email, id from `user-data` where  role = 2
        ) t2 
        on t2.id = t1.id)
    ) r2 
    on r2.id = r1.id
);

insert into queue (host, dtime, name) 
    select t2.ip as host, now() as dtime, t1.name from (
        select vpn_id as id, name from `user-vpn`
            where user_id in (select id from tmp)
    ) t1
    left join (
        select id, ip from vpn
    ) t2 
    on t2.id = t1.id;

update `user-vpn` set active = 0, `delete` = now() where user_id in (
    select id from tmp
);

select * from tmp;

drop table if exists tmp;

end

-- end mysql

-- postgresql

DROP FUNCTION IF EXISTS testVpnRegi(integer, integer);

CREATE FUNCTION testVpnRegi(idd integer, vpnId integer) RETURNS 
varchar(256) AS $$
DECLARE
roles integer;
c1 integer;
c2 integer;
c3 real;
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
    IF roles = 1 THEN
        -- vpn 
        SELECT count(*) INTO c1 FROM vpn_user WHERE user_id = idd;

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
        SELECT count(*) INTO c1 FROM vpn_user WHERE user_id = idd;

        -- traffic 
        SELECT sum(count) INTO c2 FROM traffic WHERE uid = idd 
            AND date > (SELECT now() - interval '1 month');
        SELECT sum(amount) INTO c3 FROM billing WHERE uid = idd;
        IF c3 <= 0 THEN 
            RETURN 'Отказано. Не достаточно средств';
        END IF;

        IF c2 > 76800 THEN 
            RETURN 'Отказано. Превышен месячный лимит трафика в 75GB';
        END IF;
        IF c1 > 6 THEN 
            RETURN 'Отказано. Разрешено не более 7 .opvpn ключа';
        END IF;
    END IF;
    RETURN 'ok';
    END 
$$ LANGUAGE plpgsql;
-- end testVpnRegi

drop function if exists controlUser();

create function controlUser() returns
table(id integer, email character varying, cause character varying) AS $$
begin
    drop table if exists tmp;
    create temporary table tmp as ( select r2.email, r2.cause, r2.id from (
        select  user_id as id from vpn_user where active = true
    ) r1
    inner join ( 
        (select t1.id, t2.email, 'freeTrafficOut' as cause from (
            select uid as id, sum(count) as traffic from traffic 
                where TO_CHAR(date,'MM YYYY') = (select TO_CHAR(now(),'MM YYYY'))
                group by uid
                having sum(count) > (select CAST(value as real) from const where name = 'freeUserTraffic' )) t1
        inner join (
            select u.id, u.email from users as u where role = 1) t2
        on t1.id = t2.id) 
        union all(
            select t1.id, t2.email, 'fullTrafficOut' as cause from (
                select t.uid as id, sum(t.count) as traffic from traffic as t
                    where TO_CHAR(date,'MM YYYY') = (select TO_CHAR(now(),'MM YYYY'))
                    group by uid
                    having sum(count) > (select CAST(value as real) from const where name = 'fullUserTraffic' )) t1
            inner join (
                select u.id, u.email from users as u where role = 2) t2
            on t1.id = t2.id)
        union all(
            select t1.id, t2.email, 'creditOut' as cause from (
                select sum(amount) as total, uid as id from billing 
                    group by uid having sum(amount) <= 0
                ) t1
            inner join (
                select u.email, u.id from users as u where  role = 2
            ) t2 
            on t2.id = t1.id)) r2
    on r2.id = r1.id );

    insert into queue (host, date, name) 
        select t2.ip as host, now() as date, t1.name from (
            select v.vpn_id as id, v.name from vpn_user as v
                where user_id in (select tmp.id from tmp)
        ) t1
        left join (
            select v.id, v.ip from vpn_hosts as v
        ) t2 
        on t2.id = t1.id;

    update vpn_user set active = false, date_delete = now() where user_id in (
        select tmp.id from tmp
    );
    return query 
        select distinct tmp.id, tmp.email, CAST(tmp.cause as character varying) as cause  from tmp;
end;
$$ LANGUAGE plpgsql;


-- 

drop function if exists usageUser(integer);

create function usageUser(idd integer) returns
table(id integer, date character varying, x real,spent real, balance real) AS $$
begin
    return query 
        select CAST(row_number() OVER() as integer) as id, CAST(r1.dates as character varying) as date, r1.traffic as x, r1.amount as spent,
        COALESCE((select sum(b.amount) from billing as b
            where b.uid = idd and b.date < (select date_trunc('day',now() - interval '1 month'))),
        0) + 
        sum(r1.amount) OVER (ORDER BY r1.dates)  as balance from (
            select t2.dates, COALESCE(t1.traffic,0) as traffic, COALESCE(t3.amount,0) as amount from (
                select sum(t.count) as traffic, TO_CHAR(t.date,'YYYY-MM-DD') as dates
                    from traffic as t where uid = idd group by dates) t1
            right join (
                select TO_CHAR(current_date - rng,'YYYY-MM-DD') as dates
                    from generate_series(0,30,1) as rng) t2
            on t1.dates = t2.dates
            left join (
                select sum(b.amount) as amount, TO_CHAR(b.date,'YYYY-MM-DD') as dates
                    from billing as b where uid = idd group by dates) t3
            on t3.dates = t2.dates) r1;
end;
$$ LANGUAGE plpgsql;

-- dropUserData
drop function if exists dropUserData(integer);
create function dropUserData(idd integer) returns
void as $$
DECLARE
semail character varying;
begin 
    insert into queue (name, host, date) 
        select t1.name, t2.ip as host, now() as date from (
            select vu.name, vu.vpn_id as id from vpn_user as vu
            where user_id = idd and vu.active = true ) t1
        left join (
            select vh.ip, vh.id from vpn_hosts as vh ) t2
        on t1.id = t2.id;
    select u.email from users as u where u.id = idd into semail;
    update users set email = semail || '@delete', checked = false 
        where id = idd;
    update vpn_user set active = true where user_id = idd;
end
$$ LANGUAGE plpgsql;
-- end dropUserData

-- end postgresql