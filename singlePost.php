<!-- view a single post in detail with liked counts and all comments -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/78ed85043c.js" crossorigin="anonymous"></script>
    <title>Single Post</title>
</head>
<?php
    session_start([
        "name" => "userLogin",
    ]);

    $id = $_SESSION["id"];
?>
<header>
    <ul class="naviLink">
        <li><a href="mainPage.php?id=<?php echo $id;?>"><h4>My Page</h4></a></li>
        <li><a href="latestPost.php"><h4>Latest Post</h4></a></li>
        <li><a href="following.php"><h4>Following</h4></a></li>
        <li><a href="search.php"><h4>Search</h4></a></li>
    </ul>
    <a class="cta" id="addPost" href="addPost.php"><h4>+</h4></a>
    <a class="cta" href="logOut.php"><h4>Logout</h4></a>
</header>
<body>
<?php
    // get information required
    $isList = $_GET["list"]; // the location where the user enter: give .1, .2, or userId for mainpage
    $postId = $_GET["post"]; // target postId to diaplay
    $hasLike = $_GET["have"];// whether the logged in user liked the post
    $toDelete = false; // whether delete comment btn isset
    $showEdit = false; // whether to show the edit mode (show if edit btn isset)
    if (isset($_POST["commentId"])){
        $showEdit = isset($_POST["showEdit$_POST[commentId]"]);
        $toDelete = isset($_POST["deleteComment$_POST[commentId]"]);
    }
    // set the location to redirect back
    $location = "#";
    if ($isList == .1){
        $location = "latestPost.php#$postId";
    }
    else if ($isList == .2){
        $location = "likeList.php";
    }
    else{
        $location = "mainPage.php?id=$isList#$postId";
    }

    require("connectSever.php");
    echo "<div class=\"cards\">";
    // get the post including the posted user's info
    $getPost = $conn-> prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic`, `post`.`picture`, `post`.`postId`,`post`.`content`, `post`.`postTime` FROM `post`
                INNER JOIN `user` ON `post`.`userId`=`user`.`userId` WHERE `post`.`postId` = ?");
    $getPost->bindParam(1, $postId);
    $getPost->execute();
    
    while($row = $getPost->fetch()){
        // display the small profile for the posted user with name and profile pic
        echo "    
        <a id=\"back\" class=\"circleButton\" href=$location>
            <i style=\"color: white;\" class=\"fa-solid fa-arrow-left\"></i>
        </a>
        <div class=\"card\">
            <div class=\"smallProfile\">
                <a class=\"smallImg\" href=\"mainPage.php?id=$row[userId]\">
                    <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>
                </a>
                <a class=\"smallName\" href=\"mainPage.php?id=$row[userId]\"><h4>$row[name]</h4></a>
            </div>";
            // single => 
            // pre-determine variable that tell postContent.php to direct to singlePost.php
            // when forms are submited
            $single = true;
            // display the post content
            require("postContent.php");
?>
            <script>
                // hide comment count which is not require for this page
                document.getElementById("commentCount").style.display = "none";
            </script>
<?php
            // add comment with isset
            if(isset($_POST["addComment$row[postId]"])){
                $getComment = $conn->prepare("INSERT INTO `comment` (`postId`, `userId`, `content`) VALUES (:postId, :userId, :content)");
                $getComment->bindParam(':postId', $row["postId"]);
                $getComment->bindParam(':userId', $_SESSION["id"]);
                $getComment->bindParam(':content', $_POST["newComment"]);
        
                $getComment-> execute();
            }
            // delete the comment with hidden input "commentId"
            if($toDelete){
                $deleteComment = $conn->prepare("DELETE FROM `comment` where `commentId` = ?");
                $deleteComment->bindParam(1, $_POST["commentId"]);
        
                $deleteComment-> execute();
            }
            // delete the comment with hidden input "commentId" and "newComment" input
            if (isset($_POST["updateComment$row[postId]"])){
                $updateComment = $conn->prepare("UPDATE `comment` SET `content` = ? WHERE `commentId` = ?");
                $updateComment->bindParam(1, $_POST["newComment"]);
                $updateComment->bindParam(2, $_POST["commentId"]);
        
                $updateComment-> execute();
            }
            
            // get all comments on the display post with the commented users
            $getComment = $conn->prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic`, `comment`.`commentId`, `comment`.`content`, `comment`.`commentTime` FROM `comment`
                                        INNER JOIN `user` ON `comment`.`userId`=`user`.`userId` WHERE `comment`.`postId` = ? ORDER BY `comment`.`commentTime` DESC");
            $getComment->bindParam(1, $row["postId"]);
        
            $getComment-> execute();
            
            // if there is comment show the comment section
            $getCommentRow = $getComment->fetch();
            if (!empty($getCommentRow)){
                echo "
                <div class=\"comments\">";
                do{
                    echo "
                    <div class=\"comment\">
                        <div class=\"smallProfile\">
                            <a class=\"smallImg\" href=\"mainPage.php?id=$getCommentRow[userId]\">
                                <img  src=\"data:image/png;base64,".base64_encode($getCommentRow["profilePic"])."\"/>
                            </a>
                            <a class=\"smallName\" href=\"mainPage.php?id=$getCommentRow[userId]\"><h4>$getCommentRow[name]</h4></a>
                        </div>
                        <p id=\"commentContent\">$getCommentRow[content]</p>
                        <br>
                        <p class=\"timestamp\">$getCommentRow[commentTime]</p>";
                    // if the commented user is the logged in user: print the comment controls to edit or delete the comment
                    if ($_SESSION["id"] == $getCommentRow["userId"]){
                        echo"
                        <div class=\"commentControl\">
                            <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\">
                                <input type=\"hidden\" name=\"commentId\" value=$getCommentRow[commentId]>";
                        // whether or not to show the edit control 
                        // (if the user already in edit mode, the edit control btn should not display)
                        if (!$showEdit ||($showEdit && $getCommentRow["commentId"] != $_POST["commentId"])){
                                echo "
                                <input type=\"submit\" name=\"showEdit$getCommentRow[commentId]\" value=\"Edit\">";
                        }
                        echo"
                                <input type=\"submit\" name=\"deleteComment$getCommentRow[commentId]\" value=\"Delete\">
                            </form>
                        </div>";
                    }
                    echo"  
                    </div>";
                }while ($getCommentRow = $getComment->fetch());
                echo"
                </div>";
            }
            // whether is in edit mode, which allow the commented user to update the comment
            if($showEdit){
                echo "
                <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\" autocomplete=\"off\">
                    <input type=\"hidden\" name=\"commentId\" value=$_POST[commentId]>
                    <input type=\"text\" name=\"newComment\" id=\"newComment\" placeholder=\"New Comment\">
                    <input type=\"submit\" style=\"margin-top:10px;\"name=\"updateComment$row[postId]\" value=\"Edit\">
                </form>";
            }
            // if not in edit mode, show a form that allow user to add comment
            else{
                echo "
                <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\" autocomplete=\"off\">
                    <input type=\"text\" name=\"newComment\" id=\"newComment\" placeholder=\"New Comment\">
                    <input type=\"submit\" style=\"margin-top:10px;\" name=\"addComment$row[postId]\" value=\"Add\">
                </form>";
            }
            echo"
        </div>
    </div>";
    }
?>
</body>
</html>