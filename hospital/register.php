<?php
require 'function.php';

// Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = "user";

    // Check if the username already exists
    $cekdatabase = mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username'");
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung == 0) {
        // Insert new user data into the database
        $insert = mysqli_query($koneksi, "INSERT INTO login (username, password, role) VALUES ('$username', '$password', '$role')");

        if ($insert) {
            echo '<script>
                        alert("Registration successful. Please login.");
                        window.location.href = "login.php";
                    </script>';
        } else {
            echo '<script>
                        alert("Registration failed. Please try again.");
                    </script>';
        }
    } else {
        echo '<script>
                    alert("Username already exists. Please choose a different username.");
                </script>';
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
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div class="bg-gambar">
        <style>
            body {
                background-image: url('assets/img/rs.jpg');
                background-repeat: no-repeat;
                background-size: cover;
            }
        </style>
    </div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-3">Register</h3>
                                    <h5 class="text-center font-weight-light my-2">Buat akun anda</h5>
                                </div>
                                <div class="card-body">
                                    <div class="gambar">
                                        <img src="assets/img/iconrs.png" alt="Image" height="256" width="256" style="display:block; margin:auto;">
                                    </div>
                                    <form method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Username" required />
                                            <label for="inputUsername">Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <a href="login.php">Sudah punya akun?</a>
                                        <div class="d-flex align-items-center justify-content-between mt-3 mb-0">
                                            <button class="btn btn-primary" name="register">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
