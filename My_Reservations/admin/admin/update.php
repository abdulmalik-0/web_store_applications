<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="icon" href="http://localhost/proj/My_Reservations/admin/admin/logo.svg" type="image/x-icon">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update | تعديل منتج</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php
    include('config.php');
    $ID=$_GET['id'];
    $up = mysqli_query($conn, "select * from list where id =$ID");
    $data = mysqli_fetch_array($up);
    
    ?>
    <center>
        <div class="main">
            <form action="up.php" method="post" enctype="multipart/form-data">
                <h2>تعديل المنتجات</h2>
                <input type="text" name='id' value='<?php echo $data['id']?>'  style='display:none;'>
                <br>
                <input type="text" name='name' value='<?php echo $data['name']?>'>
                <br>
                <input type="text" name='price' value='<?php echo $data['price']?>'>
                <br>
                <input type="file" id="file" name='image' style='display:none;'>
                <label for="file"> تحديث صورة المنتج</label>
                <button name='update' type='submit'>تعديل المنتج</button>
                <br><br>
                <a href="list.php">عرض كل المنتجات</a>
            </form>
        </div>
    </center>
</body>
</html>