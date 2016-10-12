<?php

namespace Okvpn\OkvpnBundle\Entity;

class Balance extends \ORM
{
    
    use GetSetUserTrait;
    
    const AMOUNT_REAL = 'real';

    const AMOUNT_FREE = 'free';

    protected $_table_name = 'billing'; // @codingStandardsIgnoreLine

    protected $_foreign_key_suffix = ''; // @codingStandardsIgnoreLine

    protected $_belongs_to = array( // @codingStandardsIgnoreLine
        'user' => array(
            'model'       => 'Ovpn:Entity:Users',//todo: rename
            'foreign_key' => 'uid',
        ),
    );

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return $this
     */
    public function setDate($date = null)
    {
        if (null == $date) {
            $this->date = date('Y-m-d H:i:s');
            return $this;
        }
        $this->date = $date;

        return $this;
    }
}
