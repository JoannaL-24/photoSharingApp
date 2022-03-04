<?php
    $checkFollow = $conn->prepare("SELECT `followingUser` FROM `following` WHERE follower = ?");
    $checkFollow -> bindParam(1, $_SESSION["id"]);
    $checkFollow->execute();

    $hasFollow = false;
    while($checkFollowRow = $checkFollow->fetch()){
        if ($checkFollowRow["followingUser"] == $viewId){
            $hasFollow = true;
        }
    }
?>