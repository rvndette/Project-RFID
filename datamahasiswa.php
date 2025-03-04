<!DOCTYPE html>
<html>

<head>
    <?php include "header.php"; ?>
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web/css/all.min.css">
    <script type="text/javascript" src="./fontawesome-free-6.4.0-web/js/all.min.js"></script>


    <style>
        #hapusTeks {
            display: none;
        }

        <?php
        if (isset($_GET['q']) && $_GET['q'] != '') {
            echo '#hapus_search { display: inline-block; }';
        } else {
            echo '#hapus_search { display: none; }';
        }
        ?>
    </style>
</head>

<body>


    <?php include "menu.php"; ?>



    <!--ISI-->


    <div class="container-fluid" style="width:100%;">
        <h3>Data Mahasiswa</h3>



        <style>
            .form-container {
                border: 1px solid black;
                padding: 0px;
                display: inline-block;
                border-radius: 4px;
            }

            .form-container input[type="text"] {
                width: 150px;
                background-color: white;
                border: 0px solid #ccc;
                border-radius: 4px;
                outline: none;
                /* Menghapus outline border */
                padding: 5px;
            }

            .form-container span {
                width: 20px;
                background-color: white;
                border: 0px solid #ccc;
                border-radius: 4px;
                outline: none;
                /* Menghapus outline border */
                padding: 5px;
                display: inline-block;
            }
        </style>

        <!-- form pencarian -->
        <div class="table-header" style="float: left; margin-right: 0;">
            <form method="GET" action="">
                <div class="form-container" style="width: 180px;">
                    <td><input type="text" id="myInput" name="q" placeholder="Masukkan Kata Kunci" style="width: 150px; background-color: white; border-radius: 4px; display: inline-block;" value="<?php echo isset($_GET['q']) ? $_GET['q'] : '' ?>"></td>
                    <td><span type="button" id="hapus_search" style="width: 20px; background-color: white; border-radius: 4px;"><i class="fa-solid fa-x"></i></span></td>
                </div>
                <div class="table-header" style="float: right; padding-left: 4px">
                    <button type="submit" class="btn btn-primary" style="height: 34px;"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>

            </form>
        </div>

        <script>
            document.getElementById("myInput").addEventListener("input", function() {
                if (this.value !== "") {
                    document.getElementById("hapus_search").style.display = "inline-block";
                } else {
                    document.getElementById("hapus_search").style.display = "none";
                }
            });

            document.getElementById("hapus_search").addEventListener("click", function() {
                document.getElementById("myInput").value = "";
                this.style.display = "none";
                document.querySelector('form').submit();
            });
        </script>



        <!-- tombol tambah data mahasiswa -->
        <div class="table-header" style="float: right;">
            <a href="tambah.php"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Data Mahasiswa</button></a>
        </div>



        <div style="clear: both;"></div>

        <table class="table table-bordered" style="margin-top: 10px; ">

            <thead>
                <tr style="background-color: grey; color: white; height:100%;">
                    <th style="width: 10px; text-align: center">No.</th>
                    <th style="width: 175px; text-align: center">NIM</th>
                    <th style="width: 10%; text-align: center">ANGKATAN</th>
                    <th style="width: 300px; text-align: center">NAMA</th>
                    <th style="width: 300px; text-align: center">KELAS PRAKTIKUM</th>
                    <th style="width: 4.75%; text-align: center">AKSI</th>
                </tr>
            </thead>

            <tbody>

                <?php
                // koneksi ke database
                include "koneksi.php";

                // cek apakah ada data yang dicari

                if (isset($_GET['q']) && $_GET['q'] != '') {
                    $keyword = $_GET['q'];
                    // query pencarian data mahasiswa
                    $sql = mysqli_query($konek, "SELECT mahasiswa.*, GROUP_CONCAT(kelas.singkatan SEPARATOR ', ') as singkatan 
                FROM mahasiswa 
                LEFT JOIN kelas ON FIND_IN_SET(kelas.id_kelas, mahasiswa.id_kelas) 
                WHERE mahasiswa.nokartu LIKE '%$keyword%' OR mahasiswa.nama LIKE '%$keyword%' OR kelas.singkatan LIKE '%$keyword%'
                GROUP BY mahasiswa.id");

                    if (mysqli_num_rows($sql) > 0) {
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; font-size: 15px; color: red;'>Tidak ada hasil yang ditemukan. Silakan periksa kembali kata kunci Anda.</td></tr>";
                    }
                } else {
                    // query menampilkan seluruh data mahasiswa
                    $sql = mysqli_query($konek, "SELECT mahasiswa.*, GROUP_CONCAT(kelas.singkatan SEPARATOR ', ') as singkatan FROM mahasiswa LEFT JOIN kelas ON FIND_IN_SET(kelas.id_kelas, mahasiswa.id_kelas) GROUP BY mahasiswa.id");
                }

                $no = 0;
                while ($data = mysqli_fetch_array($sql)) {
                    $no++;
                    $angkatan = substr($data['nokartu'], 0, 4); // ambil 4 digit awal dari NIM
                ?>

                    <tr>
                        <td style="text-align: center"> <?php echo $no; ?> </td>
                        <td style="text-align: center"> <?php echo $data['nokartu']; ?> </td>
                        <td style="text-align: center"> <?php echo $angkatan; ?> </td> <!-- tambahkan kolom Angkatan -->
                        <td style="text-align: center"> <?php echo $data['nama']; ?> </td>
                        <td style="text-align: center;"> <?php echo $data['singkatan']; ?> </td>
                        <td style="text-align: center;">
                            <a href="edit.php?id=<?php echo $data['id']; ?>"><button class="btn btn-success btn-sm"><i class="fa-regular fa-pen-to-square"></i></button></a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusModal<?php echo $data['id']; ?>"><i class="fa-solid fa-trash"></i></button>

                        </td>
                    </tr>


                    <?php include "koneksi.php"; ?>
                    <!-- Modal Hapus Mahasiswa -->
                    <div class="modal fade" id="hapusModal<?php echo $data['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel" aria-hidden="true">
                        <div class="modal-dialog " style="justify-content: center; margin-top: 15%; width: 350px" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus Data</h2>

                                </div>
                                <div class="modal-body">
                                    <h4>Apakah anda yakin akan menghapus data ini?</h4>
                                </div>
                                <div class="modal-footer">
                                    <form action="hapus.php?id=<?php echo $data['id']; ?>" method="post">
                                        <input type="submit" class="btn btn-danger" name="hapus" value="Hapus">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>




    </div>
<?php } ?>
</tbody>
</table>

</div>



</body>

<?php include "footer.php" ?>

</html>