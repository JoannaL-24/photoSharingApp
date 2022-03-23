<!-- process the log in form input -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Fail to Log In</title>
</head>
<body>
<?php
    require("connectSever.php");

    // get the sign up form inputs and pre-process them
    $searchEmail = addslashes($_POST["email"]);
    $searchPass = $_POST["passW"];

    // get the user with the inputed email
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE `email` LIKE ?");
    $stmt->bindParam(1, $searchEmail);
    $stmt->execute();

    $matchId = 0;
    $matchName = "";
    $matchBio = "";
    $matchProfile = "";
    while ($row = $stmt->fetch()) {
        $matchPass = $row["password"];
        // the password match record the user info
        if (password_verify($searchPass, $matchPass)) {
            $matchId = $row["userId"];
            $matchName = $row["name"];
            $matchBio = $row["bio"];
            $matchProfile = $row['profilePic'];
        }
    }
    // if there is a match user
    if (strcmp($matchName, "") != 0) {
        echo "Log In Successfully<br>";

        // create a session
        session_start([
            "name" => "userLogin",
        ]);
        $_SESSION["id"] = $matchId;
        $_SESSION["name"] = $matchName;
        $_SESSION["email"] = $searchEmail;
        $_SESSION["bio"] = $matchBio;
        $_SESSION["profilePic"] = base64_encode($matchProfile);

        header("Location: mainPage.php?id=$_SESSION[id]");
    } 
    // if not: display error msg
    else {
        echo "<div class=\"card\">
                Fail to Log In: username or password is incorrect<br>
                <a href=\"logIn.php\">Try Again</a><br>
                <a href=\"signUp.php\">Sign Up</a><br>
                </div>";
    }
    echo "</div>";
?>
</body>

</html>