<!DOCTYPE html>
<html lang="en">
<head>
<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
?>
   <link rel="icon" href="http://localhost/proj/My_Reservations/admin/admin/logo.png" type="image/x-icon">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | المنتجات </title>
    <style>
        h3{
            font-family: 'Cairo', sans-serif;
            font-weight: bold;
        }
        .card{
            float: right;
            margin-top: 20px;
            margin-left: 10px;
            margin-right: 10px;
        }
        .card img{
            width: 100%;
            height: 200px;
        }
        main{
            width: 60%;
        }
    </style>
</head>
<body>
    <center>
        <h3>لوحة تحكم الادمن</h3><br>
        <a href='index.php? id=$row[id]' class='btn btn-primary'>رجوع لصفحة الاضافه</a>
        <a href='http://localhost/proj/My_Reservations/admin/index.php? id=$row[id]' class='btn btn-primary'>رجوع للصفحة الرئيسية</a>
    </center>
    <?php
    include('config.php');
    $result = mysqli_query($conn, "SELECT * FROM list where r_id = $admin_id");
    while($row = mysqli_fetch_array($result)){
        echo "
        <center>
        <main>
            <div class='card' style='width: 15rem;'>
                <img src='$row[image]' class='card-img-top'>
                <div class='card-body'>
                    <h5 class='card-title'>$row[name]</h5>
                    <p class='card-text'>$row[price]</p>
                    <a href='delete.php? id=$row[id]' class='btn btn-danger'>حذف منتج</a>
                    <a href='update.php? id=$row[id]' class='btn btn-primary'>تعديل منتج</a>
                </div>
            </div>
        </main>
        <center>
        ";
    }
    ?>
</body>
</html>