<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

$data =[
    'pageTitle' => 'Quên tài khoản'
];

layouts('header-login',$data);

//kiểm tra trạng thái đăng nhap
if(isLogin()) {
    redirect('?module=home&action=dashboard');
}

if(isPost()) {
    $fillterAll = filter();
    if(!empty($fillterAll['email'])) {
        $email = $fillterAll['email'];

        //kiểm tra email có tồn tại trong db ko
        $queryUser = oneRaw("SELECT id FROM users WHERE email = '$email'");
        if(!empty($queryUser)) {
            $userId = $queryUser['id'];

            //tạo forgot token
            $forgotToken = sha1(uniqid().time());

            $dataUpdate = [
                'forgotToken' => $forgotToken,
            ];

            $updateStatus = update('users',$dataUpdate,"id=$userId"); 
            if($updateStatus){
                //tạo link reset, khôi phục mk
                $linkReset = _WEB_HOST.'?module=auth&action=reset&token='.$forgotToken;

                //gửi mail cho người dùng
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn'.'</br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. Vui lòng click vào link dươí đây để đổi lại mật khẩu: </br>';
                $content .= $linkReset.'</br>';
                $content .= 'Trân trọng cảm ơn';

                $senEmail = senMail($email,$subject,$content);
                if($senEmail) {
                    setFlashData('msg','Vui lòng kiểm tra email để xem hướng dẫn lấy lại mật khẩu');
                    setFlashData('msg_type','success');   
                } else {
                    setFlashData('msg','Lỗi hệ thống vui lòng thử lại sau!');
                    setFlashData('msg_type','danger');
                }
            } else {
                setFlashData('msg','Lỗi hệ thống vui lòng thử lại sau!');
                setFlashData('msg_type','danger');
            }

        } else {
            setFlashData('msg','email không hợp lệ!');
            setFlashData('msg_type','danger');
        }
    } else {
        setFlashData('msg','vui lòng nhập email');
        setFlashData('msg_type','danger');
    }
}


$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');



?>
<div class="row">
    <div class="col-4" style="margin: 50px auto">
        <h2 class="text-center text-uppercase">Quên mật khẩu</h2>
        <?php
            getSmg($msg,$msgType);
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ">
            </div>
            <button type="submit" class="btn-form btn btn-primary btn-block">Gửi</button>
            <hr>
            <p class="text-center">
                <a href="?module=auth&action=forgot">Đăng nhập</a>
            </p>
            <p class="text-center">
                <a href="?module=auth&action=register">Đăng ký tài khoản</a>
            </p>
        </form>
    </div>
</div>



<?php
    layouts('footer-login')
?>