<?php
require "db.php";
$msg = "";

if(isset($_POST['register'])){

    $p = password_hash($_POST['password'],PASSWORD_DEFAULT);

    $s = $conn->prepare("INSERT IGNORE INTO admins
    (name,email,password) VALUES(?,?,?)");

    $s->bind_param("sss",$_POST['name'],$_POST['email'],$p);
    $s->execute();

    $msg = $s->affected_rows > 0
        ? "Registration Successful!"
        : "Email already registered!";
}
?>

<link rel="stylesheet" href="style.css">

<header><h1>Admin Registration</h1></header>

<form method="post">

    <input name="name" placeholder="Full Name" required>

    <input type="email" name="email"
           placeholder="Email" required>

    <input type="password" name="password"
           placeholder="Password" minlength="6" required>

    <button name="register">Register</button>

    <p><?= e($msg) ?></p>

    <a href="login.php">Login</a>
</form>