<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Log In</title>
</head>
<header>
    <nav>
        <ul class="naviLink">
            <li><a  href="index.php"><h4>Welcome</h4></a></li>
            <li><a href="signUp.php"><h4>Sign Up</h4></a></li>
            <li class = "active"><a href="logIn.php"><h4>Login</h4></a></li>              
        </ul>
    </nav>
</header>
<body>
    <div class="cards">
        <div class="card">
            <form action="logInFormhandler.php" method="post">
                <div class="fill">
                    <label for ="email">Email:</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="fill">
                    <label for ="passW">Passowrd:</label>
                    <input type="password" name="passW" id="passW">
                </div>
                <input type="submit" value="submit">
                <br>
                <br>
            </form>
        </div>
    </div>
</body>
</html>
