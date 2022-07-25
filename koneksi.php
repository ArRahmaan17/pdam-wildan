<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "air";

$conn = mysqli_connect($host, $username, $password, $database);
date_default_timezone_set('Asia/Jakarta');
session_start();
