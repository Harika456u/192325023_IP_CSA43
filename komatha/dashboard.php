<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="style.css">

<header>
    <h1>Administrator Dashboard</h1>
    <p>Welcome, <?= e($_SESSION['admin']) ?></p>
</header>

<div class="container">

<?php
foreach(['products','orders','vendors','cows'] as $table){
    $r = $conn->query("SELECT COUNT(*) AS total FROM $table");
    $row = $r->fetch_assoc();
?>

<div class="card">
    <h2><?= ucfirst($table) ?></h2>
    <h1><?= $row['total'] ?></h1>
</div>

<?php } ?>

</div>

<div class="panel">

    <a class="btn" href="stock.php">Stock Entry</a>
    <a class="btn" href="reports.php?type=daily">Daily Report</a>
    <a class="btn" href="reports.php?type=monthly">Monthly Report</a>
    <a class="btn" href="reports.php?type=yearly">Yearly Report</a>
    <a class="btn" href="customers.php">Customer Purchases</a>
    <a class="btn" href="vendors.php">Milk Vendors</a>
    <a class="btn" href="cows.php">Cow Details</a>
    <a class="btn" href="logout.php">Logout</a>

</div>