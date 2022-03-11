<?php
    session_start([
        "name"=>"userLogin"
    ]);

    $isList = $_GET["list"];
    $postId = $_GET["post"];
    $hasLike = $_GET["have"];

    require("connectSever.php");
        
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
?>