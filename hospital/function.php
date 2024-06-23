<?php
session_start();

$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "hospital";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi){
    die("Tidak bisa terkoneksi ke database");
}

$sukses     = "";
$error      = "";

?>