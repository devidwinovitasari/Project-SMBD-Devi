<?php
//jika belum login
if(isset($_SESSION['admin'])){

} else {
    header('location:index.php');
}
?>