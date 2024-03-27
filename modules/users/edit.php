<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if (!defined('_CODE')) {
    die('Access denide...');
}


$fillterAll = filter();
if (!empty($fillterAll['id'])) {
    $userId = $fillterAll['id'];

    //kt xem userId có tồn tại trong db hay không
    //nếu tồn tại thì lấy ra thông tin
    //nếu không tồn tại => chuyển hướng về trang list

    $userDetail = oneRaw("SELECT * FROM users WHERE id = '$userId'");
    if (!empty($userDetail)) {
        //tồn tại

        setFlashData('userDetail', $userDetail);
    } else {
        redirect('?module=users&action=list');
    }
}

if (isPost()) {
    $fillterAll = filter();
    $errors = []; // mảng chứ lỗi

    //validate fullname: bắt buộc phải nhập, min 5 ký tự
    if (empty($fillterAll['fullname'])) {
        $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen($fillterAll['fullname']) <= 5) {
            $errors['fullname']['min'] = 'Họ tên phải lớn hơn 5 kí tự';
        }
    }

    //Email Validate: bắt buộc phải nhập, đúng định dạng email, kiểm tra email đã tồn tại trong csdl chưa
    if (empty($fillterAll['email'])) {
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    } else {
        $email = $fillterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email' AND id <> $userId";
        if (getRows($sql) > 0) {
            $errors['email']['unique'] = 'Email đã tồn tại';
        }
    }

    // Validate Sđt: bắt buộc nhập, có đúng định dạng không
    if (empty(($fillterAll['phone']))) {
        $errors['phone']['required'] = 'số điện thoai bắt buộc phải nhập';
    } else {
        if (!isPhone($fillterAll['phone'])) {
            $errors['phone']['isPhone'] = 'số điện thoai không hợp lệ';
        }
    }


    if (!empty($fillterAll['password'])) {
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
    }


    if (empty($errors)) {
        $dataUpdate = [
            'fullname' => $fillterAll['fullname'],
            'email' => $fillterAll['email'],
            'phone' => $fillterAll['phone'],
            'status' => $fillterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($fillterAll['password'])) {
            $dataUpdate['password'] = password_hash($fillterAll['password'], PASSWORD_DEFAULT);
        }

        $condition = "id = $userId";
        $UpdateStatus = update('users', $dataUpdate, $condition);
        if ($UpdateStatus) {
            setFlashData('smg', 'Sửa người dùng thành công');
            setFlashData('smg_type', 'success');
        } else {
            setFlashData('smg', 'Thêm người dùng thất bại');
            setFlashData('smg_type', 'danger');
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $fillterAll);
    }
    redirect('?module=users&action=edit&id='.$userId);
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$userDetail = getFlashData(('userDetail'));
if (!empty($userDetail)) {
    $old = $userDetail;
}


$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];

layouts('header-login', $data);



?>

<div class="container">
    <div class="row" style="margin: 50px auto">
        <h2 class="text-center text-uppercase">Update tin tài khoản</h2>
        <?php
        getSmg($smg, $smg_type);
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Họ tên</label>
                        <input name="fullname" type="fullname" class="form-control" placeholder="fullname" value="<?php
                                                                                                                    echo old('fullname', $old) ?>">
                        <?php
                        echo form_error('fullname', '<span class="error">', '</span> ', $errors);
                        ?> <?php //reset() là lấy pt đầu tiên trong mảng
                            ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="
                        <?php
                        echo old("email", $old) ?>">
                        <?php
                        echo form_error('email', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số điện thoại</label>
                        <input name="phone" type="number" class="form-control" placeholder="Số điện thoại" value='<?php
                                                                                                                    echo old("phone", $old) ?>'>
                        <?php
                        echo form_error('phone', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu(không nhập nếu không thay đổi)">
                        <?php
                        echo form_error('password', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại password</label>
                        <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại password(không nhập nếu không thay đổi)">
                        <?php
                        echo form_error('password_confirm', '<span class="error">', '</span> ', $errors);
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (old('status', $old) == 0) ? 'selected' : false ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (old('status', $old) == 1) ? 'selected' : false ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>


            <input type="hidden" name="id" value="<?php echo $userId ?>">
            <button type="submit" class="mg-btn btn btn-primary btn-block">Update</button>
            <a href="?module=users&action=list" class="mg-btn btn btn-success btn-block">Quay lại</a>

            <hr>
        </form>
    </div>
</div>

<?php
layouts('footer-login');
