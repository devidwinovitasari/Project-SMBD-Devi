<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

// Check if 'op=edit' and 'id' parameter is set
if (isset($_GET['op']) && $_GET['op'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve existing data based on id_rawat_inap
    $sql = "SELECT * FROM rawat_inap WHERE id_rawat_inap = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $id_rawat_inap = $data['id_rawat_inap'];
        $id_pasien = $data['id_pasien'];
        $id_dokter = $data['id_dokter'];
        $id_ruang = $data['id_ruang'];
        $diagnosa = $data['diagnosa'];
        $tanggal_masuk = $data['tanggal_masuk'];
        $tanggal_keluar = $data['tanggal_keluar'];
    } else {
        $error = "Data tidak ditemukan.";
    }
} else {
    // Redirect to tabelrawatinap.php if no id provided or op is not edit
    header("Location: tabelrawatinap.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_rawat_inap = $_POST['id_rawat_inap'];
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $id_ruang = $_POST['id_ruang'];
    $diagnosa = $_POST['diagnosa'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    
    // Format tanggal_masuk dan tanggal_keluar ke format MySQL
    $tanggal_masuk_mysql = date('Y-m-d H:i:s', strtotime($tanggal_masuk));
    $tanggal_keluar_mysql = date('Y-m-d H:i:s', strtotime($tanggal_keluar));
    
    if ($tanggal_keluar_mysql < $tanggal_masuk_mysql) {
        $error = "Tanggal keluar tidak boleh sebelum tanggal masuk.";
    } else  {
        // Update data into database
        $sql_update = "UPDATE rawat_inap SET id_pasien = ?, id_dokter = ?, id_ruang = ?, diagnosa = ?, tanggal_masuk = ?, tanggal_keluar = ? WHERE id_rawat_inap = ?";
        $stmt_update = mysqli_prepare($koneksi, $sql_update);
        mysqli_stmt_bind_param($stmt_update, 'sssssss', $id_pasien, $id_dokter, $id_ruang, $diagnosa, $tanggal_masuk_mysql, $tanggal_keluar_mysql, $id_rawat_inap);
        $q_update = mysqli_stmt_execute($stmt_update);
        
        if ($q_update) {
            $sukses = "Data rawat inap berhasil diupdate".($tanggal_masuk_mysql).( $tanggal_keluar_mysql).( $id_rawat_inap);
        } else {
            $error = "Gagal melakukan update data: " . mysqli_error($koneksi);
        }
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
    <title>Update Data Rawat Inap</title>
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
        .form-group select {
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
                    <h1 class="mt-4">Update Data Rawat Inap</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="tabelrawatinap.php">Data Rawat Inap</a></li>
                        <li class="breadcrumb-item active">Update Data</li>
                    </ol>
                    <div class="card mb-4">
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
                            <form method="post">
                                <input type="hidden" name="id_rawat_inap" value="<?= $id ?>">
                                <div class="form-group">
                                    <label for="id_pasien" class="form-label">ID Pasien</label>
                                    <input type="text" class="form-control" id="id_pasien" name="id_pasien" value="<?php echo htmlspecialchars($id_pasien); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_dokter" class="form-label">ID Dokter</label>
                                    <input type="text" class="form-control" id="id_dokter" name="id_dokter" value="<?php echo htmlspecialchars($id_dokter); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_ruang" class="form-label">ID Ruang</label>
                                    <input type="text" class="form-control" id="id_ruang" name="id_ruang" value="<?php echo htmlspecialchars($id_ruang); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="diagnosa" class="form-label">Diagnosa</label>
                                    <input type="text" class="form-control" id="diagnosa" name="diagnosa" value="<?php echo htmlspecialchars($diagnosa); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo isset($tanggal_masuk) ? date('Y-m-d\TH:i', strtotime($tanggal_masuk)) : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="<?php echo isset($tanggal_keluar) ? date('Y-m-d\TH:i', strtotime($tanggal_keluar)) : '' ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>