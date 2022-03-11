<?php
    $servername = "localhost";
    $username = "root";
    $password = "Joanna24*";
    $databaseName = "photosharingapp";

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