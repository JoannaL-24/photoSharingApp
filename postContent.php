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
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList&single=$single\">
                <i class=\"fa-solid fa-heart xl\"></i>
            </a>";
        
    }
    else{
        echo"
            <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList&single=$single\">
                <i class=\"fa-regular fa-heart xl\"></i>
            </a>";
    }
    $getCommentCount = $conn->prepare("SELECT COUNT(`commentId`) AS `count` FROM `comment` WHERE `postId` = ?");
    $getCommentCount->bindParam(1, $row["postId"]);

    $getCommentCount-> execute();

    $getCommentCount = $getCommentCount->fetch();
    $location = "";
    if ($single){
        $location = "singlePost.php?post=$postId&have=$hasLike&list=$isList";
    }
    else if ($isList == .1){
        $location="latestPost.php#$row[postId]";
    }
    else if ($isList == .2){
        $location="likeList.php";
    }
    else{
        $location="mainPage.php?id=$isList#$row[postId]";
    }
    echo"
        <a id=\"commentCount\" href = \"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
            $getCommentCount[count] Comments
        </a>
        <form action=$location method=\"POST\">
            <input type=\"hidden\" name=\"postToShowLike\" value=$row[postId]>
            <input type=\"submit\" name=\"showLike$row[postId]\" id=\"showLike\" value=\"Liked User\">
        </form>
    </div>";
    if (isset($_POST["showLike$row[postId]"])){
        $getLikedUser = $conn->prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic` FROM `like`
        INNER JOIN `user` ON `like`.`userId`=`user`.`userId` WHERE `like`.`postId` = ?");
        $getLikedUser -> bindParam(1, $_POST["postToShowLike"]);
        $getLikedUser->execute();

        echo "        
        <div id=\"likedUser\" class=\"modal\">
            <div class=\"modal-content\">
                <span class=\"close\">&times;</span>";
        $hasLikedUser = false;
        while($getLikedUserRow = $getLikedUser->fetch()){
            $hasLikedUser = true;
            echo"
            <div class=\"smallProfile\">
                <a class=\"smallImg\" href=\"mainPage.php?id=$getLikedUserRow[userId]\">
                    <img  src=\"data:image/png;base64,".base64_encode($getLikedUserRow["profilePic"])."\"/>
                </a>
                <a class=\"smallName\" href=\"mainPage.php?id=$getLikedUserRow[userId]\"><h4>$getLikedUserRow[name]</h4></a>
            </div>";
        }
        if (!$hasLikedUser){
            echo"There is no user who liked the post :c";
        }
        echo "
            </div>
        </div>
        ";
    }
?>
<script defer>
    var modal = document.getElementById("likedUser");
    var btn = document.getElementById("showLike");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
    modal.style.display = "block";
    }

    span.onclick = function() {
    modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>