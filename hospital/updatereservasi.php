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

    // Ambil data reservasi berdasarkan id_reservasi
    $sql = "SELECT * FROM reservasi WHERE id_reservasi = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            die('Data reservasi tidak ditemukan.');
        }
    } else {
        die('Gagal mengeksekusi query: ' . mysqli_error($koneksi));
    }



    // Formulir disubmit untuk update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_pasien = $_POST['id_pasien'];
        $id_dokter = $_POST['id_dokter'];
        $tanggal_reservasi = $_POST['tanggal_reservasi'];
        $tanggal_temu_reservasi = $_POST['tanggal_temu_reservasi'];
        $poli = $_POST['poli'];
        $status = $_POST['status'];

        $currentDate = date('Y-m-d H:i:s');
    
        if ($tanggal_temu_reservasi < $currentDate) {
            $error = "Tanggal temu harus hari ini atau setelahnya.";
        } else {
            // Query untuk update data reservasi
            $sql_update = "UPDATE reservasi SET id_pasien=?, id_dokter=?, tanggal_reservasi=?, tanggal_temu_reservasi=?, poli=?, status=? WHERE id_reservasi=?";
            $stmt_update = mysqli_prepare($koneksi, $sql_update);
            mysqli_stmt_bind_param($stmt_update, 'iissssi', $id_pasien, $id_dokter, $tanggal_reservasi, $tanggal_temu_reservasi, $poli, $status,  $id);

            // Eksekusi query update
            if (mysqli_stmt_execute($stmt_update)) {
                $sukses = "Data reservasi berhasil diupdate";
            } else {
                $error = "Gagal melakukan update data reservasi: " . mysqli_error($koneksi);
            }
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
    <title>Update Data Reservasi</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            margin-right: 10px;
            min-width: 150px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 2;
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
                    <h1 class="mt-4">Update Data Reservasi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="tabelreservasi.php">Data Reservasi</a></li>
                        <li class="breadcrumb-item active">Update Data</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card mb-4">
                            <div class="card-header">
                                Update Data Reservasi
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
                                    <div class="form-group">
                                        <label for="id_pasien" class="form-label">ID Pasien</label>
                                        <input type="text" class="form-control" id="id_pasien" name="id_pasien"
                                            value="<?php echo isset($row['id_pasien']) ? $row['id_pasien'] : '' ?>"
                                            readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_dokter" class="form-label">ID Dokter</label>
                                        <input type="text" class="form-control" id="id_dokter" name="id_dokter"
                                            value="<?php echo isset($row['id_dokter']) ? $row['id_dokter'] : '' ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_reservasi" class="form-label">Tanggal Reservasi</label>
                                        <input type="datetime-local" class="form-control" id="tanggal_reservasi" name="tanggal_reservasi"
                                            value="<?php echo isset($row['tanggal_reservasi']) ? date('Y-m-d\TH:i', strtotime($row['tanggal_reservasi'])) : '' ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_temu_reservasi" class="form-label">Tanggal Temu Reservasi</label>
                                        <input type="datetime-local" class="form-control" id="tanggal_temu_reservasi" name="tanggal_temu_reservasi"
                                            value="<?php echo isset($row['tanggal_temu_reservasi']) ? date('Y-m-d\TH:i', strtotime($row['tanggal_temu_reservasi'])) : '' ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="poli" class="form-label">Poli</label>
                                        <input type="text" class="form-control" id="poli" name="poli"
                                            value="<?php echo isset($row['poli']) ? $row['poli'] : '' ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Aktif" <?php echo isset($row['status']) && $row['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                            <option value="Tidak Aktif" <?php echo isset($row['status']) && $row['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
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


