<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/';
$route['student_login'] = 'auth/student_login/login';
$route['404_override'] = 'MyCustomError';
$route['translate_uri_dashes'] = FALSE;