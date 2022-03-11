<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/78ed85043c.js" crossorigin="anonymous"></script>
    <title>Liked Posts</title>
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
<script>
    function loading() {
            if (document.readyState != "complete") {
                document.querySelector("header").style.visibility = "visible";
                document.querySelector("body").style.visibility = "hidden";
                document.querySelector("#loader").style.visibility = "visible";
            } 
            else {
                document.querySelector("#loader").style.display = "none";
                document.querySelector("body").style.visibility = "visible";
            }
        };
</script>
<div id="loader" class="center"></div>
<body onload="loading()">
    <div class="cards fromButtom">
        <?php
            require("connectSever.php");
            $stmt = $conn->prepare("SELECT * FROM `like` WHERE userId = ?");
            $stmt -> bindParam(1, $_SESSION["id"]);
            $stmt->execute();

            $likedPosts = array();
            while($row = $stmt->fetch()){
                array_push($likedPosts, $row["postId"]);
            }

            if (!empty($likedPosts)){
                $getPost = $conn-> prepare("SELECT `user`.`userId`, `user`.`name`, `user`.`profilePic`, `post`.`picture`, `post`.`postId`,`post`.`content`, `post`.`postTime` FROM `post`
                INNER JOIN `user` ON `post`.`userId`=`user`.`userId` WHERE `post`.`postId` IN (".implode(',', $likedPosts). ") ORDER BY `post`.`postTime` DESC");
                
                $getPost->execute();
                while($row = $getPost->fetch()){
                        echo "
                        <div class=\"card\">
                            <div class=\"smallProfile\">
                                <a class=\"smallImg\" href=\"mainPage.php?id=$row[userId]\">
                                    <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>
                                </a>
                                <a class=\"smallName\" href=\"mainPage.php?id=$row[userId]\"><h4>$row[name]</h4></a>
                            </div>";
                        $isList = .2;
                        require("postContent.php");
                        echo "</div>";
                }
            }
            else{
                echo "
                <div class=\"card\">
                    There no post:C
                </div>";
            }
            echo "</div>";
        ?>
</body>
</html>