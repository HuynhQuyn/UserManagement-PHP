<?php


// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}
    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function layouts($layout='header',$data=[]) {
    if(file_exists(_WEB_PATH_TEMPLATES.'/layout/'.$layout.'.php')) {
        require_once _WEB_PATH_TEMPLATES.'/layout/'.$layout.'.php';
    }
}

//ham gui mail
function senMail($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'leomessi81101@gmail.com';                     //SMTP username
        $mail->Password   = 'kpenumfimhtzfjgw';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('leomessi81101@gmail.com', 'Messi Leo');
        $mail->addAddress($to);     //Add a recipient
        
        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        
        //PHPMailer SSL certificate verity failed
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
            );


        $senMail =  $mail->send();
        if($senMail) {
            // echo 'Gửi thành công';
            return true;
        }

    } catch (Exception $e) {
        echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}

//kiem tra phuong thuc GET
function isGet() {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

//kiem tra phuong thuc POST
function isPost() {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}


//ham filer loc du lieu
function filter() {
    $filterArr = [];
    if(isGet()){
        //xử lý dữ liệu trước khi hiển thị ra
        //return GET;
        if(!empty($_GET)){
            foreach($_GET as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);

                } else {
                    $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
                  
                }
            }
        }
    }
    if(isPost()){
        if(!empty($_POST)){
            foreach($_POST as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
                }        
            }
        }
    }
    return $filterArr;
}

//hàm kiểm tra email
function isEmail($email){
    $checkEmail = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

//hàm kiểm tra số nguyên INT
function isNumberInt($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_INT);
    return $checkNumber;
}

//hàm kiểm tra số thực float
function isNumberFloat($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

//hàm kiểm tra phone
function isPhone($phone) {
    $check = false;
    //kiểm tra có phải số 0 đầu tiên không
    if($phone[0] == 0){
        $check = true;
        $phone = substr($phone,1);
    }
    //đk2: đằng sau có 9 số nguyên
    $checkNumber = false;
    if(isNumberInt($phone) && strlen($phone) == 9) {
        $checkNumber = true;
    }

    if($check && $checkNumber) {
        return true;
    }
}

//hàm thông báo lỗi khi dữ liệu sai
function getSmg($smg,$type='success') {
    echo '<div class="alert alert-'.$type.'">';
    echo $smg;
    echo '</div>';
}

//hàm chuyển hướng
function redirect($path=''){
    header("location: $path");
    exit;
}

//hàm thông báo lỗi validate
function form_error($fillname,$beforeHtml='',$afterHtml='',$errors) {
    return (!empty($errors[$fillname])) ? $beforeHtml.reset($errors[$fillname]).$afterHtml : null ;
}

//hàm hiển thị dữ liệu cũ
function old($fillname,$old,$default = null)
{
    return !empty($old) ? $old[$fillname]  : $default ;
}

//hàm kt trạng thái đăng nhập
function isLogin() {
    $checkLogin = false;
    if(getSession('loginToken')){
        $tokenLogin = getSession('loginToken');
        //kiểm tra token có giống trong db ko
        $queryToken = oneRaw("SELECT user_Id FROM tokenlogin WHERE token = '$tokenLogin'");
        if(!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('loginToken');
        }
    }
    return $checkLogin;
}