<?php
session_start();

$conn = new mysqli("localhost", "root", "", "traffic_squad", 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function e($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>