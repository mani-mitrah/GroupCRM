<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route[LOGIN_PAGE] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['recover'] = 'auth/recover';

$route['reset_password'] = 'auth/reset_password';
$route['update_password'] = 'auth/update_password';

$route['admin/branch'] = 'admin/Branches/index';
$route['admin/branch/add'] = 'admin/Branches/branch_add';
$route['admin/branch/edit/(:any)'] = 'admin/Branches/branch_edit/$1';
$route['admin/branch/status_change/(:any)'] = 'admin/Branches/status_change/$1';
$route['admin/branch/delete/(:any)'] = 'admin/Branches/branch_delete/$1';
// $route['admin/mail/(:any)'] = 'admin/Branches/mails/$1';