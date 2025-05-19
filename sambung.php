<?php
$host = 'localhost';
$user = 'root';
$password = ''; 
$database = 'warehouse';


$sambungan = mysqli_connect($host, $user, $password, $database);


if (!$sambungan) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo 'Connected successfully';
?>
