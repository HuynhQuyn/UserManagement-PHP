<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

if(!isLogin()) {
    redirect('?module=auth&action=login');
}

$data =[
    'pageTitle' => 'Danh sách tài khoản'
];

layouts('header',$data);

//truy vấn bảng users
$listUsers = getRaw("SELECT * FROM users ORDER BY update_at");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
// $errors = getFlashData('errors');
// $old = getFlashData('old');

?>
<div class="container">
    <hr>
    <h2>Quản lý người dùng</h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm">thêm người dùng <i class="fa-solid fa-plus"></i></a>
    </p>
    <?php
        if(!empty($msg)) {

            getSmg($msg, $msg_type);
        }
        ?>
    <table class="table table-bordered">
        <thead>
            <th>STT</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th with = "5%">Sửa</th>
            <th with = "5%">Xóa</th>
        </thead>
        <tbody>
            <?php
                if(!empty($listUsers)):
                    $count = 0;
                    foreach($listUsers as $item):
                        $count++;
                
            ?>
            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $item['fullname'] ?></td>
                <td><?php echo $item['email'] ?></td>
                <td><?php echo $item['phone'] ?></td>
                <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>' ?></td>
                <td><a href="<?php echo _WEB_HOST; ?> ?module=users&action=edit&id=<?php echo $item['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                <td><a href="<?php echo _WEB_HOST; ?> ?module=users&action=delete&id=<?php echo $item['id'] ?>" onclick="return confirm('bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
            <?php
                    endforeach;
                else:
            ?>
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger text-center">>Không có người dùng nào!!</div>
                </td>
            </tr>
            <?php
            endif;

            ?>
        </tbody>
    </table>
</div>



<?php
layouts('footer');