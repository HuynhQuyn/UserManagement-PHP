<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Quản lý người dùng' ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES;?>/css/style.css?ver=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    
</body>
</html>