<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}
$data =[
    'pageTitle' => 'Trang Dashboard'
];

layouts('header',$data);

//kiểm tra trạng thái đăng nhập

if(!isLogin()) {
    redirect('?module=auth&action=login');
}


?>
<h1>DASHBOARD</h1>
<?php
layouts('footer');

