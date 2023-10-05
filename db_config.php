<?php

$conn = mysqli_connect('localhost','root','','ecommerce');

if ($conn->connect_error) {
    die("Connection failure: "
        . $conn->connect_error);
}
?>
