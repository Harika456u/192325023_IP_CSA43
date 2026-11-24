<?php
require "db.php";

if(isset($_POST['subscribe'])){
    $s = $conn->prepare(
        "INSERT IGNORE INTO subscribers(email) VALUES(?)"
    );
    $s->bind_param("s",$_POST['email']);
    $s->execute();

    echo $s->affected_rows > 0
        ? "Subscribed Successfully!"
        : "Already Subscribed!";
}

$result = $conn->query("SELECT * FROM news");
?>

<link rel="stylesheet" href="style.css">

<header><h1>News and Upcoming Events</h1></header>

<?php while($r = $result->fetch_assoc()){ ?>

<div class="panel">
    <h2><?= e($r['title']) ?></h2>
    <p><?= e($r['description']) ?></p>
    <p>Date: <?= e($r['event_date']) ?></p>
</div>

<?php } ?>

<form method="post">
    <h2>Subscribe to Newsletter</h2>

    <input type="email" name="email"
           placeholder="Enter Email" required>

    <button name="subscribe">Subscribe</button>
</form>

<a href="index.php">Back to Home</a>