<?php

namespace Ovpn\Repository;

use Ovpn\Entity\Users;

class UserRepository
{
    /**
     * @param string $email
     * @return null|Users
     * @throws \Kohana_Exception
     */
    public function findUserByEmail(string $email)
    {
        /** @var Users $user */
        $user = (new Users)
            ->where('email', '=', $email)
            ->find();
        
        return (null !== $user->getId()) ? $user : null; 
    }

    /**
     * @param int $id
     * @return array
     */
    public function getTrafficMeters($id)
    {
        return \DB::query(\Database::SELECT,
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
                    on t3.dates = t2.dates) r1")
            ->param(':idd', $id)
            ->execute()
            ->as_array();
    }
}