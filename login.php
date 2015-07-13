<?php
include_once('config.php');
include_once('header.php');
//session_start();
$user="";
$password="";
if(isset($_GET["r"]))$_SESSION['return']=filter_var($_GET["r"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if(isset($_SESSION['iduserx']))
{
echo "<h1> ".$lngHi." ".$_SESSION['iduserx']."</h1>. ".$lngConnected."<a href='logout.php?r=".$_SESSION['return']."'>".$lngLogOut."</a>";
}else if(isset($_GET["recover"])){
die('<h1>'.$lngRecoverPass.'</h1><form id="form1" name="form1" method="post" action="login.php"><br>'.$lngUserName.':<input id="recover" name="recover" type="text" onclick="this.value=\'\'" onchange="submit.value=\'enviar\'" placeholder="'.$lngName.'" value="'.$lngName.'"/><input type="submit" name="submit" id="submit" value="'.$lngEnter.'" /><br></form>');
}else if(isset($_POST["recover"])&&$_POST["recover"]!=""){

if (file_exists($cnfUsers."/".$_POST["recover"][0].".php")){
	  ini_set('memory_limit', '-1');
		
		$contenido = file_get_contents($cnfUsers."/".$_POST["recover"][0].".php");
	
		$posA = strpos($contenido,$user."=",0);
		if($posA!==false){
		$posB = strpos($contenido,"=",$posA);
		$posC = strpos($contenido,",",$posB);
		$len =$posC-$posB;
		$intVotes=substr($contenido,$posB+1,$len-1); 
		
		
		$posD = strpos($contenido,",",$posC+1);
		$lenDC =$posD-$posC;		
		$intPost=substr($contenido,$posC+1,$lenDC-1); 
		
		$posE = strpos($contenido,",",$posD+1);
		$lenED =$posE-$posD;		
		$intHijos=substr($contenido,$posD+1,$lenED-1); 
		
		$posF = strpos($contenido,",",$posE+1);
		$lenFE =$posF-$posE;		
		$email=substr($contenido,$posE+1,$lenFE-1); 
		
		
		$strRecover=uniqid($_POST["recover"][0]);
		
		$_SESSION['timeSec'] = time();
		$_SESSION['hashSec'] = $strRecover;
		
	
		
	$titulo= 'Acceso cuenta';
	$mensaje   = "Código:".$strRecover;
	$cabeceras = 'From: '.$cnfEmail. "\r\n" .	 'Reply-To: '.$cnfEmail. "\r\n".'X-Mailer: PHP/'.phpversion();
	if(mail($email, $titulo, $mensaje, $cabeceras)){
	$strMailCheck="<h1>".$lngEmailSend." :)</h1>";
	}else{
	$strMailCheck="<h1>".$lngEmailNotSend." :(</h1>";
	}
	$_SESSION['sheep'] = $_POST["recover"];
	die(header( "refresh:0;url=user.php/".$_POST["recover"]));
		}else{
		echo "<h1>".$lngUserNot." :(</h1>";
		}
		
		}else {
		echo "<h1>".$lngUserNot." :(</h1>";
		}
}
if(isset($_SESSION['login']))if(isset($_POST["answer1"]))if(($cnfAnswer1!=$_POST["answer1"])){
echo "<h4 class='h4Bad'>Respuesta de seguridad incorrecta</h4>";
die(header( "refresh:2;"));			
}


if(isset($_POST["user"])&&isset($_POST["password"])){
  $user = $_POST["user"];
  $password = $_POST["password"];
  }
if($user!=""&&$password!=""){
  $shaBeeP=sha1($user.$password);
  $fileP=$cnfUsers."/".strtolower($user[0]).".php";
      if (file_exists($fileP)) {
		$contenido=file_get_contents($fileP);			
		if(strpos($contenido,",".$shaBeeP.";",0)!==false){
		
		$contenido = file_get_contents($cnfUsers."/".$_POST["user"][0].".php");
		//echo "holaaaaa".$contenido."<br>";
		$posA = strpos($contenido,$user."=",0);
		//die("-->".$posA);
		if($posA!==false){
		$posB = strpos($contenido,"=",$posA);
		$posC = strpos($contenido,",",$posB);
		$len =$posC-$posB;
		$intVotes=substr($contenido,$posB+1,$len-1); 	
		}
		
		
				
		$_SESSION['iduserx']=$user;
		$_SESSION['level']=$intVotes;
		
		if($cnfCookie!=""&&isset($_POST['cookie'])){
			setcookie( "iduserx", $user,  time() + (10 * 365 * 24 * 60 * 60)) ;
		}
		
	//die($_SESSION['iduserx']);
		header( "refresh:0;url=".utf8_decode($_SESSION['return']));
	
		}else{
		
		echo "<h4 class='h4Bad'>".$lngUserPassNot."</h4>";
		$_SESSION['login']=1;
		}
							 }
							 else
							 {
		echo "<h4 class='h4Bad'>".$lngUserPassNot."</h4>";
		$_SESSION['login']=1;
		
								}
								
  
}


?>
<head>
<title><?php echo $cnfTitle;?> | Login</title>
</head>
<body>
<center>
<html>
<nav>
<a href="<?php echo $_SESSION['return'];?>"><?php echo "Volver a ".$_SESSION['return'];?></a> >
Login
</nav>
<article>
  <header>
	<h1  class='h1GrayCenter'>Conectarse</h1>
  </header>
  <section>
	<form id="form1" name="form1" method="post" action="login.php">
	<input onclick="this.value=''" placeholder="<?php echo $lngUser;?>" value="<?php echo $lngUser;?>" name="user" id="user" maxlength="15" type="text" />	<br>
	<input onclick="this.value=''"  placeholder="<?php echo $lngPass;?>" value=""  id="password" name="password" type="password" /><br>
	<?php
	if(isset($_SESSION['login'])){
	//echo "asdasd";
	if($cnfQuestion1!="")echo $cnfQuestion1.': <input id="answer1" name="answer1" type="text" onclick="this.value=\'\'" placeholder="Respuesta" value="Respuesta"/><br>';
	
	}
	if($cnfCookie!="")echo "Recordar:<input type='checkbox' name='cookie' id='cookie'/><br>";
	?>
	
	<input type="submit" name="submit" id="submit" value="Conectar" /><br>
	</form>
	<a href="register.php"><?php echo $lngReg;?></a>
	<a href="login.php?recover=1"><?php echo $lngLostPass;?></a>
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
	
  