<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'default';
$query_builder = true;
$root = true;

$root = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    if ($root == '/staging/') {
        $database       = 'ontimedigital_crm_staging';
        $environment    = 'development';
    } else {

        $database       = 'ontimedigital_crm';
        $environment    = 'production';
    }

    $hostname     = 'localhost';
    $username     = 'root';
    $password = 'df240b484c';

$db['default'] = array(
    'dsn' => '',
    'hostname' => $hostname,
    'username' => $username,
    'password' => $password,
    'database' => $database,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== $environment),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true,
);

$db['auth'] = array(
    'dsn' => '',
    'hostname' => $hostname,
    'username' => $username,
    'password' => $password,
    'database' => 'ontimedigital_oneauth',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== $environment),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true,
);

// $db['pg'] = array(
//     'dsn'   => '',
//     'hostname' => '159.89.171.179',
//     'username' => 'nanouser',
//     'password' => 'TigerTable149',
//     'database' => 'ontimebots',
//     'dbdriver' => 'postgre',
//     'dbprefix' => '',
//     'pconnect' => FALSE,
//     'port'     => '5432',
//     'db_debug' => (ENVIRONMENT !== 'development'),
//     'cache_on' => FALSE,
//     'cachedir' => '',
//     'char_set' => 'utf8',
//     'dbcollat' => 'utf8_general_ci',
//     'swap_pre' => '',
//     'encrypt' => FALSE,
//     'compress' => FALSE,
//     'stricton' => FALSE,
//     'failover' => array(),
//     'save_queries' => TRUE
// );