<?php
$conn = new mysqli("localhost", "root", "", "artistryhub", 3306);

if ($conn->connect_error) {
    die("Database connection failed");
}

function e($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}
?>