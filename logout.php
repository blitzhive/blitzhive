<?php
include("config.php");
include('header.php');
if (!isset($_SESSION)) { session_start(); }
$r=$cnfHome;
unset($_SESSION['iduserx']);
unset($_SESSION['uploadedText']);
unset($_SESSION['answer']);
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
echo "<h1>Bye! :_(</h1>";
header( "refresh:2;url=".$_SESSION['return']);
session_unset();
session_destroy();
?>
