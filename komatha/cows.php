<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['save'])){

    $s = $conn->prepare("INSERT INTO cows
    (vendor_id,cow_number,breed,age,milk_per_day,health_status)
    VALUES(?,?,?,?,?,?)");

    $s->bind_param("issids",$_POST['vendor'],
    $_POST['number'],$_POST['breed'],$_POST['age'],
    $_POST['milk'],$_POST['health']);

    $s->execute();

    echo "Cow Details Added Successfully!";
}

$vendors = $conn->query("SELECT * FROM vendors");

$result = $conn->query("SELECT c.cow_number,c.breed,
c.age,c.milk_per_day,c.health_status,v.name
FROM cows c JOIN vendors v ON c.vendor_id=v.id");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Cow Detail Maintenance</h1></header>

<form method="post">

    <select name="vendor" required>

        <option value="">Select Vendor</option>

        <?php while($v = $vendors->fetch_assoc()){ ?>

        <option value="<?= $v['id'] ?>">
            <?= e($v['name']) ?>
        </option>

        <?php } ?>

    </select>

    <input name="number" placeholder="Cow Identification Number" required>

    <input name="breed" placeholder="Cow Breed" required>

    <input type="number" name="age"
           placeholder="Age" min="0" required>

    <input type="number" name="milk"
           placeholder="Milk per Day in Litres"
           min="0" step="0.01" required>

    <select name="health">
        <option>Healthy</option>
        <option>Under Treatment</option>
        <option>Under Observation</option>
    </select>

    <button name="save">Save Cow Details</button>

</form>

<table>

<tr>
    <th>Cow Number</th>
    <th>Breed</th>
    <th>Age</th>
    <th>Milk per Day</th>
    <th>Health Status</th>
    <th>Vendor</th>
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