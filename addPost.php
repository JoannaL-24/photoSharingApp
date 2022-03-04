<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Post</title>
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
    <a class="cta postActive" id="addPost" href="addPost.php">
        <h4>+</h4>
    </a>
    <a class="cta" href="logOut.php">
        <h4>Logout</h4>
    </a>
</header>

<body>
    <div class="cards">
        <div class="card">
            <form method="POST" action="addPostFormhandler.php" enctype="multipart/form-data">
                <div class="fill">
                    <label for="picture">Photo:</label>
                    <input type="file" id="picture" name="picture">
                </div>
                <div class="fill">
                    <label for="des">Description:</label>
                    <textarea id="des" name="des" cols="45" rows="5"></textarea>
                </div>
                <input type="submit" value="submit">
            </form>
        </div>
    </div>
</body>

</html>