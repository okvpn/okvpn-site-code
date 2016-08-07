<?php

/**
 * Created by PhpStorm.
 * @author Jurasikt
 */
class BitpaySimpleTest extends PHPUnit_Framework_TestCase
{
    private $bitpay;

    private $transaction;

    public function setUp()
    {
        $this->bitpay = Model::factory('Bitpay');
        $this->transaction = ORM::factory('Transaction', 2);
    }

    public function testRefresh()
    {
        $this->assertNotNull($this->bitpay);
        $this->assertNotNull($this->transaction);
        $this->assertInstanceOf('Model_TransactionInerface', $this->bitpay->refreshStatus($this->transaction));
    }

    /**
     * @depends testRefresh
     */
    public function testTransactionSum()
    {
        $this->assertNotNull($this->bitpay);
        $this->assertNotNull($this->transaction);
        $this->assertSame(false, $this->bitpay->getTransactionSum($this->transaction));
    }

}
