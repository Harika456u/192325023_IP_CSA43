<?php
require "db.php";
$message = "";

if(isset($_POST['register'])){

    $s = $conn->prepare("INSERT IGNORE INTO registrations
    (name,email,phone,department) VALUES(?,?,?,?)");

    $s->bind_param("ssss",$_POST['name'],
    $_POST['email'],$_POST['phone'],$_POST['department']);

    $s->execute();

    $message = $s->affected_rows > 0
        ? "Registration Successful!"
        : "Email already registered!";
}
?>

<link rel="stylesheet" href="style.css">

<header>
    <h1>Event Registration</h1>
</header>

<form method="post">

    <h2>Register for Tech Summit</h2>

    <input name="name" placeholder="Full Name" required>

    <input type="email" name="email"
           placeholder="Email Address" required>

    <input type="tel" name="phone"
           placeholder="Phone Number"
           pattern="[0-9]{10}" required>

    <select name="department" required>
        <option value="">Select Department</option>
        <option>AIML</option>
        <option>CSE</option>
        <option>IT</option>
        <option>ECE</option>
        <option>Others</option>
    </select>

    <button name="register">Register</button>

    <p style="color:green"><?= $message ?></p>

    <a href="index.php">Back to Home</a>

</form>