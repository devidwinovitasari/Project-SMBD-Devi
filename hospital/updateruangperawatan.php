<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

// Check if 'op=edit' and 'id' parameter is set
if (isset($_GET['op']) && $_GET['op'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve existing data based on id_ruang
    $sql = "SELECT * FROM ruang_perawatan WHERE id_ruang = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $id_ruang = $data['id_ruang'];
        $nama_ruang = $data['nama_ruang'];
        $jenis_ruang = $data['jenis_ruang'];
        $biaya = $data['biaya'];
        $status = $data['status'];
    } else {
        $error = "Data tidak ditemukan.";
    }
} else {
    // Redirect to tabelruangperawatan.php if no id provided or op is not edit
    header("Location: tabelruangperawatan.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_ruang = $_POST['nama_ruang'];
    $jenis_ruang = $_POST['jenis_ruang'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    
    // Update data into database
    $sql_update = "UPDATE ruang_perawatan SET nama_ruang = ?, jenis_ruang = ?, biaya = ?, status = ? WHERE id_ruang = ?";
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt_update, 'ssssi', $nama_ruang, $jenis_ruang, $biaya, $status, $id_ruang);
    $q_update = mysqli_stmt_execute($stmt_update);
    
    if ($q_update) {
        $sukses = "Data ruang perawatan berhasil diupdate";
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
    <title>Update Data Ruang Perawatan</title>
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
                    <h1 class="mt-4">Update Data Ruang Perawatan</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="tabelruangperawatan.php">Data Ruang Perawatan</a></li>
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
                                <div class="form-group">
                                    <label for="id_ruang" class="form-label">ID Ruang</label>
                                    <input type="text" class="form-control" id="id_ruang" name="id_ruang"
                                        value="<?php echo $id_ruang; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nama_ruang" class="form-label">Nama Ruang</label>
                                    <input type="text" class="form-control" id="nama_ruang" name="nama_ruang"
                                        value="<?php echo $nama_ruang; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_ruang" class="form-label">Jenis Ruang</label>
                                    <select class="form-select" id="jenis_ruang" name="jenis_ruang" required>
                                        <option value="">Pilih jenis ruang</option>
                                        <option value="VVIP" <?php echo ($jenis_ruang == 'VVIP') ? 'selected' : ''; ?>>VVIP</option>
                                        <option value="VIP" <?php echo ($jenis_ruang == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                                        <option value="REGULAR" <?php echo ($jenis_ruang == 'REGULAR') ? 'selected' : ''; ?>>REGULAR</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="biaya" class="form-label">Biaya</label>
                                    <input type="text" class="form-control" id="biaya" name="biaya"
                                        value="<?php echo $biaya; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-label">Status Kamar</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="Tersedia" <?php echo ($status == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                        <option value="Tidak Tersedia" <?php echo ($status == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                                    </select>
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
