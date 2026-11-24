<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$type = $_GET['type'] ?? 'daily';

if($type == 'monthly'){
    $condition = "YEAR(entry_date)=YEAR(CURDATE())
    AND MONTH(entry_date)=MONTH(CURDATE())";
}elseif($type == 'yearly'){
    $condition = "YEAR(entry_date)=YEAR(CURDATE())";
}else{
    $type = 'daily';
    $condition = "entry_date=CURDATE()";
}

$result = $conn->query("SELECT p.name,p.unit,
SUM(s.quantity) AS total
FROM stock s JOIN products p
ON s.product_id=p.id
WHERE $condition
GROUP BY p.id,p.name,p.unit");
?>

<link rel="stylesheet" href="style.css">

<header>
    <h1><?= ucfirst($type) ?> Stock Report</h1>
</header>

<nav>
    <a href="?type=daily">Daily</a>
    <a href="?type=monthly">Monthly</a>
    <a href="?type=yearly">Yearly</a>
</nav>

<table>

<tr>
    <th>Product</th>
    <th>Stock Received</th>
    <th>Unit</th>
</tr>

<?php while($r = $result->fetch_assoc()){ ?>

<tr>
    <td><?= e($r['name']) ?></td>
    <td><?= e($r['total']) ?></td>
    <td><?= e($r['unit']) ?></td>
</tr>

<?php } ?>

</table>

<a href="dashboard.php">Back to Dashboard</a>