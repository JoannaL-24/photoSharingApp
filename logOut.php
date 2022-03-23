<!-- log out page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Log Out</title>
</head>
<body>
    <?php
        session_start([
            "name" => "userLogin",
        ]);

        // log user out by destroying the session
        session_destroy();
        echo "
        <div class=\"cards\">
            <div class=\"card\">
                Log Out Successfully
                <br>
                <br>
                <a href=\"signUp.php\">Sign Up</a><br>
                <br>
                <a href=\"logIn.php\">Log In</a><br>
            </div>
        </div>
        ";

?>
</body>
</html>
