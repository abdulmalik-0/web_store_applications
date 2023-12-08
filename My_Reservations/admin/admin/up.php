<?php
include('index.php');

include('config.php');

$admin_id = $_SESSION['admin_id'];

if(isset($_POST['update'])){
    $NAME  = $_POST['name'];
    $PRICE = $_POST['price'];
    $ID= $_POST['id'];
    $quantity = $_POST['quantity'];
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
    $image_up = "http://localhost/proj/My_Reservations/admin/admin/images/".$image_name;
    $update = "UPDATE list SET name='$NAME' , price='$PRICE', image='$image_up' WHERE id=$ID";
    mysqli_query($conn, $update);

    header('location: index.php');
}
?>
