<?php 

function __autoload($class)
{
	$dir = __DIR__.DIRECTORY_SEPARATOR;

    $map[] = $dir.str_replace('\\',DIRECTORY_SEPARATOR, $class).'.php';
    $map[] = $dir.str_replace('\\',DIRECTORY_SEPARATOR,'App\\vendor\\symfony\\src\\'.$class).'.php';
    $map[] = $dir.str_replace('\\',DIRECTORY_SEPARATOR,'App\\vendor\\mailgun\\mailgun-php\\src\\'.$class).'.php';
    $map[] = $dir.str_replace('\\',DIRECTORY_SEPARATOR,'App\\vendor\\guzzle\\guzzle\\src\\'.$class).'.php';

    foreach ($map as $name) {
        if (file_exists($name)) {
            require_once $name;
            break;
        }
    }
}
