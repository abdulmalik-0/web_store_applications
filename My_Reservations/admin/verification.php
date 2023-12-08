<?php

include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(isset($_POST['submit'])){
    $verifi= mysqli_query($conn, "SELECT token FROM `admin` WHERE id = '$admin_id'") or die('query failed');
    $select = mysqli_query($conn, "SELECT * FROM `admin` WHERE id = '$admin_id'") or die('query failed');
 
    if(mysqli_num_rows($select) > 0){
       $row = mysqli_fetch_assoc($select);
       $_SESSION['admin_id'] = $row['id'];
       if($row['token'] = 'code'){
        $veri = mysqli_query($conn, "UPDATE `admin` SET `token`= null WHERE id = '$admin_id'") or die('query failed');
        header('location:index.php');
       }
       else{
        $message[] = 'incorrect code';
       }

    }else{
       $message[] = 'incorrect code';
    }
 
 }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

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
      <h3>التحقق</h3>
      <input type="text" name="code" required placeholder="رمز التحقق" class="box">
      <input type="submit" name="submit" class="btn" value="تاكيد">
   </form>

</div>

</body>
</html>