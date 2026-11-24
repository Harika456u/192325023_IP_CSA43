<?php
require "db.php";

foreach ([
    ["Admin","admin@traffic.com","admin123","admin"],
    ["Police","police@traffic.com","police123","police"]
] as $a) {
    $p = password_hash($a[2], PASSWORD_DEFAULT);
    $s = $conn->prepare("INSERT IGNORE INTO users
    (name,email,password,role) VALUES (?,?,?,?)");
    $s->bind_param("ssss",$a[0],$a[1],$p,$a[3]);
    $s->execute();
}

if(isset($_POST['login'])){
    $s = $conn->prepare("SELECT * FROM users WHERE email=?");
    $s->bind_param("s",$_POST['email']);
    $s->execute();
    $u = $s->get_result()->fetch_assoc();

    if($u && password_verify($_POST['password'],$u['password'])){
        session_regenerate_id(true);
        $_SESSION['user']=$u;
        header("Location: dashboard.php");
        exit;
    }
    echo "Invalid login";
}
?>

<link rel="stylesheet" href="style.css">

<h1>Traffic Squad Management System</h1>

<form method="post">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
    <p>New User? <a href="register.php">Register</a></p>
</form>