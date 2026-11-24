<?php
require "db.php";
$result = $conn->query("SELECT * FROM artworks");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Art Portfolio</h1></header>

<div class="gallery">

<?php while($r = $result->fetch_assoc()){ ?>

<div class="card">
    <img src="images/<?= e($r['image']) ?>"
         onclick="showImage(this.src)">

    <h2><?= e($r['title']) ?></h2>

    <p><?= e($r['description']) ?></p>
</div>

<?php } ?>

</div>

<div id="preview" onclick="this.style.display='none'">
    <img id="largeImage">
</div>

<a href="index.php">Back to Home</a>

<script src="script.js"></script>