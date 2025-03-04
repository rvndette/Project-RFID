<?php
include "koneksi.php";

$cari = mysqli_query($konek, "SELECT * FROM `kirimdata`");
$hasil = mysqli_fetch_array($cari);
$nokartu = $_GET['nokartu'];
mysqli_query($konek, "delete from tmprfiddaftar");
$simpan = mysqli_query($konek, "insert into tmprfiddaftar(nokartu)values('$nokartu')");
$nim = $hasil['nokartu'];
if ($simpan) {
	// echo "Write\n";
	if ($hasil != null)
		echo $nim;
		mysqli_query($konek, "delete from tmprfidscan");
	$nokartu2 = $_GET['nokartu'];
	if ($nim ==  $nokartu2) {
		echo "Done";
		mysqli_query($konek, "delete from tmprfidscan");
		mysqli_query($konek, "delete from tmprfiddaftar");
		mysqli_query($konek, "delete from kirimdata");
	}
}

