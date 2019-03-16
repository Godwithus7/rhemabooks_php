<?php
    $db = mysqli_connect('localhost', 'root','','rhemabooks');
    if(mysqli_connect_errno()){
        echo 'Database connection failed with the following errors: '. mysqli_connect_errno();
        die();
    }
    require_once $_SERVER['DOCUMENT_ROOT'].'/rhemabooks/config.php';
    require_once BASEURL.'helpers/helpers.php';
?>