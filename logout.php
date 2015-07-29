<?php
include("config.php");
if (!isset($_SESSION)) { session_start(); }
$r=$cnfHome;
if(isset($_GET["r"]))$r=filter_var($_GET["r"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
unset($_SESSION['iduserx']);
unset($_SESSION['image0']);
unset($_SESSION['image1']);
unset($_SESSION['image2']);
unset($_SESSION['answer']);
unset($_SESSION['return']);
unset($_SESSION['error']);
unset($_SESSION['allowdelete']);
unset($_SESSION['emaila']);
unset($_SESSION['parent']);
unset($_SESSION['title']);
unset($_SESSION['body']);
unset($_SESSION['tag']);
unset($_SESSION['mod']);
unset($_SESSION['login']);
unset($_SESSION['timeSec']);
unset($_SESSION['hashSec']);
unset($_SESSION['tempUser']);
unset($_SESSION['level']);
unset($_SESSION['sheep']);
unset($_SESSION['enviadorecover']);
unset($_SESSION['fProgramadas']);
if($cnfCookie!=""){
unset($_COOKIE['iduserx']);
setcookie('iduserx', null, -1, '/');
setcookie('iduserx');
}
session_unset();
session_destroy();
header( "refresh:0;url=".$r );
?>
