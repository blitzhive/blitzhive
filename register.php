<?php
include('config.php');
include('header.php');
/*include('mail.php');*/
session_start();


//echo $_GET["r"]."".utf8_encode($_GET["r"]);
if(isset($_GET["r"]))$_SESSION['return']=htmlspecialchars($_GET["r"], ENT_QUOTES, "UTF-8");
//if(isset($_GET["r"]))$_SESSION['return']=$_GET["r"];
///echo utf8_decode($_SESSION['return']);
$parent=0;
if(isset($_GET["p"])){$_SESSION['parent']=filter_var($_GET["p"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$parent=$_SESSION['parent'];
}else if(isset($_SESSION['parent'])){$parent=$_SESSION['parent'];}

if(isset($_SESSION['iduserx']))
{
echo "<h1>Hola ".$_SESSION['iduserx']."</h1>. ".$lngConnected."<a href='logout.php?r=".$_SESSION['return']."'>".$lngLogOut."</a>";
}
?>
<title><?php echo $cnfTitle;?> | Register</title>
</head>
<body>
<center>
<html>
<nav>
<a href="<?php echo $_SESSION['return'];?>"><?php echo "Volver a ".$_SESSION['return'];?></a> >
Register
</nav>
<article class="article">
  <header>
	<h1  class='h1GrayCenter'><?php echo $lngReg;?></h1>
  </header>
  <?php
/*$code="0";
if(isset($_GET["code"]))$code=$_GET["code"];
if($code=="blitzito"){*/
  ?>
  <section>
 <form id="form1" name="form1" method="post" action="register.php">
  <input id="user" maxlength="15" onclick="this.value=''" placeholder="Usuario" name="user" type="text" value="Usuario"/><br>
 <input  id="password" placeholder="<?php echo $lngPass;?>" name="password" type="password" /><br>
 <input  id="password2" placeholder="<?php echo $lngRepPass;?>" name="password2" type="password" value=""/><br>
  <input  id="email" maxlength="30" onclick="this.value=''" placeholder="Email" name="email" type="text" value="Email"/><br>
  <?php
  if($cnfQuestion1!="")echo $cnfQuestion1.': <input id="answer1" name="answer1" type="text" onclick="this.value=\'\'" placeholder="'.$lngAnswer.'" value="'.$lngAnswer.'"/><br>';
  if($cnfQuestion2!="")echo $cnfQuestion2.': <input id="answer2" name="answer2" type="text" onclick="this.value=\'\'" placeholder="'.$lngAnswer.'" value="'.$lngAnswer.'"/><br>';
  ?>
  <input type="submit" name="submit" id="submit" value="<?php echo $lngReg;?>" />
  </form>
  
 <?php
 
//die($_POST["user"]."-".preg_match('/[a-zA-Z0-9 ]+$/' , $_POST["user"]));
 
	if($_POST["user"]!=""&&$_POST["password"]!=""&&$_POST["password2"]!=""&&$_POST["email"]!=""
	||
	($cnfQuestion1!=""&&$_POST["answer1"]=="")
	||
	($cnfQuestion2!=""&&$_POST["answer2"]=="")
	){
	if(
	($cnfQuestion1!="" && $cnfAnswer1!=$_POST["answer1"])
	||
	($cnfQuestion2!="" && $cnfAnswer2!=$_POST["answer2"])
	)
	{
	echo "<h4 class='h4Bad'>".$lngWrongAss.".</h4>";
	}else if($_POST["password"]!=$_POST["password2"])
	{
	echo "<h4 class='h4Bad'>".$lngNotPassPair.".</h4>";
	}else if( filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)===false)
	{
	echo "<h4 class='h4Bad'>".$lngNotFormat.".</h4>";
	}
	else if(preg_match('/[a-zA-Z0-9 ]+$/' , $_POST["user"])==0)
	{
	echo "<h4 class='h4Bad'>".$lngNotForUse.".</h4>";
	}
	else if(strlen($_POST["user"])>15)
	{
	echo "<h4 class='h4Bad'>".$lngNotLongUser.".</h4>";
	}
	else if(strlen($_POST["email"])>30){
	echo "<h4 class='h4Bad'>".$lngNotLongEmail.".</h4>";
	}
	else{
	$_POST["user"]=strtolower($_POST["user"]);
	$enter=false;
	if(!is_dir($cnfUsers))mkdir($cnfUsers, 0777, true);
    if (file_exists($cnfUsers."/".$_POST["user"][0].".php")) 
	{
		if(!is_writable($cnfUsers."/".$_POST["user"][0].".php"))chmod($cnfUsers."/".$_POST["user"][0].".php", 0644);
	
		$contenido=file_get_contents("users/".strtolower($_POST["user"][0]).".php");	
			if(strpos(htmlentities($contenido),$_POST["user"]."=",0)!==false){
			echo "<h4 class='h4Bad'>".$lngStillUser."</h4>";
			}else if(strpos(htmlentities($contenido),",".$_POST["email"].",",0)!==false){
			echo "<h4 class='h4Bad'>".$lngStillEmail."</h4>";
			}else{
			$fp = fopen($cnfUsers."/".$_POST["user"][0].".php", "r+");
			rewind($fp);
			fseek($fp,strlen($contenido)-2);
			fwrite($fp, $_POST["user"].'=0,0,0,'.$_POST["email"].','.time().','.$parent.',0,'.sha1($_POST["user"].$_POST["password"]).';?>');
			fclose($fp);
			$enter=true;
			}
	}else{
	$fp = fopen($cnfUsers."/".$_POST["user"][0].".php", "w+");
	fwrite($fp, '<?php '.$_POST["user"].'=0,0,0,'.$_POST["email"].','.time().','.$parent.',0,'.sha1($_POST["user"].$_POST["password"]).';?>');
	chmod($cnfUsers."/".$_POST["user"][0].".php", 0644);
	fclose($fp);
	$enter=true;
	}
	
	if($enter==true){	
//filemtime() 	
	$_SESSION['iduserx']=$_POST["user"];		
	$_SESSION['level']=0;
	echo "Bienvenido ".$_POST["user"].". ";		
	
$titulo= 'Bienvenido ';
$mensaje   = $cnfRegMailHeader." ".$_POST["user"].".".$cnfRegMailFooter;
$cabeceras = 'From: '.$cnfEmail. "\r\n" .	 'Reply-To: '.$cnfEmail. "\r\n" .
    'X-Mailer: PHP/'.phpversion();

if(mail($_POST["email"], $titulo, $mensaje, $cabeceras)){
$strMailCheck="<h1>".$lngEmailSend." :)</h1>";
}else{
$strMailCheck="<h1>".$lngEmailNotSend." :(.</h1>";
}
header( "refresh:1;url=".$_SESSION['return']);
	}else{
	echo "<h4 class='h4Bad'>".$lngNotReg.".</h4>";
	}
	}
	}else{
	echo "<h4 class='h4Bad'>".$lngFillAll.".</h4>";
	}
	
    
?>
    </section>
  </article>
 <footer>
	<?php
	include('footer.php');
	?>
 </footer>
</center>
</body>
</html>
