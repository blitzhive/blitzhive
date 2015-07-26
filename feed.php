<?php
//ini_set('error_reporting', E_ALL);
include('config.php');
if($cnfError=="checked"){
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
}else{
ini_set('display_errors',0);
ini_set('display_startup_errors',0);
error_reporting(0);
	
}
if(isset($_GET['s']))$s=filter_var($_GET['s'], FILTER_SANITIZE_SPECIAL_CHARS);
else $s="";
if(isset($_GET['t']))$t=filter_var($_GET['t'],FILTER_SANITIZE_SPECIAL_CHARS);
else $t=2;


if(!is_numeric($t))die("Variable t incorrecta");



header('Content-type: text/xml; charset="UTF-8"', true);


$strLink="";
$strLinkEnd="";
if($cnfPermaLink==0){$strLink="index.php/";
$strLinkEnd="/";
$posVar=strrpos($s, "index.php/");
}
else if($cnfPermaLink==1){$strLink="?m=";
$strLinkEnd="";
$posVar=strrpos($s, "?");
}
else {$strLink="?";
$strLinkEnd="";
$posVar=strrpos($s, "?");
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>
<channel>
	<title><?php echo $cnfTitle;?></title>
	<?php
	if($s==""||$t==2)echo '<atom:link href="'.$cnfHome.'feed.php" rel="self" type="application/rss+xml" />';
	else if($t==1)echo '<atom:link href="'.$cnfHome.'feed.php?t=1&amp;s='.$s.'" rel="self" type="application/rss+xml" />';
	else if($t==0)echo '<atom:link href="'.$cnfHome.'feed.php?t=0&amp;s='.$s.'" rel="self" type="application/rss+xml" />';
	?>
 
	<link><?php echo $cnfHome; ?></link>
	<description><?php echo $cnfHeaderText; ?></description>
	<lastBuildDate>Fri, 14 Feb 2014 11:57:44 +0000</lastBuildDate>
	<language><?php echo $cnfLanguage; ?></language>
		<sy:updatePeriod>hourly</sy:updatePeriod>
		<sy:updateFrequency>1</sy:updateFrequency>
		<generator><?php echo $cnfHome."feed.php"; ?></generator>
	<?php
if($t==0){
$files = array(2);
$auxS=$s;
if($s=="")$folder=".";
else $folder=$s;
	if ($handle = opendir($folder)) {
$xr=0;
while (false !== ($file = readdir($handle))) {
       if ($file != "cache" && $file != "." && $file != ".."  && $file != "index.php" 
	   && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml'
	   &&strlen($file)>6&&strrpos($file, "_T_")===false
	   ) {
	   
	   	/*$posVarTag=strrpos($file, "_T_");
			
		  if($posVarTag===false){*/
		  
          $files[filemtime($file)][0] = $file;
		  $files[filemtime($file)][1] = filemtime($file);
		  //echo basename($file, ".xml").PHP_EOL;
		   $fileName=basename($file,".xml").PHP_EOL;
		   $titleFile=str_replace ("-"," ",$fileName);
		   $fileName=utf8_encode($fileName);
		   $fileName=preg_replace("/\\s+/iu","",$fileName);
		   //die($cnfHome.$s."/".$strLink.$fileName.$strLinkEnd);
		  $xml=simplexml_load_file($s.'/'.$file);
			
if($s!="")$auxS=$s."/";
/*$rssLink="";
if($s!="")$rssLink=str_replace("/", "/".$strLink, $fileName).$strLinkEnd;
else $rssLink=$strLink.$fileName.$strLinkEnd;*/
			?>
<item>
<title><?php echo utf8_encode($titleFile);?></title>
<link><?php echo $cnfHome.$auxS.$strLink.$fileName.$strLinkEnd;?></link>
<comments><?php echo $cnfHome.$auxS.$strLink.$fileName.$strLinkEnd."#answer";?></comments>
<pubDate><?php echo gmdate("D, d M Y H:i:s O",(int)$xml->p[0]->a); ?></pubDate>
<dc:creator><![CDATA[<?php echo $xml->p[0]->u;?>]]></dc:creator>
<?php
$strTags=explode(" ",strtolower($xml->p[0]->g));
for($xf=0;$xf<count($strTags);$xf++){
?>
<category><![CDATA[<?php echo $strTags[$xf];?>]]></category>
<?php
}
?>
<description><![CDATA[<?php echo $xml->p[0]->b;?>]]></description>
<wfw:commentRss><?php echo  $cnfHome."feed.php?t=1&amp;s=".$auxS.$strLink.$fileName.$strLinkEnd;?></wfw:commentRss>
</item><?php
$xr++;
if($xr>$cnfNumberFeed)break;
		//	}
       }

   }
   closedir($handle);
}
}else if($t==1){
//$posVar=strrpos($s, "?");
if($cnfPermaLink==0){
$titleFile=str_replace ("-"," ",substr($s,$posVar+10));
$fileName=str_replace ("/".$strLink,"/",substr($s,0,strlen($s)-1).".xml");
//die($fileName);
}
else{
	//echo "asdasd";
$titleFile=str_replace ("-"," ",substr($s,$posVar+1));
$fileName=str_replace ("/".$strLink,"/".$strLink,$s.".xml");
}
//die($titleFile);
$fileName=utf8_decode($fileName);
//die($fileName);
if(!file_exists($fileName)){

$_SESSION['error']=$fileName;
header( "refresh:0;url=".$cnfHome."/404.php");
}
$xml=simplexml_load_file($fileName);
$xr=0;
//die($);
foreach ($xml->p as $repuesta) {
?>
<item>
<title><?php echo $titleFile;?></title>
<link><?php echo $cnfHome.$s; ?></link>
<comments><?php echo $cnfHome.$s."#".$xr; ?></comments>
<pubDate><?php echo gmdate("D, d M Y H:i:s O",(int)$repuesta->a); ?></pubDate>
<dc:creator><![CDATA[<?php echo $repuesta->u;?>]]></dc:creator>
<?php
if($xr==0){
$strTags=explode(" ",strtolower($repuesta->g));
for($xf=0;$xf<count($strTags);$xf++){
?>
<category><![CDATA[<?php echo $strTags[$xf];?>]]></category>
<?php
}
}
?>
<description><![CDATA[<?php echo $repuesta->b;?>]]></description>
</item>
<?php
$xr++;
if($xr>$cnfNumberFeed)break;
}
}else{//t==2


 if(file_exists("i.xml")){
$xmlP = new DOMDocument();
$xmlP=simplexml_load_file("i.xml");
foreach ($xmlP->h as $programado) {
$xml=simplexml_load_file(utf8_decode($programado->t).".xml");

//else $rssLink=$strLink.utf8_decode($_GET['w']).$strLinkEnd."#".$t;
$rssLink=str_replace("/", "/".$strLink, $programado->t).$strLinkEnd;
?>
<item>
<title><?php echo str_replace("-"," ",$xml->p[0]->t);?></title>
<link><?php echo $cnfHome.$rssLink;?></link>
<comments><?php echo $cnfHome.$rssLink."#answer"; ?></comments>
<pubDate><?php echo gmdate("D, d M Y H:i:s O",(int)$xml->p[0]->a); ?></pubDate>
<dc:creator><![CDATA[<?php echo $xml->p[0]->u;?>]]></dc:creator>
<?php
$strTags=explode(" ",strtolower($xml->p[0]->g));
for($xf=0;$xf<count($strTags);$xf++){
?>
<category><![CDATA[<?php echo $strTags[$xf];?>]]></category>
<?php
}
?>
<description><![CDATA[<?php echo $xml->p[0]->b;?>]]></description>
</item>
<?php

 }
}
else{


foreach(explode(";",$arrForums) as $line){
$item=explode("*",$line);     
if(isset($item[1])){
if ($handle = opendir($item[4])) {

$xr=0;
    while (false !== ($file = readdir($handle))) {
	if ($file != "cache" && $file != "." && $file != ".."  && $file != "index.php" 
	&& strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml'
	   &&strlen($file)>6&&strrpos($file, "_T_")===false
	   ) {
$xml=simplexml_load_file($item[4]."/".$file);
$file=str_replace(".xml","",$file);
//foreach ($xml->p as $repuesta) {
?>
<item>
<title><?php echo str_replace("-"," ",utf8_encode($file));?></title>
<link><?php echo $cnfHome.$item[4]."/".$strLink.utf8_encode($file).$strLinkEnd; ?></link>
<comments><?php echo $cnfHome.$item[4]."/".$strLink.utf8_encode($file).$strLinkEnd."#answer"; ?></comments>
<pubDate><?php echo gmdate("D, d M Y H:i:s O",(int)$xml->p[0]->a); ?></pubDate>
<dc:creator><![CDATA[<?php echo $xml->p[0]->u;?>]]></dc:creator>
<?php
//if($xr==0){
$strTags=explode(" ",strtolower($xml->p[0]->g));
for($xf=0;$xf<count($strTags);$xf++){
?>
<category><![CDATA[<?php echo $strTags[$xf];?>]]></category>
<?php
}
//}
?>
<description><![CDATA[<?php echo $xml->p[0]->b;?>]]></description>
</item>
<?php

//}
	}
$xr++;
if($xr>$cnfNumberFeed)break;
		}
}		
//****

}
$xx++;
}
}
}

?>	
</channel>
</rss>

		