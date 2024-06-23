<?php
//jika belum login
if(isset($_SESSION['user'])){

} else {
    header('location:index.php');
}
?>