<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

$nik = $_GET['nik'];
$jenis_operasi = $_GET['jenis_operasi'];

// Fetch patient data
$query = "SELECT * FROM pasien WHERE nik = '$nik'";
$result = mysqli_query($koneksi, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $patient = mysqli_fetch_assoc($result);
    $id_pasien = $patient['id_pasien'];
    $namapasien = $patient['nama_pasien'];
    $tanggallahir = $patient['tanggal_lahir'];
    $telepon = $patient['no_telepon'];
    $alamat = $patient['alamat'];
    $jenis_kelamin = $patient['jenis_kelamin'];
} else {
    $error =("Patient not found.");
}

// Fetch doctors from the database
$query = "SELECT * FROM dokter WHERE spesialis = '$jenis_operasi'";
$result = mysqli_query($koneksi, $query);
$doctors = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];

    // Insert reservation
    $query2 = "CALL insert_operasi('$id_pasien', '$doctor_id', '$jenis_operasi')";
    if (mysqli_query($koneksi, $query2)) {
        echo '<script>
                alert("Pendaftaran anda sukses.");
                window.location.href = "finishoperasi.php?namapasien=' . urlencode($namapasien) . '&tanggallahir=' . urlencode($tanggallahir) . '&telepon=' . urlencode($telepon) . '&alamat=' . urlencode($alamat) . '&jenis_kelamin=' . urlencode($jenis_kelamin) . '&doctor_id=' . urlencode($doctor_id) . '&jenis_operasi=' . urlencode($jenis_operasi) . '";
            </script>';
    } else {
        $error =("Error inserting reservation: " . mysqli_error($koneksi));
    }

    mysqli_close($koneksi);
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
    <title>Proses Rawat Jalan</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .grid-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .grid-item.unavailable {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .grid-item h3 {
            margin-top: 0;
        }
        .sb-sidenav-menu .nav .sb-sidenav-menu-heading,
        .sb-sidenav-menu .nav .nav-link {
            color: white !important;
        }
    </style>
    <script>
        function selectDoctor(id) {
            document.getElementById('doctor_id').value = id;
            document.getElementById('doctorForm').submit();
        }
    </script>
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
                        <li class="breadcrumb-item active">Pendaftaran Operasi</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card">
                            <div class="card-header">
                                Pilih Dokter
                            </div>
                            <div class="card-body">
                                <?php if ($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error; ?>
                                    </div>
                                <?php } ?>
                                <form method="POST" id="doctorForm">
                                    <input type="hidden" id="doctor_id" name="doctor_id" value="">
                                    <div class="grid-container">
                                        <?php foreach ($doctors as $doctor): ?>
                                            <div class="grid-item <?php echo $doctor['status'] == 'Tidak Tersedia' ? 'unavailable' : ''; ?>">
                                                <h3><?php echo $doctor['nama_dokter']; ?></h3>
                                                <p>Spesialis: <?php echo $doctor['spesialis']; ?></p>
                                                <p>Jenis kelamin: <?php echo $doctor['jenis_kelamin']; ?></p>
                                                <p>Status: <?php echo $doctor['status']; ?></p>
                                                <?php if ($doctor['status'] != 'Tidak Tersedia'): ?>
                                                    <button type="button" class="btn btn-primary" onclick="if (confirm('Yakin tentang pilihan dokter anda?')) selectDoctor(<?php echo $doctor['id_dokter']; ?>)">Pilih</button>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </form>
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
