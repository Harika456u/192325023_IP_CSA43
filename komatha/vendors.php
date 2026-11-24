<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['save'])){

    $s = $conn->prepare("INSERT INTO vendors
    (name,phone,address,milk_quantity,rate)
    VALUES(?,?,?,?,?)");

    $s->bind_param("sssdd",$_POST['name'],
    $_POST['phone'],$_POST['address'],
    $_POST['milk'],$_POST['rate']);

    $s->execute();

    echo "Vendor Added Successfully!";
}

$result = $conn->query("SELECT * FROM vendors");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Milk Vendor Management</h1></header>

<form method="post">

    <input name="name" placeholder="Vendor Name" required>

    <input name="phone" placeholder="Phone Number" required>

    <textarea name="address" placeholder="Address" required></textarea>

    <input type="number" name="milk" min="0.01"
           step="0.01" placeholder="Milk Quantity in Litres" required>

    <input type="number" name="rate" min="0.01"
           step="0.01" placeholder="Rate per Litre" required>

    <button name="save">Add Vendor</button>

</form>

<table>

<tr>
    <th>ID</th>
    <th>Vendor</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Milk Quantity</th>
    <th>Rate</th>
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