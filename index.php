<?php
include('config.php');
include('header.php');
$tr=0;
if(isset($_GET['c'])){
$_SESSION['cookieAdv']=1;;
die(header("refresh:0;url=".filter_var($_GET['r'], FILTER_SANITIZE_SPECIAL_CHARS)));	
}


if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx'])){include "cache.php";}
$strLinkUser="?user=";$strLinkEnd="";$strLink="";$strLinkCat="=";
if($cnfPermaLink==0){$strLinkUser="/";$strLinkEnd="/";$strLinkCat="/";$strLink="index.php/";}
?>
<meta name="Keywords" content="<?php echo $cnfKeywords;?>">
<meta name="Description" content="<?php echo $cnfMetaDescription;?>">
<link rel="canonical" href="<?php echo $cnfHome; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $cnfMetaDescription;?>" href="<?php echo $cnfHome."feed.php";?>" />

<meta itemprop="name" content="<?php echo $cnfMetaDescription; ?>"/>
<meta itemprop="description" content="<?php echo $cnfKeywords;?>"/>
<meta itemprop="image" content="<?php echo $cnfHome."".$cnfLogo;?>"/>

<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $cnfTitle;?>" />
<meta property="og:url" content="<?php echo $cnfHome; ?>" />
<meta property="og:site_name" content="<?php echo $cnfMetaDescription; ?>" />
<meta property="og:image" content="<?php echo $cnfHome."".$cnfLogo;?>" />
<meta property="og:locale" content="<?php echo $cnfLanguage;?>" />

<meta property="article:publisher" content="https://www.facebook.com/<?php echo $cnfFbFan;?>" />

<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="<?php echo $cnfTwFollow;?>"/>
<meta name="twitter:creator" content=""/>
<meta name="twitter:domain" content="<?php echo $cnfHome; ?>">
<meta name="twitter:description" content="<?php echo $cnfMetaDescription; ?>"/>
<meta name="twitter:title" content="<?php echo $cnfHeaderText." | ".$cnfTitle;?>"/>
<meta name="twitter:image:src" content="<?php echo $cnfHome."".$cnfLogo;?>"/>

<title><?php echo $cnfHeaderText." | ".$cnfTitle;?></title>
</head>
<body>
<div class="box1">
<img class="logo" title="<?php echo $cnfHeaderText;?>"  src="<?php echo $cnfHome.$cnfLogo;?>"  />
<div class="boxAlignVertical">
<h1 class='h1Vertical'><?php echo $cnfHeaderText;?></h1>
<?php
//session_start();
if(isset($_SESSION['iduserx'])){
echo "<h4 class='h4hello'>".$lngHi." <a title='".$lngSeeProfile."' href='".$cnfHome."user.php".$strLinkUser.$_SESSION['iduserx'].$strLinkUser."'>".$_SESSION['iduserx']."</a></h4><a class='aLogin' href='logout.php?r=index.php'>¿Salir?</a>";
if($_SESSION['iduserx']==$cnfAdm){
echo "<a class='aLogin' href='".$cnfHome."admin.php'>".$lngAdm."</a>";	
}}else{
echo "<a class='aLogin' href='login.php?r=".$_SERVER["REQUEST_URI"]."'>".$lngEnter."&nbsp;|&nbsp; </a>";
echo "<a class='aLogin' href='register.php?r=".$_SERVER["REQUEST_URI"]."'>".$lngReg."</a>";
}
if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx'])){
$cache = new SimpleCachePhp(__FILE__,$cnfHomeCacheTime);
}


?>
</div>
<div class="boxTools">
<?php
if($cnfFbFan!=""){echo '<a class="portadaLinkSocial" title="'.$lngFollow.' en Facebook" href="http://fb.com/'.$cnfFbFan.'" target="_blank" />Facebook</a>';}
if($cnfTwFollow!=""){echo '<a class="portadaLinkSocial" title="'.$lngFollow.' en Twitter" href="https://twitter.com/'.$cnfTwFollow.'" target="_blank" />Twitter</a>';}
if($cnfGoogleInsignia!=""){echo '<a class="portadaLinkSocial" title="'.$lngFollow.' en Google Plus" href="https://plus.google.com/'.$cnfGoogleInsignia.'" target="_blank" />Google+</a>';}
if($cnfytChannel!=""){echo '<a class="portadaLinkSocial" title="'.$lngSub.' en Youtube" href="https://www.youtube.com/channel/'.$cnfytChannel.'" target="_blank" />Youtube</a>';}

if($cnfXGoogle!=""){echo '<input type="text" onKeyUp="fSearch0(event,0,\''.$cnfHome.'\')" id="googleSearch" /><input id="btgoogleSearch" type="button" value="'.$lngSearch.'" onclick="fSearch(0,\''.$cnfHome.'\')"	/>';}
else if($cnfGoogleSearch!=""){echo "<gcse:search></gcse:search>";}
?>
</div>
<div class="boxCat">
<?php
 //echo '<aside class="asidePortada">';
//echo '<hr>';
//echo '<h6>'.$cnfSubject.'</h6>';
 if($arrForums==""){
//echo $lngNotCat;
}else{
    $arrayOfArrays = array();
    $xx=0;
        foreach(explode(";",$arrForums) 	as $line){
        $item=explode("*",$line);     
			if(isset($item[1])){
            $arrayOfArrays[] = $item;
			}
		$xx++;
		}
if(count($arrayOfArrays)>1)usort($arrayOfArrays ,"cmp");	
	for($x=0;$x<count($arrayOfArrays);$x++){
	echo '<a  title="'.$arrayOfArrays[$x][1].'" href="'.$cnfHome.fCleanChar($arrayOfArrays[$x][0]).'">'.$arrayOfArrays[$x][0].'</a> | ';	
}}
//echo "</aside>";
?>
</div>
</div>
<?php
if($arrLinks!=""){
	echo "<ul>";
    foreach(explode(";",$arrLinks) as $line)
	{		
	$item=explode("*",$line);     
	if(isset($item[1]))
		{
		echo "<li class='liLinks'><a class='linkNav' href='".$item[0]."' title='".$item[0]."'>".$item[1]."</a></li>";
		}
	}echo "</ul>";}
echo '<br style="clear:both;">';
if(file_exists("i.xml")){
$xmlP = new DOMDocument();
$xmlP=simplexml_load_file("i.xml");
$rr=0;
$rrr=0;

if(!isset($_GET['p']))$_GET['p']=0;
if(isset($_GET['p']))if(!is_numeric($_GET['p']))$_GET['p']=0;
if($cnfNewsFeed!=""){
	$next=0;
	if(isset($_GET['p']))$next=$_GET['p']+$cnfNewsFeed;
	$nnext=intval($next)+1;
	$back=0;
	if(isset($_GET['p']))$back=$_GET['p']-$cnfNewsFeed;
	$desde=$_GET['p']+1;
	if($back>=0&&$_GET['p']!=0){echo "<a href='".$cnfHome."?p=".$back."'/><b><<</b> </a>";}
	echo "<span id='pagination'>Mensajes del :[".$desde." al ".$next."]</span>";
	if($nnext>0&&$nnext<=count($xmlP->h)){
	echo "<a href='".$cnfHome."?p=".$next."'/><b>>></b></a>";}
}
$adsx=0;
$class=0;
echo '<br style="clear:both;">';
foreach ($xmlP->h as $programado) {
	if($rr>=$_GET['p']&&$rrr<$cnfNewsFeed){
	$rrr++;
	$posVar=strpos($programado->t,"/");
	$pathForumTotal=substr($programado->t,0,$posVar);
	$pathForum=str_replace("-"," ",$programado->t); 
	if(file_exists(utf8_decode($programado->t).".xml")){
	$xml=simplexml_load_file(utf8_decode($programado->t).".xml");	
	if($class==0){echo '<article class="boxPostPortada" id="0"><header>';$class=1;}
	else {echo '<article class="boxPostPortada2" id="0"><header>';$class=0;}
	echo '<h1 class="h1Left"><a class="aGray" title="'.$lngToMes.'" href="'.str_replace("/","/".$strLink,$programado->t).$strLinkEnd.'"/>'.$xml->p[0]->t.'</a></h1>';
	echo '<p class="pSubLine"><b>'.$xml->p[0]->u.'</b> <time class="entry-date" datetime="'.gmdate("Y-m-d G:i",(int)$xml->p[0]->a).'">'.gmdate("Y-m-d G:i",(int)$xml->p[0]->a).'</time></p>';
	echo '</header>';
	echo '<br style="clear:both;">';
	echo '<section class="sectionPortada">';
	if($cnfNewShort=="")echo '<p>'.$xml->p[0]->b.'</p>';
	else echo '<p>'.substr($xml->p[0]->b,0,intval($cnfNewShort)).'</p><p>...<a href="'.str_replace("/","/".$strLink,$programado->t).$strLinkEnd.'">'.$lngToRead.'</a></p>';
	echo '</section>';
?> 


 <?php
$strTags=$xml->p[0]->g;
if($strTags!="0"&&$strTags!=""){	
	echo '<div class="boxTags" ><a class="portadaLink" href="'.$cnfHome.$pathForumTotal.'"><b>'.$pathForumTotal.':</b></a>';
	$strTags=explode(",",strtolower($strTags));
	for($xf=0;$xf<count($strTags);$xf++){
	if($strTags[$xf]!="")echo '<a  class="portadaLink"  href="'.$cnfHome.$pathForumTotal.'/'.$strLink.$cnfSubject.$strLinkCat.$strTags[$xf].$strLinkEnd.'" title="'.$strTags[$xf].'">'.$strTags[$xf].'</a>'; 
	}
	echo '</div>';
}
$adsx++;
if($cnfAdsense!=""&&$adsx>=intval($cnfAdsIndex)){
$adsx=0;
?>
<br style="clear:both;">
<center>
<?php
if($cnfAdsTitle!="")echo "<br><i>".$cnfAdsTitle."</i><br>";
echo $cnfAdsense;
 ?>
</center>
<?php
}
?>
</article>
<?php
	}
}$rr++;}}else{
//no mensajes
echo '<h6>'.$cnfSubject.'</h6>';
 if($arrForums==""){
echo $lngNotCat;
}else{
    $arrayOfArrays = array();
    $xx=0;
        foreach(explode(";",$arrForums) 	as $line){
        $item=explode("*",$line);     
			if(isset($item[1])){
            $arrayOfArrays[] = $item;
			}
		$xx++;
		}
if(count($arrayOfArrays)>1)usort($arrayOfArrays ,"cmp");	
	for($x=0;$x<count($arrayOfArrays);$x++){
	echo '<a  title="'.$arrayOfArrays[$x][1].'" href="'.$cnfHome.fCleanChar($arrayOfArrays[$x][0]).'"><b><h2>'.$arrayOfArrays[$x][0].'</h2></b></a><br>';	
}}
	
	
}
?> 
<footer>
<?php
if($cnfNumberFeed>0){
echo '<a title="RSS" target="_blank" href="'.$cnfHome.'feed.php"><b>RSS</b></a> '.$cnfFooterText;
}
include('footer.php');
?>
</footer>
<?php
if($cnfGoogleSearch!=""&&$cnfXGoogle==""){  
?>
<script>
  (function() {
    var cx = '<?php echo $cnfGoogleSearch; ?>';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<?php
}
?>
</body>
</html>
<?php
if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx'])){
$cache->CacheEnd();}
?>