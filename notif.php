<?php 
    include "koneksi.php";
    $cari = mysqli_query($konek, "SELECT * FROM `notif_device`");
    $hasil = mysqli_fetch_array($cari);
    if($hasil != null){
        $hasil = $hasil['kode'];
        echo $hasil;
    }
    
    mysqli_query($konek, "DELETE FROM notif_device");
    sleep(1);
    
    while(mysqli_fetch_array($cari) != null){
        mysqli_query($konek, "DELETE FROM notif_device");
        sleep(1);
    }
?>