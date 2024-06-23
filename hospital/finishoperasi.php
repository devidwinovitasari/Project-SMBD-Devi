<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

$namapasien = $_GET['namapasien'];
$tanggallahir = $_GET['tanggallahir'];
$telepon = $_GET['telepon'];
$alamat = $_GET['alamat'];
$jenis_kelamin = $_GET['jenis_kelamin'];
$doctor_id = $_GET['doctor_id'];
$jenis_operasi = $_GET['jenis_operasi'];

// Fetch selected doctor details
$query = "SELECT * FROM dokter WHERE id_dokter = '$doctor_id'";
$result = mysqli_query($koneksi, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $doctor = mysqli_fetch_assoc($result);
} else {
    $error = "Doctor not found.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Rawat Jalan Selesai</title>
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
        <a class="navbar-brand ps-3" href="user.php">Rumah Sakit Telang Indah</a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark bg-cyan" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-plus-circle"></i></div>
                            Beranda admin
                        </a>
                        <a class="nav-link" href="tabeldokter.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Dokter
                        </a>
                        <a class="nav-link" href="tabelruangperawatan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Ruang Perawatan
                        </a>
                        <a class="nav-link" href="tabelpasien.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Pasien
                        </a>
                        <a class="nav-link" href="tabelreservasi.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Reservasi
                        </a>
                        <a class="nav-link" href="tabelrawatinap.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Rawat Inap
                        </a>
                        <a class="nav-link" href="tabelrawatjalan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Rawat Jalan
                        </a>
                        <a class="nav-link" href="tabeloperasi.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Operasi
                        </a>
                        <a class="nav-link" href="tabellogin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div>
                            Data Login
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk sebagai:</div>
                    Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Pendaftaran Operasi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Detail Operasi</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card">
                            <div class="card-header">
                                Data Pasien
                            </div>
                            <div class="card-body">
                                <p><strong>Nama Pasien:</strong> <?php echo $namapasien; ?></p>
                                <p><strong>Tanggal Lahir:</strong> <?php echo $tanggallahir; ?></p>
                                <p><strong>Telepon:</strong> <?php echo $telepon; ?></p>
                                <p><strong>Alamat:</strong> <?php echo $alamat; ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?php echo $jenis_kelamin; ?></p>
                                <p><strong>Dokter:</strong> <?php echo $doctor['nama_dokter']; ?></p>
                                <p><strong>Operasi dari Poli:</strong> <?php echo $jenis_operasi; ?></p>
                                <p><strong>Biaya:</strong> <?php echo 'Biaya akan tersedia kalau pasien sudah checkout'; ?></p>
                            </div>
                            <div class="card-body">
                                <p>NB: Jangan lupa untuk membawa bukti pendaftaran ke rumah sakit sesuai jadwal temu.</p>
                            </div>
                        
                            <div class="card mb-4">
                                <div class="card-body">
                                    <button onclick="window.print()" class="btn btn-primary">Print</button>
                                    <button onclick="window.location.href='admin.php'" class="btn btn-danger">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
