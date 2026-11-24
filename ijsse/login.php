<?php
require "db.php";

$message = "";

if(isset($_POST['login'])){

    $s = $conn->prepare(
        "SELECT * FROM users WHERE email=?"
    );

    $s->bind_param("s",$_POST['email']);
    $s->execute();

    $u = $s->get_result()->fetch_assoc();

    if($u && password_verify(
        $_POST['password'],$u['password'])){

        if(!$u['approved']){
            $message = "Account awaiting approval!";
        }else{
            session_regenerate_id(true);

            $_SESSION['id'] = $u['id'];
            $_SESSION['name'] = $u['name'];
            $_SESSION['role'] = $u['role'];

            header("Location: dashboard.php");
            exit;
        }

    }else{
        $message = "Invalid Email or Password";
    }
}
?>

<link rel="stylesheet" href="style.css">

<header>
    <h1>IJSSE Login</h1>
</header>

<form method="post">

    <h2>Journal Login</h2>

    <input type="email" name="email"
           placeholder="Email" required>

    <input type="password" name="password"
           placeholder="Password" required>

    <button name="login">Login</button>

    <p><?= e($message) ?></p>

    <a href="index.php">Back to Home</a>

</form>