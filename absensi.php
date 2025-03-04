<!DOCTYPE html>
<html>
    <head>
        <?php include "header.php"; ?>
        <title>Rekapitulasi Absensi</title>
       <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <?php include "menu.php"; ?>

        <!-- isi -->
        <div class="container-fluid">
            <h3>Rekap Absensi</h3>

            <form method="GET" action="" id="myForm">
    <div class="table-header" style="display: inline-block;">
        <label for="id_kelas">Pilih Kelas</label><br>
        <select name="id_kelas" id="id_kelas" style="width: 250px; height: 32px;" required>
            <option value="">- Pilih Kelas -</option>
            <?php 
                include "koneksi.php";
                $filter = mysqli_query($konek, "SELECT * FROM kelas");
                while($data = mysqli_fetch_array($filter)){
                    $selected = "";
                    if(isset($_GET['id_kelas']) && $_GET['id_kelas'] == $data['id_kelas']){
                        $selected = "selected";
                    }
                    echo "<option value='$data[id_kelas]' $selected>$data[singkatan]</option>";
                }
            ?>
        </select>
    </div>

    <div class="table-header" style="display: inline-block; margin-left: 5px;">
    <label for="hari">Pilih Hari</label><br>
    <select name="hari" id="hari" style="width: 100px; height: 32px;" required>
        <option value="">- Pilih Hari -</option>
        <option value="1"<?php if(isset($_GET['hari']) && $_GET['hari'] == '1') echo ' selected="selected"'; ?>>Senin</option>
        <option value="2"<?php if(isset($_GET['hari']) && $_GET['hari'] == '2') echo ' selected="selected"'; ?>>Selasa</option>
        <option value="3"<?php if(isset($_GET['hari']) && $_GET['hari'] == '3') echo ' selected="selected"'; ?>>Rabu</option>
        <option value="4"<?php if(isset($_GET['hari']) && $_GET['hari'] == '4') echo ' selected="selected"'; ?>>Kamis</option>
        <option value="5"<?php if(isset($_GET['hari']) && $_GET['hari'] == '5') echo ' selected="selected"'; ?>>Jumat</option>
        <option value="6"<?php if(isset($_GET['hari']) && $_GET['hari'] == '6') echo ' selected="selected"'; ?>>Sabtu</option>
        <option value="7"<?php if(isset($_GET['hari']) && $_GET['hari'] == '7') echo ' selected="selected"'; ?>>Minggu</option>
    </select>
</div>


    <button type="button" id="resetBtn" style="display: none; margin-left: 5px;height: 32px;" class="btn btn-danger" onclick="window.location.href='absensi.php'">Reset</button>

    </form>
    <script>
        const selectKelas = document.getElementById('id_kelas');
        const selectHari = document.getElementById('hari');
        const resetBtn = document.getElementById('resetBtn');

        selectKelas.addEventListener('change', () => {
            showResetButton();
        });

        selectHari.addEventListener('change', () => {
            showResetButton();
        });

        function showResetButton() {
            if (selectKelas.value !== '' || selectHari.value !== '') {
                resetBtn.style.display = 'inline-block';
            } else {
                resetBtn.style.display = 'none';
            }
        }

        resetBtn.addEventListener('click', () => {
            document.getElementById('id_kelas').value = '';
            document.getElementById('hari').value = '';
            resetBtn.style.display = 'none';
            submitBtn.style.display = 'none';
            window.location.href = "absensi.php?id_kelas=&hari=";
           
        });

        function submitForm() {
            document.getElementById("myForm").submit();
        }

        selectKelas.addEventListener('change', () => {
            submitForm();
        });

        selectHari.addEventListener('change', () => {
            submitForm();
        });

        showResetButton(); // tambahkan baris ini
    </script>



            


            <table class="table table-bordered" style="margin-top: 20px">
                <thead>
                    <tr style="background-color: grey; color:white; height:100%;">
                        <th style="width: 10px; text-align: center">No.</th>
                        <th style="width: 175px; text-align: center">NIM</th>
                        <th style="width: 300px; text-align: center">NAMA</th>
                        <th style="width: 250px; text-align:center">KELAS PRAKTIKUM</th>
                        <th style="width: 40px; text-align: center">TANGGAL</th>
                        <th style="width: 30px; text-align: center">JAM ABSENSI</th>
                        <th style="width: 150px; text-align: center">KETERANGAN</th>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include "koneksi.php";

                    // baca tanggal saat ini
                    date_default_timezone_set('Asia/Jakarta');
                    $tanggal = date('Y-m-d');

                    // cek apakah filter kelas atau hari dipilih
                    if (isset($_GET['id_kelas']) && !empty($_GET['id_kelas'])) {
                        // jika filter kelas dipilih
                        $id_kelas = $_GET['id_kelas'];
                        $sql = mysqli_query($konek, "SELECT mhw.nama, absen.nokartu, kls.singkatan, absen.tanggal, absen.jam_absensi, absen.keterangan 
                                                    FROM absensi AS absen
                                                    JOIN mahasiswa AS mhw ON absen.nokartu = mhw.nokartu
                                                    JOIN kelas AS kls ON absen.id_kelas = kls.id_kelas
                                                    WHERE absen.id_kelas = '$id_kelas'");
                    } else if (isset($_GET['hari']) && !empty($_GET['hari'])) {
                        //mendapatkan nilai filter hari dari form
                        $hari = $_GET['hari'];
                      
                        //mengubah nilai filter hari menjadi nama hari
                        switch ($hari) {
                          case '1':
                            $nama_hari = "Monday";
                            break;
                          case '2':
                            $nama_hari = "Tuesday";
                            break;
                          case '3':
                            $nama_hari = "Wednesday";
                            break;
                          case '4':
                            $nama_hari = "Thursday";
                            break;
                          case '5':
                            $nama_hari = "Friday";
                            break;
                          case '6':
                            $nama_hari = "Saturday";
                            break;
                          case '7':
                            $nama_hari = "Sunday";
                            break;
                          default:
                            $nama_hari = "";
                            break;
                        }
                      
                        //menampilkan data absensi berdasarkan filter hari yang dipilih
                        $sql = mysqli_query($konek, "SELECT mhw.nama, absen.nokartu, kls.singkatan, absen.tanggal, absen.jam_absensi, absen.keterangan 
                                                      FROM absensi AS absen
                                                      JOIN mahasiswa AS mhw ON absen.nokartu = mhw.nokartu
                                                      JOIN kelas AS kls ON absen.id_kelas = kls.id_kelas
                                                      WHERE DAYNAME(absen.tanggal) = '$nama_hari'");

                      } else {
                        // jika tidak ada filter yang dipilih, tampilkan data sesuai dengan tanggal saat ini
                        $sql = mysqli_query($konek, "SELECT mhw.nama, absen.nokartu, kls.singkatan, absen.tanggal, absen.jam_absensi, absen.keterangan 
                                                    FROM absensi AS absen
                                                    JOIN mahasiswa AS mhw ON absen.nokartu = mhw.nokartu
                                                    JOIN kelas AS kls ON absen.id_kelas = kls.id_kelas
                                                    WHERE absen.tanggal = '$tanggal'");
                    }
                    

                    $no = 0;
                    while($data = mysqli_fetch_array($sql))
                    { 
                        $no++;

                        // konversi hari ke dalam Bahasa Indonesia
                        switch(date('l', strtotime($data['tanggal'])))
                        {
                            case 'Monday':
                                $hari = 'Senin';
                                break;
                            case 'Tuesday':
                                $hari = 'Selasa';
                                break;
                            case 'Wednesday':
                                $hari = 'Rabu';
                                break;
                            case 'Thursday':
                                $hari = 'Kamis';
                                break;
                            case 'Friday':
                                $hari = 'Jumat';
                                break;
                            case 'Saturday':
                                $hari = 'Sabtu';
                                break;
                            case 'Sunday':
                                $hari = 'Minggu';
                                break;
                            default:
                                $hari = '';
                                break;
                        }
                    ?>
                    <tr> <!-- Ini beberapa database di bawah ini kalau emang bisa mengambil data dari database yang ada kamu ubah aja, soalnya aku ga paham logic database yang berbeda, data di bawah ini aku ambil dari database absensi -->
                        <td style="text-align: center;"><?php echo $no; ?></td>
                        <td><?php echo $data['nokartu'] ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td style="text-align: center;"><?php echo $data['singkatan'] ?></td>
                        <td style="text-align: center;"><?php echo "$hari, " . date("d-m-Y", strtotime($data['tanggal'])); ?></td>
                        <td style="text-align: center;"><?php echo $data['jam_absensi'] ?></td>
                        <td style="text-align: center;"><?php echo $data['keterangan'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php include "footer.php"; ?>
    </body>
</html>
