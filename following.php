<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Following</title>
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
        <li class="active"><a class="active" href="following.php"><h4>Following</h4></a></li>
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
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "Joanna24*";
        $databaseName = "photosharingapp";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<div class=\"cols2 fromButtom\">";
                echo "<div class=\"cards\">
                        <div class=\"card\"><h3>Following</h3></div>";
                // echo "<div class=\"card\">
                //         Connected Successfully
                //     </div>";

            $stmt = $conn->prepare("SELECT * FROM `following` WHERE follower = ? OR followingUser = ?");
            $stmt -> bindParam(1, $id);
            $stmt -> bindParam(2, $id);
            $stmt->execute();

            $follower = array();
            $following = array();
            while($row = $stmt->fetch()){
                if ($row["followingUser"] == $id){
                    array_push($follower, $row["follower"]);
                }
                else if ($row["follower"] == $id){
                    array_push($following, $row["followingUser"]);
                }
            }

            if (!empty($following)){
                $getUser = $conn-> prepare("SELECT `userId`,`name`, `bio`, `profilePic` FROM `user` WHERE `userId` IN (".implode(',', $following). ")");
                $getUser->execute();
                while($row = $getUser->fetch()){
                    echo "
                    <div class=\"card profile\">
                        <div class=\"profilePic\">
                            <a href=\"mainPage.php?id=$row[userId]\">
                                <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>
                            </a>
                                </div>
                        <div class=\"profileCont\">
                            <a href=\"mainPage.php?id=$row[userId]\"><h3>$row[name]</h3></a>
                            <p>$row[bio]</p>
                        </div>
                        <form method=\"POST\" action=\"followFormhandler.php?list=",1,"&have=".true."&id=$row[userId]\">
                                <input type=\"submit\" value=\"Unfollow\" id=\"followBtn\">
                        </form>
                    </div>";
                }
            }
            else{
                echo "
                <div class=\"card\">
                    There's no following user!
                </div>";
            }

            echo "
                </div>
            <div class=\"cards\">
                <div class=\"card\"><h3>Followers</h3></div>
            ";
            if (!empty($follower)){
                $getUser = $conn-> prepare("SELECT `userId`,`name`, `bio`, `profilePic` FROM `user` WHERE `userId` IN (".implode(',', $follower). ")");
                $getUser->execute();

                while($row = $getUser->fetch()){
                    $viewId = $row["userId"];
                    echo "
                    <div class=\"card profile\">
                        <div class=\"profilePic\">
                            <a href=\"mainPage.php?id=$viewId\">
                                <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>
                            </a>
                                </div>
                        <div class=\"profileCont\">
                            <a href=\"mainPage.php?id=$viewId\"><h3>$row[name]</h3></a>
                            <p>$row[bio]</p>
                        </div>
                        <form method=\"POST\" action=\"followFormhandler.php?list=2&have=". false."&id=$row[userId]\">
                            <input type=\"submit\" value=\"Remove\" id=\"followBtn\">
                        </form>
                    </div>";
                }
            }
            else{
                echo "
                <div class=\"card\">
                    There's no follower! Sagde :c
                </div>";
            }
            echo "</div>";
        }catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    ?>
</body>
</html>