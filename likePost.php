<?php
    session_start([
        "name"=>"userLogin"
    ]);

    $isList = $_GET["list"];
    $postId = $_GET["post"];
    $hasLike = $_GET["have"];

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
        
        if ($hasLike){
            $stmt = $conn->prepare("DELETE FROM `like` WHERE `postId` = ? AND `userId` = ?");
            $stmt->bindParam(1, $postId);
            $stmt->bindParam(2, $_SESSION["id"]);
    
            $stmt-> execute();
        }
        else{
            $stmt = $conn->prepare("INSERT INTO `like` (`postId`, `userId`) VALUES (:postId, :userId)");
            $stmt->bindParam(':postId', $postId);
            $stmt->bindParam(':userId', $_SESSION["id"]);
    
            $stmt-> execute();
            
        }
        if ($isList == .1){
            header( "Location: latestPost.php" );
        }
        else if ($isList == .2){
            header( "Location: likeList.php" );
        }
        else{
            header( "Location: mainPage.php?id=$isList" );
        }
        
        echo "</div>";
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>