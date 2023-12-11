<?php

include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($admin_id);
   session_destroy();
   header('location:login.php');
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>صفحتي</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">
<center>
<div class="admin-profile">

   <?php
      $select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE id = '$admin_id'") or die('query failed2');
      if(mysqli_num_rows($select_admin) > 0){
         $fetch_admin = mysqli_fetch_assoc($select_admin);
      };
   ?>

   <p>المستخدم الحالي : <span><?php echo $fetch_admin['r_name']; ?></span> </p>
   <div class="flex">
         <a href='admin/list.php? id=$row[id]' class='btn btn-primary'>منتجاتي</a>
         <a href='admin/index.php? id=$row[id]' class='btn btn-primary'>اضافة منتجات</a>
      <a href="index.php?logout=<?php echo $admin_id; ?>" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');" class="delete-btn">تسجيل الخروج</a>
  
</center>

   <?php
   include('config.php');
   $result = mysqli_query($conn, "SELECT * FROM list");      
   while($row = mysqli_fetch_array($result)){
   ?>

   <?php
      };
   ?>

   </div>

</div>
</div>

</body>
</html>