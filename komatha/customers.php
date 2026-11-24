<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT c.name,c.phone,c.address,
p.name AS product,o.quantity,o.total,
o.delivery_date,o.status
FROM orders o
JOIN customers c ON o.customer_id=c.id
JOIN products p ON o.product_id=p.id");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Customer Purchase Details</h1></header>

<table>

<tr>
    <th>Customer</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Delivery</th>
    <th>Status</th>
</tr>

<?php while($r = $result->fetch_assoc()){ ?>

<tr>
    <?php foreach($r as $value){ ?>
        <td><?= e($value) ?></td>
    <?php } ?>
</tr>

<?php } ?>

</table>

<a href="dashboard.php">Back to Dashboard</a>