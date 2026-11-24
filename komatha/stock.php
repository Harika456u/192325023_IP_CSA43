<?php
require "db.php";

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

$msg = "";

if(isset($_POST['add'])){

    $id = (int)$_POST['product'];
    $qty = (float)$_POST['quantity'];

    if($qty > 0){

        $conn->begin_transaction();

        try{
            $conn->query("INSERT INTO stock
            (product_id,quantity,entry_date)
            VALUES($id,$qty,CURDATE())");

            $conn->query("UPDATE products
            SET quantity=quantity+$qty WHERE id=$id");

            $conn->commit();
            $msg = "Stock Added Successfully!";
        }catch(Throwable $ex){
            $conn->rollback();
            $msg = "Unable to add stock!";
        }
    }
}

$result = $conn->query("SELECT * FROM products");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Stock Entry</h1></header>

<form method="post">

    <select name="product" required>

    <?php while($r = $result->fetch_assoc()){ ?>

        <option value="<?= $r['id'] ?>">
            <?= e($r['name']) ?> - <?= e($r['unit']) ?>
        </option>

    <?php } ?>

    </select>

    <input type="number" name="quantity"
           placeholder="Quantity" min="0.01"
           step="0.01" required>

    <button name="add">Add Stock</button>

    <p><?= e($msg) ?></p>

    <a href="dashboard.php">Back to Dashboard</a>

</form>