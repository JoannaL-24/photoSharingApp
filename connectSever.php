<?php
    $servername = "localhost";
    $username = "root";
    $password = "Joanna24*";
    $databaseName = "photosharingapp";
    // $servername = "us-cdbr-east-05.cleardb.net";
    // $username = "bcf2d653cd78c2";
    // $password = "5eef9235";
    // $databaseName = "heroku_dd4d001e84cca6c";
    // $severURL = "mysql://bcf2d653cd78c2:5eef9235@us-cdbr-east-05.cleardb.net/heroku_dd4d001e84cca6c?reconnect=true";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "<div class=\"card\">
        //         Connected Successfully
        //     </div>";
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>