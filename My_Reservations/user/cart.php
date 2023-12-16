<?php

include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();
$user_id = $_SESSION['user_id'];
$r_name = $_GET['r_name'];
$r_id = $_GET['r_id'];
if (!isset($user_id)) {
   header('location:login.php');
};

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
};
if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $product_restaurant = $_POST['product_restaurant'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($select_cart) > 0) {
      $message[] = 'المنتج أضيف بالفعل إلى العربة!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(r_id,user_id, name, price, image, quantity) VALUES('$product_restaurant','$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'تم اضافة المنتج  !';
   }
};
if (isset($_GET['confirm'])) {

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE  user_id = '$user_id' and r_id = '$r_id'") or die('query failed');
   $select_restaurant = mysqli_query($conn, "SELECT * FROM `admin` WHERE  id = '$r_id'") or die('query failed');
   $select_receiver = mysqli_query($conn, "SELECT * FROM `users` WHERE  id = '$user_id'") or die('query failed');
   $receiver_row = mysqli_fetch_array($select_receiver);
   $restaurant_row = mysqli_fetch_array($select_restaurant);


   if (mysqli_num_rows($select_cart) == 0) {
      header('location:index.php');
      return;
   } else {
      $email_body = get_email_body($r_name, $receiver_row['name'], $select_cart);
      send_confirmation_email($receiver_row['email'], $email_body);
      send_confirmation_email($restaurant_row['email'], $email_body);
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $message[] = 'تم تحديث كمية السلة بنجاح!';
   }


   // mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id' And r_id = '$r_id'") or die('query failed');
   // header('location:index.php');
};

if (isset($_POST['update_cart'])) {
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'تم تحديث كمية السلة بنجاح!';
}

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header("location:cart.php?r_name=$r_name&r_id=$r_id");
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header("location:cart.php?r_name=$r_name&r_id=$r_id");
}

function send_confirmation_email($receiverEmail, $body)
{
   $mail = new PHPMailer(true);
   $mail->isSMTP();
   $mail->Host = 'smtp.gmail.com';
   $mail->SMTPAuth = true;
   $mail->Username = 'myreservations01@gmail.com';
   $mail->Password = 'qxofafecsgeguypg';
   $mail->SMTPSecure = 'ssl';
   $mail->Port = 465;
   $mail->setFrom('myreservations01@gmail.com');
   $mail->addAddress($receiverEmail);
   $mail->isHTML(true);
   $mail->Subject = "New Order";
   $mail->Body = $body;

   $mail->send();
}

function get_email_body($restaurant, $receiver, $select)
{
   $rows = "";
   $total = 0;
   while ($row = mysqli_fetch_array($select)) {
      $rows .= get_row($row["name"], $row["price"], $row["quantity"]);
      $total += $row["price"] * $row["quantity"];
   }
   return "
   <div dir='rtl' style='font-size:x-large; '>
       <h3><span style='margin-right: 10px;'>المطعم:</span>$restaurant<span style='margin-right: 10px;'></span></h3>
       <h3><span style='margin-right: 10px;'>المستلم:</span>$receiver<span style='margin-right: 10px;'></span></h3>

   </div>

   <table style='border: 1px solid;width: 100%;border-collapse: collapse;text-align: center; font-size:x-large;'>
       <thead>
           <th style='border: 1px solid;'>الاسم</th>
           <th style='border: 1px solid;'>السعر</th>
           <th style='border: 1px solid;'>العدد</th>
           <th style='border: 1px solid;'>السعر الكلي</th>
       </thead>
       <tbody>

       $rows

           <tr style='border: 1px solid;' class='table-bottom'>
               <td style='border: 1px solid; text-align: right;' dir='rtl' colspan='4'>المبلغ الإجمالي :$total</td>

           </tr>
       </tbody>
   </table>";
}



function get_row($name, $price, $count)
{
   $amount = $price * $count;
   return "   <tr style='border: 1px solid;'>
   <td style='border: 1px solid;'>$name</td>
   <td style='border: 1px solid;'>$price$</td>
   <td style='border: 1px solid;'>
       $count
   </td>
   <td style='border: 1px solid;'>$amount$</td>
</tr>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>العربة</title>


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
            <a href='index.php' class='btn btn-primary'>الرجوع</a>
            <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');" class="delete-btn">تسجيل الخروج</a>
         </div>

      </div>

      <div class="products">

         <h1 class="heading">الطعام</h1>

         <div class="box-container">

            <?php
            include('config.php');
            $result = mysqli_query($conn, "SELECT * FROM list WHERE r_id = '$r_id'");
            while ($row = mysqli_fetch_array($result)) {
            ?>
               <form method="post" class="box" action="">
                  <img src="<?php echo $row['image']; ?>" width="200">
                  <div class="name"><?php echo $row['name']; ?></div>
                  <div class="price"><?php echo $row['price']; ?></div>
                  <input type="number" min="1" name="product_quantity">
                  <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                  <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                  <input type="hidden" name="product_restaurant" value="<?php echo $row['r_id']; ?>">
                  <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               </form>
            <?php
            };
            ?>

         </div>

      </div>

      <div class="shopping-cart">

         <h1 class="heading"> العربة</h1>

         <table dir="rtl" style=" text-align: center; ">
            <thead>
               <th>الصورة</th>
               <th>الاسم</th>
               <th>السعر</th>
               <th>العدد</th>
               <th>السعر الكلي</th>
               <th>العمل</th>
            </thead>
            <tbody>
               <?php
               $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' and r_id = '$r_id'") or die('query failed');
               $grand_total = 0;
               if (mysqli_num_rows($cart_query) > 0) {

                  while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
               ?>
                     <tr>


                        <input type="hidden" name="product_restaurant" value="<?php echo $fetch_cart['r_id']; ?>">
                        <td><input type="image" name="product_image" src="<?php echo $fetch_cart['image']; ?>" height="75" alt=""></td>
                        <td><span name="product_name" value=""><?php echo $fetch_cart['name']; ?> </span></td>
                        <td><span name="product_price" value=""><?php echo $fetch_cart['price']; ?>$ </span></td>
                        <td>
                           <form action="" method="post">
                              <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                              <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                              <input type="submit" name="update_cart" value="تعديل" class="option-btn">
                           </form>
                        </td>
                        <td><?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>$</td>
                        <td><a href="cart.php?remove=<?php echo $fetch_cart['id'] . "&r_name=$r_name&r_id=$r_id"; ?>" class="delete-btn" onclick="return confirm('إزالة العنصر من السلة ');">حذف</a></td>
                     </tr>

               <?php
                     $grand_total += $sub_total;
                  }
               } else {
                  echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">العربة فارغة</td></tr>';
               }
               ?>
               <tr class="table-bottom">
                  <td colspan="4">المبلغ الإجمالي :</td>
                  <td><?php echo $grand_total; ?>$</td>
                  <td>
                     <a href="<?php echo "cart.php?delete_all&r_name=$r_name&r_id=$r_id" ?>" onclick="return confirm('حذف كل المنتجات من العربة؟');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">حذف الكل</a>
                     <a href="<?php echo "cart.php?confirm&r_name=$r_name&r_id=$r_id" ?>" onclick="return confirm('تاكيد الطلب؟');" class="option-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">تاكيد الطلب</a>
                  </td>
               </tr>
            </tbody>
         </table>



      </div>

   </div>

</body>

</html>