<!-- [for require] -->
<!-- the general template for post -->
<!-- pre-condition: 
    have fetched row in while loop, 
    $isList for entering location, 
    $single for whether the entering location is "singlePost.php" -->
<?php
    // print the content from post
    echo"
    <div class=\"postContent\" id=$row[postId]>
        <img class=\"postImg\" src=\"data:image/png;base64,".base64_encode(($row["picture"]))."\"/>
        <p>$row[content]</p>
        <br>
        <p class=\"timestamp\">$row[postTime]</p>
    </div>";
    // check whether the logged in user liked the post
    // and print different icon accordingly 
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

    // get comment count
    $getCommentCount = $conn->prepare("SELECT COUNT(`commentId`) AS `count` FROM `comment` WHERE `postId` = ?");
    $getCommentCount->bindParam(1, $row["postId"]);

    $getCommentCount-> execute();

    $getCommentCount = $getCommentCount->fetch();

    // generate the location for forms to get back and use isset()
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

    // print the comment count and btn to display all users who liked the post
    echo"
        <a id=\"commentCount\" href = \"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\">
            $getCommentCount[count] Comments
        </a>
        <form action=$location method=\"POST\">
            <input type=\"hidden\" name=\"postToShowLike\" value=$row[postId]>
            <input type=\"submit\" name=\"showLike$row[postId]\" id=\"showLike\" value=\"Liked User\">
        </form>
    </div>";

    // if the showLike btn is pressed: get the liked users from the database
    if (isset($_POST["showLike$row[postId]"])){
        $getLikedUser = $conn->prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic` FROM `like`
        INNER JOIN `user` ON `like`.`userId`=`user`.`userId` WHERE `like`.`postId` = ?");

        $getLikedUser -> bindParam(1, $_POST["postToShowLike"]);
        $getLikedUser->execute();

        // print the users in a hidden modal
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
        // if there is no liked from users: display that
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
    // the onclick functions that is defered, so the modal would be ready to display
    // show the modal when the showLike btn is clicked
    // and close the modal when the "x" or other area is clicked
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