
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
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
<body>
    <?php   
        echo "<div class=\"cards\">";

        $servername = "localhost";
        $username = "root";
        $password = "Joanna24*";
        $databaseName = "photosharingapp";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /*echo "
                <div class=\"card\">
                    Connected Successfully
                </div>
            ";*/

            if ($viewId == $id){
                // echo "<div class=\"card\" style=\"height:100px;\">self</div>";
                $name = $_SESSION["name"];
                $email = $_SESSION["email"];    
                $bio = $_SESSION["bio"];
                $profilePic = $_SESSION["profilePic"];
                echo "
                    <div class=\"card\">
                        <p>ID: $id</p>
                        <p>Name: $name</p>
                        <p>Email: $email</p>
                        <p>Bio: $bio</p>
                        <p>Profile Picture: </p>
                        <img  src=\"data:image/png;base64,$profilePic\"/>
                        <br>
                        <a href=\"changeProfile.php\">Change Profile</a>
                    </div>";
            }
            else{
                // echo "<div class=\"card\" style=\"height:100px;\">else</div>";
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
                    if (!$hasFollow){
                        echo "<form method=\"POST\" action=\"followFormhandler.php?have=$hasFollow&id=$viewId\">
                            <input type=\"submit\" value=\"Follow\" id=\"followBtn\">
                        </form>
                    </div>";
                    }
                    else{
                        echo "<form method=\"POST\" action=\"followFormhandler.php?list=".false."&have=$hasFollow&id=$viewId\">
                            <input type=\"submit\" value=\"Unfollow\" id=\"followBtn\">
                        </form>
                    </div>";
                    }
                        
                }
            }


            $getPost = $conn-> prepare("SELECT `picture`,`content`,`postTime` FROM `post` WHERE `userId` = ?");
            $getPost->bindParam(1, $viewId);
            $getPost->execute();

            while($row = $getPost->fetch()){
                
                echo "
                <div class=\"card\">
                    <img class=\"postImg\" src=\"data:image/png;base64,".base64_encode(($row["picture"]))."\"/>
                    <p>$row[content]</p>
                    <p>$row[postTime]</p>
                </div>";
            }
            
        }catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        echo "</div>";

    ?>
</body>
</html>