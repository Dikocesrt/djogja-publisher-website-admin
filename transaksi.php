<?php
    session_start();
    
    require "koneksi.php";

    date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Transaksi</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="vendor/fontawesome-free-6.5.2-web/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Djogja Publisher</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Operation
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="pengguna.php">
                    <i class="fas fa-fw fa-address-book"></i>
                    <span>Pengguna</span>
                </a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="admin.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Admin</span>
                </a>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="produk.php">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Produk</span>
                </a>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item active">
                <a class="nav-link" href="transaksi.php">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>Transaksi</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="pt-4">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Data Transaksi</h1>
                    </div>
                    
                    <!-- Content Row -->
                    <div class="row">

                        <?php
                            $sql = "SELECT COUNT(*) AS total_data_transaksi FROM pesanan";
                            $result = $connection->prepare($sql);
                            $result->execute();
                            $firstRow = $result->fetch(PDO::FETCH_ASSOC);
                            $totalDataTransaksi = $firstRow['total_data_transaksi'];
                            // Data total Transaksi
                            echo '<div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Total Transaksi</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalDataTransaksi . '</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>'
                        ?>

                        <?php
                            $currentDateWIB = date('Y-m-d', strtotime('now'));
                            $sql = "SELECT COUNT(*) AS total_data_transaksi FROM pesanan WHERE DATE(tanggal) = ?";
                            $result = $connection->prepare($sql);
                            $result->execute([$currentDateWIB]);
                            $firstRow = $result->fetch(PDO::FETCH_ASSOC);
                            $totalDataTransaksi = $firstRow['total_data_transaksi'];
                            // Data Transaksi
                            echo '<div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Pesanan Hari Ini</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">'. $totalDataTransaksi  .'</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-business-time fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>'
                        ?>
                    </div>

                    <!-- Content Row -->

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">ID</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Nama Pengguna</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Nama Produk</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Jumlah Pesanan</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Total Harga</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Catatan</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Nomor WA</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Metode Bayar</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Bank Tujuan</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Tanggal Transaksi</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Bukti Transfer</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT 
                                                pesanan.id AS pesanan_id,
                                                pesanan.user_id,
                                                user.nama AS nama_user,
                                                pesanan.produk_id,
                                                produk.nama AS nama_produk,
                                                pesanan.jumlah,
                                                pesanan.total_harga,
                                                pesanan.catatan,
                                                pesanan.metode,
                                                pesanan.bank_tujuan,
                                                pesanan.nomor_wa,
                                                pesanan.bukti,
                                                pesanan.tanggal AS tanggal_pesanan
                                            FROM 
                                                pesanan
                                            LEFT JOIN 
                                                user ON pesanan.user_id = user.id
                                            LEFT JOIN 
                                                produk ON pesanan.produk_id = produk.id";
                                    $result = $connection->prepare($sql);
                                    $result->execute();
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo ' <tr class="text-center">
                                                <th scope="row" style="vertical-align: middle;"><p class="text-primary fw-bold mb-0">'. $row['pesanan_id'] .'</p></th>
                                                <td style="vertical-align: middle;">'. $row['nama_user'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['nama_produk'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['jumlah'] .'</td>
                                                <td style="vertical-align: middle;">Rp. '. number_format($row['total_harga'], 2, ',', '.') .'</td>
                                                <td style="vertical-align: middle;">'. $row['catatan'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['nomor_wa'].'</td>
                                                <td style="vertical-align: middle;">'. $row['metode'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['bank_tujuan'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['tanggal_pesanan'] .'</td>
                                                <td style="vertical-align: middle;"><a href="'. $row['bukti'] .'" target="_blank"><img src="'. $row['bukti'] .'" alt="bukti transfer" width="100"></a></td>
                                            </tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Diko Cesartista 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>