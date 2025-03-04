<?php
    include "koneksi.php";

    //baca ID Data yang akan dihapus
    $id = $_GET['id'];

   if(isset($_POST['hapus'])){
        //hapus data
        $hapus = mysqli_query($konek, "delete from mahasiswa where id='$id'");

        //jika berhasil terhapus tampilkan pesan Data Berhasil Terhapus
        //kemudian kembali ke Data Mahasiswa
        if($hapus)
        {
            echo "
                <script>
                    location.replace('datamahasiswa.php');
                </script>
                ";
        }
        else{
            echo "
                <script>
                    location.replace('datamahasiswa.php');
                </script>
                ";
        }
   }
?>


<!DOCTYPE html>
<html>
    <head>
       <?php include "header.php"; ?>
        <title>Konfirmasi Hapus Data</title>
        <link rel="stylesheet" type="text/css" href="style.css">

    </head>

    <body>
    <h3>Konfirmasi Hapus Data</h3>
    <p>Apakah anda yakin akan menghapus data ini?</p>
    <form method="post">
        <input type="submit" name="hapus" value="Hapus">
        <input type="button" value="Batal" onclick="location.href='datamahasiswa.php'">
    </form>

    <?php include "footer.php"; ?>
</body>
</html>

