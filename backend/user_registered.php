<?php
include_once '../classes/User.php';
if(isset($_GET['udid']))
{    
    if(User::userExists($_GET['udid']) )
    {
        echo "success";
        exit;
    }else{
        echo "failure";
        exit;
    }
}else{
      echo "failure";
      exit;
} 
?>
