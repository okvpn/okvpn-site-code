<?php

/**
 * Created by PhpStorm.
 * @author Jurasikt
 */
class BitpayFullTest extends PHPUnit_Framework_TestCase
{
    private $bitpay;

    private $transaction;

    public function testCreateInvoice()
    {
        $bitpay = Model::factory('Bitpay');
        $this->bitpay = $bitpay;
        $this->transaction = $bitpay->createInvoice(41, 1.00);
        $this->assertInstanceOf('Model_TransactionInerface', $this->transaction);

        $this->assertInstanceOf('Model_TransactionInerface', $this->bitpay->refreshStatus($this->transaction));

        $status = $this->bitpay->refreshStatus($this->transaction)->getStatus();
        $this->assertSame(Model_Bitpay::STATUS_NEW, $status);

        $this->assertSame(false, $this->bitpay->getTransactionSum($this->transaction));
    }
}