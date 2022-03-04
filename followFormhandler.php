<?php

    session_start([
        "name"=>"userLogin"
    ]);

    $isList = $_GET["list"];
    $hasFollow = $_GET["have"];
    $viewId = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "Joanna24*";
    $databaseName = "photosharingapp";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "
        <div class=\"cards\">
            <div class=\"card\">
                Connected Successfully
            </div>
        ";
        
        if ($hasFollow){
            $stmt = $conn->prepare("DELETE FROM `following` WHERE `follower` = ? AND `followingUser` = ?");
            $stmt->bindParam(1, $_SESSION["id"]);
            $stmt->bindParam(2, $viewId);
    
            $stmt-> execute();
        }
        else{
            $stmt = $conn->prepare("INSERT INTO `following` (`follower`, `followingUser`) VALUES (:follower, :followingUser)");
            $stmt->bindParam(':follower', $_SESSION["id"]);
            $stmt->bindParam(':followingUser', $viewId);
    
            $stmt-> execute();
            
        }
        if ($isList){
            header( "Location: following.php?id=$viewId" );
        }
        else{
            header( "Location: mainPage.php?id=$viewId" );
        }
        
        echo "</div>";
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>