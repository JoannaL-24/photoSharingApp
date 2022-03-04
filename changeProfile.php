<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Change Profile</title>
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
    <div class="cards">
        <div class="card">
            <form action="changePFormhandler.php" method="POST" enctype="multipart/form-data">
                <div class="fill">
                    <label for ="name">Username:</label>
                    <input type="text" name="name" id="name">
                </div>
                <div class="fill">
                    <label for ="bio">Bio:</label>
                    <textarea id="bio" name="bio"></textarea>
                </div>
                <div class="fill">
                    <label for ="profilePic">Profile Picture:</label>
                    <input type="file" name="profilePic" id="profilePic" >
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
    </div>
</body>
</html>