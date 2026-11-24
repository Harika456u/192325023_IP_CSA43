<?php
require "db.php";

if(isset($_POST['buy'])){
    $s = $conn->prepare("INSERT INTO orders
    (customer,email,artwork,amount)
    SELECT ?,?,title,price FROM artworks WHERE id=?");

    $s->bind_param("ssi",$_POST['name'],
        $_POST['email'],$_POST['artwork_id']);

    $s->execute();

    echo $s->affected_rows > 0
        ? "Order Placed Successfully!"
        : "Invalid Artwork";
}

$result = $conn->query("SELECT * FROM artworks");
?>

<link rel="stylesheet" href="style.css">

<header><h1>ArtistryHub Store</h1></header>

<div class="gallery">

<?php while($r = $result->fetch_assoc()){ ?>

<div class="card">
    <img src="images/<?= e($r['image']) ?>">

    <h2><?= e($r['title']) ?></h2>

    <p>Price: ₹<?= e($r['price']) ?></p>

    <form method="post">
        <input type="hidden" name="artwork_id"
               value="<?= $r['id'] ?>">

        <input name="name" placeholder="Your Name" required>

        <input type="email" name="email"
               placeholder="Email" required>

        <button name="buy">Buy Print</button>
    </form>
</div>

<?php } ?>

</div>

<a href="index.php">Back to Home</a>