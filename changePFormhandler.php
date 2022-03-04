<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="style.css" rel="stylesheet"> -->
    <title>Document</title>
</head>
<body>
    
</body>
</html><?php
    session_start([
        "name" => "userLogin",
    ]);

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
        $name = $_SESSION["name"];
        $bio = $_SESSION["bio"];
        $profilePic = base64_decode($_SESSION["profilePic"]);
        // var_dump($profilePic);
        
        
        if (strcmp($_POST["name"],"")!=0){
            $name = addslashes($_POST["name"]);
        }
        if (strcmp($_POST["bio"],"")!=0){
            $bio = addslashes($_POST["bio"]);
        }
        echo $name." ".$bio;
        if (!$_FILES["profilePic"]['error']){
            echo "change";
            $profilePic = file_get_contents($_FILES["profilePic"]['tmp_name']);
        }
            
        $stmt = $conn->prepare("UPDATE `user` SET `name` = ?, `bio` = ?, `profilePic` = ? WHERE `userId`= ?");
        $stmt->bindParam(1 , $name);
        $stmt->bindParam(2, $bio);
        $stmt->bindParam(3, $profilePic);
        $stmt->bindParam(4, $_SESSION["id"]);

        $stmt-> execute();

        
        $_SESSION["name"] = $name;
        $_SESSION["bio"] = $bio;
        $_SESSION["profilePic"] = base64_encode($profilePic);

        header( "Location: mainPage.php?id=$_SESSION[id]" );
        echo "</div>";
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>