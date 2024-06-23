<?php
    require 'function.php';
    require 'check.php';

    $error = "";
    $sukses = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nik = $_POST['nik'];
        $namapasien = $_POST['namapasien'];
        $tanggallahir = $_POST['tanggallahir'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $jenis_operasi = $_POST['jenis_operasi'];
    
        // Validate birth date and meeting date
        $currentDate = date('Y-m-d');
        $minBirthDate = date('Y-m-d', strtotime('-15 years'));
    
        // Check if NIK already exists
        $query_check = "SELECT * FROM pasien WHERE nik = '$nik'";
        $result_check = mysqli_query($koneksi, $query_check);

        if (mysqli_num_rows($result_check) > 0) {
            // NIK exists, show confirmation popup
            echo "<script>
                    if (confirm('Data pasien dengan NIK ini sudah ada. Lanjutkan dengan data yang ada?')) {
                        window.location.href = 'processoperasi.php?nik=$nik&jenis_operasi=$jenis_operasi';
                    } else {
                        window.location.href = 'operasi.php'; // Redirect to another page or home
                    }
                    </script>";
            exit(); // Stop further execution
        } else {
            // NIK does not exist, proceed with insertion
            $query_insert = "CALL insert_pasien('$nik', '$namapasien', '$tanggallahir', '$telepon', '$alamat', '$jenis_kelamin')";
            $result_insert = mysqli_query($koneksi, $query_insert);

            if ($result_insert) {
                $sukses = "Data berhasil disimpan.";
                header("Location: processoperasi.php?nik=$nik&jenis_operasi=$jenis_operasi");
                exit();
            } else {
                $error = "Error: " . mysqli_error($koneksi);
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
    <title>Pendaftaran</title>
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
                    <h1 class="mt-4">Pendaftaran Pasien</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Pendaftaran pasien operasi.</li>
                    </ol>
                    <div class="mx-auto">
                        <div class="card">
                            <div class="card-header">
                                Tambah Data Pendaftaran
                            </div>
                            <div class="card-body">
                                <?php if ($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error; ?>
                                    </div>
                                <?php } ?>
                                <?php if ($sukses) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $sukses; ?>
                                    </div>
                                <?php } ?>
                                <form action="" method="POST">
                                    <div class="mb-3 row">
                                        <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nik" name="nik" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="namapasien" class="col-sm-2 col-form-label">Nama Pasien</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="namapasien" name="namapasien" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="tanggallahir" class="col-sm-2 col-form-label">Tanggal lahir</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="tanggallahir" name="tanggallahir" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="telepon" name="telepon" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="Laki - Laki">Laki - Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="jenis_operasi" class="col-sm-2 col-form-label">Operasi dari Poli</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="jenis_operasi" name="jenis_operasi" required>
                                                <option value="Bedah">Bedah</option>
                                                <option value="Mata">Mata</option>
                                                <option value="Saraf">Saraf</option>
                                                <option value="Kandungan">Kandungan</option>
                                                <option value="Gigi">Gigi</option>
                                                <option value="Anak">Anak</option>
                                                <option value="THT">THT</option>
                                                <option value="Kulit">Kulit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" name="simpan" value="Submit" class="btn btn-primary">
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
