<?php
    require "koneksi.php";

    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $sql = "SELECT * FROM admin WHERE username = ?";
        $result = $connection->prepare($sql);
        $result->execute([$username]);
        $pegawaiRow = $result->fetch(PDO::FETCH_ASSOC);

        if ($pegawaiRow) {
            if ($password == $pegawaiRow["password"]) {
                session_start();
                $_SESSION['adminUsername'] = $pegawaiRow["username"];
                $_SESSION['adminId'] = $pegawaiRow["id"];

                if(isset($_POST["remember-me"])) {
                    setcookie('username', $username, time() + 60 * 60 * 7);
                    setcookie('password', $password, time() + 60 * 60 * 7);
                }else{
                    setcookie('username', "", time()-1);
                    setcookie('password', "", time()-1);
                }

                header("location: dashboard.php");
            } else {
                echo "<script>alert('Password salah!');</script>";
            }
        } else {
            echo "<script>alert('Username salah!');</script>";
        }
}

$connection = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login-signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Login Admin</title>
</head>
<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                <!-- <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                    class="img-fluid" alt="Sample image"> -->
                    <img src="img/login-gambar.webp"
                    class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form name="login" method="POST">
                        <!-- Username input -->
                        <div class="form-outline mb-4">
                            <input name="username" type="text" id="username" class="form-control form-control-lg" placeholder="Enter username" />
                        </div>
            
                        <!-- Password input -->
                        <div class="form-outline mb-3">
                            <div class="input-group">
                                <input name="password" type="password" id="password" class="form-control form-control-lg" placeholder="Enter password" />
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eye"></i>
                                </button>
                            </div>
                        </div>
            
                        <div class="d-flex justify-content-between align-items-center">
                        <!-- Checkbox -->
                            <div class="form-check mb-0 ms-2">
                                <input class="form-check-input me-2 fs-5" type="checkbox" value="" name="remember-me" id="remember-me" checked/>
                                <label class="form-check-label fs-5" for="remember-me">Remember me</label>
                            </div>
                        </div>
            
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <input type="submit" name="login" class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem; width: 100%;"></input>
                            <p class="small fw-bold mt-2 pt-1 mb-0 text-center" style="font-size: 1rem;">Belum punya akun? <a href="register.php"
                                class="link-danger text-decoration-none font-weight-bold">Register</a></p>
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
    </script>

</body>
</html>

<?php
if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];

    echo "<script>
    document.getElementById('username').value = '$username';
    document.getElementById('password').value = '$password';
    </script>";
}
?>