<?php 
$host = "localhost";
$user = "root";
$pass = "";
$db = "todolist";

$koneksi = new mysqli($host, $user, $pass, $db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$nama = "";
$deskripsi_tugas = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM mahasiswa WHERE id = $id";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Data gagal dihapus";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM mahasiswa WHERE id = $id";
    $q1 = mysqli_query($koneksi, $sql1);
    
    // Cek apakah query berhasil dan data ada
    if ($q1 && mysqli_num_rows($q1) > 0) {
        $r1 = mysqli_fetch_array($q1);
        $nama = $r1['nama'];
        // Periksa apakah key 'deskripsi_tugas' ada dalam array
        $deskripsi_tugas = isset($r1['deskripsi_tugas']) ? $r1['deskripsi_tugas'] : '';
    } else {
        $error = "Data tidak ditemukan"; // Tambahkan pesan error jika data tidak ditemukan
    }
}


if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $deskripsi_tugas = $_POST['deskripsi_tugas'];

    if ($nama && $deskripsi_tugas) {
        if ($op == 'edit') {
            $sql1 = "UPDATE mahasiswa SET nama = '$nama', deskripsi_tugas = '$deskripsi_tugas' WHERE id = $id";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data Berhasil diupdate";
            } else {
                $error = "Data Gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO mahasiswa (nama, deskripsi_tugas) VALUES ('$nama', '$deskripsi_tugas')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil Memasukkan Data Baru";
            } else {
                $error = "Gagal Memasukkan Data";
            }
        }
    } else {
        $error = "Silahkan Masukkan Semua Data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <!--memasukkan dataaaa -->
            <div class="card-header bg-primary text-dark text-center">
                TO DO LIST
            </div>

            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success " role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="deskripsi_tugas" class="col-sm-2 col-form-label">Deskripsi Tugas</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="deskripsi_tugas" name="deskripsi_tugas"><?php echo $deskripsi_tugas ?></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <!--mengeluarkan data-->
        <div class="card">
             <div class="card-header bg-primary text-dark text-center">
            List Tugas
             </div>

            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Deskripsi Tugas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM mahasiswa ORDER BY id DESC";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nama = $r2['nama'];
                            $deskripsi_tugas = $r2['deskripsi_tugas'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td><?php echo $nama ?></td>
                                <td><?php echo $deskripsi_tugas ?></td>
                                <td>
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-danger">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-warning">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
