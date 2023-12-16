<?php

include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
if(isset($_POST['submit'])){
   $msg= 'verification code';
  
   $token_verification = createToken(6);
  
   
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

   $mail = new PHPMailer(true);
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = true;
   $mail->Username = 'myreservations01@gmail.com';
   $mail->Password = 'qxofafecsgeguypg';
   $mail->SMTPSecure = 'ssl';
   $mail->Port = 465;
   $mail->setFrom('myreservations01@gmail.com');
   $mail->addAddress($email);
   $mail->isHTML (true);
   $mail->Subject = $msg;
   $mail->Body = getBody($token_verification);
   



   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed1');

   if(mysqli_num_rows($select) > 0){
      $message[] = 'user already exist!';
   }
   elseif($cpass==$pass){
      mysqli_query($conn, "INSERT INTO `users`(name, email, password ,token) VALUES('$name', '$email', '$pass' ,'$token_verification')") or die('query failed2');
      $message[] = 'registered successfully!';
      $mail->send();
      header('location:login.php');
   }
   else{
      $message[] = 'password is not identical';
   }
}
function getBody($token) {
   return "
   <h1 style='text-align: center;
   witdh:100%;'>welcome</h1>
  <p>$token</p>
  ";
 }
 function createToken($length) {
   $char_string = '0123456789abcdefghijklmnopqrstuvwxyz';
   $token_verification = '';
   while (strlen($token_verification) < $length) {
   $token_verification.= $char_string[random_int(0, strlen($char_string))];
   } 
   return $token_verification;
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      input{
         text-align: center;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>انشاء حساب جديد</h3>
      <input type="text" name="name" required placeholder="اسم السمتخدم" class="box">
      <input type="email" name="email" required placeholder="البريد الالكتروني" class="box">
      <input type="password" name="password" required placeholder="كلمة المرور" class="box">
      <input type="password" name="cpassword" required placeholder="تأكيد كلمة المرور" class="box">
      <input type="submit" name="submit" class="btn" value="تسجيل حساب">
      <p>هل لديك حساب؟ <a href="login.php"> تسجيل دخول</a></p>
   </form>

</div>

</body>
</html>