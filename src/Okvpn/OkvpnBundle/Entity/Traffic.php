<?php

namespace Okvpn\OkvpnBundle\Entity;

use Okvpn\KohanaProxy\ORM;

class Traffic extends ORM
{
    
    use GetSetUserTrait;
    
    protected $_table_name = 'traffic'; // @codingStandardsIgnoreLine

    protected $_foreign_key_suffix = ''; // @codingStandardsIgnoreLine

    protected $_belongs_to = array( // @codingStandardsIgnoreLine
        'user' => array(
            'model'       => 'OkvpnFramework:Users',
            'foreign_key' => 'uid',
        ),
    );
    
    /**
     * @return float
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     * @return Traffic
     */
    public function setCount($count)
    {
        $this->count = $count;
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
     * @return Traffic
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
