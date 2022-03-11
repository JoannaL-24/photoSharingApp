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

    $searchEmail = addslashes($_POST["email"]);
    $searchPass = $_POST["passW"];

    $stmt = $conn->prepare("SELECT * FROM `user` WHERE `email` LIKE ?");
    $stmt->bindParam(1, $searchEmail);
    $stmt->execute();

    $matchId = 0;
    $matchName = "";
    $matchBio = "";
    $matchProfile = "";
    while ($row = $stmt->fetch()) {
        //var_dump($row);
        $matchPass = $row["password"];
        if (password_verify($searchPass, $matchPass)) {
            $matchId = $row["userId"];
            $matchName = $row["name"];
            $matchBio = $row["bio"];
            $matchProfile = $row['profilePic'];
        }
    }
    if (strcmp($matchName, "") != 0) {
        echo "Log In Successfully<br>";

        session_start([
            "name" => "userLogin",
        ]);
        $_SESSION["id"] = $matchId;
        $_SESSION["name"] = $matchName;
        $_SESSION["email"] = $searchEmail;
        $_SESSION["bio"] = $matchBio;
        $_SESSION["profilePic"] = base64_encode($matchProfile);

        header("Location: mainPage.php?id=$_SESSION[id]");
    } else {
        echo "<div class=\"card\">
                Fail to Log In: username or password is incorrect<br>
                <a href=\"logIn.php\">Try Again</a><br>
                <a href=\"index.php\">Sign Up</a><br>
                </div>";
    }
    echo "</div>";
?>
</body>

</html>