<?php

include_once 'properties/serverproperties.php';
    if(isset($_GET['UDID']) && trim($_GET['UDID']) != ""){
        $param = '';
        if (isset($_GET['os'])) {
            $param .= "&os=".$_GET['os'];
        }else if (isset($_GET['OS'])) {
            $param .= "&os=".$_GET['OS'];
        }
        if (isset($_GET['mac'])) {
            $param .= "&mac=".$_GET['mac'];
        } else if (isset($_GET['MAC'])) {
            $param .= "&mac=".$_GET['MAC'];
        }
        if(isset($_GET['legacy'])){
            $param .= '&legacy='.$_GET['legacy'];
        }
        header("location: {$serverRoot}backend/charcreate.php?UDID=".$_GET['UDID'].$param);
        exit;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login</title>
</head>
<body>
Enter the user id:
<form action="<?php echo $serverRoot; ?>backend/login.php" method="post">
<input type="text" name="id"/>
<input type="submit" value="Submit"/>
</form>
</body>
</html>