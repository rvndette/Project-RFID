<nav class="navbar navbar-inverse" style="background-color: #2c3e50; margin: 5px;">
    <div class="container-fluid" style="color: white;">
        <div class="navbar-header" style=>
            <a href="#" class="navbar-brand">ABSENSI</a>
        </div>
        <ul class="nav navbar-nav">
            <li <?php if(basename($_SERVER['PHP_SELF'])=='index.php'){echo 'class="active " ';} ?>> <a href="index.php">HOME </a> </li>
            <li <?php if(in_array(basename($_SERVER['PHP_SELF']), ['datamahasiswa.php', 'edit.php', 'tambah.php'])){echo 'class="active"';} ?>> <a href="datamahasiswa.php"> Data Mahasiswa </a> </li>
            <li <?php if(basename($_SERVER['PHP_SELF'])=='absensi.php'){echo 'class="active"';} ?>> <a href="absensi.php"> Rekapitulasi Absensi </a> </li>
            <li <?php if(basename($_SERVER['PHP_SELF'])=='scan.php'){echo 'class="active"';} ?>> <a href="scan.php"> Scan Kartu </a> </li>
        </ul>
    </div>
</nav>
