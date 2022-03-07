<?php
    $checkLike = $conn->prepare("SELECT `postId` FROM `like` WHERE userId = ?");
    $checkLike -> bindParam(1, $_SESSION["id"]);
    $checkLike->execute();

    $hasLike = false;
    while($checkLikeRow = $checkLike->fetch()){
        if ($checkLikeRow["postId"] == $row["postId"]){
            $hasLike = true;
        }
    }
?>