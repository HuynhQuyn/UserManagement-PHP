<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

try {
    if(class_exists('PDO')) { //class_exists kiểm tra xem hàm PDO có tồn tại không
        $dsn = 'mysql:host='._HOST.';dbname='._DB;

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // tạo thông báo ra ngoại lệ khi gặp lỗi
        ];

        $conn = new PDO($dsn,_USER,_PASS);
        // if($conn) {
        //     echo 'ket noi thanh cong';
        // }
    }


} catch(Exception $exception) {
    echo '<div style="color: red; padding: 5px 15px; border: 1px red solid">';
    echo  $exception -> getMessage().'<br>';
    echo '</div>';
   die();
}