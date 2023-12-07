<?php

include('config.php');
include('index.php');
session_start();
$admin_id = $_SESSION['admin_id'];

if(isset($_POST['upload'])){
    $NAME  = $_POST['name'];
    $PRICE = $_POST['price'];
    
    $IMAGE = $_FILES['image'];
    
    if(in_array($_FILES["image"]["type"],["image/png"]) ){
    $image_name = $admin_id.$NAME.".png";
    }
    elseif( in_array($_FILES["image"]["type"],["image/jpeg"])) {
        $image_name = $admin_id.$NAME.".jpg";
    }
    else {
        die("not image");
    };
    $image_location = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_location,'images/'. $image_name);
    $image_up = "http://localhost/project/My_Reservations/admin/admin/images/".$image_name;
    $insert = "INSERT INTO  list (name,r_id, price ,image) VALUES ('$NAME','$admin_id','$PRICE','$image_up')";
    echo" $image_name";
    mysqli_query($conn, $insert);
    header('location: index.php');
}
?>