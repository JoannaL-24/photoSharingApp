<!-- create a user in database -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<?php
    require("connectSever.php");

    // add slashes to the form inputs
    $name = addslashes($_POST["name"]);
    $email = addslashes($_POST["email"]);
    $passW = addslashes($_POST["passW"]);
    $bio = addslashes($_POST["bio"]);

    // get the raw pic code from the input tmp_name
    $profilePic = file_get_contents($_FILES["profilePic"]['tmp_name']);
    // hash the password
    $passW = password_hash($passW, PASSWORD_BCRYPT);

    // check if the email is used
    $checkSql = $conn->prepare("SELECT `email` FROM `user`");
    $checkSql->execute();

    $hasAccount = false;
    while($row = $checkSql->fetch()){
        if (strcmp($email, $row["email"])==0){
            $hasAccount = true;
        }
    }
    if ($hasAccount){
        echo "
        <div class=\"card\">
            <h3>The email is used:C</h3>
            <a href=\"signUp.php\">Try Again!</a>
        </div>";
    }
    else{
        // if there is no account with the email, create new account
        $stmt = $conn->prepare("INSERT INTO `user` (`name`, `email`, `password`, `bio`, `profilePic`) VALUES (:name, :email, :passW, :bio, :profilePic)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passW', $passW);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':profilePic', $profilePic);

        $stmt-> execute();

        // get the user info to store in session
        $getPic = $conn-> prepare("SELECT `userId`,`profilePic` FROM `user` WHERE `email`like?");
        $getPic->bindParam(1, $email);
        $getPic->execute();
        $row = $getPic->fetch(PDO::FETCH_ASSOC);

        // create session
        session_start([
            "name" => "userLogin",
        ]);
        
        $_SESSION["id"] = $row['userId'];
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["bio"] = $bio;
        $_SESSION["profilePic"] = base64_encode($row['profilePic']);

        // redirect
        header( "Location: mainPage.php?id=$_SESSION[id]" );
    }
    echo "</div>";
?>
</body>
</html>
