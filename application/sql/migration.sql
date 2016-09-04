SELECT CASE WHEN count(*) > 0
  THEN TRUE
       ELSE FALSE END AS allow_connect
FROM users u
  INNER JOIN roles r ON u.role = r.id
  INNER JOIN vpn_user vu ON vu.user_id = u.id
WHERE vu.active = TRUE
      AND vu.name = ':name'
      AND u.id = ':uid'
      AND r.min_balance < (
  SELECT sum(amount)
  FROM billing
  WHERE uid = ':uid'
)
      AND r.traffic_limit < (
                              SELECT sum(
                                  CASE WHEN count > 0
                                    THEN count
                                  ELSE 0 END
                              )
                              FROM traffic
                              WHERE uid = ':uid'
                                    AND date > (SELECT date_trunc('day', now() - INTERVAL '1 month')
                              )) + (
                              SELECT sum(
                                  CASE WHEN count < 0
                                    THEN count
                                  ELSE 0 END
                              )
                              FROM traffic
                              WHERE uid = ':uid'
                            );

SELECT date_trunc('day', now() - INTERVAL '1 month')


select
  case when count(*) > 0 then true else false end as allow_connect
from users u
  inner join roles r on u.role = r.id
  inner join vpn_user vu on vu.user_id = u.id
where vu.active = true
      and vu.name = 'nl2-3368cf13'
      and u.id = 41
      and r.min_balance < (
  select coalesce(sum(amount),0) from billing
  where uid = 41
)
      and r.traffic_limit > (
                              select coalesce(sum(
                                                  case when count > 0 then count else 0 end
                                              ), 0) from traffic
                              where uid = 41
                                    and date > (select date_trunc('day', now() - interval '1 month')
                              )) + (
                              select coalesce(sum (
                                                  case when count < 0 then count else 0 end
                                              ), 0) from traffic
                              where uid = 41)
