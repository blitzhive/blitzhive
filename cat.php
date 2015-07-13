<?php
$blogMode=0;
if(basename(__FILE__)==basename($_SERVER["SCRIPT_FILENAME"])){$blogMode=1;}
$auxBlog="/";
$tr=1;
if($blogMode==0){
include('../config.php');
include('../header.php');
}else{
$auxBlog="";
include('config.php');
include('header.php');	
$tr=0;
}

function fCategorias($arrForums,$cnfHome){
$strAside="";
if($arrForums==""){
$strAside=$lngNotCat."</br>";
}else {
$strAside.='<aside class="asidePortadaIn">';
if(isset($cnfSubject))$strAside.='<h6>'.$cnfSubject.'</h6>';
$strAside.="";
$arrayOfArrays = array();

foreach(explode(";",$arrForums) as $line){
            $item=explode("*",$line);     
			if(isset($item[1])){$arrayOfArrays[] = $item;}}
//usort($arrayOfArrays , function($a, $b) {return $a[2]- $b[2];});	
if(count($arrayOfArrays)>1)usort($arrayOfArrays ,"cmp");	
    for($x=0;$x<count($arrayOfArrays);$x++){
	$strAside.='<a class="portadaLink" title="'.$arrayOfArrays[$x][1].'" href="'.$cnfHome.fCleanChar($arrayOfArrays[$x][0]).'"><b>'.$arrayOfArrays[$x][0].'</b></a><br>';	
	}
$strAside.='</aside>';
return $strAside;
}}

$strLink="";$strLinkEnd="";$strLinkCat="=";$strLinkUser="?user=";

if($cnfPermaLink==0){$strLink="index.php/";$strLinkEnd="/";$strLinkCat="/";$strLinkUser="/";}
else if($cnfPermaLink==1){$strLink="?m=";}
else {$strLink="?";}

$posCache=strrpos($_SERVER['REQUEST_URI'], $strLink);
$posVarCat=strrpos($_SERVER['REQUEST_URI'], "index.php/".$cnfSubject."/");

if($posVarCat!==false){
$strCategoria=substr($_SERVER['REQUEST_URI'],$posVarCat+strlen("index.php/".$cnfSubject."/"));
if(substr($strCategoria,-1)=="/")$strCategoria=substr($strCategoria,0,-1);
$strCategoria= utf8_decode($strCategoria);}


if($blogMode==0){
if(($cnfForumCacheTime!=""&&$cnfForumCacheTime!="0")||($cnfMessageCacheTime!=""&&$cnfMessageCacheTime!="0")||($cnfCatCacheTime!=""&&$cnfCatCacheTime!="0"))include('../cache.php');
}else {
if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx']))include "cache.php";
}	

if($blogMode==0){
if($cnfForumCacheTime!=""&&$cnfForumCacheTime!="0"&&!isset($_SESSION['iduserx'])&&$posVarCat===false)$cache = new SimpleCachePhp(__FILE__,$cnfForumCacheTime);
}else {
if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx']))$cache = new SimpleCachePhp(__FILE__,$cnfHomeCacheTime);
}	


 if($blogMode==0){
if($cnfMessageCacheTime!=""&&$cnfMessageCacheTime!="0"&&!isset($_SESSION['iduserx'])&&$posVarCat===false&&!isset($_SESSION['iduserx'])){
$fakeArry=array(); 
$strMensaje=substr($_SERVER['REQUEST_URI'],$posCache+strlen("index.php/"));
$cache = new SimpleCachePhp(__FILE__,$cnfMessageCacheTime,0,$fakeArry,"cachepost",$strMensaje);
}
}

if(strpos(getcwd(),"/")===false){
$pathForum=str_replace ("-"," ",explode('\\',getcwd())); 
$pathForum=end($pathForum);
$pathForumTotal=explode('\\',  getcwd());
$pathForumTotal=end($pathForumTotal);
}else{
$pathForum=str_replace ("-"," ",explode('/',  getcwd())); 
$pathForum=end($pathForum);
$pathForumTotal=explode('/',  getcwd());
$pathForumTotal=end($pathForumTotal);
}

if($blogMode!=0)$pathForumTotal="";
$forumName="";
$forumDes="";
$forumMod="";
$forumSel=$lngMoveTo.'<select name="selMove" id="selMove">';
     foreach(explode(";",$arrForums) as $line){
            $item=explode("*",$line);     
			if(isset($item[4]))if($item[4]==str_replace(" ","-",$pathForum)){
			$forumName=$item[0];
			$forumDes=$item[1];
			$forumMod=$item[3];
			if(isset($_SESSION['iduserx']))if($forumMod==$_SESSION['iduserx']){
			$_SESSION['mod']=$item[0];}
            }else{
			if($item[0]!=""&&$item[4]!="")$forumSel.='<option value="'.$item[4].'">'.$item[0].'</option>';
			}}
$forumSel.='</select><input type="submit" name="submitMove" id="submitMove" value="Mover"></form>';
$strBoxPost='<div id="divUplaod">';

if(isset($_SESSION['image0'])){
$strBoxPost.='<img src="'.$cnfHome.'upload/'.$_SESSION["image0"].'" class="imgUpload"/> Use <b><input type="button" value="[pic0]" onclick="addtag(\''.$cnfHome.'upload/'.$_SESSION["image0"].'\',1)"/></b> '.$lngIntoMes;}
$strBoxPost.='<input type="file" name="file[]" id="file0"><br>';
if(isset($_SESSION['image1'])){
$strBoxPost.='<img src="'.$cnfHome.'upload/'.$_SESSION["image1"].'" class="imgUpload"/> Use <b><input type="button" value="[pic1]" onclick="addtag(\''.$cnfHome.'upload/'.$_SESSION["image1"].'\',1)"/></b> '.$lngIntoMes;}
$strBoxPost.='<input type="file" name="file[]" id="file1"><br>';
if(isset($_SESSION['image2'])){
$strBoxPost.='<img class="imgUpload" src="'.$cnfHome.'upload/'.$_SESSION["image2"].'" /> Use <b><input type="button" value="[pic2]" onclick="addtag(\''.$cnfHome.'upload/'.$_SESSION["image2"].'\',1)"/></b> '.$lngIntoMes;}

$strBoxPost.='<input style="float:left;" type="file" name="file[]" id="file2">
<input type="submit" name="submitFile" id="submitFile" value="'.$lngUpload.'">
</div>';
$strBoxPost.='
<br style="clear:both;">
<input type="button" value="B" onclick="addtag(\'b\')" style="width:30px; font-weight:bold;" />
<input type="button" value="I" onclick="addtag(\'i\')"  style="width:30px; font-style:italic;" />
<input type="button" value="U" onclick="addtag(\'u\')"  style="width:30px; text-decoration:underline;" />
<input type="button" value="S" onclick="addtag(\'s\')"  style="width:30px; text-decoration:strike;" />
<input type="button" value="<h1>" onclick="addtag(\'h1\')"  style="width:30px; text-decoration:strike;" />
<input type="button" value="<h2>" onclick="addtag(\'h2\')"  style="width:30px; text-decoration:strike;" />
<input type="button" value="<h3>" onclick="addtag(\'h3\')"  style="width:30px; text-decoration:strike;" />
<input type="button" value="'.$lngImage.'" onclick="addtag(\'img\')"  />
<input type="button" value="'.$lngCode.'" onclick="addtag(\'code\')"  />
<input id="linkUrl" style="width:16%;" type="text" value="'.$cnfHome.'" />
<input type="button" value="Link" onclick="addtag(\'a\')"  />
<input id="linkUrlTag" style="width:20%;" type="text" value="'.$cnfHome.$cnfSubject.'/'.'" />
<input type="button" value="Tag" onclick="addtag(\'at\')"  />
<input type="checkbox" name="no" id="no" value="'.$_SESSION['iduserx'].'" checked>'.$lngNotify;
/*?>
<script src="<?php echo $cnfHome."blitzhive.js";?>" type="text/javascript" ></script>
<?php*/

if($cnfPermaLink==0){
$posVar=strrpos($_SERVER['REQUEST_URI'], "index.php/");
$posVarP=strrpos($_SERVER['REQUEST_URI'], "?p=");
$posVarI=strrpos($_SERVER['REQUEST_URI'], "?");

if($posVar===false&&$posVarP===false&&!(isset($_GET[$cnfSubject]))&&$posVarI!==false){
$_SERVER['REQUEST_URI']=str_replace("m=","",$_SERVER['REQUEST_URI']);
$_SERVER['REQUEST_URI']=str_replace("?","index.php/",$_SERVER['REQUEST_URI']);
header( "refresh:0;url=".$_SERVER['REQUEST_URI']);}}
else $posVar=strrpos($_SERVER['REQUEST_URI'], "?");

$rss="";$opcion=0;$w="";

if ($posVar===false&&!isset($_GET[$cnfSubject])) {
/*$subTitle="";
if($blogMode==0)$subTitle=$forumName." | ".$forumDes." | ";*/
if($blogMode==0){
?>
<meta name="Keywords" content="<?php echo $forumName;?>">
<meta name="Description" content="<?php echo $forumDes;?>">
<link rel="canonical" href="<?php echo $cnfHome.$pathForumTotal; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $forumDes;?>" href="<?php echo $cnfHome."feed.php?s=".$pathForumTotal."&t=0";?>" />

<meta itemprop="name" content="<?php echo $forumDes; ?>"/>
<meta itemprop="description" content="<?php echo $forumName.",".$cnfKeywords;?>"/>
<meta itemprop="image" content="<?php echo $cnfHome."".$cnfLogo;?>"/>

<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $cnfTitle;?>" />
<meta property="og:url" content="<?php echo $cnfHome; ?>" />
<meta property="og:site_name" content="<?php echo $cnfMetaDescription; ?>" />
<meta property="og:image" content="<?php echo $cnfHome."".$cnfLogo;?>" />
<meta property="og:locale" content="<?php echo $cnfLanguage;?>" />

<?php if($cnfFbFan!=""){?><meta property="article:publisher" content="https://www.facebook.com/<?php echo $cnfFbFan;?>" /><?php } ?>
<!--<meta property="article:tag" content="facebook" />-->

<meta name="twitter:card" content="summary"/>
<?php if($cnfTwFollow!=""){?>
<meta name="twitter:site" content="<?php echo $cnfTwFollow;?>"/>
<meta name="twitter:creator" content="<?php echo $cnfTwFollow;?>"/>
<?php } ?>
<meta name="twitter:domain" content="<?php echo $cnfHome; ?>">
<meta name="twitter:description" content="<?php echo $forumDes; ?>"/>
<meta name="twitter:title" content="<?php echo $forumName?>"/>
<meta name="twitter:image:src" content="<?php echo $cnfHome."".$cnfLogo;?>"/>
<title><?php echo $forumName." | ".$forumDes." | ".$cnfTitle;?></title>
<?php
}else{
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

<?php if($cnfFbFan!=""){?><meta property="article:publisher" content="https://www.facebook.com/<?php echo $cnfFbFan;?>" /><?php } ?>

<meta name="twitter:card" content="summary"/>
<?php if($cnfTwFollow!=""){?>
<meta name="twitter:site" content="<?php echo $cnfTwFollow;?>"/>
<meta name="twitter:creator" content="<?php echo $cnfTwFollow;?>"/>
<?php } ?>
<meta name="twitter:domain" content="<?php echo $cnfHome; ?>">
<meta name="twitter:description" content="<?php echo $cnfMetaDescription; ?>"/>
<meta name="twitter:title" content="<?php echo $cnfHeaderText." | ".$cnfTitle;?>"/>
<meta name="twitter:image:src" content="<?php echo $cnfHome."".$cnfLogo;?>"/>

<title><?php echo $cnfHeaderText." | ".$cnfTitle;?></title>
<?php		
}
/***----------------TAGS----------------------***/
/***----------------TAGS----------------------***/
}else if(isset($_GET[$cnfSubject])&&$_GET[$cnfSubject]!=""&&$cnfPermaLink!=0){
$opcion=1;
?>
<title><?php echo $_GET[$cnfSubject]." | ".$cnfTitle;?></title>
<?php
}else if($posVarCat!==false&&$cnfPermaLink==0){
$opcion=1;
$subforumNameUrl="";
$subforumNameTitle="";
if($blogMode==0){
	$subforumNameUrl=$pathForumTotal."/";
	$subforumNameTitle=" | ".$forumName;
}
?>
<meta name="Keywords" content="<?php echo urldecode($strCategoria);?>">
<meta name="Description" content="<?php echo $lngResultsFor.": ".urldecode($strCategoria).", ".$cnfMetaDescription;?>">
<link rel="canonical" href="<?php echo $cnfHome.$subforumNameUrl."index.php/".$cnfSubject."/".$strCategoria."/"; ?>" />

<meta itemprop="name" content="<?php echo $lngResultsFor.": ".urldecode($strCategoria);?>"/>
<meta itemprop="description" content="<?php echo urldecode($strCategoria).",".$cnfKeywords;?>"/>
<meta itemprop="image" content="<?php echo $cnfHome."".$cnfLogo;?>"/>

<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo urldecode($strCategoria).$subforumNameTitle." | ".$cnfTitle;?>" />
<meta property="og:url" content="<?php echo $cnfHome.$subforumNameUrl."index.php/".$cnfSubject."/".$strCategoria."/"; ?>" />
<meta property="og:site_name" content="<?php echo $lngResultsFor.": ".urldecode($strCategoria); ?>" />
<meta property="og:image" content="<?php echo $cnfHome."".$cnfLogo;?>" />
<meta property="og:locale" content="<?php echo $cnfLanguage;?>" />

<?php if($cnfFbFan!=""){?><meta property="article:publisher" content="https://www.facebook.com/<?php echo $cnfFbFan;?>" /><?php } ?>

<meta name="twitter:card" content="summary"/>
<?php if($cnfTwFollow!=""){?>
<meta name="twitter:site" content="<?php echo $cnfTwFollow;?>"/>
<meta name="twitter:creator" content="<?php echo $cnfTwFollow;?>"/>
<?php } ?>
<meta name="twitter:domain" content="<?php echo $cnfHome; ?>">
<meta name="twitter:description" content="<?php echo $lngResultsFor.": ".urldecode($strCategoria); ?>"/>
<meta name="twitter:title" content="<?php echo urldecode($strCategoria).$subforumNameTitle." | ".$cnfTitle;?>"/>
<meta name="twitter:image:src" content="<?php echo $cnfHome."".$cnfLogo;?>"/>
<?php
echo "<title>".urldecode($strCategoria).$subforumNameTitle." | ".$cnfTitle."</title>";
}else{
$opcion=2;
if($cnfPermaLink==0){
$w=substr($_SERVER['REQUEST_URI'],$posVar+10);
if(strpos($w, "/")!==false)$w=substr($w,0,strpos($w, "/"));
if(substr($w,-1)=="/")$w=substr($w,0,-1);
}
else if($cnfPermaLink==1){
$w=substr($_SERVER['REQUEST_URI'],$posVar+1);
}else{$w=$_GET['m'];}

$w=urldecode($w);
$w=filter_var($w, FILTER_SANITIZE_SPECIAL_CHARS);
$titleFile=str_replace ("-"," ",$w);
?>
<meta name="Keywords" content="<?php echo str_replace(" ", ",", $titleFile);?>">
<meta name="Description" content="<?php echo $titleFile;?>">
<link rel="canonical" href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $forumDes;?>" href="<?php echo $cnfHome."feed.php?s=".$pathForumTotal."/".$strLink.$w.$strLinkEnd."&t=1";?>" />

<meta itemprop="name" content="<?php echo $titleFile; ?>"/>
<meta itemprop="description" content="<?php echo $forumName.",".str_replace(" ", ",", $titleFile);?>"/>
<!--<meta itemprop="image" content="<?php echo $cnfHome."".$cnfLogo;?>"/>-->

<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $titleFile;?>" />
<meta property="og:url" content="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
<meta property="og:site_name" content="<?php echo str_replace(" ", ",", $titleFile); ?>" />
<!--<meta property="og:image" content="<?php echo $cnfHome."".$cnfLogo;?>" />-->
<meta property="og:locale" content="<?php echo $cnfLanguage;?>" />

<?php if($cnfFbFan!=""){?><meta property="article:publisher" content="https://www.facebook.com/<?php echo $cnfFbFan;?>" /><?php } ?>

<meta name="twitter:card" content="summary"/>
<?php if($cnfTwFollow!=""){?>
<meta name="twitter:site" content="<?php echo $cnfTwFollow;?>"/>
<meta name="twitter:creator" content="<?php echo $cnfTwFollow;?>"/>
<?php } ?>
<meta name="twitter:domain" content="<?php echo $cnfHome; ?>">
<meta name="twitter:description" content="<?php echo str_replace(" ", ",", $titleFile); ?>"/>
<meta name="twitter:title" content="<?php echo $titleFile?>"/>
<!--<meta name="twitter:image:src" content="<?php echo $cnfHome."".$cnfLogo;?>"/>-->
<?php
echo "<title>".$titleFile." | ".$forumName." | ".$cnfTitle."</title>";
}
?>
</head>
<body>
<div class="box1">
<a href="<?php echo $cnfHome; ?>" alt="<?php echo $lngBackIndex;?>" title="<?php echo $lngBackIndex;?>" >
<img class="logo" title="<?php echo $cnfHeaderText;?>"  src="<?php echo $cnfHome.$cnfLogo;?>"  /></a>
<div class="boxAlignVertical">
<!--<span class='h1Vertical'><?php echo $cnfHeaderText;?></h2>-->
<?php
//session_start();
if(isset($_SESSION['iduserx'])){
echo "<h4 class='h4hello'>".$lngHi." <a title='".$lngSeeProfile."' href='".$cnfHome."user.php".$strLinkUser.$_SESSION['iduserx'].$strLinkUser."'>".$_SESSION['iduserx']."</a></h4><a class='aLogin' href='".$cnfHome."logout.php?r=index.php'>¿Salir?</a>";
if($_SESSION['iduserx']==$cnfAdm){
echo "<a class='aLogin' href='".$cnfHome."admin.php'>".$lngAdm."</a>";	
}}else{
echo "<a class='aLogin' href='".$cnfHome."login.php?r=".$_SERVER["REQUEST_URI"]."'>".$lngEnter."&nbsp;|&nbsp; </a>";
echo "<a class='aLogin' href='".$cnfHome."register.php?r=".$_SERVER["REQUEST_URI"]."'>".$lngReg."</a>";
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
if($cnfCatCacheTime!=""&&$cnfCatCacheTime!="0"&&$posVarCat!==false){
$fakeArry=array(); 
$cache = new SimpleCachePhp(__FILE__,$cnfCatCacheTime,0,$fakeArry,"cachecat",$strCategoria);
}
if($arrLinks!=""){
echo "<ul>\r\n";
    foreach(explode(";",$arrLinks) as $line){		
			$item=explode("*",$line);     
	if(isset($item[1])){
echo "<li class='liLinks'><a class='linkNav' href='".$item[0]."' title='".$item[0]."'>".$item[1]."</a></li>\r\n";
		}}}
echo "</ul>\r\n";
?>
<div style="clear:both;"></div>
<?php
 if ($opcion==0) {
 $title="";
 $body="";
 $rss='<a title="RSS" target="_blank" href="'.$cnfHome.'feed.php?s='.$pathForumTotal.'&t=0"><b>RSS</b> </a>';
 if(isset($_SESSION['title']))$title=$_SESSION['title'];
 if(isset($_SESSION['body']))$body=preg_replace('#<br\s*?/?>#i', "",$_SESSION['body']);  
 
 if($blogMode==0){	 
 ?>
<nav>
<a href="../index.php">Inicio</a> >> 
<?php echo $forumName.": <i>".$forumDes."</i>";?>

</nav>
<?php
 }
$files = array();$strFilesT="";$xx=0;
if(file_exists("t.xml")){
	$xmlT = new DOMDocument();
	$xmlT->load("t.xml");
	foreach ($xmlT->getElementsByTagName('t') as $element){
	$files[$xx][0]=utf8_decode($element->textContent.".xml");
	$files[$xx][1]=time();
	$files[$xx][2]=filemtime(utf8_decode($element->textContent.".xml"));
	$strFilesT.=$element->textContent.".xml;";
	$xx++;
}}

if ($handle = opendir('.')) {
if(!isset($_GET['p']))$_GET['p']=0;
if(isset($_GET['p']))if(!is_numeric($_GET['p']))$_GET['p']=0;
$next=0;
if(isset($_GET['p']))$next=$_GET['p']+$cnfNumberPage;
$nnext=intval($next)+1;
$back=0;
if(isset($_GET['p']))$back=$_GET['p']-$cnfNumberPage;

while (false !== ($file = readdir($handle))) {
       if ($file != "cache" && $file != "." && $file != ".."
	   && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml'
	   &&strlen($file)>6
	   &&strpos($strFilesT,utf8_encode($file).";",0)===false
	   ) {	  
		  $posVarTag=strrpos($file, "_T_");
		  if($posVarTag===false){
		  $files[$xx][0] = $file;
		  $files[$xx][1] = filemtime($file);
		  $files[$xx][2] =0;	
				}
	 $xx++;
	   }
   }
   closedir($handle);   
   
if($xx<=$cnfNumberPage||$_GET['p']+$cnfNumberPage>$xx){
$nnext=0;   
$next=$xx;
}
   
if(count($files)>0){	   
function filetime_callback($a, $b)
{ if ($a[1] === $b[1]) return 0;
  return $a[1] < $b[1] ? -1 : 1; 
}
usort($files, "filetime_callback");
$files=array_reverse($files);

$desde=$_GET['p']+1;
if($back>=0&&$_GET['p']!=0){
echo "<a href='".$cnfHome.$pathForumTotal."/?p=".$back."'/><b><<</b> </a>";
}
echo "Mensajes del :[".$desde." al ".$next."]";
if($nnext>0){
echo "<a href='".$cnfHome.$pathForumTotal."/?p=".$next."'/><b>>></b></a>";}
$xx=1;
$alg=0;
?>
<div style="clear:both;"></div>
<div class="boxBody">
<?php 
if($blogMode==0){
echo '<div class="box2">';}else{
echo '<div class="boxBlog">';}
?>
<?php
$adsx=0;
foreach($files as $file) {
	
if($xx>$_GET['p']&&$xx<=$next){
$fileName=basename($file[0], ".xml").PHP_EOL;
$strPermalink="";

if($cnfPermaLink==0)$strPermalink=$cnfHome.$pathForumTotal.$auxBlog."index.php/".utf8_encode($fileName)."/";
else if($cnfPermaLink==2)$strPermalink=$cnfHome.$pathForumTotal.$auxBlog."?m=".utf8_encode($fileName);
if((int)$file[2]==0){
 echo "<div class='boxForum2in'><h5 class='h5Left'><a href='".$strPermalink."'>".str_replace("-"," ",utf8_encode($fileName))."</a></h5>&nbsp;&nbsp;".$lngModified.":&nbsp;&nbsp;<time class='entry-date' datetime='".gmdate("D, d M Y H:i:s O",(int)$file[1])."'>".gmdate("<b>d-M-Y</b> G:i",(int)$file[1])."</time></div>";
 $alg=1;
 }else{
 echo "<div class='boxForumPin'><h5 class='h5Left'><a href='".$strPermalink."'>".str_replace("-"," ",utf8_encode($fileName))."</a></h5>&nbsp;&nbsp;".$lngLastAnwser.":&nbsp;&nbsp;<time class='entry-date' datetime='".gmdate("D, d M Y H:i:s O",(int)$file[2])."'>".gmdate("<b>d-M-Y</b> G:i",(int)$file[2])."</time></div>";
  $alg=1;}

$adsx++;
//$adsTotal++;
if($cnfAdsense!=""&&$adsx>=intval($cnfAdsCat)){
$adsx=0;
?>
<center>

<?php if($cnfAdsTitle!="")echo "<br><i>".$cnfAdsTitle."</i><br>";?>
<?php echo $cnfAdsense; ?>
</center>
<?php

}

}
$xx++;

 }
if($alg==0)header( "refresh:0;url=".$cnfHome.$pathForumTotal);
}else{echo "<h4 class='h4Gray'>".$lngNotMes."</h4>";}
?>
</div><!--box2-->
<?php
//if($blogMode==0)echo fCategorias($arrForums,$cnfHome);
if(isset($_SESSION['iduserx'])){	
?>
<div style="clear:both;"></div>
<h6 style="text-align:center;">Comenzar un nuevo tema</h6>
<form class="formBlitz" method="post" action="<?php echo $cnfHome."w.php?q=0&t=".$pathForumTotal;?>" enctype="multipart/form-data">

<?php
if($_SESSION['iduserx']==$cnfAdm||
$_SESSION['iduserx']==$forumMod
){

echo'<input type="checkbox" name="d" id="d" value="1">'.$lngBlock.'<input type="checkbox" name="f" id="f" value="1">'.$lngPin;
if($_SESSION['iduserx']==$cnfAdm){
if($blogMode==0)echo'<input type="checkbox" name="po" id="po" value="1">'.$lngIndex;
echo ' | '.$lngIn.' <input type="text" style="width:20px" name="ti" id="ti" value="0"> '.$lngHours;
}}
?>
<input type="text" name="w" id="w"   onKeyUp='feChangeW()' placeholder="Title" autocomplete="off" value="<?php echo $title;?>">
<br style="clear:both;">
<?php
echo $strBoxPost;
?>
<div id="dvCont">0</div>
<textarea id="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()'  name="txtE"><?php echo $body;?></textarea>
 <!--<iframe style="width:100px;">
       <body style="width:100px;" contenteditable="true">holaholahola</body>
</iframe>-->
<!--<IFRAME style="OVERFLOW: auto; WIDTH: 100%; HEIGHT: 200px; 
BACKGROUND-COLOR: white" onKeyUp='feChange()' onMouseUp='feSel()'
 id="txtContent" name="txtContent" src="<?php echo $cnfHome."/"?>content.htm"</IFRAME>-->
<br>
<input type="text" name="u" id="u" placeholder="tag1,tag2" autocomplete="off" style="width:73%;" >
<br style="clear:both;">
<input type="submit" name="submit" id="submit" value="<?php echo $lngCreate;?>"/>
<br><?php echo $lngPreView;?>
<div id="e" name="e" contenteditable="true" onkeyup="feChange2()" ></div>
</form>
 <?php
}
}
/*********************************************/
/*****************T A G ************************/
/*****************T A G ************************/
}else if($opcion==1){
if($posVarCat!==false){
}else{$strCategoria=$_GET[$cnfSubject];}

$strCategoria=filter_var($strCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$strCategoria=urldecode($strCategoria);
$notag=0;
$strEnlaces="";
 if ($handle = opendir('.')) {   
$xtag=0; 
if(!isset($strFilesT))$strFilesT="";
    while (false !== ($file = readdir($handle))) {
		
		//$file=utf8_encode($file);
		//echo $strCategoria.",".utf8_encode($file)."<br>";

$fechaRss=gmdate("D, d M Y H:i:s O",(int)filectime($file));
if(filemtime($file)!==FALSE)$fechaRss=gmdate("D, d M Y H:i:s O",(int)filemtime($file));

$fecha=gmdate("<b>d-M-Y</b> G:i",(int)filectime($file));
if(filemtime($file)!==FALSE)$fecha=gmdate("<b>d-M-Y</b> G:i",(int)filemtime($file));	
	
$posVarTag=strrpos($file, "_T_");

if ($file != "cache" && $file != "." && $file != ".."
	   && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml'
	   &&strlen($file)>6
	   &&strpos($strFilesT,utf8_encode($file).";",0)===false
	   &&$posVarTag===false
	   ) 
{

if($cnfUltraSearch=='checked'){
$xml=simplexml_load_file($file);
if(strpos(strtolower($xml->p[0]->b),$strCategoria,0)!==false
||strpos(strtolower($xml->p[0]->t),$strCategoria,0)!==false
||strpos(strtolower($xml->p[0]->g),$strCategoria,0)!==false
||strpos(strtolower($file),$strCategoria,0)!==false
)
{
$notag=1;		
$file=basename($file, ".xml").PHP_EOL;
$strEnlaces.="<div class='boxTag'><h5 class='h5Left'><a class='aFloatMessage' href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.utf8_encode(basename($file, ".xml").PHP_EOL).$strLinkEnd."'>".str_replace("-"," ",utf8_encode(basename($file, ".xml").PHP_EOL))."</a></h5>&nbsp;&nbsp;Última respuesta:&nbsp;&nbsp;<time class='entry-date' datetime='".$fechaRss."'>".$fecha."</time></div>";
}
}else if(strpos(strtolower(utf8_encode($file)),$strCategoria,0)!==false){
	//echo "asd";
$notag=1;		
$strEnlaces.="<div class='boxTag'><h5 class='h5Left'><a class='aFloatMessage' href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.utf8_encode(basename($file, ".xml").PHP_EOL).$strLinkEnd."'>".str_replace("-"," ",utf8_encode(basename($file, ".xml").PHP_EOL))."</a></h5>&nbsp;&nbsp;Última respuesta:&nbsp;&nbsp;<time class='entry-date' datetime='".$fechaRss."'>".$fecha."</time></div>";
$xtag++;
if($cnfAdsense!=""&&$xtag>=intval($cnfAdsTag)){
$xtag=0;
$strEnlaces.="<center>";
if($cnfAdsTitle!="")$strEnlaces.="<br><i>".$cnfAdsTitle."</i><br>";
$strEnlaces.=$cnfAdsense;
$strEnlaces.="</center>";
}
 }

 //
 }}}
if($notag==0){
$_SESSION['error']=$strCategoria;
die(include("../404.php")); 
}else{
echo '<h2 class="h1GrayCenter">'.$lngResultsFor.' "'.$strCategoria.'":</h2>';
if($blogMode==0){
echo '<div class="box2">'.$strEnlaces.'</div>';
//echo fCategorias($arrForums,$cnfHome);
}else{echo '<div class="boxBlog">'.$strEnlaces.'</div>';}}
}else{	
$rss='<a title="RSS" target="_blank" href="'.$cnfHome.'feed.php?s='.$pathForumTotal."/".$strLink.$w.$strLinkEnd.'&t=1" ><b>RSS</b> </a>';
$w=utf8_decode($w);
if(!file_exists($w.".xml")){
$_SESSION['error']=$w;
die(include("../404.php")); 
}
$xml=simplexml_load_file($w.".xml");
$t=time();
$interval=$t-$xml->p[0]->a;
$interMinuts=$interval/60;
if($interMinuts<15&&$xml->p[0]->u==$_SESSION['iduserx']){$last15=1;
$_SESSION['allowdelete']=1;
}
?>
<nav>
<a href="<?php echo $cnfHome;?>">HOME</a> >>
<?php if($blogMode==0){?>
<a href="<?php echo $cnfHome.$pathForumTotal; ?>"><?php echo $forumName; ?></a> >
<?php }?>
<?php echo $titleFile;?>
</nav>
<?php
$xr=0;
if($cnfPermaLink==0){
$posVarP=strrpos($_SERVER['REQUEST_URI'], "/p/");
$posVarPP=strrpos($_SERVER['REQUEST_URI'],"/",$posVarP+strlen("/p/"));
$lenPP=$posVarPP-($posVarP+strlen("/p/"));
if($posVarP!==false)$_GET['p']=substr($_SERVER['REQUEST_URI'],$posVarP+strlen("/p/"),$lenPP);
}
if(!isset($_GET['p']))$_GET['p']=0;
if(isset($_GET['p']))if(!is_numeric($_GET['p']))$_GET['p']=0;
$next=intval($_GET['p'])+intval($cnfNumberPage);
$nnext=intval($next)+1;
$desde=$_GET['p']+1;
$back=$_GET['p']-$cnfNumberPage;
if($back>=0){
if($cnfPermaLink==0)echo "<a href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.$w."/p/".$back."/'/><b><<</b></a>";
else echo "<a href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.$w."&p=".$back."'/><b><<</b> </a>";
}
$hasta=count($xml->p->children());
if($hasta>$next)$hasta=$next;
echo "Respuestas :[".$desde." al ".$hasta."]";
echo '<br style="clear:both;">';
if($nnext>0&&isset($xml->p[$next])){
if($cnfPermaLink==0)echo "<a href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.$w."/p/".$next."/'/><b>>></b></a>";
else echo "<a href='".$cnfHome.$pathForumTotal.$auxBlog.$strLink.$w."/&p=".$next."'/><b>>></b></a>";}

$xr=0;$alg=0;$author=0;$adsx=0;
foreach ($xml->p as $repuesta) {
if($xr==0&&$repuesta->u==$_SESSION['iduserx']){
$author=1;	
	
}	
if($xr>=$_GET['p']&&$xr<$next){
$alg=1;
$subStyle='';
$subStyle='style="background-color:#FFF;"'; 
$boxPostPortada='boxPostPortadaR';
if($xr%2!=0){
$subStyle='style="background-color:#F5F5F5;"'; 
}
if($xr!=0&&$xr==$xml->p[0]->f)$subStyle='style="background-color:#D6FFAE;"'; 
if($xr==0)$boxPostPortada='boxPostPortada';
echo "\r\n";
?>
<article class="<?php echo $boxPostPortada;?>" <?php echo $subStyle; ?>  id="<?php echo $xr; ?>">
  <header>
	<?php if($xr==0)echo "<h1 id='h2Title' class='h2Gray'>".$repuesta->t."</h1>\r\n";
	 if($xr!=0&&$xr==$xml->p[0]->f)echo " <h5>✔<i>".$lngFavorite."</i></h5>\r\n";
	?>
	<p class="pSubLine"><?php echo "<a href='".$cnfHome."user.php".$strLinkUser.$repuesta->u.$strLinkEnd."'  title='Ver perfil de ".$repuesta->u."'>".$repuesta->u."</a> ";?>
	<time class="entry-date" datetime="<?php echo gmdate("D, d M Y H:i:s O",(int)$repuesta->a)?>"><?php echo gmdate("<b>d-M-Y</b> G:i",(int)$repuesta->a)?></time></p>
  </header>
  <br style="clear:both;">
  <section class="sectionPortada" id="<?php echo "sec".$xr; ?>" ><?php 
  if($xr!=0&&$repuesta->m==0)echo $repuesta->b;
  else if($xr!=0&&$repuesta->u==$_SESSION['iduserx'])echo "<i><b>Hola ".$_SESSION['iduserx']." ".$lngModYour.".</b></i>";
  else if($xr!=0&&$_SESSION['iduserx']!=$cnfAdm&&$_SESSION['iduserx']!=$forumMod)echo "<i>".$lngNotMod.".</i>";
  else echo $repuesta->b;
  ?>

  </section>
  
  <div id="divPost"> 
   <?php
if($xr==0&&$xml->p[0]->f!=0){
	
echo "<a href='#".$xml->p[0]->f."'>✔ ".$lngFavOne."</a>";
}
   
if($xr!=0&&$author==1&&$xr!=$xml->p[0]->f&&$_SESSION['iduserx']!=$xml->p[0]->u){
echo '<form  method="post" action="'.$cnfHome."w.php?q=7&w=".$pathForumTotal.'/'.utf8_encode($w).'&t='.$xr.'&y='.$repuesta->u.'"> 
<input class="favButton" type="submit" id="likeButton" name="likeButton" value="Puedes elegirla como la mejor respuesta"></form>';
}
if($cnfVoteLevel=="")$cnfVoteLevel=0;
//echo $cnfVoteLevel;
 if($repuesta->v!=""
 ){
$intVotes=count(explode(",", $repuesta->v))-1;
if($repuesta->v!="")$strUsersVoted.="(".substr($repuesta->v,1).")";
echo "<span id='lbVotes'>".$lngLikeTo.":</span><div id='votes'><b>".$intVotes."</b>".$strUsersVoted."</div>";

 }else if(isset($_SESSION['iduserx'])){
 if($_SESSION['iduserx']!=$repuesta->u&&intval($_SESSION['level'])>=intval($cnfVoteLevel)){
echo "<span  id='lbVotes'>".$lngFirstLike."</span>";  
 }
 }
$strButtonVote="";
 if(isset($_SESSION['iduserx'])
 &&
 $_SESSION['iduserx']!=$repuesta->u
 &&
 strpos($repuesta->v,','.$_SESSION['iduserx'],0)===false
 &&
 intval($_SESSION['level'])>=intval($cnfVoteLevel)
 ){?>
 <form  method="post" action="<?php echo  $cnfHome."w.php?q=3&w=".$pathForumTotal.'/'.utf8_encode($w);?>&t=<?php echo $xr;?>&y=<?php echo $repuesta->u;?> ">
 <?php 
 $strButtonVote='  <input class="likeButton" type="submit" id="likeButton" name="likeButton" value="+"></form>'; 
 }else{
 if(isset($_SESSION['level']))if(intval($_SESSION['level'])<intval($cnfVoteLevel))$strButtonVote='<span  id="lbVotes"><i>'.$lngAllowLevel.' <a href="'.$cnfHome.'swarm.php" title="'.$lngMoreInfoLevel.'">('.$_SESSION['level'].'>='.$cnfVoteLevel.')</a> '.$lngToVote.'</i></span><br style="clear:both;">';
 else echo '<br style="clear:both;">';
 }
 echo $strButtonVote;
if($xr==0&&($cnfFacebook=="checked"||$cnfTwitter=="checked"||$cnfGooglePlus=="checked"
||$cnfPinterest=="checked"||$cnfLinkedin=="checked")){
if($cnfHashtags!="checked"){
$hashtags="";
$datahashtags="";
}
?>
<span id="lbVotes"><?php echo $lngShareIs;?> :)</span>
 <?php 
$sxr="";
if($xr!=0)$sxr="#".$xr;
if($cnfFacebook=="checked"){?>
<div class="fb-like"  data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".$sxr;?>"></div>
<?php }
if($cnfTwitter=="checked"){
?>
<span> </span><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".$sxr;?>" data-via="<?php echo $cnfTwFollow;?>"
<?php echo 'data-hashtags="'.$datahashtags.'"'; ?>
>Tweet</a>
<?php }
if($cnfGooglePlus=="checked"){
?>
<div class="g-plusone" data-href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".$sxr;?>" data-annotation="inline"  data-width="120"></div>
<?php }
if($cnfPinterest=="checked"){
?>
<a href="//es.pinterest.com/pin/create/button/?url=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".$sxr;?>&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
<?php }
if($cnfLinkedin=="checked"){
?>
<script type="IN/Share" data-url="http://google.com" data-counter="right"></script>
<?php
}
?>
 <?php 
 }
 ?>

</div>
<br style="clear:both;">
<div class="boxTags" >
<?php
  $strTags=$repuesta->g;
  $hashtags="";
  $datahashtags="";
  $footerTag="";
$strTags=$xml->p[0]->g;
 if($xr==0&&$strTags!="0"&&$strTags!=""){
//echo '<h6>'.$cnfSubject.'</h6>';
if($blogMode==0)echo '<a class="portadaLink" href="'.$cnfHome.$pathForumTotal.'"><b>'.$pathForumTotal.':</b></a>';

 $strTags=explode(",",strtolower($strTags));
 for($xf=0;$xf<count($strTags);$xf++){	 
 if($strTags[$xf]!=""){
	 echo '<a  class="portadaLink"  href="'.$cnfHome.$pathForumTotal.$auxBlog.$strLink.$cnfSubject.$strLinkCat.$strTags[$xf].$strLinkEnd.'" title="'.$strTags[$xf].'">'.$strTags[$xf].'</a>';
 $datahashtags.="".$strTags[$xf]." #";
 }
	}
 if(substr($datahashtags,-1)=="#")$datahashtags=substr($datahashtags,0,-1);
 
 } 
echo "<span>".$footerTag."</span><br>"; 
?>
</div>
</article>
<br style="clear:both;">
<?php
$adsx++;	
if($cnfAdsense!=""&&$adsx>=intval($cnfAdsMes)){
$adsx=0;
?>
<center>
<?php if($cnfAdsTitle!="")echo "<br><i>".$cnfAdsTitle."</i><br>";?>
<?php echo $cnfAdsense; ?>
</center>
<?php
}

?>
<hr class="hrMin" />
<?php
$strReMe="el mensaje";
if($xr!=0)$strReMe="la respuesta";
if(isset($last15)&&$last15==1&&$_SESSION['iduserx']!=$cnfAdm&&$_SESSION['iduserx']!=$forumMod){
$howMuch=15-$interMinuts;
echo '<a id="cf'.$xr.'" title="Eliminar '.$strReMe.'" OnMouseUP="fConfirmDelete(this.id,'.$xr.',\''.$cnfHome.'w.php?q=4&t='.$xr.'&w='.$pathForumTotal.$auxBlog.utf8_encode($w).'\')"  href="#">Tienes '.round($howMuch).' minutos si deseas eliminar '.$strReMe.'</a>';
echo '<div style="float:left;" id="dc'.$xr.'" ></div>';
}
else if(isset($_SESSION['iduserx'])){
	if($_SESSION['iduserx']==$cnfAdm||$_SESSION['iduserx']==$forumMod){
echo '<div name="admTool" id="admTool">';
if($xr==0){echo '<form class="formLite" name="formMove" method="post" action="'.$cnfHome.'w.php?q=6&y='.$repuesta->i.'&w='.$pathForumTotal.$auxBlog.utf8_encode($w).'" enctype="multipart/form-data">'.$forumSel;}

$pin=0;
if(isset($repuesta->i)&&$repuesta->i==1)$pin=1;
$block=0;
if(isset($repuesta->l)&&$repuesta->l==1)$block=1;
$_SESSION['allowdelete']=1;
echo '<a class="aTool" id="cf'.$xr.'" title="Eliminar '.$strReMe.'" OnMouseUP="fConfirmDelete(this.id,'.$xr.',\''.$cnfHome.'w.php?q=4&t='.$xr.'&w='.$pathForumTotal.$auxBlog.utf8_encode($w).'&y='.$pin.'\')"  >Eliminar '.$strReMe.'</a>';
echo '<div style="float:left;" id="dc'.$xr.'" ></div>';

echo '<a class="aTool" id="ed'.$xr.'" title="Editar '.$strReMe.'" OnMouseUP="fEdit('.$pin.','.$block.',\''.$repuesta->g.'\',this.id,'.$xr.',\''.$cnfHome.'w.php?q=5&t='.$xr.'&w='.$pathForumTotal.$auxBlog.utf8_encode($w).'\')"  > Editar '.$strReMe.'</a> ';
echo '</div>';
}
}
}//if en page
$xr++;
}

if($alg==0)header( "refresh:0;url=".$cnfHome.$pathForumTotal."/?".$w);
if(isset($_SESSION['iduserx'])&&$repuesta->l!="1"
||($_SESSION['iduserx']==$cnfAdm)
||($_SESSION['iduserx']==$forumMod)
){
?>
<form class="formBlitz2" name="answer" id="answer" method="post" action="<?php echo  $cnfHome."w.php?q=2&t=".$xr."&w=".$pathForumTotal.$auxBlog.utf8_encode($w);?>" enctype="multipart/form-data">
<?php
echo $strBoxPost;
?>
<br />
<?php
if($_SESSION['iduserx']==$cnfAdm
||
$_SESSION['iduserx']==$forumMod
){
echo '<div id="blopin" style="display:none"><input type="checkbox" name="d" id="d" value="1" >Bloqueado<input type="checkbox" name="f" id="f" value="1" >'.$lngPin.'</div>';
if($_SESSION['iduserx']==$cnfAdm){
if($blogMode==0)echo '<input type="checkbox" name="po" id="po" value="1">'.$lngIndex;
}
}
?>
<input type="text" name="w" id="w" placeholder="Title" autocomplete="off" style="width:73%;display:none">
<div id="dvCont">0</div>
<textarea id="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()'  name="txtE"><?php  if(isset($_SESSION['answer']))echo $_SESSION['answer'];?></textarea>
<br>
<input type="text" name="u" id="u" placeholder="tag1,tag2" autocomplete="off" style="width:73%;display:none" >
<input type="submit" name="submit" id="submit" value="Responder"/>	
<br><?php echo $lngPreView;?>
<div id="e" name="e"><?php if(isset($_SESSION['answer']))echo $_SESSION['answer'];?></div>
<br style="clear:both;">	
</form>
<?php
}else{
}
?>
<p><time pubdate datetime="<?php  echo gmdate("D, d M Y H:i:s O",(int)$xml->p[$xr-1]->a);?>"></time></p>
<?php
}//End load File
?>
<?php
//echo "mammio".$cnfFacebook;
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
<?php 

if($cnfFacebook=="checked"){	

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php }
if($cnfTwitter=="checked"){?>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<?php
} 
if($cnfGooglePlus=="checked"){
?>
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
}
if($cnfPinterest=="checked"){
?>
<!-- Please call pinit.js only once per page -->
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
<?php
}
if($cnfLinkedin=="checked"){
?>
<script src="//platform.linkedin.com/in.js" type="text/javascript">
  lang: en_US
</script>
<?php
}
?>

<footer>
<?php
echo $rss.' '.$cnfFooterText;
?>
<?php
if(isset($repuesta)){
if(!isset($_SESSION['iduserx'])&&$repuesta->l!="1"){
echo "<a href='".$cnfHome."login.php?r=".$_SERVER["REQUEST_URI"]."'>".$lngConToWri."</a><br>";
}else if($repuesta->l=="1"){
echo $lngNotAllAns;
}
}
include('../footer.php');
?>
</footer>
</body>
</html>
<?php


if($blogMode==0){
if(($cnfCatCacheTime!=""&&$cnfCatCacheTime!="0")
||
((($cnfForumCacheTime!=""&&$cnfForumCacheTime!="0")
||($cnfMessageCacheTime!=""&&$cnfMessageCacheTime!="0"))
&&!isset($_SESSION['iduserx']))
){
$cache->CacheEnd();
}
}else{
if($cnfHomeCacheTime!=""&&$cnfHomeCacheTime!="0"&&!isset($_SESSION['iduserx']))$cache->CacheEnd();
}
?>