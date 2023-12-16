<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
};

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if (isset($_POST['update_cart'])) {
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'تم تحديث كمية سلة التسوق بنجاح!';
}

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}

if (isset($_GET['conform'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $product_restaurant = $_POST['product_restaurant'];

   $select_order = mysqli_query($conn, "SELECT * FROM `order` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($select_order) > 0) {
      $message[] = 'المنتج أضيف بالفعل إلى العربة!';
   } else {
      mysqli_query($conn, "INSERT INTO `order`(r_id,user_id, name, price, image, quantity) VALUES('$product_restaurant','$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'تم اضافة المنتج  !';
   }
   header('location:index.php');
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>المطاعم</title>


   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
      }
   }
   ?>

   <div class="container">

      <div class="user-profile">

         <?php
         $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user);
         };
         ?>

         <p>المستخدم الحالي : <span><?php echo $fetch_user['name']; ?></span> </p>
         <div class="flex">
            <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');" class="delete-btn">تسجيل الخروج</a>
         </div>

      </div>

      <div class="products">

         <h1 class="heading">المطاعم</h1>

         <div class="box-container">

            <?php
            include('config.php');
            $result = mysqli_query($conn, "SELECT * FROM admin");
            while ($row = mysqli_fetch_array($result)) {

            ?>
               <form method="get" class="box" action="cart.php">
                  <div class="name"><?php echo $row['r_name']; ?></div>
                  <input type="hidden" name="r_name" value="<?php echo $row['r_name']; ?>">
                  <input type="hidden" name="r_id" value="<?php echo $row['id']; ?>">
                  <input type="submit" value=" select" name="select_r" class="btn">
               </form>
            <?php
            };
            ?>

         </div>

      </div>


   </div>

</body>

</html>