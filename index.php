<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
if(isset($_POST['name']));
include_once "config.php";
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if($name == "" || $email == "" || $password == ""){
    echo "not connected";
}else{
    $sql = "INSERT INTO `login`(`name`, `email`, `password`) VALUES ( '$name', '$email', '$password')";
    $result = mysqli_query($conn, $sql);
}
}
?>
<!-- html start -->
 
    <div class="container">
    <div class="form" method="">
        <form action="" method="POST">
        <label for="Name">name</label>
        <input type="text" id="name" placeholder="Enter name" name="name" required>
        </div>
    <div class="form">
        <label for="Name">Email</label>
        <input type="text" id="email" placeholder="Enter Email" name="email">
        </div>
    <div class="form">
        <label for="Name">Password</label>
        <input type="text" id="password" placeholder="Enter password" name="password">
        </div>
   
        <button type="submit">login</button>
        </form>
        <a href="user.php"><button type="submit">Already user</button></a>
    </div>    
</body>
</html>