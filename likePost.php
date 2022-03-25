<!-- change the database when like or dislike btn is click -->
<!-- pre-condition:
     the href link to "likePost.php" has give [list], [post], and [have] through GET -->
<?php
    session_start([
        "name"=>"userLogin"
    ]);

    $isList = $_GET["list"];
    $postId = $_GET["post"];
    $hasLike = $_GET["have"];

    require("connectSever.php");
    
    // if the logged in user has liked the post with postId: undo that by deleting the row from the database
    if ($hasLike){
        $stmt = $conn->prepare("DELETE FROM `like` WHERE `postId` = ? AND `userId` = ?");
        $stmt->bindParam(1, $postId);
        $stmt->bindParam(2, $_SESSION["id"]);

        $stmt-> execute();
    }
    // if not: insert the liked info into the database
    else{
        $stmt = $conn->prepare("INSERT INTO `like` (`postId`, `userId`) VALUES (:postId, :userId)");
        $stmt->bindParam(':postId', $postId);
        $stmt->bindParam(':userId', $_SESSION["id"]);

        $stmt-> execute();
        
    }

    // redirect to the previous entering page based on the info from GET
    if (($_GET["single"])){
        header( "Location: singlePost.php?post=$postId&have=$hasLike&list=$isList" );
    }
    else if ($isList == .1){
        header( "Location: latestPost.php#$postId" );
    }
    else if ($isList == .2){
        header( "Location: likeList.php" );
    }
    else{
        header( "Location: mainPage.php?id=$isList#$postId" );
    }
    
    echo "</div>";
?>