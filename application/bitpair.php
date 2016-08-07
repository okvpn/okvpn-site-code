<?php
use Bitpay\PrivateKey;
use Bitpay\PublicKey;
use Bitpay\KeyManager;
use Bitpay\Storage\FilesystemStorage;
use Bitpay\Currency;
use Bitpay\Invoice;
use Bitpay\Item;
use Bitpay\Token;
use Bitpay\Bitpay;

require_once __DIR__.'/vendor/autoload.php';

$dir = __DIR__;

/*$privateKey = new PrivateKey("$dir/private.key");
$public_key  = new PublicKey("$dir/public.pub");

$privateKey->generate();

$public_key->setPrivateKey($privateKey);

$manager = new KeyManager(new FilesystemStorage());
$manager->persist($privateKey);
$manager->persist($public_key);*/

//3tywvrShMj6H1vRJG1hhZ3

$bitpay = new Bitpay(
    array(
        'bitpay' => array(
            'network'     => 'livenet', 
            'public_key'  => "$dir/public.pub",
            'private_key' => "$dir/private.key",
            'key_storage' => 'Bitpay\Storage\EncryptedFilesystemStorage',
        )
    )
);

/**
 * Create the client that will be used to send requests to BitPay's API
 */
$client = $bitpay->get('client');

$tokens = $client->getTokens();
print_r($tokens);


/*$item = new Item();
$item->setPrice('0.85');

$invoice = new Invoice();
$invoice->setCurrency(new Currency('USD'))
	->setItem($item)
	->setNotificationUrl('https://okvpn.org/bitpay/ipn');

$bitpay = new Bitpay(
    array(
        'bitpay' => array(
            'network'     => 'livenet', 
            'public_key'  => "$dir/public.pub",
            'private_key' => "$dir/private.key",
            'key_storage' => 'Bitpay\Storage\EncryptedFilesystemStorage',
        )
    )
);

$client = $bitpay->get('client');

$token = new Token();
$token->setToken('3tywvrShMj6H1vRJG1hhZ3');
$client->setToken($token);

$keyManager = new KeyManager(new FilesystemStorage());
$privateKey = $keyManager->load("$dir/private.key");
$publicKey  = $keyManager->load("$dir/public.pub");

$client->setPrivateKey($privateKey);
$client->setPublicKey($publicKey);

$invoice = $client->createInvoice($invoice);

echo $invoice->getUrl() . "\n";

var_dump($invoice);*/


