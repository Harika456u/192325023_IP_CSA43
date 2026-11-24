<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ijsse", 3306);

if ($conn->connect_error) {
    die("Database Connection Failed");
}

$conn->set_charset("utf8mb4");

function e($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>