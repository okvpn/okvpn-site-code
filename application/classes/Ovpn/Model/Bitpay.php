<?php
use Bitpay\Storage\EncryptedFilesystemStorage;
use Bitpay\Network\Livenet;
use Bitpay\Client\Client;
use Bitpay\Client\Adapter\CurlAdapter;
use Bitpay\KeyInterface;
use Bitpay\Token;
use Bitpay\Invoice;
use Bitpay\Item;
use Bitpay\Currency;

class Model_Bitpay extends Model implements Model_BitpayInerface {

    protected $_config;

    protected $_token;

    protected $_publicKey;

    protected $_privateKey;

    protected $_isInstance = false;

    protected $_bitpayUrl;

    public static $allowSumPaid = [0.3, 0.6, 1.0, 2.0, 3.0];

    public function __construct()
    {
        $this->_config = Kohana::$config->load('bitpay');

        $this->_token = DB::select('value')
            ->from('const')
            ->where('name', '=', $this->_config['token'])
            ->execute()
            ->get('value');

        if (file_exists(APPPATH . $this->_config['private']) 
                && file_exists(APPPATH . $this->_config['public'])) {

            $this->_isInstance = true;

            $storageEngine = new EncryptedFilesystemStorage('3141');

            $this->_privateKey = $storageEngine->load(APPPATH . $this->_config['private']);
            $this->_publicKey  = $storageEngine->load(APPPATH . $this->_config['public']);
        }

        //parent::__construct();
    }

    public function getUrl()
    {
        return $this->_bitpayUrl;
    }

    public function _isInstance()
    {
        return $this->_isInstance;
    }

    public function getPrivateKey()
    {
        if (!$this->_isInstance()) {
            throw new \Exception("The private key must be load");
        }
        return $this->_privateKey;
    }

    public function getPublicKey()
    {
        if (!$this->_isInstance()) {
            throw new \Exception("The public key must be load");
        }

        return $this->_publicKey;
    }

    /**
     * @return Bitpay\Token; 
     * 
     * @throw \Exception
     */
    public function getToken()
    {
        if (empty($this->_token)) {
            throw new \Exception("Token can not be empty");
        }

        $token = new Token();
        $token->setToken($this->_token);
        return $token;
    }

    /**
     * @param Bitpay\KeyInterface  $priKey
     * @return $this
     */
    public function setPrivateKey(KeyInterface $priKey)
    {
        $this->_privateKey = $priKey;
        return $this;
    }

    /**
     * @param KeyInterface  $pubKey
     * @return $this
     */
    public function setPublicKey(KeyInterface $pubKey)
    {
        $this->_publicKey = $pubKey;
        return $this;
    }

    /**
     * @param double $sum
     * @param integer $uid
     * @return bool
     */
    public function createInvoice($uid, $sum) 
    {
        if (!in_array($sum, self::$allowSumPaid)) {
            throw new \Exception("This amount $sum is not allow to pay");
        }
        
        $privateKey = $this->getPrivateKey();
        $publicKey  = $this->getPublicKey();

        $client     = new Client();
        $network    = new Livenet();
        $adapter    = new CurlAdapter();

        $client->setPrivateKey($privateKey);
        $client->setPublicKey($publicKey);
        $client->setNetwork($network);
        $client->setAdapter($adapter);
        $client->setToken($this->getToken());

        $transaction = $this->invokeBitpay();
        $transaction->setUserId($uid);

        $invoice = new Invoice();

        /**
         * Item is used to keep track of a few things
         */
        $item = new Item();
        $item
            ->setDescription('VPN access 75 GB $1/mo.')
            ->setPrice($sum);

        $invoice->setItem($item)
            ->setNotificationUrl($transaction->getNotificationUrl())
            ->setRedirectUrl($transaction->getRedirectUrl());

        /**
         * BitPay supports multiple different currencies. Most shopping cart applications
         * and applications in general have defined set of currencies that can be used.
         * Setting this to one of the supported currencies will create an invoice using
         * the exchange rate for that currency.
         *
         * @see https://test.bitpay.com/bitcoin-exchange-rates for supported currencies
         */
        $invoice->setCurrency(new Currency('USD'));
        
        try {

            $invoice = $client->createInvoice($invoice);
        } catch (\Exception $e) {

            throw $e;
        }

        $this->_bitpayUrl = $invoice->getUrl();

        $transaction->setInvoiceId($invoice->getId());
        $transaction->save();

        return $transaction;
    }

    /**
     *  Can be `new` (invoice has not yet been fully paid),
     * `paid` (received payment but has not yet been fully confirmed), 
     * `confirmed` (confirmed based on the transaction speed settings), 
     * `complete` (confirmed by BitPay and credited to the ledger), 
     * `expired` (can no longer receive payments) 
     * `invalid` (invoice has received payments but was invalid)
     *
     * @param Model_TransactionInerface $transaction
     * @return Model_TransactionInerface
     */
    public function refreshStatus(Model_TransactionInerface $transaction)
    {
        if ($transaction->getStatus() == self::STATUS_COMPLETE ||
            $transaction->getStatus() == self::STATUS_EXPIRED) {

            return $transaction;
        }

        $client = new Client;
        $client->setNetwork(new Livenet());
        $client->setToken(new Token());

        $invoice = $client->getInvoice($transaction->invoce_id);
        $paid = $invoice->getBtcPaid() * $invoice->getRate();

        $transaction
            ->setPaidSum($paid)
            ->setStatus($invoice->getStatus());
        $transaction->refreshBilling();
        $transaction->save();
        return $transaction;
    }

    /**
     * @param Model_TransactionInerface
     * @return false if it not yet paid or sum in usd 
     */
    public function getTransactionSum(Model_TransactionInerface $transaction)
    {
        $transaction = $this->refreshStatus($transaction);

        if (in_array($transaction->getStatus(), [self::STATUS_COMPLETE, self::STATUS_PAID, 
                self::STATUS_CONFIRMED])) {

            $billingSum = $transaction->billing->amount;

            if (abs($billingSum - $transaction->getPaidSum()) > self::PARAM_IS_ROUND ) {
                throw new \LogicException('The payment amount is not equal to the amount in the transaction');
            }

            return $transaction->getPaidSum();
        }

        return false;
    }

    protected function invokeBitpay()
    {
        $transaction = ORM::factory('Transaction');
        $transaction
            ->setNotificationUrl(Text::random('alnum', 16))
            ->setRedirectUrl(Text::random('alnum', 16))
            ->setPaidSum(0)
            ->setStatus('new')
            ->setResponse('')
            ->setDateCreate(date("Y-m-d H:i:s"));
        

        return $transaction;
    }

}