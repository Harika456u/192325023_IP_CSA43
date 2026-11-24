<?php
require "db.php";
$result = $conn->query("SELECT * FROM artworks");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Artwork Catalogue</h1></header>

<table>
<tr>
    <th>ID</th>
    <th>Artwork</th>
    <th>Description</th>
    <th>Release Date</th>
    <th>Price</th>
</tr>

<?php while($r = $result->fetch_assoc()){ ?>

<tr>
    <td><?= $r['id'] ?></td>
    <td><?= e($r['title']) ?></td>
    <td><?= e($r['description']) ?></td>
    <td><?= e($r['release_date']) ?></td>
    <td>₹<?= e($r['price']) ?></td>
</tr>

<?php } ?>

</table>

<a href="index.php">Back to Home</a>