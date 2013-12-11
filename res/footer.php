<?php 

    $xml = simplexml_load_file('../conf/app_conf.xml');
    $json = json_encode($xml);
    $obj = json_decode($json);
        
    $app_copyright = $obj->APP_COPYRIGHT;  


?>

  <hr />
  <p class="footer">Copyright &copy; <?php echo $app_copyright; ?></p>
</body>
</html>
