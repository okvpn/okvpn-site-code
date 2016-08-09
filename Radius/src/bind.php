<?php 
require_once __DIR__.'/autoload.php';
use App\Socket\Socket;

$demon = new Socket;

$demon->run();