<?php
/**
 * Copyright (c) 2014-2015 BitPay
 *
 * 003 - Creating Invoices
 *
 * Requirements:
 *   - Account on https://test.bitpay.com
 *   - Baisic PHP Knowledge
 *   - Private and Public keys from 001.php
 *   - Token value obtained from 002.php
 */
require __DIR__.'/../vendor/autoload.php';

// See 002.php for explanation
$storageEngine = new \Bitpay\Storage\EncryptedFilesystemStorage('3141'); // Password may need to be updated if you changed it
$privateKey    = $storageEngine->load('bitpay.pri');
$publicKey     = $storageEngine->load('bitpay.pub');
$client        = new \Bitpay\Client\Client();
$network       = new \Bitpay\Network\Livenet();
$adapter       = new \Bitpay\Client\Adapter\CurlAdapter();
$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);
$client->setNetwork($network);
$client->setAdapter($adapter);
// ---------------------------

/**
 * The last object that must be injected is the token object.
 */
$token = new \Bitpay\Token();
$token->setToken('3K3wW7gKGVPXzRmH9fTJ1SccmZBwtu2MTFXx4x1HP6WB'); // UPDATE THIS VALUE

/**
 * Token object is injected into the client
 */
$client->setToken($token);

/**
 * This is where we will start to create an Invoice object, make sure to check
 * the InvoiceInterface for methods that you can use.
 */
$invoice = new \Bitpay\Invoice();

/**
 * Item is used to keep track of a few things
 */
$item = new \Bitpay\Item();
$item
    ->setDescription('VPN access 75 GB 1 month')
    ->setPrice('0.89');
$invoice->setItem($item)
    ->setNotificationUrl('https://okvpn.org/notification/bitpay/QsPfeGdhmLeWeV');

/**
 * BitPay supports multiple different currencies. Most shopping cart applications
 * and applications in general have defined set of currencies that can be used.
 * Setting this to one of the supported currencies will create an invoice using
 * the exchange rate for that currency.
 *
 * @see https://test.bitpay.com/bitcoin-exchange-rates for supported currencies
 */
$invoice->setCurrency(new \Bitpay\Currency('USD'));

/**
 * Updates invoice with new information such as the invoice id and the URL where
 * a customer can view the invoice.
 */
try {
    $client->createInvoice($invoice);
} catch (\Exception $e) {
    $request  = $client->getRequest();
    $response = $client->getResponse();
    echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
    echo (string) $response.PHP_EOL.PHP_EOL;
    exit(1); // We do not want to continue if something went wrong
}

echo 'Invoice "'.$invoice->getId().'" created, see '.$invoice->getUrl().PHP_EOL;
