<?php
require 'function.php';
require 'check.php';

// Memastikan koneksi berhasil
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database: " . mysqli_connect_error());
}

$error = "";
$sukses = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Jika formulir disubmit untuk update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_dokter = $_POST['nama_dokter'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $no_telepon = $_POST['no_telepon'];
        $spesialis = $_POST['spesialis'];
        $harga_bayar = $_POST['harga_bayar'];
        $status = $_POST['status'];

        // Query untuk update data dokter
        $sql_update = "UPDATE dokter SET nama_dokter=?, jenis_kelamin=?, no_telepon=?, spesialis=?, harga_bayar=?, status=? WHERE id_dokter=?";
        $stmt = mysqli_prepare($koneksi, $sql_update);
        
        if ($stmt === false) {
            $error = "Error dalam persiapan statement SQL: " . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssssssi', $nama_dokter, $jenis_kelamin, $no_telepon, $spesialis, $harga_bayar, $status, $id);
            // Eksekusi query update
            $q_update = mysqli_stmt_execute($stmt);

            if ($q_update) {
                $sukses = "Data dokter berhasil diupdate";
            } else {
                $error = "Gagal melakukan update data dokter: " . mysqli_error($koneksi);
            }
        }
    }

    // Ambil data dokter dari database untuk ditampilkan di form
    $sql_select = "SELECT * FROM dokter WHERE id_dokter=?";
    $stmt = mysqli_prepare($koneksi, $sql_select);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Pastikan ada hasil data yang ditemukan
    if (!$row) {
        $error = "Data dokter tidak ditemukan";
    }
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
    <title>Update Data Dokter</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
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
                    <h1 class="mt-4">Update Data Dokter</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="tabeldokter.php">Data Dokter</a></li>
                        <li class="breadcrumb-item active">Update Data</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card mb-4">
                            <div class="card-header">
                                Formulir Update Data Dokter
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
                                    <div class="mb-3 row">
                                        <label for="nama_dokter" class="col-sm-2 col-form-label">Nama Dokter</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="<?= isset($row['nama_dokter']) ? $row['nama_dokter'] : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="Laki - Laki" <?= isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Laki - Laki' ? 'selected' : '' ?>>Laki - Laki</option>
                                                <option value="Perempuan" <?= isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="no_telepon" class="col-sm-2 col-form-label">Nomor Telepon</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= isset($row['no_telepon']) ? $row['no_telepon'] : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="spesialis" class="col-sm-2 col-form-label">Spesialis</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="spesialis" name="spesialis" value="<?= isset($row['spesialis']) ? $row['spesialis'] : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="harga_bayar" class="col-sm-2 col-form-label">Harga Bayar</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="harga_bayar" name="harga_bayar" value="<?= isset($row['harga_bayar']) ? $row['harga_bayar'] : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="Tersedia" <?= isset($row['status']) && $row['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                                <option value="Tidak Tersedia" <?= isset($row['status']) && $row['status'] == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                                            </select>
                                        </div>
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
