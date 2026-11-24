<?php
require "db.php";

$role = $_GET['role'] ?? 'author';
$message = "";

if(!in_array($role,['author','reviewer','editor'])){
    $role = 'author';
}

if(isset($_POST['register'])){

    $password = password_hash(
        $_POST['password'], PASSWORD_DEFAULT
    );

    $approved = ($role == 'author') ? 1 : 0;

    $s = $conn->prepare("INSERT IGNORE INTO users
    (name,email,password,institution,role,approved)
    VALUES(?,?,?,?,?,?)");

    $s->bind_param("sssssi",
        $_POST['name'],
        $_POST['email'],
        $password,
        $_POST['institution'],
        $role,
        $approved
    );

    $s->execute();

    if($s->affected_rows > 0){
        $message = $approved
        ? "Registration Successful! Please Login."
        : "Registration Successful! Awaiting Approval.";
    }else{
        $message = "Email already registered!";
    }
}
?>

<link rel="stylesheet" href="style.css">

<header>
    <h1><?= ucfirst($role) ?> Registration</h1>
</header>

<form method="post">

    <input name="name" placeholder="Full Name" required>

    <input type="email" name="email"
           placeholder="Email Address" required>

    <input name="institution"
           placeholder="Institution Name" required>

    <input type="password" name="password"
           placeholder="Password" minlength="6" required>

    <button name="register">Register</button>

    <p><?= e($message) ?></p>

    <a href="login.php">Login</a>
    |
    <a href="index.php">Home</a>

</form>