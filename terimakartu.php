<?php
	include "koneksi.php";
	$nokartu = $_GET['nokartu'];
	//simpan nomor kartu yang baru ke tabel tmprfid
	$simpan = mysqli_query($konek, "insert into tmprfidscan(nokartu)values('$nokartu')");
	if($simpan){
		include "bacakartu.php";
	}
 ?>
