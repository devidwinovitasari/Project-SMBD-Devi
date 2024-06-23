<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

// Memastikan koneksi berhasil
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database: " . mysqli_connect_error());
}

if (isset($_GET['op']) && $_GET['op'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pasien berdasarkan id_pasien
    $sql = "SELECT * FROM pasien WHERE id_pasien = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die('Data pasien tidak ditemukan.');
        }
    } else {
        die('Gagal mengeksekusi query: ' . mysqli_error($koneksi));
    }

    // Formulir disubmit untuk update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nik = $_POST['nik'];
        $nama_pasien = $_POST['nama_pasien'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];

        // Query untuk update data pasien
        $sql_update = "UPDATE pasien SET nik=?, nama_pasien=?, alamat=?, no_telepon=?, tanggal_lahir=?, jenis_kelamin=? WHERE id_pasien=?";
        $stmt_update = mysqli_prepare($koneksi, $sql_update);
        mysqli_stmt_bind_param($stmt_update, 'ssssssi', $nik, $nama_pasien, $alamat, $no_telepon, $tanggal_lahir, $jenis_kelamin, $id);

        // Eksekusi query update
        if (mysqli_stmt_execute($stmt_update)) {
            $sukses = "Data pasien berhasil diupdate";
        } else {
            $error = "Gagal melakukan update data pasien: " . mysqli_error($koneksi);
        }
    }
} else {
    die('Akses tidak sah.');
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
    <title>Update Data Pasien</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .form-label {
            display: inline-block;
            width: 150px;
            text-align: right;
            margin-right: 10px;
        }
        .form-control, .form-select {
            display: inline-block;
            width: calc(100% - 170px);
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
                    <h1 class="mt-4">Update Data Pasien</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="tabelpasien.php">Data Pasien</a></li>
                        <li class="breadcrumb-item active">Update Data</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card mb-4">
                            <div class="card-header">
                                Update Data Pasien
                            </div>
                            <div class="card-body">
                                <?php if ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error; ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($sukses): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $sukses; ?>
                                </div>
                                <?php endif; ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="nik" name="nik"
                                            value="<?php echo isset($row['nik']) ? $row['nik'] : '' ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_pasien" class="form-label">Nama Pasien</label>
                                        <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                            value="<?php echo isset($row['nama_pasien']) ? $row['nama_pasien'] : '' ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat"
                                            required><?php echo isset($row['alamat']) ? $row['alamat'] : '' ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_telepon" class="form-label">No Telepon</label>
                                        <input type="text" class="form-control" id="no_telepon" name="no_telepon"
                                            value="<?php echo isset($row['no_telepon']) ? $row['no_telepon'] : '' ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                            value="<?php echo isset($row['tanggal_lahir']) ? $row['tanggal_lahir'] : '' ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="Laki - Laki"
                                                <?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Laki - Laki' ? 'selected' : '' ?>>
                                                Laki - Laki</option>
                                            <option value="Perempuan"
                                                <?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Data</button>
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
