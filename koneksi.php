<?php
    // urutan = server, userdb, passdb, namadb
    $host = "localhost";
    $port = 3306;
    $username = "root";
    $password = "";
    $dbname = "wirooo";

    // buat koneksi
    $konek = mysqli_connect($host, $username, $password, $dbname, $port);

    // mengecek koneksi
    if (!$konek) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>
