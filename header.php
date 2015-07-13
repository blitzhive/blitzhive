<?php 
if(basename(__FILE__)== basename($_SERVER["SCRIPT_FILENAME"]))die(); 
include('lang-'.$cnfLanguage.'.php');
if($cnfError=="checked"){
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
}

if (!isset($_SESSION)) { session_start(); }
if($_COOKIE['iduserx'])$_SESSION['iduserx']=$_COOKIE['iduserx'];

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
	$originales = ' ';
	$modificadas ='-';
	$cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, $originales, $modificadas);   
	$cadena = preg_replace("/[?¿!¡|*\\/:%+]/", "", $cadena);
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
//fProgramadas();

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
<meta name="viewport" content="width=device-width">
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>
