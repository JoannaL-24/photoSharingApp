<!-- [for require] -->
<!-- the general template for small profile that only show the user profile pic and name -->
<?php
    echo "
    <div class=\"card profile\">
        <div class=\"profilePic\">
            <a href=\"mainPage.php?id=$row[userId]\">
                <img  src=\"data:image/png;base64,".base64_encode($row["profilePic"])."\"/>
            </a>
        </div>
        <div class=\"profileCont\">
            <a href=\"mainPage.php?id=$row[userId]\"><h3>$row[name]</h3></a>
            <p>$row[bio]</p>
        </div>
    ";
?>