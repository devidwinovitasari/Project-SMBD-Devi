<?php
require 'function.php';
require 'check.php';

// Memastikan koneksi berhasil
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database: " . mysqli_connect_error());
}

$error = "";
$sukses = "";

if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $sql1 = "DELETE FROM dokter WHERE id_dokter = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data: " . mysqli_error($koneksi);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_dokter'])) {
    $nama_dokter = $_POST['nama_dokter'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $spesialis = $_POST['spesialis'];
    $harga_bayar = $_POST['harga_bayar'];
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO dokter (nama_dokter, jenis_kelamin, no_telepon, spesialis, harga_bayar, status) 
                   VALUES ('$nama_dokter', '$jenis_kelamin', '$no_telp', '$spesialis', '$harga_bayar', '$status')";
    $q_insert = mysqli_query($koneksi, $sql_insert);

    if ($q_insert) {
        $sukses = "Berhasil menambahkan dokter baru";
    } else {
        $error = "Gagal menambahkan dokter: " . mysqli_error($koneksi);
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
    <title>Data Dokter</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
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
                    <h1 class="mt-4">Data Dokter</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Untuk mengecek data dokter.</li>
                    </ol>
                    <div class="mx-auto">
                        <!-- untuk menampilkan data -->
                        <div class="card mb-4">
                            <div class="card-header">
                                Data Dokter
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
                                            <th>Nama Dokter</th>
                                            <th>Jenis Kelamin</th>
                                            <th>No. Telepon</th>
                                            <th>Spesialis</th>
                                            <th>Harga Bayar</th>
                                            <th>Status</th>
                                            <th>Total pasien</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "SELECT * FROM view_dokter ORDER BY id_dokter ASC";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        if ($q2) {
                                            while ($r2 = mysqli_fetch_array($q2)) {
                                                $id_dokter = $r2['id_dokter'];
                                                $nama_dokter = $r2['nama_dokter'];
                                                $jenis_kelamin = $r2['jenis_kelamin'];
                                                $no_telp = $r2['no_telepon'];
                                                $spesialis = $r2['spesialis'];
                                                $harga_bayar = $r2['harga_bayar'];
                                                $status = $r2['status'];
                                                $total_pasien = $r2['total_pasien'];
                                        ?>
                                                <tr>
                                                    <td><?= $urut++ ?></td>
                                                    <td><?= $nama_dokter ?></td>
                                                    <td><?= $jenis_kelamin ?></td>
                                                    <td><?= $no_telp ?></td>
                                                    <td><?= $spesialis ?></td>
                                                    <td><?= $harga_bayar ?></td>
                                                    <td><?= $status ?></td>
                                                    <td><?= $total_pasien ?></td>
                                                    <td>
                                                        <a href="updatedokter.php?op=edit&id=<?= $id_dokter ?>"><button type="button" class="btn btn-warning">Ubah</button></a>
                                                        <a href="tabeldokter.php?op=delete&id=<?= $id_dokter ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Hapus</button></a>
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
                                <button type="button" class="btn btn-primary" id="openModalButton">Tambah Dokter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Modal Tambah Dokter -->
    <div id="tambahDokterModal" class="modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokter</h5>
                    <button type="button" class="btn-close" id="closeModalButton"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_dokter" class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki - Laki">Laki - Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" required>
                        </div>
                        <div class="mb-3">
                            <label for="spesialis" class="form-label">Spesialis</label>
                            <input type="text" class="form-control" id="spesialis" name="spesialis" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_bayar" class="form-label">Harga Bayar</label>
                            <input type="text" class="form-control" id="harga_bayar" name="harga_bayar" required>
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
                        <button type="submit" class="btn btn-primary" name="tambah_dokter">Tambah Dokter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('openModalButton').addEventListener('click', function () {
            document.getElementById('tambahDokterModal').style.display = 'block';
        });

        document.getElementById('closeModalButton').addEventListener('click', function () {
            document.getElementById('tambahDokterModal').style.display = 'none';
        });

        document.getElementById('closeModalFooterButton').addEventListener('click', function () {
            document.getElementById('tambahDokterModal').style.display = 'none';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-BQ6p6Gp/x8kT9w1LlywvHgZouRUfEt57XWIYjG1XyFlTBB6HyFMZou7r9YcUDgfA" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLR8eXs1h8c12V5p6I5R9k8/b8CV3slW0cdYFmlwYG" crossorigin="anonymous"></script>
</body>

</html>
