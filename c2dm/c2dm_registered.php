<?php
include_once '../classes/User.php';
if(isset($_GET['udid']))
{
    $user = User::getUser($_GET['udid']);
    if($user &&$user->getC2DMToken())
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
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
