<?php 
if(basename(__FILE__)== basename($_SERVER["SCRIPT_FILENAME"]))die(); 


if(cnfAutoLanguage!="checked"){include('lang-'.$cnfLanguage.'.php');}
else{
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
switch ($lang){
	case "es":
    include('lang-es-ES.php');
        break;        
    default:
	include('lang-en-US.php');
       break;
}

}


if($cnfError=="checked"){
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
}
if(isset($_COOKIE['iduserx'])){
	if($_COOKIE['iduserx']!=""){
	if (!isset($_SESSION)) { session_start(); }	
	$_SESSION['iduserx']=$_COOKIE['iduserx'];
	$_SESSION['level']=$_COOKIE['level'];
	}
}
//echo $_COOKIE['iduserx']."-->".$_SESSION['iduserx'];
function cmp($a, $b)
{
    return $a[2]- $b[2];
}

function fCleanChar($cadena,$mod=0){
	$originales = ' ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ?¿!¡';
	$modificadas ='-aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr    ';
	$cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	$cadena = preg_replace("/[?¿!¡|*\\/:]/", "", $cadena);
	return $cadena;	
}

function fCleanSimple($cadena,$mod=0){	
	/*$originales = ' ';
	$modificadas ='-';*/
	$conv = array(" " => "-", "_T_" => "_t_");
	$cadena = utf8_decode($cadena);
    $cadena = strtr($cadena,$conv);   
	$cadena = preg_replace("/[#?¿!¡|*\\/:%+]/", "", $cadena);
	return $cadena;
}	

function fReconvert($cadena,$mod=0){	
	if($mod==0){
	$conv = array("&#39;" => "'", "&#34;" => '"');
	$cadena = strtr($cadena,$conv);  
	//$cadena=addslashes($cadena); 	
	}
	else{	
	$conv = array("&#39;" => "\\'");
	$cadena = strtr($cadena,$conv); 
	}
	//else $cadena=addcslashes($cadena, "'");
	//if($mod!=0)$cadena=addcslashes($cadena, "'");
	//die($cadena);
	return $cadena;
}	



function fProgramadas(){
if(file_exists("p.xml")){

$ahora=time();
$xmlP=simplexml_load_file("p.xml");
foreach ($xmlP->h as $programado) {
if((int)$programado->p<=(int)$ahora){
rename($programado->t,str_replace("_T_","",$programado->t));
unset($programado[0][0]);
break;
}
}
if(count($xmlP)==0)unlink("p.xml");
else $xmlP->asXml("p.xml");
}



}


function fGetUser($user,$id){
$email="";
 if (file_exists("users/".$user[0].".php")){
	  ini_set('memory_limit', '-1');
		
		$contenido = file_get_contents("users/".$user[0].".php");
		//echo "holaaaaa".$contenido."<br>";
		$posA = strpos($contenido,$user."=",0);
		if($posA!==false){
		$posB = strpos($contenido,"=",$posA);
		$posC = strpos($contenido,",",$posB);
		$len =$posC-$posB;
		$intVotes=substr($contenido,$posB+1,$len-1); 
		//die("<br>---->".$intVotes);
		
		$posD = strpos($contenido,",",$posC+1);
		$lenDC =$posD-$posC;		
		$intPost=substr($contenido,$posC+1,$lenDC-1); 
		
		$posE = strpos($contenido,",",$posD+1);
		$lenED =$posE-$posD;		
		$intHijos=substr($contenido,$posD+1,$lenED-1); 
		
		$posF = strpos($contenido,",",$posE+1);
		$lenFE =$posF-$posE;		
		$email=substr($contenido,$posE+1,$lenFE-1); 
		
		$posG = strpos($contenido,",",$posF+1);
		$lenGF =$posG-$posF;		
		$intDate=substr($contenido,$posF+1,$lenGF-1); 
		
		$posH = strpos($contenido,",",$posG+1);
		$lenHG =$posH-$posG;		
		$strPadre="0";
		$strPadre=substr($contenido,$posG+1,$lenHG-1);
		if($strPadre=="0"){$strPadre="Sin padre";}		
		
		$posJ = strpos($contenido,",",$posH+1);
		$lenJH =$posJ-$posH;		
		$strDes="0";
		$strDes=substr($contenido,$posH+1,$lenJH-1);
		if($strDes=="0"){$strDes="Sin descripción";}
		}

}
if($id==3)return $email;
}

function thumbnail($thumbnail,$cnfHome,$cnfLogo,$cnfThumbnail){	
if($cnfThumbnail!=""){	
$thumbnail="../".$cnfThumbnail."/".$thumbnail;
//echo $thumbnail.".jpg";
	if(file_exists($thumbnail.".jpg"))$thumbnail=$thumbnail.".jpg";
	else if	(file_exists($thumbnail.".jpeg"))$thumbnail=$thumbnail.".jpeg";
	else if	(file_exists($thumbnail.".png"))$thumbnail=$thumbnail.".png";
	else if	(file_exists($thumbnail.".gif"))$thumbnail=$thumbnail.".gif";
	else if	(file_exists($thumbnail.".bmp"))$thumbnail=$thumbnail.".bmp";
	else $thumbnail=$cnfHome.$cnfLogo;
	
	
}else{
    $thumbnail=$cnfHome.$cnfLogo;	
}
$thumbnail=str_replace("../",$cnfHome,$thumbnail);
return $thumbnail;
}
//fProgramadas();
if($cnfAutoPosting!="")fProgramadas();


if(isset($_GET["p"])){
	$_SESSION['parent']=filter_var($_GET["p"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	setcookie( "parent", $_SESSION['parent'],  time() + (10 * 365 * 24 * 60 * 60)) ;
//$parent=$_SESSION['parent'];
}else if(isset($_COOKIE['parent']))
{
$_SESSION['parent']=filter_var($_COOKIE['parent'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
}
	

//echo $_SESSION['parent'];

?>
<!DOCTYPE html>
<html itemtype="http://schema.org/WebPage" lang="<?php echo $cnfLanguage;?>">
<head>
<link rel="icon" href="<?php echo $cnfHome.$cnfFav;?>" type="image/x-icon">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $cnfHome.$cnfStyle;?>">
<?php
if($cnfGoogleAuthor!="")echo '<link rel="author" href="'.$cnfGoogleAuthor.'" />';
if($cnfGoogleInsignia!="")echo '<link href="https://plus.google.com/'.$cnfGoogleInsignia.'" rel="publisher"/>';
?>
<script src="<?php echo $cnfHome.$cnfJava;?>" type="text/javascript" ></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="content-language" content="<?php echo $cnfLanguage;?>" />
<meta name="viewport" content="width=device-width">

<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>