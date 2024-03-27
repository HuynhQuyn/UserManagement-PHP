<?php
session_start();
require_once('config.php');
require_once('includes/connect.php');

//thư viện phpmailer
require_once('includes/phpmailer/Exception.php');
require_once('includes/phpmailer/PHPMailer.php');
require_once('includes/phpmailer/SMTP.php');

require_once('includes/function.php');
require_once('includes/database.php');
require_once('includes/session.php');



$_module = _MODULE;
$_action = _ACTION;

if(!empty($_GET['module'])){
    if(is_string($_GET['module'])){
        $_module = $_GET['module'];
    }
}

if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $_action = $_GET['action'];
    }
}

$path = 'modules/'.$_module.'/'.$_action.'.php';
if(file_exists($path)){
    require_once($path);
}else{
    require_once('modules/error/404.php');
}




