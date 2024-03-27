<?php

const _MODULE = 'home';
const _ACTION = 'dashboard';
const _CODE = 'true';

// Thiết lập host

define('_WEB_HOST','http://'.$_SERVER['HTTP_HOST'] .'/learn_PHP/manager_user');
define('_WEB_HOST_TEMPLATES',_WEB_HOST .'/templates');

//thiết lập path
define('_WEB_PATH',__DIR__);
define('_WEB_PATH_TEMPLATES',_WEB_PATH.'/templates');

//Thông tin kết nối
const _HOST = "localhost:3307";
const _DB = "quyn123";
const _USER = "root";
const _PASS = "";