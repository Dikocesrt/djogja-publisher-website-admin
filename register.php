<?php
    require "koneksi.php";

    $username = "";
    $email = "";
    $password = "";
    $confirmPassword = "";

    if(isset($_POST["register"])){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm-password"];
        
        if($username == ""){
            ?>
                <script>alert("Username tidak boleh kosong!!!");</script>
            <?php
        }else if($email == ""){
            ?>
                <script>alert("Email tidak boleh kosong!!!");</script>
            <?php
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            ?>
                <script>alert("Email tidak ditemukan!!!");</script>
            <?php
        }else if($password == ""){
            ?>
                <script>alert("Password tidak boleh kosong!!!");</script>
            <?php
        }else if($confirmPassword == ""){
            ?>
                <script>alert("Konfirmasi password tidak boleh kosong!!!");</script>
            <?php
        }else if($password != $confirmPassword){
            ?>
                <script>alert("Password tidak cocok!!!");</script>
            <?php
        }else{
            $sql = "SELECT COUNT(*) AS jumlah_admin FROM admin WHERE email = ?";
            $result = $connection->prepare($sql);
            $result->execute([$email]);
            foreach($result as $row){
                if($row['jumlah_admin'] > 0){
                    echo '<script>alert("Email sudah terdaftar!!!");</script>';
                }else{
                    $sql = "SELECT COUNT(*) AS jumlah_admin FROM admin WHERE username = ?";
                    $result = $connection->prepare($sql);
                    $result->execute([$username]);
                    foreach($result as $row){
                        if($row['jumlah_admin'] > 0){
                            echo '<script>alert("Username sudah terdaftar!!!");</script>';
                        }else{
                            $sql = "insert into admin (username, password, email, created_at) values (?, ?, ?, ?)";
                            $result = $connection->prepare($sql);
                            date_default_timezone_set('Asia/Jakarta');
                            $result->execute([$username, $password, $email, date('Y-m-d H:i:s')]);
                            $sql = "SELECT id FROM admin WHERE username = ? AND password = ?";
                            $result = $connection->prepare($sql);
                            $result->execute([$username, $password]);
                            session_start();
                            foreach($result as $row){
                                $_SESSION['adminId'] = $row['id'];
                            }
                            $_SESSION['adminUsername'] = $username;
                            setcookie('username', $username, time() + 60 * 60 * 7);
                            setcookie('password', $password, time() + 60 * 60 * 7);
                            header("location: dashboard.php");
                            ?>
                            <script>alert("Berhasil mendaftarkan akun!!!");</script>
                        <?php
                        }
                    }
                }
            }
        }
    }
    $connection = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Registrasi Admin</title>
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="img/sign-up-gambar.svg"
                    class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form name="register" method="POST">
                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Enter a username" />
                        </div>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter a valid email address" />
                        </div>
    
                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter password" />
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirmation Password input -->
                        <div class="form-outline mb-3">
                            <div class="input-group">
                                <input type="password" id="confirm-password" name="confirm-password" class="form-control form-control-lg" placeholder="Enter Confirmation password" />
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                    <i class="fas fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
    
                        <!-- Submit button -->
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <input type="submit" name="register" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem; width: 100%;"></input>
                            <p class="small fw-bold mt-2 pt-1 mb-0 text-center" style="font-size: 1rem;">Sudah punya akun? <a href="index.php"
                                class="link-danger text-decoration-none font-weight-bold">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>





    <!-- js script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            $("#togglePassword").click(function(){
                var passwordField = $("#password");
                var passwordFieldType = passwordField.attr('type');
                if(passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                    $("#eye").removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $("#eye").removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });

        $(document).ready(function(){
            $("#togglePassword2").click(function(){
                var passwordField = $("#confirm-password");
                var passwordFieldType = passwordField.attr('type');
                if(passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                    $("#eye2").removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $("#eye2").removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
        </script>
</body>
</html>