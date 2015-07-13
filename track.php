<?php
include('config.php');
if($cnfTrack!=""&&!isset($_COOKIE['visit'])){
/*error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);*/
//track
$ip="0";
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
//echo $ip;
if($ip!="0"){

	$xml2 = new DOMDocument();
	$xml2->encoding= 'utf-8';
	if(!isset($tr))$tr=0;
	if($tr==1)$fileDate="../".$cnfTrack."/".gmdate("d-m-Y",time()).".xml";
	else $fileDate=$cnfTrack."/".gmdate("d-m-Y",time()).".xml";
	//echo $tr."--".$fileDate;
	if(file_exists($fileDate)){
	$xml2->load($fileDate);
	$xml_document2=$xml2->getElementsByTagName("d")->item(0);	
	$lengh=$xml2->getElementsByTagName("t")->length;	
	for ($x=0;$x<$lengh;$x++){	
	//echo $xml2->getElementsByTagName("t")->item($x)->textContent."==".$ip;
	if($xml2->getElementsByTagName("t")->item($x)->textContent==$ip){	
	die("");
	}
	}	
	}else{
	$xml_document2 = $xml2->createElement("d");
	}
	$xml_title2 = $xml2->createElement("t");
	$xml_title2->appendChild($xml2->createTextNode($ip)); 
	$xml_document2->appendChild($xml_title2);
	$xml2->appendChild($xml_document2);	
	$xml2->save($fileDate);	
	setcookie("visit", "1", time()+60*60*24); 
	//total
	}
}//track
?>