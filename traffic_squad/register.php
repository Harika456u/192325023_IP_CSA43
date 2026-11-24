<?php
require "db.php";

if(isset($_POST['register'])){
    $p = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $s = $conn->prepare("INSERT IGNORE INTO users
    (name,email,password,role) VALUES (?,?,?,'user')");

    $s->bind_param("sss",$_POST['name'],$_POST['email'],$p);
    $s->execute();

    if($s->affected_rows > 0){
        header("Location: index.php");
        exit;
    }
    echo "Email already registered";
}
?>

<link rel="stylesheet" href="style.css">

<h1>User Registration</h1>

<form method="post">
    <input name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="register">Register</button>
    <p><a href="index.php">Back to Login</a></p>
</form>