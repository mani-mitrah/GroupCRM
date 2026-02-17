<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;
$root = TRUE;

$root = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
if ($root == '/staging/') {
	$database 		= 'ontimedigital_crm_staging';
	$environment 	= 'development';
} else {

	$database 		= 'ontimedigital_crm';
	$environment 	= 'production';
}

// $hostname = '104.248.245.156';
// $username = 'chatbot_user';
// $password = '5700d$A4';

$database = getenv('DB_NAME');
$environment = getenv('DB_ENVIRONMENT');
$hostname = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

$db['default'] = array(
	'dsn' => '',
	'hostname' => $hostname,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== $environment),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE,
);
