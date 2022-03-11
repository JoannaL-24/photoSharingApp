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

        session_start([
            "name"=>"userLogin"
        ]);
        

        $des = addslashes($_POST["des"]);
        $picture = (file_get_contents($_FILES["picture"]['tmp_name']));

        $stmt = $conn->prepare("INSERT INTO `post` (`userId`, `picture`, `content`) VALUES (:userId, :picture, :content)");
        $stmt->bindParam(':userId', $_SESSION["id"]);
        $stmt->bindParam(':picture', $picture);
        $stmt->bindParam(':content', $des);

        $stmt-> execute();

        session_start([
            "name" => "userLogin",
        ]);

        $id = $_SESSION["id"];
        header("Location: mainPage.php?id=$id");
    ?>
</body>
</html>