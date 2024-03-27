<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}
if(isLogin()) {
    $token = getSession('loginToken');
    delete('tokenlogin',"token='$token'");
    removeSession('loginToken');
    redirect('?module=auth&action=login');
}