<?php
    echo"
    <div class=\"postContent\" id=$row[postId]>
        <img class=\"postImg\" src=\"data:image/png;base64,".base64_encode(($row["picture"]))."\"/>
        <p>$row[content]</p>
        <br>
        <p class=\"timestamp\">$row[postTime]</p>
    </div>";
    require("checkLike.php");
    echo"
        <div class=\"postControl\">";
    if ($hasLike){
        echo"
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
                <i class=\"fa-solid fa-heart xl\"></i>
            </a>";
        
    }
    else{
        echo"
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
                <i class=\"fa-regular fa-heart xl\"></i>
            </a>";
    }
    $getCommentCount = $conn->prepare("SELECT COUNT(`commentId`) AS `count` FROM `comment` WHERE `postId` = ?");
    $getCommentCount->bindParam(1, $row["postId"]);

    $getCommentCount-> execute();

    $getCommentCount = $getCommentCount->fetch();
    $location = "";
    if ($isList == .1){
        $location="latestPost.php#$row[postId]";
    }
    else if ($isList == .2){
        $location="likeList.php";
    }
    else{
        $location="mainPage.php?id=$isList#$row[postId]";
    }
    echo"
        <a href = \"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
            $getCommentCount[count] Comments
        </a>
    </div>";
    // if (isset($_POST["addComment$row[postId]"])){
    //     echo "
    //     <form action=$location method=\"POST\">
    //         <input type=\"submit\" name=\"showComment$row[postId]\" value=\"".$getCommentCount["count"]+1 ." comments\">
    //     </form>
    // </div>";
    // }
    // else{
    //     echo "
    //     <form action=$location method=\"POST\">
    //         <input type=\"submit\" name=\"showComment$row[postId]\" value=\"$getCommentCount[count] comments\">
    //     </form>
    // </div>";
    // }
    
?>