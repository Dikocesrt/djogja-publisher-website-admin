<?php
    session_start();

    require "koneksi.php";

    date_default_timezone_set('Asia/Jakarta');

    require 'vendor/autoload.php';

    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;

    Configuration::instance([
        'cloud' => [
            'cloud_name' => 'dmmwfystc',
            'api_key' => '465954757295179',
            'api_secret' => 'N0ZQidT7ZtCIrygzbi4fBkaVnD8',
        ],
        'url' => [
            'secure' => true
        ]
    ]);

    if (isset($_POST['tambah-produk'])) {
        $nama_produk = $_POST['nama'];
        $harga_produk = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_nama = $_FILES['gambar']['name'];

        try {
            $response = (new UploadApi())->upload($gambar_tmp, ['folder' => 'djogja-publisher']);

            $gambar_url = $response['secure_url'];

            $sql = "INSERT INTO produk (nama, harga, gambar, deskripsi, created_at) VALUES (?, ?, ?, ?, ?)";
            $result = $connection->prepare($sql);
            $result->execute([$nama_produk, $harga_produk, $gambar_url, $deskripsi, date('Y-m-d H:i:s')]);
        } catch (Exception $e) {
            echo '<script>alert("Gagal upload gambar karena ' . $e->getMessage() . '");</script>';
        }
    }

    if (isset($_POST['edit-produk'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];

        if (!empty($_FILES['gambar']['tmp_name'])) {
            $gambar_tmp = $_FILES['gambar']['tmp_name'];
            $gambar_nama = $_FILES['gambar']['name'];

            try {
                $response = (new UploadApi())->upload($gambar_tmp, ['folder' => 'djogja-publisher']);
    
                $gambar_url = $response['secure_url'];
    
                $sql = "UPDATE produk SET nama = ?, harga = ?, gambar = ?, deskripsi = ? WHERE id = ?";
                $result = $connection->prepare($sql);
                $result->execute([$nama, $harga, $gambar_url, $deskripsi, $id]);
            } catch (Exception $e) {
                echo '<script>alert("Gagal upload gambar karena ' . $e->getMessage() . '");</script>';
            }
        }else{
            $sql = "UPDATE produk SET nama = ?, harga = ?, deskripsi = ? WHERE id = ?";
            $result = $connection->prepare($sql);
            $result->execute([$nama, $harga, $deskripsi, $id]);
        }
    }

    if (isset($_POST['delete-produk'])) {
        $id = $_POST['id'];

        $sql = "SELECT * FROM pesanan WHERE produk_id = ?";
        $result = $connection->prepare($sql);
        $result->execute([$id]);
        if ($result->rowCount() > 0) {
            echo '<script>alert("Tidak bisa menghapus produk karena terdapat transaksi dengan produk ini");</script>';
            // header("Location: produk.php");
        }else{
            $sql = "DELETE FROM produk WHERE id = ?";
            $result = $connection->prepare($sql);
            $result->execute([$id]);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Produk</title>

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
            <li class="nav-item active">
                <a class="nav-link collapsed" href="produk.php">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Produk</span>
                </a>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
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
                        <h1 class="h3 mb-0 text-gray-800">Data Produk</h1>
                    </div>
                    
                    <!-- Content Row -->
                    <div class="row">

                        <?php
                            $sql = "SELECT COUNT(*) AS total_data_produk FROM produk";
                            $result = $connection->prepare($sql);
                            $result->execute();
                            $firstRow = $result->fetch(PDO::FETCH_ASSOC);
                            $totalDataProduk = $firstRow['total_data_produk'];
                            // Data Produk
                            echo '<div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Jumlah Produk</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">' . $totalDataProduk . '</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-boxes-stacked fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>'
                        ?>

                        <?php
                            $currentDateWIB = date('Y-m-d', strtotime('now'));
                            $sql = "SELECT COUNT(*) AS total_data_produk FROM produk WHERE DATE(CONVERT_TZ(created_at, @@global.time_zone, '+07:00')) = ?";
                            $result = $connection->prepare($sql);
                            $result->execute([$currentDateWIB]);
                            $firstRow = $result->fetch(PDO::FETCH_ASSOC);
                            $totalDataProduk = $firstRow['total_data_produk'];
                            // Data Produk
                            echo '<div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Publish Hari Ini</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">'. $totalDataProduk  .'</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-box fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>'
                        ?>
                    </div>
                    
                    <div class="d-sm-flex align-items-center justify-content-end mb-2">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Tambah Data
                        </button>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Produk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-4">
                                    <form method="post" name="tambah-produk" enctype="multipart/form-data">
                                        <!-- Nama input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Produk" />
                                        </div>

                                        <!-- Harga input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="number" id="harga" name="harga" class="form-control" placeholder="Masukkan Harga Produk" />
                                        </div>

                                        <!-- Deskripsi input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi Produk" />
                                        </div>
                    
                                        <!-- Gambar input -->
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="file" id="gambar" name="gambar" placeholder="Masukkan Gambar"/>
                                        </div>
                    
                                        <!-- Submit button -->
                                        <input type="submit" name="tambah-produk" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block" value="Tambah Data"></input>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">ID</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Gambar</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Nama Produk</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Harga Produk</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Deskripsi</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Tanggal Publish</p></th>
                                    <th scope="col"><p class="text-primary text-center fw-bold mb-0">Aksi</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT * FROM produk";
                                    $result = $connection->prepare($sql);
                                    $result->execute();
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo ' <tr class="text-center">
                                                <th scope="row" style="vertical-align: middle;"><p class="text-primary fw-bold mb-0">'. $row['id'] .'</p></th>
                                                <td style="vertical-align: middle;"><a href="'. $row['gambar'] .'" target="_blank"><img src="'. $row['gambar'] .'" alt="gambar buku" width="100" height="130"></a></td>
                                                <td style="vertical-align: middle;">'. $row['nama'] .'</td>
                                                <td style="vertical-align: middle;">Rp. '. number_format($row['harga'], 2, ',', '.') .'</td>
                                                <td style="vertical-align: middle;">'. $row['deskripsi'] .'</td>
                                                <td style="vertical-align: middle;">'. $row['created_at'] .'</td>
                                                <td style="vertical-align: middle;">
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal" data-id="'. $row['id'] .'" data-nama="'. $row['nama'] .'" data-harga="'. $row['harga'] .'" data-deskripsi="'. $row['deskripsi'] .'">Edit</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="'. $row['id'] .'">Delete</button>
                                                </td>
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

            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Data Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="post" name="edit-produk" enctype="multipart/form-data">
                                <!-- Nama input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Produk" />
                                </div>

                                <!-- Harga input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="number" id="harga" name="harga" class="form-control" placeholder="Masukkan Harga Produk" />
                                </div>

                                <!-- Deskripsi input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi" />
                                </div>

                                <!-- Gambar input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="file" id="gambar" name="gambar" placeholder="Masukkan Gambar"/>
                                </div>

                                <input type="hidden" name="id" id="id">

                                <!-- Submit button -->
                                <input type="submit" name="edit-produk" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block" value="Edit Data"></input>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Yakin Ingin Menghapus Data?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Data yang di hapus tidak dapat dikembalikan.</div>
                        <form method="post" name="delete-produk">
                            <input type="hidden" name="id" id="id">
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" name="delete-produk" type="submit">Hapus</>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Tadika Mesra 2024</span>
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

    <script>
        // Event listener untuk menampilkan modal dan mengisi field dengan data yang sesuai
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button yang men-trigger modal
            var nama = button.data('nama'); // Ambil data-nama
            var harga = button.data('harga'); // Ambil data-harga
            var deskripsi = button.data('deskripsi');
            var id = button.data('id'); // Ambil data-id

            var modal = $(this);
            modal.find('.modal-body input#nama').val(nama);
            modal.find('.modal-body input#harga').val(harga);
            modal.find('.modal-body input#deskripsi').val(deskripsi);
            modal.find('.modal-body input#id').val(id);
            // Tidak melakukan apa pun pada field gambar
        });

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button yang men-trigger modal
            var id = button.data('id'); // Ambil data-id

            var modal = $(this);
            modal.find('input#id').val(id);
        });
    </script>

</body>

</html>