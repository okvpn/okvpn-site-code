<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require_once __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
