<?php
require "db.php";
$result = $conn->query("SELECT * FROM products");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Our Dairy Products</h1></header>

<div class="container">

<?php while($r = $result->fetch_assoc()){ ?>

<div class="card">

    <img src="images/<?= e($r['image']) ?>">

    <h2><?= e($r['name']) ?></h2>

    <p><?= e($r['description']) ?></p>

    <p>Price: ₹<?= e($r['price']) ?> / <?= e($r['unit']) ?></p>

    <p>Available Stock: <?= e($r['quantity']) ?></p>

    <a class="btn" href="orders.php?product=<?= $r['id'] ?>">
        Buy Now
    </a>

</div>

<?php } ?>

</div>

<a href="index.php">Back to Home</a>