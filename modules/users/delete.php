<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

//kiểm tra id trong db có tồn tại hay không -> tồn tại -> xóa
//kiểm tra dữ liệu bảng token -> xóa dữ liệu bảng user
$fillterAll = filter();
if(!empty($fillterAll['id'])) {
    $userId = $fillterAll['id'];
    $userDetail = getRaw("SELECT * FROM users WHERE id = $userId");
    if($userDetail > 0) {
        //có dữ liệu
        $deleteToken = delete('tokenlogin',"user_Id = $userId");
        if($deleteToken) {
            //xóa user
            $deleteUser = delete('users',"id = $userId");
            if($deleteUser){
                setFlashData('msg','Xóa thành công');
                setFlashData('msg_type','success');
            } else {
                setFlashData('msg','Lỗi hệ thống');
                setFlashData('msg_type','danger');
            }
        }
    } else {
        setFlashData('msg','Người dùng không tồn tại trong hệ thống');
        setFlashData('msg_type','danger');
    }


} else {
    setFlashData('msg','Liên kết không tồn tại');
    setFlashData('msg_type','danger');

}
redirect('?module=users&action=list');


?>