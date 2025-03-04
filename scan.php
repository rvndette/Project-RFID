<!DOCTYPE html>
<html>
    <head>
        <?php include "header.php"; ?>
        <title>Scan Kartu</title>
       <link rel="stylesheet" type="text/css" href="style.css">

       <!-- scanning membaca kartu RFID -->
       <script type="text/javascript">
            var timeout;
            $(document).ready(function(){
                timeout = setTimeout(function(){
                    $('header').fadeOut();
                    $('footer').fadeOut();
                }, 1000);

                $(document).on('mousemove keydown', function(){
                    $('header').fadeIn();
                    $('footer').fadeIn();

                    clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        $('header').fadeOut();
                        $('footer').fadeOut();
                    }, 1000);
                });

                setInterval(function(){
                    $("#cekkartu").load('bacakartu.php')
                }, 4000);
            });
       </script>
         <header>
    <?php include "menu.php"; ?>
</header>
    </head>
  

    <body>
        <!-- isi -->
        <div class="container-fluid" style="text-align: center ">
            <div id="cekkartu"></div>
        </div>
        
    </body>
    <footer style="position: fixed; bottom: 0; width: 100%; text-align: center ;">
    <?php include "footer.php"; ?>
</footer>

</html>
