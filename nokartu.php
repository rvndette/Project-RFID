<?php
    include "koneksi.php";
    //Baca isi tabel tmprfid
    $sql = mysqli_query($konek, "SELECT * from tmprfiddaftar ");
    if (mysqli_num_rows($sql) > 0) {
        $data = mysqli_fetch_array($sql);
        //Membaca No Kartu
        $nokartu = $data['nokartu'];
    } 
?>
<!-- INPUTAN UNTUK NOMOR KARTU -->
<div class="form-group">
    <label>Nomor Kartu Sekarang</label>
    <?php
    $cari = mysqli_query($konek, "SELECT * FROM `tmprfiddaftar` ");
    $hasil = mysqli_fetch_array($cari);
        if (empty($hasil['nokartu'])) {
            echo '<div class="input-group">
                    <input type="text" name="tampilnomor" id="nokartu" placeholder="Harap Menempelkan Kartu" class="form-control" style="width: 250px" readonly>
                   <br><label style="color: red; font-size: smaller;">*Pastikan Kartu Tetap Menempel Pada RFID</label>
                </div>';
        } else {
            echo '<div class="input-group">
                    <input type="text" name="tampilnomor" id="nokartu" placeholder="Ubah No. Kartu sesuai NIM" class="form-control" style="width: 250px" readonly value="' . $hasil['nokartu'] . '">
                    <br><label style="color: red; font-size: smaller;">*Pastikan Kartu Tetap Menempel Pada RFID</label>
                    </div>';
        }
    ?>
</div>