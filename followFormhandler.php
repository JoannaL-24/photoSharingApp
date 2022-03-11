<?php

    session_start([
        "name"=>"userLogin"
    ]);

    $isList = $_GET["list"];
    $hasFollow = $_GET["have"];
    $viewId = $_GET["id"];

    require("connectSever.php");
    if ($isList==2){
        $stmt = $conn->prepare("DELETE FROM `following` WHERE `follower` = ? AND `followingUser` = ?");
        $stmt->bindParam(1, $viewId);
        $stmt->bindParam(2, $_SESSION["id"]);

        $stmt-> execute();
    }
    else{
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
    }
    
    if ($isList == 0){
        header( "Location: mainPage.php?id=$viewId" );
    }
    else{
        header( "Location: following.php" );
    }
        
    echo "</div>";
?>