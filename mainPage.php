
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/78ed85043c.js" crossorigin="anonymous"></script>
    <title>Home</title>
</head>
<?php
    $viewId = $_GET["id"];
    session_start([
        "name" => "userLogin",
    ]);

    $id = $_SESSION["id"];
?>
<header>
    <ul class="naviLink">
        <li class="active"><a class="active" href="mainPage.php?id=<?php echo $id;?>"><h4>My Page</h4></a></li>
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
    var showProfileDetail = false;
    function profileMore(){
        if (!showProfileDetail){
            document.getElementById("profileDetail").classList.add("popup");
            // document.getElementById("profileDetail").style.visibility = "visible";
            showProfileDetail = true;
        }
        else{
            
            document.getElementById("profileDetail").classList.remove("popup");
            // document.getElementById("profileDetail").style.visibility = "hidden";
            showProfileDetail = false;
        }
        
    }
</script>
<div id="loader" class="center"></div>
<body onload="loading()">
    <?php   
        echo "<div class=\"cards fromButtom\">";

        require("connectSever.php");

        if ($viewId == $id){
            $name = $_SESSION["name"];
            $email = $_SESSION["email"];    
            $bio = $_SESSION["bio"];
            $profilePic = $_SESSION["profilePic"];
            echo "
            
                <div class=\"card\">
                    <div class=\"more\" style=\"justify-content: flex-end\">
                        <div id=\"profileDetail\" class=\"card\">
                            <a class=\"detailLink\" href=\"likeList.php\">Liked Posts</a>
                            <a class=\"detailLink\" href=\"changeProfile.php\">Change Profile</a>
                        </div>
                        <a id=\"profileMore\" class=\"circleButton\" onclick=\"profileMore()\">
                            <i class=\"fa-solid fa-ellipsis-vertical\"></i>
                        </a>
                    </div>
                    <br>
                    <img  src=\"data:image/png;base64,$profilePic\"/>
                    <br><br>
                    <p>ID: $id</p>
                    <p>Name: $name</p>
                    <p>Email: $email</p>
                    <p>Bio: ".stripslashes($bio)."</p>                    
                </div>";
        }
        else{
            require_once("checkFollow.php");

            $getUser = $conn-> prepare("SELECT `name`, `bio`, `profilePic` FROM `user` WHERE userId = ?");
            $getUser->bindParam(1, $viewId);
            $getUser->execute();
            while($row = $getUser->fetch()){
                echo "
                <div class=\"card\">
                    <p>Name: $row[name]</p>
                    <p>Bio: $row[bio]</p>
                    <p>Profile Picture: </p>
                    <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>";
                require("checkFollow.php");
                if (!$hasFollow){
                    echo "<form method=\"POST\" action=\"followFormhandler.php?list=0&have=$hasFollow&id=$viewId\">
                        <input type=\"submit\" value=\"Follow\" id=\"followBtn\">
                    </form>
                </div>";
                }
                else{
                    echo "<form method=\"POST\" action=\"followFormhandler.php?list=0&have=$hasFollow&id=$viewId\">
                        <input type=\"submit\" value=\"Unfollow\" id=\"followBtn\">
                    </form>
                </div>";
                }
                    
            }
        }


        $getPost = $conn-> prepare("SELECT `postId`,`picture`,`content`,`postTime` FROM `post` WHERE `userId` = ?");
        $getPost->bindParam(1, $viewId);
        $getPost->execute();

        while($row = $getPost->fetch()){
            $isList = $viewId;
            echo "<div class=\"card\">";
            require("postContent.php");
            echo "</div>";
        }
        echo "</div>";

    ?>
</body>
</html>