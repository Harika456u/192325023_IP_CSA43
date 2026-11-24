<?php
$conn = new mysqli("localhost","root","","event_db",3306);

if($conn->connect_error){
    die("Connection Failed");
}
?>