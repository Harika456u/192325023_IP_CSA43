<?php
require "db.php";
$msg = "";

if(isset($_POST['order'])){

    $pid = (int)$_POST['product'];
    $qty = (float)$_POST['quantity'];
    $date = $_POST['delivery'];

    $conn->begin_transaction();

    try{

        $p = $conn->query("SELECT * FROM products
        WHERE id=$pid FOR UPDATE")->fetch_assoc();

        if(!$p || $qty <= 0 || $qty > $p['quantity']){
            throw new Exception("Insufficient Stock!");
        }

        if($date < date('Y-m-d')){
            throw new Exception("Invalid Delivery Date!");
        }

        $total = $qty * $p['price'];

        $s = $conn->prepare("INSERT INTO customers
        (name,email,phone,address) VALUES(?,?,?,?)");

        $s->bind_param("ssss",$_POST['name'],
        $_POST['email'],$_POST['phone'],$_POST['address']);

        $s->execute();
        $cid = $conn->insert_id;

        $s = $conn->prepare("INSERT INTO orders
        (customer_id,product_id,quantity,total,
        order_date,delivery_date)
        VALUES(?,?,?,?,CURDATE(),?)");

        $s->bind_param("iidds",$cid,$pid,$qty,$total,$date);
        $s->execute();

        $conn->query("UPDATE products
        SET quantity=quantity-$qty WHERE id=$pid");

        $conn->commit();

        $msg = "Order Successful! Total Amount: ₹".$total;

    }catch(Throwable $ex){
        $conn->rollback();
        $msg = "Order failed. Check stock and delivery date.";
    }
}

$result = $conn->query("SELECT * FROM products");
?>

<link rel="stylesheet" href="style.css">

<header><h1>Order Dairy Products Online</h1></header>

<form method="post">

    <input name="name" placeholder="Customer Name" required>

    <input type="email" name="email"
           placeholder="Email" required>

    <input name="phone" placeholder="Phone Number"
           pattern="[0-9]{10}" required>

    <textarea name="address"
              placeholder="Delivery Address" required></textarea>

    <select name="product">

    <?php while($r = $result->fetch_assoc()){ ?>

        <option value="<?= $r['id'] ?>"
        <?= (int)($_GET['product'] ?? 0)==$r['id']
        ? 'selected' : '' ?>>

            <?= e($r['name']) ?> -
            ₹<?= e($r['price']) ?>/<?= e($r['unit']) ?>

        </option>

    <?php } ?>

    </select>

    <input type="number" name="quantity"
           placeholder="Quantity" min="0.01"
           step="0.01" required>

    <label>Delivery Date</label>

    <input type="date" name="delivery"
           min="<?= date('Y-m-d') ?>" required>

    <button name="order">Place Order</button>

    <p><?= e($msg) ?></p>

    <a href="index.php">Back to Home</a>

</form>