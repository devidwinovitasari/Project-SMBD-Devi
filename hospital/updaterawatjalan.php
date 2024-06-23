<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

// Check if 'op=edit' and 'id' parameter is set
if (isset($_GET['op']) && $_GET['op'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve existing data based on id_rawat_jalan
    $sql = "SELECT * FROM rawat_jalan WHERE id_rawat_jalan = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $id_rawat_jalan = $data['id_rawat_jalan'];
        $id_pasien = $data['id_pasien'];
        $id_dokter = $data['id_dokter'];
        $tanggal_periksa = $data['tanggal_periksa'];
        $biaya_periksa = $data['biaya_periksa'];
        $diagnosa = $data['diagnosa'];
    } else {
        $error = "Data tidak ditemukan.";
    }
} else {
    // Redirect to tabelrawatjalan.php if no id provided or op is not edit
    header("Location: tabelrawatjalan.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_rawat_jalan = $_POST['id_rawat_jalan'];
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $diagnosa = $_POST['diagnosa'];
    
    // Update data into database
    $sql_update = "UPDATE rawat_jalan SET id_pasien = ?, id_dokter = ?, tanggal_periksa = ?, diagnosa = ? WHERE id_rawat_jalan = ?";
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'sssss', $id_pasien, $id_dokter, $tanggal_periksa,  $diagnosa, $id_rawat_jalan);
    $q_update = mysqli_stmt_execute($stmt_update);
    
    if ($q_update) {
        $sukses = "Data rawat jalan berhasil diupdate";
    } else {
        $error = "Gagal melakukan update data: " . mysqli_error($koneksi);
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
    <title>Update Data Rawat Jalan</title>
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
                    <h1 class="mt-4">Update Data Rawat Jalan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="tabelrawatjalan.php">Data Rawat Jalan</a></li>
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
                                <input type="hidden" name="id_rawat_jalan" value="<?= $id ?>">
                                <div class="form-group">
                                    <label for="id_pasien" class="form-label">ID Pasien</label>
                                    <input type="text" class="form-control" id="id_pasien" name="id_pasien" value="<?php echo htmlspecialchars($id_pasien); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_dokter" class="form-label">ID Dokter</label>
                                    <input type="text" class="form-control" id="id_dokter" name="id_dokter" value="<?php echo htmlspecialchars($id_dokter); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_periksa" class="form-label">Tanggal Periksa</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_periksa" name="tanggal_periksa" value="<?php echo isset($tanggal_periksa) ? date('Y-m-d\TH:i', strtotime($tanggal_periksa)) : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="diagnosa" class="form-label">Diagnosa</label>
                                    <textarea class="form-control" id="diagnosa" name="diagnosa" required><?php echo htmlspecialchars($diagnosa); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
