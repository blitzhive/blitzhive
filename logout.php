<?php

include("config.php");
include('header.php');
if (!isset($_SESSION)) { session_start(); }
echo "<h1>Good Bye! ".$_SESSION['iduserx']." :_( </h1>";
$r=$cnfHome;
unset($_SESSION['iduserx']);
unset($_SESSION['uploadedText']);
unset($_SESSION['onclickMultiple']);
unset($_SESSION['select']);
unset($_SESSION['select']);
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
unset($_SESSION['parent']);



header( "refresh:1;url=".$_SESSION['return']);

if($cnfCookie!=""){
unset($_COOKIE['iduserx']);
setcookie('iduserx', null, -1, '/');
setcookie('iduserx');
setcookie('level', null, -1, '/');
setcookie('level');
}


session_unset();
session_destroy();

?>
