<!DOCTYPE html>
<html>

<head>
    <?php include "header.php"; ?>
    <title>Tambah Data Mahasiswa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="./fontawesome-free-6.4.0-web/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./css/all.min.css">
    <script type="text/javascript" src="./fontawesome-free-6.4.0-web/js/all.min.js"></script>
    <script type="text/javascript" src="./js/multiselect-dropdown.js"></script>
</head>

<body>
    <?php include "menu.php"; ?>
    <?php include "koneksi.php";
    //baca Data Mahasiswa berdasarkan id
    $cari = mysqli_query($konek, "SELECT * FROM `tmprfiddaftar`");
    $hasil = mysqli_fetch_array($cari);
    $tambah_kelas = mysqli_query($konek, "SELECT * FROM `kelas`");
    $data = mysqli_fetch_array($tambah_kelas);
    mysqli_query($konek, "TRUNCATE TABLE kirimdata");
    ?>


    <!--Pembacaan No Kartu Otomatis-->
    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function() {
                $("#tampilnomor").load('nokartu.php')
            }, 0)
        });
    </script>

    <script>
        var nokartuInput = document.getElementById("nokartu");
        nokartuInput.addEventListener("input", function() {
            var value = this.value;
            var numericValue = value.replace(/\D/g, ""); // Menghapus karakter non-angka dari input

            // Jika nilai input tidak berubah setelah menghapus karakter non-angka,
            // berarti input hanya berisi angka yang valid
            if (value !== numericValue) {
                this.value = numericValue; // Memperbarui nilai input dengan nilai numerik yang valid
            }
        });
    </script>
    <!-- isi-->

    <form id="form-mahasiswa" method="POST">
        <!-- form input -->
        <div class="container-fluid">

            <h3>Tambah Data Mahasiswa</h3>

            <!-- INPUTAN UNTUK NOMOR KARTU-->
            <div id="tampilnomor"></div>

            <!-- INPUTAN UNTUK NIM-->
            <div class="form-group">
                <label>Nomor Induk Mahasiswa (NIM)</label>
                <div class="input-group">
                    <input type="text" name="nokartu" id="nokartu" placeholder="Ubah No. Kartu sesuai NIM" class="form-control" style="width: 250px; border-radius: 4px;" required>
                </div>
            </div>

            <!--INPUTAN UNTUK NAMA MAHASISWA-->
            <div class="form-group">
                <label>Nama Mahasiswa</label>
                <input type="text" name="nama" id="nama" placeholder="Nama Mahasiswa" class="form-control" style="width: 250px" required>

            </div>



            <!-- INPUTAN UNTUK KELAS PRAKTIKUM -->

            <style type="text/css">
                select {
                    width: 265px;
                    padding: 5px
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var selectElement = document.querySelector("select[name='select']");
                    var options = selectElement.options;

                    selectElement.addEventListener('input', function() {
                        var searchValue = this.value.toUpperCase();

                        for (var i = 0; i < options.length; i++) {
                            var option = options[i];
                            var nama = option.getAttribute('data-nama').toUpperCase();

                            if (nama.includes(searchValue)) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                });
            </script>

            <div class="form-group">
                <label for="id_kelas">Kelas Praktikum</label><br>
                <select name="select[]" multiple multiselect-search="true" multiselect-select-all="true" multiselect-select-max-items="true">
                    <?php
                    $tambah_kelas = mysqli_query($konek, "SELECT * FROM kelas");
                    while ($data = mysqli_fetch_array($tambah_kelas)) {
                        echo "<option value='$data[id_kelas]'>$data[singkatan]</option>";
                    }
                    ?>
                </select>
            </div>

            <button class="btn btn-primary" type="submit" name="btnSimpan">Simpan</button>
            <button class="btn btn-warning" name="batal" onclick="location.href='datamahasiswa.php'">Batal</button>
        </div>
    </form>

    <?php include "koneksi.php";
    //kondisi jika tombol simpan diklik
    if (isset($_POST['btnSimpan'])) {
        // Baca isi inputan form
        $nokartu = $_POST['nokartu'];
        $nama = $_POST['nama'];

        // Periksa apakah $_POST['select'] terdefinisi dan merupakan array
        $id_kelas = '';
        if (isset($_POST['select']) && is_array($_POST['select'])) {
            $id_kelas = implode(",", $_POST['select']);
        }

        $cek_kelas = mysqli_query($konek, "SELECT * FROM kelas WHERE id_kelas='$id_kelas'");
        if (empty($id_kelas) || $id_kelas == '0') {
            echo "
                            <script>
                            $(document).ready(function() {
                                $('#kelas_kosong').modal('show');
                                $('#nokartu').val('$nokartu');
                                $('#nama').val('$nama');
                            });
                            </script>
        ";
        } else {
            //validasi inputan id_kelas
            $cek_kelas = mysqli_query($konek, "SELECT * FROM kelas WHERE id_kelas='$id_kelas'");
            $count_kelas = mysqli_num_rows($cek_kelas);
            if ($count_kelas == 0) {
                echo "
                            <script>
                            $(document).ready(function() {
                                $('#kelas_kosong').modal('show');
                                $('#nokartu').val('$nokartu');
                                $('#nama').val('$nama');
                            });
                            </script>
                        ";
            } else {
                //mengecek apakah ada data yang sama
                $cek_nokartu = mysqli_query($konek, "SELECT * FROM mahasiswa WHERE nokartu='$nokartu'");
                $cek_nama = mysqli_query($konek, "SELECT * FROM mahasiswa WHERE nama='$nama'");
                if (mysqli_num_rows($cek_nokartu) > 0) {
                    echo "
                                <script>
                                    $(document).ready(function() {
                                    $('#nim_sama').modal('show');
                                    $('#nokartu').val('$nokartu');
                                    $('#nama').val('$nama');
                                    });
                                </script>
                                ";
                } else if (strlen($nokartu) != 15) {
                    echo "<script>
                            $(document).ready(function() {
                            $('#nim_15').modal('show');
                            $('#nokartu').val('$nokartu');
                            $('#nama').val('$nama');
                            });</script>";
                } else if (mysqli_num_rows($cek_nama) > 0) {
                    echo "
                                <script>
                                    $(document).ready(function() {
                                    $('#nama_sama').modal('show');
                                    $('#nokartu').val('$nokartu');
                                    $('#nama').val('$nama');
                                    });
                                </script>
                                ";
                } else if (!is_numeric($nokartu)) {
                    echo "
                                <script>
                                    $(document).ready(function() {
                                    $('#bukan_angka').modal('show');
                                    $('#nokartu').val('$nokartu');
                                    $('#nama').val('$nama');
                                    });
                                </script>
                                ";
                } else {

                    //simpan ke tabel mahasiswa
                    $simpan = mysqli_query($konek, "insert into mahasiswa(nokartu, nama, id_kelas)values('$nokartu', '$nama', '$id_kelas')");
                    // kirim nomor kartu ke tabel kirimdata untuk diproses di adddata.php
                    $insert_data = mysqli_query($konek, "INSERT INTO kirimdata(nokartu) VALUES ('$nokartu')");

                    if ($simpan) {
                        // menghapus nomor kartu dari tabel tmprfid
                        $hapus = mysqli_query($konek, "DELETE FROM `tmprfiddaftar` WHERE 1");
                        echo "
                                <script>
                                    $(document).ready(function() {
                                    $('#pesan_berhasil').modal('show');
                                    });
                                </script>";
                        mysqli_query($konek, "delete from tmprfidscan");
                    } else {
                        echo "
                                <script>
                                    $(document).ready(function() {
                                    $('#pesan_gagal').modal('show');
                                    });
                                </script>";
                    }
                }
            }
        }
    }
    if (isset($_POST['batal'])) {
        echo "
                <script>
                    location.replace('datamahasiswa.php');
                </script>";
    }
    ?>
    <!-- modal Berhasil -->
    <div class="modal fade" id="pesan_berhasil" tabindex="-1" role="dialog" aria-labelledby="pesanberhasilLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 350px; margin-top: 5%;">
            <div class="modal-content">
                <div class="modal-body" style="background-color: green; color: white">
                    <h4><strong>Data Berhasil Tersimpan</strong></h4>
                </div>
                <div class="modal-body">
                    <h4>Silahkan Tekan "OKE"</h4>
                    <h4>Tempelkan Ulang Kartu RFID</h4>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="color: blue; border: 1px solid black; border-color: blue" data-dismiss="modal" onclick="window.location.href='datamahasiswa.php'">Oke</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal Gagal -->
    <div class="modal fade" id="pesan_gagal" tabindex="-1" role="dialog" aria-labelledby="pesan gagal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: red; color: white">
                    <h4><strong>Data Gagal Tersimpan</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: blue; border: 1px solid black; border-color: red" data-dismiss="modal" onclick="window.location.href='datamahasiswa.php'">Oke</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- modal Nim Sama -->
    <div class="modal fade" id="nim_sama" tabindex="-1" role="dialog" aria-labelledby="nim sama" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: 	#DAA520; color: black">
                    <h4><strong>NIM sudah terdaftar. Silakan masukkan NIM lain.</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black; border: 1px solid black; border-color: yellow" data-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- modal Nim 15 -->
    <div class="modal fade" id="nim_15" tabindex="-1" role="dialog" aria-labelledby="nim sama" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: 	#DAA520; color: black">
                    <h4><strong>NIM harus berjumlah 15 digit.</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black; border: 1px solid black; border-color: yellow" data-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- modal Nim Bukan Angka -->
    <div class="modal fade" id="bukan_angka" tabindex="-1" role="dialog" aria-labelledby="nim sama" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: 	#DAA520; color: black">
                    <h4><strong>NIM harus berupa angka.</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black; border: 1px solid black; border-color: yellow" data-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- modal Nama Sama -->
    <div class="modal fade" id="nama_sama" tabindex="-1" role="dialog" aria-labelledby="nama sama" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color:	#DAA520; color: black">
                    <h4><strong>Nama sudah terdaftar. Silakan masukkan Nama lain.</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black; border: 1px solid black; border-color: yellow" data-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- modal Pilih Kelas -->
    <div class="modal fade" id="kelas_kosong" tabindex="-1" role="dialog" aria-labelledby="kelas_kosong" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: 	#DAA520; color: black">
                    <h4><strong>Harap memilih kelas terlebih dahulu.</strong></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="color: black; border: 1px solid black; border-color: yellow" data-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener("keyup", function(event) {
            if (event.key === "Enter") {
                document.getElementById("btnSimpan").click();
            }
        });
    </script>

    <?php include "footer.php"; ?>
</body>

</html>