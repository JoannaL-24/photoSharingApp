<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Search Result</title>
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
    echo "<div class=\"cards\">";

    require("connectSever.php");
    
    $searchName = addslashes("%$_POST[searchName]%");

    $getUser = $conn-> prepare("SELECT `userId`,`name`, `bio`, `profilePic` FROM `user` WHERE `name` like ?");
    $getUser->bindParam(1, $searchName);
    $getUser->execute();

    $haveMatch = false;
    while($row = $getUser->fetch()){
        $haveMatch = true;
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
        </div>";
    }
    if (!$haveMatch){
        echo "
        <div class=\"card\">
            <p>No Match Found</p>
        </div>
        ";
    }

    echo "</div>";
?>
</body>
</html>