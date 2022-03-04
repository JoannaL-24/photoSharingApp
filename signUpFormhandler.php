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
        $servername = "localhost";
        $username = "root";
        $password = "Joanna24*";
        $databaseName = "photosharingapp";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "
            <div class=\"cards\">
                <div class=\"card\">
                    Connected Successfully
                </div>
            ";

            $name = addslashes($_POST["name"]);
            $email = addslashes($_POST["email"]);
            $passW = addslashes($_POST["passW"]);
            $bio = addslashes($_POST["bio"]);
            $profilePic = file_get_contents($_FILES["profilePic"]['tmp_name']);
            
            $passW = password_hash($passW, PASSWORD_BCRYPT);

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
                $stmt = $conn->prepare("INSERT INTO `user` (`name`, `email`, `password`, `bio`, `profilePic`) VALUES (:name, :email, :passW, :bio, :profilePic)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passW', $passW);
                $stmt->bindParam(':bio', $bio);
                $stmt->bindParam(':profilePic', $profilePic);
        
                $stmt-> execute();

                $getPic = $conn-> prepare("SELECT `userId`,`profilePic` FROM `user` WHERE `email`like?");
                $getPic->bindParam(1, $email);
                $getPic->execute();
                $row = $getPic->fetch(PDO::FETCH_ASSOC);

                session_start([
                    "name" => "userLogin",
                ]);
                
                $_SESSION["id"] = $row['userId'];
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;
                $_SESSION["bio"] = $bio;
                $_SESSION["profilePic"] = base64_encode($row['profilePic']);

                header( "Location: mainPage.php?id=$_SESSION[id]" );
            }
            echo "</div>";
        }catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    ?>
</body>
</html>
