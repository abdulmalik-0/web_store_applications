<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
$select_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE id = '$admin_id'") or die('query failed 2');
if(mysqli_num_rows($select_admin) > 0){
   $fetch_admin = mysqli_fetch_assoc($select_admin);
};

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($admin_id);
   session_destroy();
   header('location:http://localhost/project/My_Reservations/admin/login.php');
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اضافة منتجات</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <center>
    <div class="admin-profile">

<p>المستخدم الحالي : <span><?php echo $fetch_admin['r_name']; ?></span> </p>
<div class="flex">
   <a href="index.php?logout=<?php echo $admin_id; ?>" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');" class="delete-btn">تسجيل الخروج</a>
</div>
            <form action="insert.php" method="post" enctype="multipart/form-data">
                <h2>موقع حجوزاتي</h2>
                <div class="main"><img src="logo1.png" alt="logo" width="300px" height="250px"></div>
                <br>
                <input type="text" name='name' placeholder="اسم المنتج">
                <br>
                <input type="number" name='price' placeholder="السعر" >
                <br>
                <input type="file" id="file" name='image' style='display:none;'>
                <label for="file" width: 20%;> اختيار صورة للمنتج</label>
                <button name='upload'>رفع المنتج</button>
                <br><br>
                <a href="list.php">منتجاتي</a>
            </form>
        </div>
    </center>

</body>
</html>
 