<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

$op = $_GET['op'] ?? "";

if ($op == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM operasi WHERE id_operasi = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['checkout']) && isset($_POST['id_operasi'])) {
    $id = $_POST['id_operasi'];
    $sql2 = "CALL checkout_operasi('$id')";
    $q2 = mysqli_query($koneksi, $sql2);
    
    if ($q2) {
        $sukses = "Berhasil checkout pasien";
    } else {
        $error = "Gagal melakukan checkout pasien: " . mysqli_error($koneksi);
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
    <title>Data Rawat Inap</title>
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
                    <h1 class="mt-4">Data Operasi</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Untuk mengecek data operasi.</li>
                    </ol>
                    <div class="mx-auto">
                        <!-- untuk menampilkan data -->
                        <div class="card mb-4">
                            <div class="card-header">
                                Data Operasi
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
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pasien</th>
                                            <th>Dokter</th>
                                            <th>Jenis</th>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "CALL view_operasi();";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        if ($q2) {
                                            while ($r2 = mysqli_fetch_array($q2)) {
                                                $id = $r2['id_operasi'];
                                                $pasien = $r2['nama_pasien'];
                                                $dokter = $r2['nama_dokter'];
                                                $jenis_operasi = $r2['jenis_operasi'];
                                                $tanggal_operasi = $r2['tanggal_operasi'];
                                                $jam_masuk = $r2['jam_masuk'];
                                                $jam_keluar = $r2['jam_keluar'];
                                                $biaya_operasi = $r2['biaya_operasi'];
                                        ?>
                                                <tr>
                                                    <td><?= $urut++ ?></td>
                                                    <td><?= $pasien ?></td>
                                                    <td><?= $dokter ?></td>
                                                    <td><?= $jenis_operasi ?></td>
                                                    <td><?= $tanggal_operasi ?></td>
                                                    <td><?= $jam_masuk ?></td>
                                                    <td><?= $jam_keluar ?></td>
                                                    <td><?= $biaya_operasi ?></td>
                                                    <td>
                                                        <a href="updateoperasi.php?op=edit&id=<?= $id ?>"><button type="button" class="btn btn-warning">Ubah</button></a>
                                                        <a href="tabeloperasi.php?op=delete&id=<?= $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Hapus</button></a>
                                                        <?php if (empty($r2['jam_keluar'])): ?>
                                                            <form method="POST" style="display:inline;">
                                                                <input type="hidden" name="id_operasi" value="<?= $id ?>">
                                                                <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
                                                            </form>
                                                        <?php else: ?>
                                                            <button onclick="window.print()" class="btn btn-primary">Print</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            echo "Gagal mengeksekusi query: " . mysqli_error($koneksi);
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
