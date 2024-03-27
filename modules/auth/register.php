<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

if(isPost()) {
    $fillterAll = filter();
    $errors = [];// mảng chứ lỗi
    
    //validate fullname: bắt buộc phải nhập, min 5 ký tự
    if(empty($fillterAll['fullname'])){
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if(strlen($fillterAll['fullname']) <= 5) {
            $errors['fullname']['min'] = 'Họ tên phải lớn hơn 5 kí tự';
        }
    }

    //Email Validate: bắt buộc phải nhập, đúng định dạng email, kiểm tra email đã tồn tại trong csdl chưa
    if(empty($fillterAll['email'])){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        $email = $fillterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        if(getRows($sql) > 0) {
            $errors['email']['unique'] = 'Email đã tồn tại';
        }
    }

    // Validate Sđt: bắt buộc nhập, có đúng định dạng không
    if(empty(($fillterAll['phone']))) {
        $errors['phone']['required'] = 'số điện thoai bắt buộc phải nhập';
    } else {
        if(!isPhone($fillterAll['phone'])){
            $errors['phone']['isPhone'] = 'số điện thoai không hợp lệ';
        }
    }

    //Validate password: bắt buộc phải nhập, phải lớn hơn or = 8
    if(empty(($fillterAll['password']))) {
        $errors['password']['required'] = 'bạn chưa nhập mật khẩu';
    } else {
        if(strlen($fillterAll['password']) < 8){
            $errors['password']['min'] = 'mật khẩu phải lớn hơn hoặc bằng 8 kí tự';
        }
    }

    //Validate password_confirm: bắt buộc phải nhập, giống password
    if(empty(($fillterAll['password_confirm']))) {
        $errors['password_confirm']['required'] = 'nhập lại mật khẩu';
    } else {
        if(($fillterAll['password_confirm']) != $fillterAll['password']){
            $errors['password_confirm']['match'] = 'nhập lại mật khẩu không giống';
        }
    }

    if(empty($errors)) {
        $activeToken = sha1(uniqid().time()); // lấy thời gian hiện tại ngẫu nhiên
        $dataInsert = [
            'fullname' => $fillterAll['fullname'],
            'email' => $fillterAll['email'],
            'phone' => $fillterAll['phone'],
            'password' => password_hash($fillterAll['password'],PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];


        $insertStatus = insert('users',$dataInsert);
        if($insertStatus) {
            //tạo link kích hoạt
            $linkActive = _WEB_HOST . '?module=auth&action=active&token='.$activeToken;

            //thiết lập gửi mail
            $subject = $fillterAll['fullname'].'vui lòng kích hoạt tài khoản!!';
            $content = 'Chào'.$fillterAll['fullname'].'</br>';
            $content .= 'Vui lòng click vào link dươí đây để kích hoạt tài khoản: </br>';
            $content .= $linkActive.'</br>';
            $content .= 'Trân trọng cảm ơn';

            //tiến hành gửi mail
            $senMail = senMail($fillterAll['email'],$subject, $content);
            if($senMail){
                setFlashData('msg','Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản!!');
                setFlashData('msg_type','success');
            } else {
                setFlashData('msg','hệ thống đang gặp sự cố, vui lòng thử lại sau');
                setFlashData('msg_type','danger');
            }


        } else {
            setFlashData('smg','đăng ký không thành công');
            setFlashData('smg_type','danger');
        }
        redirect('?module=auth&action=login');
    } else {
        setFlashData('smg','Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type','danger');
        setFlashData('errors',$errors);
        setFlashData('old',$fillterAll);
        redirect('?module=auth&action=register');
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

   

$data =[
    'pageTitle' => 'Đăng ký tài khoản'
];

layouts('header-login',$data);



?>

<div class="row">
    <div class="col-4" style="margin: 50px auto">
        <h2 class="text-center text-uppercase">Đăng ký tài khoản</h2>
        <?php
            getSmg($smg,$smg_type);
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input name="fullname" type="fullname" class="form-control" placeholder="fullname" value="<?php
                 echo old('fullname',$old) ?>">
                <?php
                    echo form_error('fullname','<span class="error">','</span> ',$errors);
                ?> <?php //reset() là lấy pt đầu tiên trong mảng?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="<?php
                 echo old("email",$old) ?>">
                <?php 
                    echo form_error('email','<span class="error">','</span> ',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input name="phone" type="number" class="form-control" placeholder="Số điện thoại" value="<?php
                 echo old("phone",$old) ?>">
                <?php 
                    echo form_error('phone','<span class="error">','</span> ',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu" value="<?php
                  echo old("password",$old) ?>">
                <?php 
                    echo form_error('password','<span class="error">','</span> ',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại password">
                <?php 
                    echo form_error('password_confirm','<span class="error">','</span> ',$errors);
                ?> 
            </div>
            <button type="submit" class="mg-btn btn btn-primary btn-block">Đăng ký</button>
            <hr>
            <p class="text-center">
                <a href="?module=auth&action=login">Đăng nhập</a>
            </p>
           
        </form>
    </div>
</div>

<?php
layouts('footer-login');