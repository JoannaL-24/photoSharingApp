<!-- the sign up form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Sign Up</title>
</head>
<header>
    <nav>
        <ul class="naviLink">
            <li><a href="index.php"><h4>Welcome</h4></a></li>
            <li class = "active"><a href="signUp.php"><h4>Sign Up</h4></a></li>
            <li><a href="logIn.php"><h4>Login</h4></a></li>              
        </ul>
    </nav>
</header>
<body>
    <div class="cards">
        <div class="card">
            <form action="signUpFormhandler.php" method="post" enctype="multipart/form-data">
                <div class="fill">
                    <label for ="name">Username:</label>
                    <input type="text" name="name" id="name" require>
                </div>
                <div class="fill">
                    <label for ="email">Email:</label>
                    <input type="email" name="email" id="email" require>
                </div>
                <div class="fill">
                    <label for ="passW">Password:</label>
                    <input type="password" name="passW" id="passW" require>
                </div>
                <div class="fill">
                    <label for ="bio">Bio:</label>
                    <textarea id="bio" name="bio" rows="5" cols="56"></textarea>
                </div>
                <div class="fill">
                    <label for ="profilePic">Profile Picture:</label>
                    <input type="file" id="profilePic" name="profilePic" >
                </div>
                <input type="submit" value="submit">
                <br>
                <br>
            </form>
        </div>
        
    </div>
    

</body>
</html>
