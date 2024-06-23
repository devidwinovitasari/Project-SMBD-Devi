<?php
    require 'function.php';
    require 'checkuser.php';

    $error = "";
    $sukses = "";

    if (isset($_GET['op'])) {
        $op = $_GET['op'];
    } else {
        $op = "";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Rumah Sakit X - Pendaftaran" />
    <meta name="author" content="Rumah Sakit X" />
    <title>Pendaftaran - Rumah Sakit Telang Indah</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .sb-sidenav-menu .nav .sb-sidenav-menu-heading,
        .sb-sidenav-menu .nav .nav-link {
            color: white !important;
        }
    </style>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-primary">
        <a class="navbar-brand ps-3" href="user.php">Rumah Sakit Telang Indah </a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark bg-cyan" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="user.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-plus-circle"></i></div>
                            Beranda User
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">SELAMAT DATANG DI RUMAH SAKIT TELANG INDAH</h1>
                    <section id="user-services" class="user-services">
                        <div class="container" data-aos="fade-up">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="icon mb-3"><i class="bx bx-trophy" style="font-size: 2rem;"></i></div>
                                            <h4 class="card-title"><a href="rawatjalan.php">Reservasi Rawat jalan</a></h4>
                                            <p class="card-text">Silahkan Reservasi...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <footer class="footer bg-primary text-white py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase">Rumah Sakit Telang Indah</h5>
                            <p>Kesehatan Anda adalah prioritas kami. Kami siap mendampingi setiap langkah dalam perjalanan menuju kehidupan yang lebih sehat dan sejahtera.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase">Kontak Kami</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-phone"></i> (021) 123-4567</li>
                                <li><i class="fas fa-envelope"></i> info@rs-telangindah.com</li>
                                <li><i class="fas fa-map-marker-alt"></i> Jl. Sehat Sentosa No.1, Telang</li>
                            </ul>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase">Ikuti Kami</h5>
                            <ul class="list-unstyled d-flex">
                                <li><a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#" class="text-white"><i class="fab fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-center">
                            <p class="m-0">Sistem Manajemen Basis Data</p>
                            <p class="m-0">&copy; 2024 220441100090-220441100130</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
