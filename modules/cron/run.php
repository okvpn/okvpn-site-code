<?php

/**
 * @package Cron
 *
 * @author      Chris Bandy
 * @copyright   (c) 2010 Chris Bandy
 * @license     http://www.opensource.org/licenses/isc-license.txt
 */
$system = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'web/index.php';

if (file_exists($system))
{
	defined('SUPPRESS_REQUEST') or define('SUPPRESS_REQUEST', TRUE);

	include $system;
	
	// If Cron has been run in APPPATH/bootstrap.php, this second call is harmless
	Cron::set('proxy_scan', array('* * * * *', 'Okvpn::proxyScan'));
	Cron::set('blockuser', array('*/5 * * * *', 'Okvpn::blockUser'));
	Cron::run();
}
