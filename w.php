<?php
include('config.php');
include('header.php');
function fRemoveT($w){
$forum=substr($w,0,strpos($w,"/",0)); 
$fileName=substr($w,strpos($w,"/",0)+1); 
if(file_exists($forum."/t.xml")){
$xmlT = new DOMDocument();
$xmlT->load($forum."/t.xml");
foreach ($xmlT->getElementsByTagName('t') as $element){
if($element->textContent==utf8_encode($fileName)){
$element->parentNode->removeChild($element);
}}
$xmlT->save($forum."/t.xml");
}}

$strLink="";
$strLinkEnd="";
if($cnfPermaLink==0){$strLink="index.php/";
$strLinkEnd="/";}
else if($cnfPermaLink==1){$strLink="?m=";
$strLinkEnd="";
}

//if (!isset($_SESSION)){session_start();}
$user="";
if(isset($_SESSION['iduserx']))$user=$_SESSION['iduserx'];

if(isset($_GET['q']))$q=filter_var($_GET['q'], FILTER_SANITIZE_SPECIAL_CHARS);
$u="0";
if(isset($_POST['u']))$u=filter_var($_POST['u'], FILTER_SANITIZE_SPECIAL_CHARS);//tags

$d="0";
if(isset($_POST['d']))$d=filter_var($_POST['d'], FILTER_SANITIZE_SPECIAL_CHARS);//block
$f="0";
if(isset($_POST['f']))$f=filter_var($_POST['f'], FILTER_SANITIZE_SPECIAL_CHARS);//ping
$po="0";
if(isset($_POST['po']))$po=filter_var($_POST['po'], FILTER_SANITIZE_SPECIAL_CHARS);//portada
$no="0";
if(isset($_POST['no']))$no=filter_var($_POST['no'], FILTER_SANITIZE_SPECIAL_CHARS);//notificacion
$ti=0;
if(isset($_POST['ti']))$ti=filter_var($_POST['ti'], FILTER_SANITIZE_SPECIAL_CHARS);//tiempo-programar

if(isset($_GET['w']))$w=filter_var($_GET['w'], FILTER_SANITIZE_SPECIAL_CHARS);//FIleName
$e="";
if(isset($_POST['txtE']))$e=nl2br($_POST['txtE']);//body
if(isset($_POST['r']))$r=filter_var($_POST['r'], FILTER_SANITIZE_SPECIAL_CHARS);//type
if(isset($_GET['t']))$t=filter_var($_GET['t'], FILTER_SANITIZE_SPECIAL_CHARS);//path
if(isset($_GET['y']))$y=filter_var($_GET['y'], FILTER_SANITIZE_SPECIAL_CHARS);
if(isset($_POST['w'])){
$_POST['w']=preg_replace("/&/", "ampersand", $_POST['w']);
if(isset($_GET['w']))$ww=filter_var($_POST['w'], FILTER_SANITIZE_SPECIAL_CHARS);//title
else $w=filter_var($_POST['w'], FILTER_SANITIZE_SPECIAL_CHARS);//title
}

$secForum="";
if($q==4||$q==6){
$pA=strpos($w,"/",0);
$secForum=substr(utf8_decode($w),0,$pA);
}

if(isset($_POST['submitFile'])){
	if($q!=2){
	  if($w!=""){
	  $_SESSION['title']=$w; }
	  if($e!=""){
	  $_SESSION['body']=$e;
	  }
	  }else{
	  $_SESSION['answer']=$e;}
$xv=0;
foreach($_FILES['file']['tmp_name'] as $key => $tmp_name ){
if($_FILES["file"]["name"][$key]!=""){
$temp = explode(".", $_FILES["file"]["name"][$key]);
$extension = end($temp);
$maxSize = intval($cnfMax);
if (($_FILES["file"]["size"][$key] < $maxSize)
&& (strpos($cnfExt,$extension,0)!==false) 
) {
if ($_FILES["file"]["error"][$key] > 0) {
    echo "Error código: " . $_FILES["file"]["error"][$key]. "<br>";
    }else{
	$originales = ' ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$modificadas ='-aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$_FILES["file"]["name"][$key] = preg_replace("/[?¿!¡|*\\/:]/", "", $_FILES["file"]["name"][$key]);
	$_FILES["file"]["name"][$key] = utf8_decode($_FILES["file"]["name"][$key]);
	$_FILES["file"]["name"][$key] = strtr($_FILES["file"]["name"][$key], utf8_decode($originales), $modificadas);
	$xc=0;
	if(!is_dir($cnfUploads))mkdir($cnfUploads, 0777, true);
    while(file_exists($cnfUploads."/" . $_FILES["file"]["name"][$key])) {
	$_FILES["file"]["name"][$key]=$xc."-".$_FILES["file"]["name"][$key];
	  $xc++;
    } 
	if(move_uploaded_file($_FILES["file"]["tmp_name"][$key],$cnfUploads."/".$_FILES["file"]["name"][$key]))
	{
	$_SESSION['image'.$xv]=$_FILES["file"]["name"][$key];
	echo "Archivo subido :) ".$_SESSION['image'.$xv]."<br>";}
    $xv++;
  }} else {
   echo "Archivo invalido ".$_SESSION['image'.$xv]."(".$cnfExt.")<br>";
   if($_FILES["file"]["size"][$key] >= $maxSize){ echo  $lngMaxSize.": ".$maxSize; }
   else if(strpos($cnfExt,$extension,0)!==false){ echo  $lngExt.": ".$cnfExt."<br>"; }
	}
}}

if($q!=2)die(header( "refresh:2;url=".$cnfHome.$t));
else die(header( "refresh:2;url=".$cnfHome.str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#answer"));
//XSS
}else if($q==0||$q==2){
if(strlen($w)<3){echo "<h3>".$lngTitle3." :)</h3>";
die(header('refresh:2;url=' . $_SERVER['HTTP_REFERER']));}
if(strlen($e)<1){echo "<h3>Write something for the Body :)</h3>";
die(header('refresh:2;url=' . $_SERVER['HTTP_REFERER']));}

$folderName=""; 
$fileName=$w;
$strSubName="";
if($ti!=0)$strSubName="_T_";
$fileName=fCleanSimple($fileName.$strSubName,0);

$xml=0;
$last15=0;
if($q==0){
$xml = new DOMDocument();
}
else if($q==2){
$xml = new DOMDocument();
$w=utf8_decode($w);
$xml->load($w.".xml");
if(!file_exists($w.".xml"))die($lngProb); 
}

$xml->encoding= 'utf-8';
if($q==0)$xml_document = $xml->createElement("d");
else $xml_document=$xml->getElementsByTagName("d")->item(0);
$xml_post = $xml->createElement("p");
$xml_body = $xml->createElement("b");
$xml_user = $xml->createElement("u");
$xml_date = $xml->createElement("a");
$xml_vote = $xml->createElement("v");
$xml_block = $xml->createElement("l");
$xml_pin = $xml->createElement("i");
$xml_not = $xml->createElement("n");
$xml_fav = $xml->createElement("f");
if($q==2)$xml_mod = $xml->createElement("m");	
unset($_SESSION['image0']);
unset($_SESSION['image1']);
unset($_SESSION['image2']);
$e=strip_tags($e,'<br><b><i><u><strike><s><a><img><iframe><div><code><pre><h1><h2><h3>');
$posA = strpos($e,"<iframe ",0);

while($posA!==false){
$posEnd = strpos($e,"</iframe>",$posA);
$posB = strpos($e,"youtube.com/embed/",$posA);
$posBB = strpos($e,"vimeo.com/video/",$posA);
if($posB!==FALSE){
$posC = strpos($e,'"',$posB);
$len =$posC-$posB;
$urlYoutube=substr($e,$posB+strlen("youtube.com/embed/"),$len-strlen("youtube.com/embed/")); 
$strYoutube='<iframe width="480" height="360" src="//www.youtube.com/embed/'.$urlYoutube.'" frameborder="0" allowfullscreen></iframe>';
$longIframe=$posEnd-$posA;
$e=substr_replace($e,$strYoutube,$posA,$longIframe);
}else if($posBB!==FALSE){
$posC = strpos($e,'"',$posBB);
$len =$posC-$posBB;
$urlYoutube=substr($e,$posBB+strlen("vimeo.com/video/"),$len-strlen("vimeo.com/video/")); 
$strYoutube='<iframe width="500" height="281" src="//player.vimeo.com/video/'.$urlYoutube.'"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
$longIframe=$posEnd-$posA;
$e=substr_replace($e,$strYoutube,$posA,$longIframe);
}else{
$e=strip_tags($e,'<br><b><i><u><strike><a><img><div><code><pre><h1><h2><h3>');
break;
}
$posA = strpos($e,"<iframe ",$posEnd);
}

$xml_body->appendChild( $xml->createTextNode($e)); 
$xml_user->appendChild( $xml->createTextNode($user)); 
$xml_date->appendChild( $xml->createTextNode(time()));
$xml_vote->appendChild( $xml->createTextNode("")); 


$xml_post->appendChild( $xml_date);
$xml_post->appendChild( $xml_user);
$xml_post->appendChild( $xml_vote);
$xml_post->appendChild( $xml_body);

$modToken="0";
if($q==0){
$xml_title = $xml->createElement("t");
$xml_title->appendChild( $xml->createTextNode($w)); 
$xml_post->appendChild( $xml_title);
if($u!="0"){
$u=trim($u);
$u = str_replace(", ", ",", $u);
$u = str_replace(" ,", ",", $u);
$u = str_replace(",,", ",", $u);
$strSubName=str_replace(",", "-", $u);
//die("-->".$u."<--");
}
$xml_tag = $xml->createElement("g");
$xml_tag->appendChild( $xml->createTextNode($u)); 
$xml_post->appendChild( $xml_tag);
$xml_block->appendChild($xml->createTextNode($d)); 
$xml_pin->appendChild($xml->createTextNode($f)); 
$xml_not->appendChild($xml->createTextNode($no)); 
$xml_fav->appendChild($xml->createTextNode("0")); 
$xml_post->appendChild( $xml_block);
$xml_post->appendChild( $xml_pin);
$xml_post->appendChild( $xml_not);
$xml_post->appendChild( $xml_fav);
}else if($q==2){
//$xml_user

if($_SESSION['iduserx']!=$cnfAdm){
if($cnfModAnswerAll!=""){
$modToken="1";
}
else if($cnfModAnswerLink!=""){
$posA = strpos($e,"<a ",0);
if($posA!==false)$modToken="1";
}
else if($cnfModAnswerLevel!=""){
$cnfModAnswerLevel=intval($cnfModAnswerLevel);
if($_SESSION['level']<$cnfModAnswerLevel)$modToken="1";
}
}
//die("-->".$t);
$xml_mod->appendChild($xml->createTextNode($modToken)); 
$xml_post->appendChild($xml_mod);
if($modToken=="1"){
$xml2 = new DOMDocument();
$xml2->encoding= 'utf-8';
if(file_exists("m.xml")){
$xml2->load("m.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);
}else{
$xml_document2 = $xml2->createElement("d");
}
$xml_title2 = $xml2->createElement("t");
$xml_time = $xml2->createElement("p");
$xml_title2->appendChild($xml2->createTextNode(utf8_encode($w))); 
$xml_time->appendChild( $xml2->createTextNode($t)); 
$xml_hijo = $xml2->createElement("h");
$xml_hijo->appendChild($xml_title2);
$xml_hijo->appendChild($xml_time);
$xml_document2->appendChild($xml_hijo);
$xml2->appendChild($xml_document2);
$xml2->save("m.xml");
}

	
}

$xml_document->appendChild($xml_post);
$xml->appendChild($xml_document);

if($q==0){
	if (file_exists($cnfUsers."/".$user[0].".php")){
	  ini_set('memory_limit', '-1');
		$contenido=file_get_contents($cnfUsers."/".strtolower($user[0]).".php");		
		$posA = strpos($contenido,$user."=",0);
		if($posA!==false){
		$posB = strpos($contenido,"=",$posA);
		$posC = strpos($contenido,",",$posB);
		$len =$posC-$posB;
		$posD = strpos($contenido,",",$posC+1);
		$lenDC =$posD-$posC;
		$intPost=substr($contenido,$posC+1,$lenDC-1); 
		$intPost=$intPost+1;
		$contenido=substr_replace($contenido,$intPost,$posC+1,$lenDC-1);
		
		if(str_word_count($e, 0)>199){		
		$intVotes=substr($contenido,$posB+1,$len-1); 
		$intVotes+=1;
		$contenido=substr_replace($contenido,$intVotes,$posB+1,$len-1);
		}
		file_put_contents($cnfUsers."/".strtolower($user[0]).".php", $contenido);
		}else{
		die("<h1>".$lngUserNot." :(</h1>");
		}
		}

if($t!=""){
	//die($t."/".$fileName.".xml");
$xml->save($t."/".$fileName.".xml");
}
else {

$xml->save($fileName.".xml");

}
unset($_SESSION['title']);
unset($_SESSION['body']);

if($f!="0"){
$xml2 = new DOMDocument();
$xml2->encoding= 'utf-8';
if(file_exists($t."/t.xml")){
$xml2->load($t."/t.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);

}else{
$xml_document2 = $xml2->createElement("d");
}
$xml_title2 = $xml2->createElement("t");

$xml_title2->appendChild($xml2->createTextNode(utf8_encode($fileName))); 
$xml_document2->appendChild($xml_title2);
$xml2->appendChild($xml_document2);
$xml2->save($t."/t.xml");

}

if($ti!=0){
$sum=$ti * 60 * 60;	
$programada=time()+$sum;
$xml2 = new DOMDocument();
$xml2->encoding= 'utf-8';
if(file_exists("p.xml")){
$xml2->load("p.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);
}else{
$xml_document2 = $xml2->createElement("d");
}
$xml_title2 = $xml2->createElement("t");
$xml_time = $xml2->createElement("p");
$xml_title2->appendChild($xml2->createTextNode($t."/".utf8_encode($fileName))); 
$xml_time->appendChild( $xml2->createTextNode($programada)); 
$xml_hijo = $xml2->createElement("h");
$xml_hijo->appendChild($xml_title2);
$xml_hijo->appendChild($xml_time);
$xml_document2->appendChild($xml_hijo);
$xml2->appendChild($xml_document2);
$xml2->save("p.xml");

}

if($po!="0"||($cnfNewsLevel!=""&&intval($_SESSION['level'])>=intval($cnfNewsLevel))){
$xml2 = new DOMDocument();
$xml2->encoding= 'utf-8';
if(file_exists("i.xml")){
$xml2->load("i.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);
}else{
$xml_document2 = $xml2->createElement("d");
}
$xml_title2 = $xml2->createElement("t");

$xml_title2->appendChild($xml2->createTextNode($t."/".utf8_encode($fileName))); 

$xml_hijo = $xml2->createElement("h");
$xml_hijo->appendChild($xml_title2);

if($xml_document2->childNodes->length>1){
$items = $xml2->getElementsByTagName('h');
$items->item(0)->parentNode->insertBefore($xml_hijo, $items->item(0));
//die("1");
}else{
//die("2");
$xml_document2->appendChild($xml_hijo);
$xml2->appendChild($xml_document2);
}
$xml2->save("i.xml");
}

if(str_word_count($e, 0)>199){
header("refresh:2;url=".$cnfHome.utf8_decode($_GET['t'])."/".$strLink.str_replace(" ", "-", html_entity_decode($fileName)).$strLinkEnd);
die($lngSwarnVote.' :)');
}else{
die(header("refresh:0;url=".$cnfHome.utf8_decode($_GET['t'])."/".$strLink.str_replace(" ", "-", html_entity_decode($fileName))).$strLinkEnd);
}

}else if($q==2){
//MAILKIN
if($no!="0"&&strpos($xml->getElementsByTagName("n")->item(0)->nodeValue,$_SESSION['iduserx'].",",0)===false)$xml->getElementsByTagName("n")->item(0)->nodeValue=$xml->getElementsByTagName("n")->item(0)->nodeValue.",".$_SESSION['iduserx'];
$notification=$xml->getElementsByTagName("n")->item(0)->nodeValue;
if($notification!="0"){
foreach(explode(",",$notification) as $line){
if($line!=$_SESSION['iduserx']){
$titulo= ''.$lngNewAnswer.'';
$mensaje   = $lngNewAnswer2.': '.$cnfHome.str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t;
$cabeceras = 'From: '.$cnfEmail.'' . "\r\n" .	 'Reply-To: '.$cnfEmail.'' . "\r\n" .
    'X-Mailer: PHP/'.phpversion();
	$emailNot=fGetUser($line,3);
	mail($emailNot, $titulo, $mensaje, $cabeceras);
 }
}
}

unset($_SESSION['answer']);
$xml->save($w.".xml");
$ifBlogMOde=strpos($_GET['w'],'/'.$strLink);
if($ifBlogMOde===false)die(header( "refresh:0;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t));
else die(header("refresh:0;url=".$strLink.utf8_decode($_GET['w']).$strLinkEnd."#".$t));
}
}else if($q==1){
$path=substr($w, 0, strpos($w,'/'));
$titleFile=substr($w, strpos($w,'/')+1);
if(!file_exists($w.".xml"))die($lngTheMes." ".$w." ".$lngNotExist); 
$xml=simplexml_load_file($w.".xml");
$t=time();
$interval=$t-$xml->p[0]->a;
$interMinuts=$interval/60;
if($interMinuts<15&&$xml->p[0]->u==$user){$last15=1;}
}else if($q==3){
$w=utf8_decode($w);
if(!file_exists($w.".xml"))die($lngTheMes." ".$w." ".$lngNotExist); 
$xml=simplexml_load_file($w.".xml");
$t=intval($t);
if(strpos($xml->p[$t]->v,",".$user,0)===false&&intval($_SESSION['level'])>=intval($cnfVoteLevel)){
$xml->p[$t]->v=$xml->p[$t]->v.",".$user;
$xml->asXML($w.'.xml');
      if (file_exists($cnfUsers."/".$y[0].".php")) {
	  ini_set('memory_limit', '-1');
		$contenido = file_get_contents($cnfUsers."/".$y[0].".php");
		$posA = strpos($contenido,$y."=",0);
		if($posA!==false){
		$posB = strpos($contenido,"=",$posA);
		$posC = strpos($contenido,",",$posB);
		$len =$posC-$posB;
		$intVotes=substr($contenido,$posB+1,$len-1); 
		$intVotes+=1;
		$contenido=substr_replace($contenido,$intVotes,$posB+1,$len-1);
		file_put_contents($cnfUsers."/".$y[0].".php", $contenido);
		}else{
		die("<h1>".$lngUserNot." :(</h1>");
		}
		}
die(header("refresh:0;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t));
}else{
echo $lngVoted.":)";
header("refresh:2;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t);
}
}else if($q==4){


$w=utf8_decode($w);
if(!file_exists($w.".xml"))die($lngTheMes." ".$w." ".$lngNotExist); 
$xml=simplexml_load_file($w.".xml");
$ti=time();
$interval=$ti-$xml->p[0]->a;
$interMinuts=$interval/60;
if(isset($_SESSION['allowdelete'])&&$interMinuts<15
||
$_SESSION['iduserx']==$cnfAdm
||
$_SESSION['mod']==$secForum
){
$t=intval($t);

if($t==0){
if($y==1)fRemoveT($w);
unlink($w.".xml");
unset($_SESSION['allowdelete']);
$posA = strpos($_GET['w'],"/",0);
echo $lngDelMes;
die(header("refresh:1;url=".$cnfHome."/".substr(utf8_decode($_GET['w']),0,$posA)));
}else{
unset($xml->p[$t]);
$xml->asXml($w.".xml");
$t=$t-1;
echo $lngDelAns;
die(header( "refresh:1;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t));
}
}
}else if($q==5){

$xml = new DOMDocument();
$w=utf8_decode($w);
$forum=substr($w,0,strpos($w,"/",0)); 
$fileName=substr($w,strpos($w,"/",0)+1); 
if(!file_exists($w.".xml"))die($lngProb); 
$xml=simplexml_load_file($w.".xml");
$t=intval($t);
$xml->p[$t]->b=$e;//body
$xml->p[$t]->t=$ww;
$xml->p[$t]->g=$u;
if($t==0){
$xml->p[$t]->l=$d;
$xml->p[$t]->i=$f;
}

if($po!="0"){//EDITION
$xml2 = new DOMDocument();
$xml2->encoding= 'utf-8';
if(file_exists("i.xml")){
$xml2->load("i.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);
}else{
$xml_document2 = $xml2->createElement("d");
}
$xml_title2 = $xml2->createElement("t");

$xml_title2->appendChild($xml2->createTextNode(utf8_encode($w))); 

$xml_hijo = $xml2->createElement("h");
$xml_hijo->appendChild($xml_title2);

if($xml_document2->childNodes->length>1){
$items = $xml2->getElementsByTagName('h');
$items->item(0)->parentNode->insertBefore($xml_hijo, $items->item(0));
}else{
$xml_document2->appendChild($xml_hijo);
$xml2->appendChild($xml_document2);
}
$xml2->save("i.xml");
}

unset($_SESSION['answer']);
$xml->asXml($w.".xml");

if($f!="0"){
$xml2 = new DOMDocument();
if(file_exists($forum."/t.xml")){
$xml2->load($forum."/t.xml");
$xml_document2=$xml2->getElementsByTagName("d")->item(0);
//die("abriendo");
}else{
$xml_document2 = $xml2->createElement("d");
}
$noT=0;
foreach ($xml2->getElementsByTagName('t') as $element){
if($element->textContent==utf8_encode($fileName)){
$noT=1;
break;}}
if($noT==0){
$xml_title2 = $xml2->createElement("t");
$xml_title2->appendChild($xml2->createTextNode(utf8_encode($fileName))); 
$xml_document2->appendChild($xml_title2);
$xml2->appendChild($xml_document2);
$xml2->save($forum."/t.xml");
}
}else{
$strFilesT="";
$xx=0;
if(file_exists($forum."/t.xml")){
$xmlT = new DOMDocument();
$xmlT->load($forum."/t.xml");
foreach ($xmlT->getElementsByTagName('t') as $element){
if($element->textContent==utf8_encode($fileName)){
$element->parentNode->removeChild($element);
}}
$xmlT->save($forum."/t.xml");
}}
die(header( "refresh:0;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t));
}
else if($q==6&&
($_SESSION['iduserx']==$cnfAdm
||
$_SESSION['mod']==$secFOrum)
){

$w=utf8_decode($w);
if(!file_exists($w.".xml"))die($lngTheMes." ".$w." ".$lngNotExist); 

$posA = strpos($w,"/",0);

if(strpos(getcwd(),"/")===false){
$folderOrigen=str_replace("/", "\\", realpath(dirname(__FILE__))."/".$w.".xml");
$folderDestino=str_replace("/", "\\", realpath(dirname(__FILE__))."/".$_POST['selMove'].substr($w,$posA));
}else{
$folderOrigen=realpath(dirname(__FILE__))."/".$w.".xml";
$folderDestino=realpath(dirname(__FILE__))."/".$_POST['selMove'].substr($w,$posA);
}

$rna=0;
while(file_exists($folderDestino.".xml")){
$folderDestino=$folderDestino.(string)$rna;
$rna++;
}
if(!copy($folderOrigen,$folderDestino.".xml"))die($lngNotMov." ".$w);
if($y==1)fRemoveT($w);
$xml=simplexml_load_file($w.".xml");
$xml->p[0]->b='
<h1>301 '.$lngMoved.'</h1>
<meta http-equiv="refresh" content="2;url=../'.$_POST['selMove'].'/'.$strLink.substr($w,$posA+1).$strLinkEnd.'">
<p>'.$lngMesMov.' <a href="../'.$_POST['selMove'].'/'.$strLink.substr($w,$posA+1).$strLinkEnd.'">'.$lngHere.'</a>.</p>';
$xml->asXml($w.".xml");
header("refresh:2;url=".$cnfHome.$_POST['selMove'].'/'.$strLink.substr($w,$posA+1).$strLinkEnd);
if($rna>0)echo $lngFileSameName." ".$rna." ".$lngAtEnd."<br>";
die($lngMoved2." ".$w." ".$lngRed." ".$_POST['selMove'].substr($w,$posA));
}else if($q==7&&$y!=$_SESSION['iduserx'])
{
$w=utf8_decode($w);
if(!file_exists($w.".xml"))die($lngTheMes." ".$w." ".$lngNotExist); 
$xml=simplexml_load_file($w.".xml");
$t=intval($t);
$xml->p[0]->f=$t;
$xml->asXML($w.'.xml');
die(header( "refresh:0;url=".str_replace("/", "/".$strLink, utf8_decode($_GET['w'])).$strLinkEnd."#".$t));
}
?>