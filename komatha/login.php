<?php
require "db.php";
$msg = "";

if(isset($_POST['login'])){

    $s = $conn->prepare("SELECT * FROM admins WHERE email=?");
    $s->bind_param("s",$_POST['email']);
    $s->execute();

    $a = $s->get_result()->fetch_assoc();

    if($a && password_verify($_POST['password'],$a['password'])){
        session_regenerate_id(true);
        $_SESSION['admin'] = $a['name'];
        header("Location: dashboard.php");
        exit;
    }

    $msg = "Invalid Login!";
}
?>

<link rel="stylesheet" href="style.css">

<header><h1>Administrator Login</h1></header>

<form method="post">

    <input type="email" name="email"
           placeholder="Email" required>

    <input type="password" name="password"
           placeholder="Password" required>

    <button name="login">Login</button>

    <p><?= e($msg) ?></p>

    <a href="register.php">Register Admin</a>
    |
    <a href="index.php">Home</a>

</form>