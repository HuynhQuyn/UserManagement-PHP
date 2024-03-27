<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

$data =[
    'pageTitle' => 'Đăng nhập tài khoản'
];

layouts('header-login',$data);

//kiểm tra trạng thái đăng nhap
if(isLogin()) {
    redirect('?module=home&action=dashboard');
}

if(isPost()){
    $fillterAll = filter();
    if(!empty(trim($fillterAll['email'])) && !empty(trim($fillterAll['password']))) {
        //kiểm tra đăng nhập
        $email = $fillterAll['email'];
        $password = $fillterAll['password'];

        //truy vấn lấy thông tin từ users để lấy password của email nhập vào trong db
        $userQuery = oneRaw("SELECT password, id FROM users WHERE email = '$email'");
        if(!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            if(password_verify($password,$passwordHash)){

                //tạo token login
                $tokenLogin = sha1(uniqid().time());
                $userId = $userQuery['id'];
                //insert vào bảng loginToken
                $dataInsert = [
                    'user_Id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $insertStatus = insert('tokenlogin',$dataInsert);
                if($insertStatus){
                    //Insert vào bảng thành công

                    //lưu tokenlogin vào session
                    setSession('loginToken',$tokenLogin);

                   redirect('?module=home&action=dashboard');
                } else {
                    setFlashData('msg','đăng nhập thất bại,vui lòng thử lại sau');
                    setFlashData('msg_type','danger');
                }


            } else {
                setFlashData('msg','sai mật khẩu');
                setFlashData('msg_type','danger');
            }
        } else {
            setFlashData('msg','Email không tồn tại');
            setFlashData('msg_type','danger');
        }


    } else {
        setFlashData('msg','chưa nhập dữ liệu');
        setFlashData('msg_type','danger');
    }
    redirect('?module=auth&action=login');

}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');



?>
<div class="row">
    <div class="col-4" style="margin: 50px auto">
        <h2 class="text-center text-uppercase">Đăng nhập quản lý Users</h2>
        <?php
            getSmg($msg,$msgType);
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="btn-form btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center">
                <a href="?module=auth&action=forgot">Quên mật khẩu</a>
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