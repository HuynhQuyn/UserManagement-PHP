<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if (!defined('_CODE')) {
    die('Access denide...');
}
$data = [
    'pageTitle' => 'Đổi mật khẩu'
];
layouts('header-login',$data);

$token = filter()['token'];
if (!empty($token)) {
    //truy vấn db
    $tokenQuey = oneRaw("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
    if (!empty($tokenQuey)) {
        $userId = $tokenQuey['id'];
        if (isPost()) {
            $fillterAll = filter();
            $errors = []; // mảng chứ lỗi

            //Validate password: bắt buộc phải nhập, phải lớn hơn or = 8
            if (empty(($fillterAll['password']))) {
                $errors['password']['required'] = 'bạn chưa nhập mật khẩu';
            } else {
                if (strlen($fillterAll['password']) < 8) {
                    $errors['password']['min'] = 'mật khẩu phải lớn hơn hoặc bằng 8 kí tự';
                }
            }

            //Validate password_confirm: bắt buộc phải nhập, giống password
            if (empty(($fillterAll['password_confirm']))) {
                $errors['password_confirm']['required'] = 'nhập lại mật khẩu';
            } else {
                if (($fillterAll['password_confirm']) != $fillterAll['password']) {
                    $errors['password_confirm']['match'] = 'nhập lại mật khẩu không giống';
                }
            }


            if(empty($errors)) {
                //xử lý việc update mk
                $passwordHash = password_hash($fillterAll['password'],PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users',$dataUpdate,"id='$userId'");
                if($updateStatus) {
                    setFlashData('msg','Đổi mật khẩu thành công!');
                    setFlashData('msg_type','success');
                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg','Lỗi hệ thống, vui lòng liên hệ quản trị viên!');
                    setFlashData('msg_type','danger');
                }
            } else {
                setFlashData('msg','Vui lòng kiểm tra lại dữ liệu!');
                setFlashData('msg_type','danger');
                setFlashData('errors',$errors);
                redirect('?module=auth&action=reset&token='.$token);
            }
        }

        $msg = getFlashData('msg');
        $msg_type = getFlashData('msg_type');
        $errors = getFlashData('errors');
?>
        <!-- FORM ĐẶT MẬT khẩu -->
        <div class="row">
            <div class="col-4" style="margin: 50px auto">
                <h2 class="text-center text-uppercase">Đặt lại mật khẩu</h2>
                <?php
                    getSmg($msg, $msg_type);
                ?> 
                <form action="" method="post">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo form_error('password', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại password</label>
                        <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại password">
                        <?php
                        echo form_error('password_confirm', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class="btn-form btn btn-primary btn-block">Gửi</button>
                    <hr>
                    <p class="text-center">
                        <a href="?module=auth&action=login">Đăng nhập</a>
                    </p>

                </form>
            </div>
        </div>

<?php


    } else {
        setFlashData('smg', 'Liên kết không tồn tại hoặc đã hết hạn');
        setFlashData('smg_type', 'danger');
    }
} else {
    setFlashData('smg', 'Liên kết không tồn tại hoặc đã hết hạn');
    setFlashData('smg_type', 'danger');
}



?>

<?php
layouts('footer-login');
?>