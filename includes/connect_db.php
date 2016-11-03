<?php
$host = "localhost";
$database = "homeautomation";
$user = 'root';
$password = '';

// Checking if the database exists, if not creating
$conn = mysqli_connect($host, $user, $password);

$sql_create_db = "CREATE DATABASE  IF NOT EXISTS homeautomation";
mysqli_query($conn, $sql_create_db);
mysqli_close($conn);

// Connection to the database
$conn = mysqli_connect($host, $user, $password, $database);

