<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM rawat_inap WHERE id_rawat_inap = ?";
    $stmt = mysqli_prepare($koneksi, $sql1);
    mysqli_stmt_bind_param($stmt, 's', $id);
    $q1 = mysqli_stmt_execute($stmt);
    
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data: " . mysqli_error($koneksi);
    }
}

if (isset($_POST['checkout'])) {
    if (isset($_POST['id_rawat_inap'])) {
        $id = $_POST['id_rawat_inap'];
        
        $sql2 = "CALL checkout_rawat_inap(?)";
        $stmt2 = mysqli_prepare($koneksi, $sql2);
        
        if ($stmt2) {
            mysqli_stmt_bind_param($stmt2, 'i', $id);
            $q2 = mysqli_stmt_execute($stmt2);
            
            if ($q2) {
                $sukses = "Berhasil checkout pasien";
            } else {
                $error = "Gagal melakukan checkout pasien: " . mysqli_error($koneksi);
            }
            mysqli_stmt_close($stmt2);
        } else {
            $error = "Gagal mempersiapkan statement: " . mysqli_error($koneksi);
        }
    } else {
        $error = "ID rawat inap tidak ditemukan";
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
                    <h1 class="mt-4">Data Rawat Inap</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Untuk mengecek data rawat inap.</li>
                    </ol>
                    <div class="mx-auto">
                        <!-- untuk menampilkan data -->
                        <div class="card mb-4">
                            <div class="card-header">
                                Data Rawat Inap
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
                                            <th>Ruang</th>
                                            <th>Diagnosa</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Tanggal Keluar</th>
                                            <th>Total Hari</th>
                                            <th>Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "SELECT * FROM view_rawat_inap ORDER BY id_rawat_inap ASC";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        if ($q2) {
                                            while ($r2 = mysqli_fetch_array($q2)) {
                                                $id = $r2['id_rawat_inap'];
                                                $pasien = $r2['nama_pasien'];
                                                $dokter = $r2['nama_dokter'];
                                                $nama_ruang = $r2['nama_ruang'];
                                                $diagnosa = $r2['diagnosa'];
                                                $tanggal_masuk = $r2['tanggal_masuk'];
                                                $tanggal_keluar = $r2['tanggal_keluar'];
                                                $total_hari = $r2['total_hari'];
                                                $biaya_rawat_inap = $r2['biaya_rawat_inap'];
                                        ?>
                                                <tr>
                                                    <td><?= $urut++ ?></td>
                                                    <td><?= $pasien ?></td>
                                                    <td><?= $dokter ?></td>
                                                    <td><?= $nama_ruang ?></td>
                                                    <td><?= $diagnosa ?></td>
                                                    <td><?= $tanggal_masuk ?></td>
                                                    <td><?= $tanggal_keluar ?></td>
                                                    <td><?= $total_hari ?></td>
                                                    <td><?= $biaya_rawat_inap ?></td>
                                                    <td>
                                                        <a href="updaterawatinap.php?op=edit&id=<?= $id ?>"><button type="button" class="btn btn-warning">Ubah</button></a>
                                                        <a href="tabelrawatinap.php?op=delete&id=<?= $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Hapus</button></a>
                                                        <?php if (empty($r2['tanggal_keluar'])): ?>
                                                            <form method="POST" style="display:inline;">
                                                                <input type="hidden" name="id_rawat_inap" value="<?= $id ?>">
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
