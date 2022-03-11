<?php
    echo"
    <div class=\"postContent\">
        <img class=\"postImg\" src=\"data:image/png;base64,".base64_encode(($row["picture"]))."\"/>
        <p>$row[content]</p>
        <p>$row[postTime]</p>
    </div>";
    require("checkLike.php");
    if ($hasLike){
        echo"
        <div class=\"postControl\">
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
                <i class=\"fa-solid fa-heart xl\"></i>
            </a>
        </div>";
    }
    else{
        echo"
        <div class=\"postControl\">
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
                <i class=\"fa-regular fa-heart xl\"></i>
            </a>
        </div>";
    }
?>