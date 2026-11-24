<?php
require "db.php";

if(isset($_POST['send'])){
    $s = $conn->prepare("INSERT INTO messages
    (name,email,message) VALUES(?,?,?)");

    $s->bind_param("sss",$_POST['name'],
        $_POST['email'],$_POST['message']);

    $s->execute();

    echo "Message Sent Successfully!";
}
?>

<link rel="stylesheet" href="style.css">

<header><h1>Contact the Artist</h1></header>

<form method="post">
    <h2>Booking and Fan Messages</h2>

    <input name="name" placeholder="Your Name" required>

    <input type="email" name="email"
           placeholder="Your Email" required>

    <textarea name="message"
              placeholder="Enter your message" required></textarea>

    <button name="send">Send Message</button>
</form>

<a href="index.php">Back to Home</a>