<?php
require 'function.php';
require 'check.php';

$error = "";
$sukses = "";

if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $sql1 = "DELETE FROM ruang_perawatan WHERE id_ruang = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data: " . mysqli_error($koneksi);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_ruang'])) {
    $nama_ruang = $_POST['nama_ruang'];
    $jenis_ruang = $_POST['jenis_ruang'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO ruang_perawatan (nama_ruang, jenis_ruang, biaya, status) VALUES ('$nama_ruang', '$jenis_ruang', '$biaya', '$status')";
    $q_insert = mysqli_query($koneksi, $sql_insert);

    if ($q_insert) {
        $sukses = "Berhasil menambahkan ruang perawatan baru";
    } else {
        $error = "Gagal menambahkan ruang perawatan: " . mysqli_error($koneksi);
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
    <title>Data Ruang Perawatan</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous">
    </script>
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
                    <h1 class="mt-4">Data Ruang Perawatan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Untuk mengecek data ruang perawatan.</li>
                    </ol>
                    <div class="mx-auto">
                        <!-- untuk menampilkan data -->
                        <div class="card mb-4">
                            <div class="card-header">
                                Data Ruang Perawatan
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
                                            <th>Nama Ruang</th>
                                            <th>Jenis Ruang</th>
                                            <th>Biaya</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "CALL view_ruang_perawatan(15)";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        if ($q2) {
                                            while ($r2 = mysqli_fetch_array($q2)) {
                                                $id = $r2['id_ruang'];
                                                $nama_ruang = $r2['nama_ruang'];
                                                $jenis_ruang = $r2['jenis_ruang'];
                                                $biaya = $r2['biaya'];
                                                $status = $r2['status'];
                                        ?>
                                                <tr>
                                                    <td><?= $urut++ ?></td>
                                                    <td><?= $nama_ruang ?></td>
                                                    <td><?= $jenis_ruang ?></td>
                                                    <td><?= $biaya ?></td>
                                                    <td><?= $status ?></td>
                                                    <td>
                                                        <a href="updateruangperawatan.php?op=edit&id=<?= $id ?>"><button type="button" class="btn btn-warning">Ubah</button></a>
                                                        <a href="tabelruangperawatan.php?op=delete&id=<?= $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Hapus</button></a>
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
                                <button type="button" class="btn btn-primary" id="openModalButton">Tambah Ruang</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Modal Tambah Ruang -->
    <div id="tambahRuangModal" class="modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ruang</h5>
                    <button type="button" class="btn-close" id="closeModalButton"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_ruang" class="form-label">Nama Ruang</label>
                            <input type="text" class="form-control" id="nama_ruang" name="nama_ruang" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_ruang" class="form-label">Jenis Ruang</label>
                            <select class="form-control" id="jenis_ruang" name="jenis_ruang" required>
                                <option value="VVIP">VVIP</option>
                                <option value="VIP">VIP</option>
                                <option value="REGULAR">REGULAR</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="biaya" class="form-label">Biaya Ruang</label>
                            <input type="text" class="form-control" id="biaya" name="biaya" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="closeModalFooterButton">Batal</button>
                        <button type="submit" class="btn btn-primary" name="tambah_ruang">Tambah Ruang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('openModalButton').addEventListener('click', function () {
            document.getElementById('tambahRuangModal').style.display = 'block';
        });

        document.getElementById('closeModalButton').addEventListener('click', function () {
            document.getElementById('tambahRuangModal').style.display = 'none';
        });

        document.getElementById('closeModalFooterButton').addEventListener('click', function () {
            document.getElementById('tambahRuangModal').style.display = 'none';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
