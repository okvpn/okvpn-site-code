<?php

class Database_PostgreSQL extends Kohana_Database_PostgreSQL 
{
    /**
     * The transaction nesting level.
     * 
     * @var integer
     */
    protected $_transactionNestingLevel = 0;

    /**
     * {@inheritdoc}
     */
    public function begin($mode = null)
    {
        ++$this->_transactionNestingLevel;
        
        if ($this->_transactionNestingLevel == 1) {
            return parent::begin($mode);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback($savepoint = null)
    {
        if ($this->_transactionNestingLevel != 0) {
            $this->_transactionNestingLevel = 0;
            return parent::rollback($savepoint);            
        }
    }

    /**
     * {@inheritdoc}
     * todo: add save point in 2.2
     */
    public function commit()
    {
        --$this->_transactionNestingLevel;
        if ($this->_transactionNestingLevel == 0) {
            return parent::commit();
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionNestingLevel()
    {
        return $this->_transactionNestingLevel;
    }
}