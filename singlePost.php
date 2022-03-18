<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/78ed85043c.js" crossorigin="anonymous"></script>
    <title>Document</title>
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
    $isList = $_GET["list"];
    $postId = $_GET["post"];
    $hasLike = $_GET["have"];
    $toDelete = false;
    $showEdit = false;
    if (isset($_POST["commentId"])){
        $showEdit = isset($_POST["showEdit$_POST[commentId]"]);
        $toDelete = isset($_POST["deleteComment$_POST[commentId]"]);
    }
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
    echo $location;

    require("connectSever.php");
    echo "<div class=\"cards\">";
    $getPost = $conn-> prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic`, `post`.`picture`, `post`.`postId`,`post`.`content`, `post`.`postTime` FROM `post`
                INNER JOIN `user` ON `post`.`userId`=`user`.`userId` WHERE `post`.`postId` = ?");
    $getPost->bindParam(1, $postId);
    $getPost->execute();
    
    while($row = $getPost->fetch()){
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
        echo"
            <div class=\"postContent\" id=$row[postId]>
                <img class=\"postImg\" src=\"data:image/png;base64,".base64_encode(($row["picture"]))."\"/>
                <p>$row[content]</p>
                <br>
                <p class=\"timestamp\">$row[postTime]</p>
            </div>";
            echo"
            <div class=\"postControl\">";
            require("checkLike.php");
            if ($hasLike){
                echo"
                    <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList&single=true\">
                        <i class=\"fa-solid fa-heart xl\"></i>
                    </a>";
            }
            else{
                echo"
                    <a class=\"circleButton\" href = \"likePost.php?post=$row[postId]&have=$hasLike&list=$isList&single=true\">
                        <i class=\"fa-regular fa-heart xl\"></i>
                    </a>";
            }
            echo "
            </div>";

            if(isset($_POST["addComment$row[postId]"])){
                $getComment = $conn->prepare("INSERT INTO `comment` (`postId`, `userId`, `content`) VALUES (:postId, :userId, :content)");
                $getComment->bindParam(':postId', $row["postId"]);
                $getComment->bindParam(':userId', $_SESSION["id"]);
                $getComment->bindParam(':content', $_POST["newComment"]);
        
                $getComment-> execute();
            }
            if($toDelete){
                $deleteComment = $conn->prepare("DELETE FROM `comment` where `commentId` = ?");
                $deleteComment->bindParam(1, $_POST["commentId"]);
        
                $deleteComment-> execute();
            }
            if (isset($_POST["updateComment$row[postId]"])){
                $updateComment = $conn->prepare("UPDATE `comment` SET `content` = ? WHERE `commentId` = ?");
                $updateComment->bindParam(1, $_POST["newComment"]);
                $updateComment->bindParam(2, $_POST["commentId"]);
        
                $updateComment-> execute();
            }
            
            
            $getComment = $conn->prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic`, `comment`.`commentId`, `comment`.`content`, `comment`.`commentTime` FROM `comment`
                                        INNER JOIN `user` ON `comment`.`userId`=`user`.`userId` WHERE `comment`.`postId` = ? ORDER BY `comment`.`commentTime` DESC");
            $getComment->bindParam(1, $row["postId"]);
        
            $getComment-> execute();
        
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
                    if ($_SESSION["id"] == $getCommentRow["userId"]){
                        echo"
                        <div class=\"commentControl\">
                            <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\">
                                <input type=\"hidden\" name=\"commentId\" value=$getCommentRow[commentId]>";
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
            if($showEdit){
                echo "
                <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\">
                    <input type=\"hidden\" name=\"commentId\" value=$_POST[commentId]>
                    <input type=\"text\" name=\"newComment\">
                    <input type=\"submit\" name=\"updateComment$row[postId]\" value=\"Edit\">
                </form>";
            }
            else{
                echo "
                <form action=\"singlePost.php?post=$row[postId]&have=$hasLike&list=$isList\" method=\"POST\">
                    <input type=\"text\" name=\"newComment\">
                    <input type=\"submit\" name=\"addComment$row[postId]\" value=\"Add\">
                </form>";
            }
            echo"
        </div>
    </div>";
    
    }
    
?>
</body>
</html>