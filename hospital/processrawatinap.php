<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

$nik = $_GET['nik'];
$poli = $_GET['poli'];

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
    $error = "Pasien tidak di temukan: " . mysqli_error($koneksi);
}

// Fetch doctors from the database
$doctors = [];
$query = "SELECT * FROM dokter WHERE spesialis = '$poli'";
$result = mysqli_query($koneksi, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
} else {
    $error = "Query failed: " . mysqli_error($koneksi);
}

// Fetch rooms from the database
$rooms = [];
$query = "SELECT * FROM ruang_perawatan";
$result = mysqli_query($koneksi, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
} else {
    $error = "Query failed: " . mysqli_error($koneksi);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ruang = $_POST['id_ruang'];
    $doctor_id = $_POST['doctor_id'];

    // Fetch selected doctor details
    $query = "SELECT * FROM dokter WHERE id_dokter = '$doctor_id'";
    $result = mysqli_query($koneksi, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $doctor = mysqli_fetch_assoc($result);

        $diagnosa = $doctor['spesialis'];

        // Insert reservation
        $query2 = "CALL insert_rawat_inap('$id_pasien', '$doctor_id', '$id_ruang', '$diagnosa')";
        $insert_result = mysqli_query($koneksi, $query2);

        if ($insert_result) {
            $sukses = "Pendaftaran anda sukses.";
            echo '<script>
                    alert("' . addslashes($sukses) . '");
                    window.location.href = "finishrawatinap.php?namapasien=' . $namapasien . '&tanggallahir=' . $tanggallahir . '&telepon=' . $telepon . '&alamat=' . $alamat . '&jenis_kelamin=' . $jenis_kelamin . '&doctor_id=' . $doctor_id . '&id_ruang=' . $id_ruang . '";
                </script>';
            exit();
        } else {
            $error = "Error inserting reservation: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Doctor not found.";
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
                    <h1 class="mt-4">Proses Rawat Inap</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Proses Rawat Inap</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card">
                            <div class="card-header">
                                Pilih Dokter dan Ruang
                            </div>
                            <div class="card-body">
                                <?php if ($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error; ?>
                                    </div>
                                <?php } ?>
                                <form method="POST">
                                    <div class="grid-container">
                                        <?php foreach ($doctors as $doctor): ?>
                                            <div class="grid-item <?php echo $doctor['status'] == 'Tidak Tersedia' ? 'unavailable' : ''; ?>">
                                                <h3><?php echo $doctor['nama_dokter']; ?></h3>
                                                <p>Spesialis: <?php echo $doctor['spesialis']; ?></p>
                                                <p>Jenis kelamin: <?php echo $doctor['jenis_kelamin']; ?></p>
                                                <p>Status: <?php echo $doctor['status']; ?></p>
                                                <?php if ($doctor['status'] != 'Tidak Tersedia'): ?>
                                                    <input type="radio" name="doctor_id" value="<?php echo $doctor['id_dokter']; ?>" required>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="grid-container">
                                        <?php foreach ($rooms as $room): ?>
                                            <div class="grid-item <?php echo $room['status'] == 'Tidak Tersedia' ? 'unavailable' : ''; ?>">
                                                <h3><?php echo $room['nama_ruang']; ?></h3>
                                                <p>Jenis: <?php echo $room['jenis_ruang']; ?></p>
                                                <p>Biaya: <?php echo $room['biaya']; ?></p>
                                                <p>Status: <?php echo $room['status']; ?></p>
                                                <?php if ($room['status'] != 'Tidak Tersedia'): ?>
                                                    <input type="radio" name="id_ruang" value="<?php echo $room['id_ruang']; ?>" required>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
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
