<?php
    require 'function.php';
    require 'checkuser.php';

    $error = "";
    $sukses = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nik = $_POST['nik'];
        $namapasien = $_POST['namapasien'];
        $tanggallahir = $_POST['tanggallahir'];
        $telepon = $_POST['telepon'];
        $alamat = $_POST['alamat'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tanggal_temu = $_POST['tanggal_temu'];
        $poli = $_POST['poli'];
    
        // Validate birth date and meeting date
        $currentDate = date('Y-m-d H:i:s');
    
        if ($tanggal_temu < $currentDate) {
            $error = "Tanggal temu harus hari ini atau setelahnya.";
        } else {
            // Check if NIK already exists
            $query_check = "SELECT * FROM pasien WHERE nik = '$nik'";
            $result_check = mysqli_query($koneksi, $query_check);

            if (mysqli_num_rows($result_check) > 0) {
                // NIK exists, show confirmation popup
                echo "<script>
                        if (confirm('Data pasien dengan NIK ini sudah ada. Lanjutkan dengan data yang ada?')) {
                            window.location.href = 'processrawatjalan.php?nik=$nik&tanggal_temu=$tanggal_temu&poli=$poli';
                        } else {
                            window.location.href = 'rawatjalan.php'; // Redirect to another page or home
                        }
                      </script>";
                exit(); // Stop further execution
            } else {
                // NIK does not exist, proceed with insertion
                $query_insert = "CALL insert_pasien('$nik', '$namapasien', '$tanggallahir', '$telepon', '$alamat', '$jenis_kelamin')";
                $result_insert = mysqli_query($koneksi, $query_insert);

                if ($result_insert) {
                    $sukses = "Data berhasil disimpan.";
                    header("Location: processrawatjalan.php?nik=$nik&tanggal_temu=$tanggal_temu&poli=$poli");
                    exit();
                } else {
                    $error = "Error: " . mysqli_error($koneksi);
                }
            }
        }
    }
?>

<<!DOCTYPE html>
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
    <style>
        .sb-sidenav-menu .nav .sb-sidenav-menu-heading,
        .sb-sidenav-menu .nav .nav-link {
            color: white !important;
        }
    </style>
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
                        <a class="nav-link" href="user.php">
                            <div class="sb-nav-link-icon" ><i class="fas fa-plus-circle"></i></div>
                            Beranda User
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Pendaftaran Pasien</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Pendaftaran pasien rawat jalan.</li>
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
                                        <label for="tanggallahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
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
                                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="Laki - Laki">Laki - Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="tanggal_temu" class="col-sm-2 col-form-label">Tanggal Temu</label>
                                        <div class="col-sm-10">
                                            <input type="datetime-local" class="form-control" id="tanggal_temu" name="tanggal_temu" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="poli" class="col-sm-2 col-form-label">Poli</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="poli" name="poli" required>
                                                <option value="Bedah">Bedah</option>
                                                <option value="Mata">Mata</option>
                                                <option value="Saraf">Saraf</option>
                                                <option value="Kandungan">Kandungan</option>
                                                <option value="Umum">Umum</option>
                                                <option value="Gigi">Gigi</option>
                                                <option value="Anak">Anak</option>
                                                <option value="THT">THT</option>
                                                <option value="Kulit">Kulit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" name="simpan" class="btn btn-primary">Submit</button>
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

