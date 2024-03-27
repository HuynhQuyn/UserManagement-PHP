<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}
$data = [
    'pageTitle' => 'kích hoạt tài khoản'
];
layouts('header-login');
$token = filter()['token'];

if(!empty($token)){
    // truy vấn để kt token với db
    $tokenQuery = oneRaw("SELECT id FROM users WHERE activeToken = '$token'");
    if(!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];
        print_r($userId);
        $updateStatus = update('users',$dataUpdate,"id=$userId");
        if($updateStatus) {
            setFlashData('msg','Kích hoạt tài khoản thành công, bạn có thể đăng nhập ngay bây giờ!!');
            setFlashData('msg_type','success');
        } else {
            setFlashData('msg','Kích hoạt tài khoản thất bại, vui lòng liên hệ với quản trị viên!!');
            setFlashData('msg_type','danger');
        }
        redirect('?module=auth&action=login');

    } else {
        getSmg('Liên kết không tồn tại hoặc hết hạn','danger');
    }
} else {
    getSmg('Liên kết không tồn tại hoặc hết hạn','danger');
}

?>

<h1>active</h1>
<?php
layouts('footer-login');