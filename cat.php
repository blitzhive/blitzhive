<?php
if (!isset($_SESSION)) { session_start(); }
$_SESSION['return']=$_SERVER["REQUEST_URI"];
$blogMode = 0;
$cnfBlogFolder="";
$cnfLanguageFb= str_replace("-","_",$cnfLanguage);
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]))
  {
    $blogMode = 1;
	$cnfBlogFolder=$cnfBlogFolder."/";
  }
  
$auxBlog = "/";
$tr      = 1;
if ($blogMode == 0)
  {
    include('../config.php');
    include('../header.php');
  }
else
  {
    $auxBlog = "";
    include('config.php');
    include('header.php');
    $tr = 0;
  }
function fCategorias($arrForums, $cnfHome)
  {
    $strAside = "";
    if ($arrForums == "")
      {
        $strAside = $lngNotCat . "</br>";
      }
    else
      {
        $strAside .= '<aside class="asidePortadaIn">';
        if (isset($cnfSubject))
            $strAside .= '<h6>' . $cnfSubject . '</h6>';
        $strAside .= "";
        $arrayOfArrays = array();
        foreach (explode(";", $arrForums) as $line)
          {
            $item = explode("*", $line);
            if (isset($item[1]))
              {
                $arrayOfArrays[] = $item;
              }
          }
        //usort($arrayOfArrays , function($a, $b) {return $a[2]- $b[2];});	
        if (count($arrayOfArrays) > 1)
            usort($arrayOfArrays, "cmp");
        for ($x = 0; $x < count($arrayOfArrays); $x++)
          {
            $strAside .= '<a class="portadaLink" title="' . $arrayOfArrays[$x][1] . '" href="' . $cnfHome . fCleanChar($arrayOfArrays[$x][0]) . '"><b>' . $arrayOfArrays[$x][0] . '</b></a><br>';
          }
        $strAside .= '</aside>';
        return $strAside;
      }
  }
$strLink     = "";
$strLinkEnd  = "";
$strLinkCat  = "=";
$strLinkUser = "?user=";
if ($cnfPermaLink == 0)
  {
    $strLink     = "index.php/";
    $strLinkEnd  = "/";
    $strLinkCat  = "/";
    $strLinkUser = "/";
  }
/*else if ($cnfPermaLink == 1)
  {
    $strLink = "?m=";
  }
else
  {
    $strLink = "?";
  }*/
$posCache  = strrpos($_SERVER['REQUEST_URI'], $strLink);
$posVarCat = strrpos($_SERVER['REQUEST_URI'], "index.php/" . $cnfSubject . "/");
$posVarPost = strrpos($_SERVER['REQUEST_URI'], "/index.php/");
$metaShare="";
if ($posVarCat !== false)
  {
    $strCategoria = substr($_SERVER['REQUEST_URI'], $posVarCat + strlen("index.php/" . $cnfSubject . "/"));
    if (substr($strCategoria, -1) == "/")
        $strCategoria = substr($strCategoria, 0, -1);
    $strCategoria = utf8_decode($strCategoria);
  }
  else if($posVarPost!==false){

  }
  
if ($blogMode == 0)
  {
	  if ((($cnfCatCacheTime != "" && $cnfCatCacheTime != "0") || ($cnfForumCacheTime != "" && $cnfForumCacheTime != "0") || ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0")) && !isset($_SESSION['iduserx']))
    //if (($cnfForumCacheTime != "" && $cnfForumCacheTime != "0") || ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0") || ($cnfCatCacheTime != "" && $cnfCatCacheTime != "0"))
        include('../cache.php');
  }
else
  {
	    if ((($cnfCatCacheTime != "" && $cnfCatCacheTime != "0") || ($cnfForumCacheTime != "" && $cnfForumCacheTime != "0") || ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0")) && !isset($_SESSION['iduserx']))
	//if (($cnfForumCacheTime != "" && $cnfForumCacheTime != "0") || ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0") || ($cnfCatCacheTime != "" && $cnfCatCacheTime != "0")  && !isset($_SESSION['iduserx']))
    //if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
        include "cache.php";
  }
  
if ($blogMode == 0)
  {
    if ($cnfForumCacheTime != "" && $cnfForumCacheTime != "0" && !isset($_SESSION['iduserx']) && $posVarCat === false)
        $cache = new SimpleCachePhp(__FILE__, $cnfForumCacheTime);
  }
else
  {
    if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
        $cache = new SimpleCachePhp(__FILE__, $cnfHomeCacheTime);
  }
  
if ($blogMode == 0)
  {
    if ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0" && !isset($_SESSION['iduserx']) && $posVarCat === false && !isset($_SESSION['iduserx']))
      {
        $fakeArry   = array();
        $strMensaje = substr($_SERVER['REQUEST_URI'], $posCache + strlen("index.php/"));
        $cache      = new SimpleCachePhp(__FILE__, $cnfMessageCacheTime, 0, $fakeArry, "cachepost", $strMensaje);
      }
  }
  
  
  if ($cnfCatCacheTime != "" && $cnfCatCacheTime != "0" && $posVarCat !== false  && !isset($_SESSION['iduserx'])){
	  $fakeArry = array();	
	   $cache      = new SimpleCachePhp(__FILE__, $cnfMessageCacheTime, 0, $fakeArry, "cachecat", $strCategoria);
		    			
	  
  }
  
if (strpos(getcwd(), "/") === false)
  {
    $pathForum      = str_replace("-", " ", explode('\\', getcwd()));
    $pathForum      = end($pathForum);
    $pathForumTotal = explode('\\', getcwd());
    $pathForumTotal = end($pathForumTotal);
  }
else
  {
    $pathForum      = str_replace("-", " ", explode('/', getcwd()));
    $pathForum      = end($pathForum);
    $pathForumTotal = explode('/', getcwd());
    $pathForumTotal = end($pathForumTotal);
  }
if ($blogMode != 0)
    $pathForumTotal = "";
$forumName = "";
$forumReal = "";
$forumDes  = "";
$forumMod  = "";
$forumSel  = $lngMoveTo . '<select name="selMove" id="selMove">';
$forumTl  = "";
foreach (explode(";", $arrForums) as $line)
  {
    $item = explode("*", $line);
	
    if (isset($item[4]))
        if ($item[4] == str_replace(" ", "-", $pathForum))
          {
            $forumName = $item[0];
            $forumDes  = $item[1];
			$forumMod  = $item[3];
			if(isset($item[4]))$forumReal = $item[4];
			if(isset($item[5]))$forumTl = $item[5];
            if (isset($_SESSION['iduserx']))
                if ($forumMod == $_SESSION['iduserx'])
                  {
                    $_SESSION['mod'] = $item[0];
                  }
          }
        else
          {
            if ($item[0] != "" && $item[4] != "")
                $forumSel .= '<option value="' . $item[4] . '">' . $item[0] . '</option>';
          }
  }
$forumSel .= '</select><input type="submit" name="submitMove" id="submitMove" value="Mover"></form>';

$strBoxPost="";
if(intval($cnfMax)!=0)$strBoxPost.='<span style="font-size:100%;float:left;margin:5px;"><b>Upload Files:</b></span><iframe src="'.$cnfHome.'/upload.php" width="100%" height="100%" name="uploadFrame"  scrolling="no"></iframe>';
$strBoxPost .= '
<span style="font-size:90%;float:left;"><i>Hotkeys: </i><b>ctrl+</b>b=(bold) <b>|</b> i=(italic) <b>|</b> u=(underline) <b>|</b> s=(strike) <b>|</b> 1=(h1) <b>|</b> 2=(h2) <b>|</b> 3=(h3) <b>|</b> m=(img) <b>|</b> h=(video) <b>|</b> y=(youtube)  <b>|</b> t=(vimeo) <b>|</b> q=(code)  <b>|</b> e=(link) <b>|</b> g=(tag)</span>
<br style="clear:both;">
<input type="button" title="ctrl+b" value="B" onclick="addtag(\'b\')" style="width:30px; font-weight:bold;" />
<input type="button" title="ctrl+i" value="I" onclick="addtag(\'i\')"  style="width:30px; font-style:italic;" />
<input type="button" title="ctrl+u" value="U" onclick="addtag(\'u\')"  style="width:30px; text-decoration:underline;" />
<input type="button" title="ctrl+s" value="S" onclick="addtag(\'s\')"  style="width:30px; text-decoration:strike;" />
<input type="button" title="ctrl+1" value="h1" onclick="addtag(\'h1\')"  style="width:30px; text-decoration:strike;" />
<input type="button" title="ctrl+2" value="h2" onclick="addtag(\'h2\')"  style="width:30px; text-decoration:strike;" />
<input type="button" title="ctrl+3" value="h3" onclick="addtag(\'h3\')"  style="width:30px; text-decoration:strike;" />
<input type="button" title="ctrl+m" value="' . $lngImage . '" onclick="addtag(\'img\')"  />
<input type="button" title="ctrl+h"  value="Video" onclick="addtag(\'video\')"  />
<input type="button" title="ctrl+y"  value="Youtube" onclick="addtag(\'youtube\')"  />
<input type="button" title="ctrl+t"  value="Vimeo" onclick="addtag(\'vimeo\')"  />
<input type="button" title="ctrl+q"  value="' . $lngCode . '" onclick="addtag(\'code\')"  />
<input type="button" title="ctrl+r"  value="Normal" onclick="addtag(\'remove\')"  />
<input id="linkUrl" style="width:16%;" type="text" value="' . $cnfHome . '" />
<input type="button" title="ctrl+e"  value="Link" onclick="addtag(\'a\')"  />
<input type="button" title="ctrl+k"  value="UnLink" onclick="addtag(\'unlink\')"  />
<input id="linkUrlTag" style="width:20%;" type="text" value="' . $cnfHome .$pathForumTotal. '/index.php/' . $cnfSubject . '/' . '" />
<input type="button" title="ctrl+g"  value="Tag" onclick="addtag(\'at\')"  />';
if(isset($_SESSION['iduserx']))$strBoxPost .='<input type="checkbox" name="no" id="no" value="' . $_SESSION['iduserx'] . '" checked>' . $lngNotify;
if ($cnfPermaLink == 0)
  {
    $posVar  = strpos($_SERVER['REQUEST_URI'], "index.php/");
    $posVarP = strpos($_SERVER['REQUEST_URI'], "?p=");
    $posVarI = strpos($_SERVER['REQUEST_URI'], "?");
    if ($posVar === false && $posVarP === false && !(isset($_GET[$cnfSubject])) && $posVarI !== false)
      {
        $_SERVER['REQUEST_URI'] = str_replace("m=", "", $_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = str_replace("?", "index.php/", $_SERVER['REQUEST_URI']);
        header("refresh:0;url=" . $_SERVER['REQUEST_URI']);
      }
  }
/*else
    $posVar = strrpos($_SERVER['REQUEST_URI'], "?");*/
$rss    = "";
$opcion = 0;
$w      = "";

if ($posVar === false )
  {
	
    if ($blogMode == 0)
      {
?>
<meta name="Keywords" content="<?php echo $forumName;
?>">
<meta name="Description" content="<?php echo $forumDes;
?>">
<link rel="canonical" href="<?php echo $cnfHome . $pathForumTotal;
?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $forumDes;
?>" href="<?php echo $cnfHome . "feed.php?s=" . $pathForumTotal . "&t=0";?>" />

<?php
        /*if ($cnfFbFan != "")
          {
?><meta property="article:publisher" content="https://www.facebook.com/<?php
            echo $cnfFbFan;
?>" /><?php
          }*/
?>
<meta name="twitter:card" content="summary"/>
<?php
        if ($cnfTwFollow != "")
          {
?>
<meta name="twitter:site" content="<?php
            echo $cnfTwFollow;
?>"/>
<meta name="twitter:creator" content="<?php
            echo $cnfTwFollow;
?>"/>
<?php
          }
?>
<meta name="twitter:domain" content="<?php
        echo $cnfHome;
?>">
<meta name="twitter:description" content="<?php
        echo $forumDes;
?>"/>
<meta name="twitter:title" content="<?php
        echo $forumName;
?>"/>
<meta name="twitter:image:src" content="<?php
        echo $cnfHome . "" . $cnfLogo;
?>"/>
<title><?php
        echo $forumName . " | " . $forumDes . " | " . $cnfTitle;
?></title>
<?php
      }
    else
      {
?>
<meta name="Keywords" content="<?php
        echo $cnfKeywords;
?>">
<meta name="Description" content="<?php
        echo $cnfMetaDescription;
?>">
<link rel="canonical" href="<?php
        echo $cnfHome;
?>" />
<link rel="alternate" type="application/rss+xml" title="<?php
        echo $cnfMetaDescription;
?>" href="<?php
        echo $cnfHome . "feed.php";
?>" />
<meta itemprop="name" content="<?php
        echo $cnfMetaDescription;
?>"/>
<meta itemprop="description" content="<?php
        echo $cnfKeywords;
?>"/>
<meta itemprop="image" content="<?php
        echo $cnfHome . "" . $cnfLogo;
?>"/>
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php
        echo $cnfTitle;
?>" />
<meta property="og:url" content="<?php
        echo $cnfHome;
?>" />
<meta property="og:site_name" content="<?php
        echo $cnfMetaDescription;
?>" />
<meta property="og:image" content="<?php
        echo $cnfHome . "" . $cnfLogo;
?>" />
<meta property="og:locale" content="<?php
        echo $cnfLanguageFb;
?>" />
<?php
      /*  if ($cnfFbFan != "")
          {
?><meta property="article:publisher" content="https://www.facebook.com/<?php
            echo $cnfFbFan;
?>" /><?php
          }*/
?>
<meta name="twitter:card" content="summary"/>
<?php
        if ($cnfTwFollow != "")
          {
?>
<meta name="twitter:site" content="<?php
            echo $cnfTwFollow;
?>"/>
<meta name="twitter:creator" content="<?php
            echo $cnfTwFollow;
?>"/>
<?php
          }
?>
<meta name="twitter:domain" content="<?php
        echo $cnfHome;
?>">
<meta name="twitter:description" content="<?php
        echo $cnfMetaDescription;
?>"/>
<meta name="twitter:title" content="<?php
        echo $cnfHeaderText . " | " . $cnfTitle;
?>"/>
<meta name="twitter:image:src" content="<?php
        echo $cnfHome . "" . $cnfLogo;
?>"/>
<title><?php
        echo $cnfHeaderText . " | " . $cnfTitle;
?></title>
<?php
      }
    /***----------------TAGS----------------------***/    
  }
else if ($posVarCat !== false && $cnfPermaLink == 0)
  {
	  
    $opcion            = 1;
    $subforumNameUrl   = "";
    $subforumNameTitle = "";
    if ($blogMode == 0)
      {
        $subforumNameUrl   = $pathForumTotal . "/";
        $subforumNameTitle = " | " . $forumName;
      }
?>
<meta name="Keywords" content="<?php
    echo urldecode($strCategoria);
?>">
<meta name="Description" content="<?php
    echo $lngResultsFor . ": " . urldecode($strCategoria) . ", " . $cnfMetaDescription;
?>">
<link rel="canonical" href="<?php
    echo $cnfHome . $subforumNameUrl . "index.php/" . $cnfSubject . "/" . $strCategoria . "/";
?>" />
<meta itemprop="name" content="<?php
    echo $lngResultsFor . ": " . urldecode($strCategoria);
?>"/>
<meta itemprop="description" content="<?php
    echo urldecode($strCategoria) . "," . $cnfKeywords;
?>"/>
<meta itemprop="image" content="<?php
    echo $cnfHome . "" . $cnfLogo;
?>"/>
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php
    echo urldecode($strCategoria) . $subforumNameTitle . " | " . $cnfTitle;
?>" />
<meta property="og:url" content="<?php
    echo $cnfHome . $subforumNameUrl . "index.php/" . $cnfSubject . "/" . $strCategoria . "/";
?>" />
<meta property="og:site_name" content="<?php
    echo $lngResultsFor . ": " . urldecode($strCategoria);
?>" />
<meta property="og:image" content="<?php
    echo $cnfHome . "" . $cnfLogo;
?>" />
<meta property="og:locale" content="<?php
    echo $cnfLanguageFb;
?>" />
<?php
    /*if ($cnfFbFan != "")
      {
?><meta property="article:publisher" content="https://www.facebook.com/<?php
        echo $cnfFbFan;
?>" /><?php
      }*/
?>
<meta name="twitter:card" content="summary"/>
<?php
    if ($cnfTwFollow != "")
      {
?>
<meta name="twitter:site" content="<?php
        echo $cnfTwFollow;
?>"/>
<meta name="twitter:creator" content="<?php
        echo $cnfTwFollow;
?>"/>
<?php
      }
?>
<meta name="twitter:domain" content="<?php
    echo $cnfHome;
?>">
<meta name="twitter:description" content="<?php
    echo $lngResultsFor . ": " . urldecode($strCategoria);
?>"/>
<meta name="twitter:title" content="<?php
    echo urldecode($strCategoria) . $subforumNameTitle . " | " . $cnfTitle;
?>"/>
<meta name="twitter:image:src" content="<?php
    echo $cnfHome . "" . $cnfLogo;
?>"/>
<?php
    echo "<title>" . urldecode($strCategoria) . $subforumNameTitle . " | " . $cnfTitle . "</title>";
  }
else
  {
	  
    $opcion = 2;
    if ($cnfPermaLink == 0)
      {
        $w = substr($_SERVER['REQUEST_URI'], $posVar + 10);
        if (strpos($w, "/") !== false)
            $w = substr($w, 0, strpos($w, "/"));
        if (substr($w, -1) == "/")
            $w = substr($w, 0, -1);
	  }
  
    $w         = urldecode($w);
    $w         = filter_var($w, FILTER_SANITIZE_SPECIAL_CHARS);
    $titleFile = str_replace("-", " ", $w);

	$thumbnail=thumbnail($w,$cnfHome,$cnfLogo,$cnfThumbnail);
		
	

?>
<meta name="Keywords" content="<?php
    echo str_replace(" ", ",", $titleFile);
?>">
<meta name="Description" content="<?php echo $titleFile;?>">
<link rel="canonical" href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $forumDes; ?>" 
href="<?php echo $cnfHome . "feed.php?s=" . $pathForumTotal . "/" . $strLink . $w . $strLinkEnd . "&t=1";
?>" />
<meta itemprop="name" content="<?php echo $titleFile;
?>"/>
<meta itemprop="description" content="<?php echo $titleFile;
?>"/>
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php echo $titleFile;?>" />
<meta property="og:url" content="<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<meta property="og:site_name" content="<?php    echo str_replace(" ", ",", $titleFile);?>" />
<?php

?>
<meta property="og:locale" content="<?php  echo $cnfLanguageFb; ?>" />
<meta property="og:image" content="<?php  echo $thumbnail; ?>" />
<meta property="og:description" content="<?php  echo $titleFile; ?>" />

<?php
    /*if ($cnfFbFan != "")
      {
?><meta property="article:publisher" content="https://www.facebook.com/<?php
        echo $cnfFbFan;
?>" /><?php
      }*/
?>
<meta name="twitter:card" content="summary"/>
<?php
    if ($cnfTwFollow != "")
      {
?>
<meta name="twitter:site" content="<?php
        echo $cnfTwFollow;
?>"/>
<meta name="twitter:creator" content="<?php
        echo $cnfTwFollow;
?>"/>
<?php
      }
?>
<meta name="twitter:domain" content="<?php
    echo $cnfHome;
?>">
<meta name="twitter:description" content="<?php
    echo str_replace(" ", ",", $titleFile);
?>"/>
<meta name="twitter:title" content="<?php
    echo $titleFile;
?>"/>
<?php
    echo "<title>" . $titleFile . " | " . $forumName . " | " . $cnfTitle . "</title>";
  }
?>
<script src="<?php
echo $cnfHome . $cnfJava;
?>" type="text/javascript"></script>
</head>
<body>
<div class="box1" >
<!--<a style="float:left;" href="<?php
echo $cnfHome;
?>" alt="<?php
echo $lngBackIndex;
?>" title="<?php
echo $lngBackIndex;
?>">
-->
<img style="cursor:pointer;" onclick="javascript:location.href='<?php echo $cnfHome;?>'" class="logo" title="<?php
echo $cnfHeaderText;
?>" src="<?php
echo $cnfHome . $cnfLogo;
?>" />
<!--</a>-->
<div class="boxAlignVertical">
<h1 class='h1Vertical'><?php
echo $cnfHeaderText;
?></h1>
<?php
if (isset($_SESSION['iduserx']))
  {
	 $_SESSION['return']="index.php";
    echo "<h4 class='h4hello'>" . $lngHi . " <a title='" . $lngSeeProfile . "' href='" . $cnfHome . "user.php" . $strLinkUser . $_SESSION['iduserx'] . $strLinkUser . "'>" . $_SESSION['iduserx'] . "</a></h4><a class='aLogin' href='". $cnfHome ."logout.php'>¿Salir?</a>";
    if ($_SESSION['iduserx'] == $cnfAdm)
      {
        echo "<a class='aLogin' href='" . $cnfHome . "admin.php'>" . $lngAdm . "</a>";
      }
  }
else
  {
    echo "<a class='aLogin' href='".$cnfHome."login.php'>" . $lngEnter . "&nbsp;|&nbsp; </a>";
    echo "<a class='aLogin' href='".$cnfHome."register.php'>" . $lngReg . "</a>";
  }
if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
  {
    $cache = new SimpleCachePhp(__FILE__, $cnfHomeCacheTime);
  }
?>

</div>
<div class="searchBox">
<?php
if ($cnfXGoogle != "")
  {
 echo '<input type="text" onKeyUp="fSearch0(event,0,\'' . $cnfHome . '\')" id="googleSearch" /><input id="btgoogleSearch" type="button" value="' . $lngSearch . '" onclick="fSearch(0,\'' . $cnfHome . '\')"	/>';
  }
else if ($cnfGoogleSearch != "")
  {
echo "<gcse:search></gcse:search>";
  }
  ?>
</div>
<div class="boxTools">
<ul id="hexPanel">

<?php



if ($cnfFbFan != "")
  {
	echo '<li class="p1">
  <a title="' . $lngFollow . ' en Facebook" href="http://fb.com/' . $cnfFbFan . '" target="_blank" >
    <b></b>
    <span>Facebook</span>
    <em></em>
  </a>
</li>';  
    //echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Facebook" href="http://fb.com/' . $cnfFbFan . '" target="_blank" />Facebook</a>';
  }
if ($cnfTwFollow != "")
  {
echo' 	  <li>
  <a title="' . $lngFollow . ' en Twitter" href="https://twitter.com/' . $cnfTwFollow . '" target="_blank" />
    <b></b>
    <span>Twitter</span>
    <em></em>
  </a>
</li>';
    //echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Twitter" href="https://twitter.com/' . $cnfTwFollow . '" target="_blank" />Twitter</a>';
  }
if ($cnfGoogleInsignia != "")
  {
echo '<li class="p2">
  <a  title="' . $lngFollow . ' en Google Plus" href="https://plus.google.com/' . $cnfGoogleInsignia . '" target="_blank" />
    <b></b>
    <span>Google+</span>
    <em></em>
  </a>
</li>';	  
    //echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Google Plus" href="https://plus.google.com/' . $cnfGoogleInsignia . '" target="_blank" />Google+</a>';
  }
if ($cnfytChannel != "")
  {
	  
	echo'<li class="p2">
  <a  class="inner" title="' . $lngSub . ' en Youtube" href="https://www.youtube.com/channel/' . $cnfytChannel . '" target="_blank" />
    <b></b>
    <span>Youtube</span>
    <em></em>
  </a>
</li>';
   // echo '<a class="portadaLinkSocial" title="' . $lngSub . ' en Youtube" href="https://www.youtube.com/channel/' . $cnfytChannel . '" target="_blank" />Youtube</a>';
  }
if ($cnfPinterestPage != "")
  {
'<li class="p2">
  <a  title="' . $lngFollow . ' en Pinterest" href="https://www.pinterest.com/' . $cnfPinterestPage . '" target="_blank" />
    <b></b>
    <span>Pinterest</span>
    <em></em>
  </a>
</li>';
   // echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Pinterest" href="https://www.pinterest.com/' . $cnfPinterestPage . '" target="_blank" />Pinterest</a>';
  }  
if ($cnfInstagramPage != "")
  {
echo '<li class="p1 p2">
  <a  title="' . $lngFollow . ' en Instagram" href="https://www.instagram.com/' . $cnfInstagramPage . '" target="_blank" />
    <b></b>
    <span>Instagram</span>
    <em></em>
  </a>
</li>';
   // echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Instagram" href="https://www.instagram.com/' . $cnfInstagramPage . '" target="_blank" />Instagram</a>';
  } 
if ($cnfLinkedinPage != "")
  {
echo '<li class="p2">
  <a class="portadaLinkSocial" title="' . $lngFollow . ' en Linkedin" href="https://www.linkedin.com/' . $cnfLinkedinPage . '" target="_blank" />
    <b></b>
    <span>Linkedin</span>
    <em></em>
  </a>
</li>';
//echo '<a class="portadaLinkSocial" title="' . $lngFollow . ' en Linkedin" href="https://www.linkedin.com/' . $cnfLinkedinPage . '" target="_blank" />Linkedin</a>';
    
  }   
  

?>
</ul>

</div>

<div class="boxCat">

<?php
if ($arrForums == "")
  {
    //echo $lngNotCat;
  }
else
  {
    $arrayOfArrays = array();
    $xx            = 0;
    foreach (explode(";", $arrForums) as $line)
      {
        $item = explode("*", $line);
        if (isset($item[1]))
          {
            $arrayOfArrays[] = $item;
          }
        $xx++;
      }
    if (count($arrayOfArrays) > 1)
        usort($arrayOfArrays, "cmp");
    for ($x = 0; $x < count($arrayOfArrays); $x++)
      {
	echo'<a  class="linkCat" title="' . $arrayOfArrays[$x][1] . '" href="' . $cnfHome . fCleanChar($arrayOfArrays[$x][0]) . '"><div id="hexCat" class="hexagon-wrapper">		   
		<div id="color0" class="hexagon2">
	</div></div>'. $arrayOfArrays[$x][0] . '</a>';
	
		//echo '<div class="hexagon100"><span></span></div><br>';
      }
  }
//echo "</aside>";
?>
</div>
</div>
<?php
//if ($cnfCatCacheTime != "" && $cnfCatCacheTime != "0" && $posVarCat !== false)
if ($cnfCatCacheTime != "" && $cnfCatCacheTime != "0" && $posVarCat !== false  && !isset($_SESSION['iduserx'])){	
    $fakeArry = array();	
    $cache    = new SimpleCachePhp(__FILE__, $cnfCatCacheTime, 0, $fakeArry, "cachecat", $strCategoria);
  }
if ($arrLinks != "")
  {
    echo "<ul>\r\n";
    foreach (explode(";", $arrLinks) as $line)
      {
        $item = explode("*", $line);
        if (isset($item[1]))
          {
            //echo "<li class='liLinks'><a class='linkNav' href='" . $item[0] . "' title='" . $item[0] . "'>" . $item[1] . "</a></li>\r\n";
			 echo '
	<li class="liLinks"><div id="hexLink" class="hexagon-wrapper">		   
		<div id="color1" class="hexagon2">
	</div>
	</div><a class="linkNav" href="' . $item[0] . '" title="' . $item[0] . '">' . $item[1] . '</a></li>
	';
          }
      }
  }
echo "</ul>\r\n";
?>
<div style="clear:both"></div>
<?php
if ($opcion == 0)
  {
    $title = "";
    $body  = "";
    $tag   = "";
    $rss   = '<a title="RSS" target="_blank" href="' . $cnfHome . 'feed.php?s=' . $pathForumTotal . '&t=0"><b>RSS</b> </a>';
    if (isset($_SESSION['title']))
        $title = $_SESSION['title'];
    if (isset($_SESSION['body']))
        $body = preg_replace('#<br\s*?/?>#i', "", $_SESSION['body']);
    if (isset($_SESSION['tag']))
        $tag = $_SESSION['tag'];
    if ($blogMode == 0)
      {
?>
<nav id="catNav" >
<a href="../index.php">Inicio</a> >>
<?php
        echo $forumName . ": <i>" . $forumDes . "</i>";
?>
</nav>

<?php
      }
	 $strPaginacion="";
    $files     = array();
    $strFilesT = "";
    $xx        = 0;
    if (file_exists("t.xml"))
      {
        $xmlT = new DOMDocument();
        $xmlT->load("t.xml");
        foreach ($xmlT->getElementsByTagName('t') as $element)
          {
            $files[$xx][0] = utf8_decode($element->textContent . ".xml");
            $files[$xx][1] = time();
            $files[$xx][2] = filemtime(utf8_decode($element->textContent . ".xml"));
            $strFilesT .= $element->textContent . ".xml;";
            $xx++;
          }
      }
    if ($handle = opendir('.'))
      {
        if (!isset($_GET['p']))
            $_GET['p'] = 0;
        if (isset($_GET['p']))
            if (!is_numeric($_GET['p']))
                $_GET['p'] = 0;
        $next = 0;
        if (isset($_GET['p']))
            $next = $_GET['p'] + $cnfNumberPage;
        $nnext = intval($next) + 1;
        $back  = 0;
        if (isset($_GET['p']))
            $back = $_GET['p'] - $cnfNumberPage;
        while (false !== ($file = readdir($handle)))
          {
            if ($file != "cache" && $file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml' && strlen($file) > 6 && strpos($strFilesT, utf8_encode($file) . ";", 0) === false)
              {
                $posVarTag = strrpos($file, "_T_");
                if ($posVarTag === false ||($_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod) )
                  {
                    $files[$xx][0] = utf8_encode($file);
                    $files[$xx][1] = filemtime($file);
                    $files[$xx][2] = 0;
                  }
                $xx++;
              }
          }
        closedir($handle);
        if ($xx <= $cnfNumberPage || $_GET['p'] + $cnfNumberPage > $xx)
          {
            $nnext = 0;
            $next  = $xx;
          }
        if (count($files) > 0)
          {
            function filetime_callback($a, $b)
              {
                if ($a[1] === $b[1])
                    return 0;
                return $a[1] < $b[1] ? -1 : 1;
              }
            usort($files, "filetime_callback");
            $files = array_reverse($files);
            $desde = $_GET['p'] + 1;
            if ($back >= 0 && $_GET['p'] != 0)
              {
                $strPaginacion.= "<a href='" . $cnfHome . $pathForumTotal . "/?p=" . $back . "'/><b><<</b> </a>";
              }
            $strPaginacion.= "Mensajes del :[" . $desde . " al " . $next . "]";
            if ($nnext > 0)
              {
                $strPaginacion.= "<a href='" . $cnfHome . $pathForumTotal . "/?p=" . $next . "'/><b>>></b></a>";
              }
            $xx  = 1;
            $alg = 0;
echo $strPaginacion;
?>

<div style="clear:both"></div>
<center><a href="#w"><b>Iniciar un nuevo tema</b></a></center>
<div class="boxBody">
<?php
            if ($blogMode == 0)
              {
                echo '<div class="box2">';
              }
            else
              {
                echo '<div class="boxBlog">';
              }
?>
<?php

            $adsx = 0;
            foreach ($files as $file)
              {
				  
                if ($xx > $_GET['p'] && $xx <= $next)
                  {					  
					  
                    $strAuxFile0 = $file[0];
                    if ((int) $file[2] == 0)
                        $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file[0]);
                    else
                        $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', utf8_encode($file[0]));
                    $strPermalink = "";
                    if ($cnfPermaLink == 0)
                        $strPermalink = $cnfHome . $pathForumTotal . $auxBlog . "index.php/" . $fileName . "/";
                    /*else if ($cnfPermaLink == 2)
                        $strPermalink = $cnfHome . $pathForumTotal . $auxBlog . "?m=" . $fileName;*/
					
					
					$thumbnail=thumbnail($fileName,$cnfHome,$cnfLogo,$cnfThumbnail);
					
                    if ((int) $file[2] == 0)
                      {						
				 
				 
                        echo "<a href='" . $strPermalink . "' title='".str_replace("-", " ", $fileName)."'><div class='boxForum2in'>";						
						echo '<div class="hexagon75" style="background-image:url('.$thumbnail.');">
						<div class="hexTop75"></div>			
						<div class="hexBottom75"></div>
						</div>';						
						echo "<h5 class='h5Left'>" . str_replace("-", " ", $fileName) . "</h5>&nbsp;&nbsp;" . $lngModified . ":&nbsp;&nbsp;<time class='entry-date' datetime='" . gmdate("D, d M Y H:i:s O", (int) $file[1]) . "'>" . gmdate("<b>d-M-Y</b> G:i", (int) $file[1]) . "</time></div></a>";
				
                        $alg = 1;
                      }
                    else
                      {
						  
						  echo "<a href='" . $strPermalink . "' title='".str_replace("-", " ", $fileName)."'><div class='boxForumPin'>";						
						echo '<div class="hexagon75" style="background-image:url('.$thumbnail.');">
						<div class="hexTop75"></div>			
						<div class="hexBottom75"></div>
						</div>';						
						echo "<h5 class='h5Left'>" . str_replace("-", " ", $fileName) . "</h5>&nbsp;&nbsp;" . $lngModified . ":&nbsp;&nbsp;<time class='entry-date' datetime='" . gmdate("D, d M Y H:i:s O", (int) $file[1]) . "'>" . gmdate("<b>d-M-Y</b> G:i", (int) $file[1]) . "</time></div></a>";
                        //echo "<div class='boxForumPin'><h5 class='h5Left'><a href='" . $strPermalink . "'>" . str_replace("-", " ", $fileName) . "</a></h5>&nbsp;&nbsp;" . $lngLastAnwser . ":&nbsp;&nbsp;<time class='entry-date' datetime='" . gmdate("D, d M Y H:i:s O", (int) $file[2]) . "'>" . gmdate("<b>d-M-Y</b> G:i", (int) $file[2]) . "</time></div>";
                        $alg = 1;
                      }
                    $adsx++;
                    if ($cnfAdsense != "" && $adsx >= intval($cnfAdsCat))
                      {
                        $adsx = 0;
?>
<br style="clear:both">
<center>
<?php
                    if ($cnfAdsTitle != "")
                        echo "<br><i>" . $cnfAdsTitle . "</i><br>";
                    echo $cnfAdsense;
?>
</center>
<?php



                      }
                  }
                $xx++;
              }
            if ($alg == 0)
                header("refresh:0;url=" . $cnfHome . $pathForumTotal);
          }
        else
          {
            echo "<h4 class='h4Gray'>" . $lngNotMes . "</h4>";
          }
?>
</div>
<?php
        if (isset($_SESSION['iduserx']))
          {
?>
<div style="clear:both"></div>
<?php
echo $strPaginacion;
?>
<h6 style="text-align:center">Comenzar un nuevo tema</h6>
<form class="formBlitz" method="post" action="<?php
            echo $cnfHome . "w.php?q=0&t=" . $pathForumTotal;
?>" enctype="multipart/form-data">

<input type="text" name="w" id="w" onKeyUp='feChangeW()' placeholder="Title" autocomplete="off" value="<?php
            echo $title;
?>">
<br style="clear:both">
<?php
            echo $strBoxPost;
?>
<div id="dvCont">0</div>
<!--<textarea id="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()' name="txtE"><?php
            echo $body;
?></textarea>-->
<textarea name="hide" id="hide" style="display:none;"></textarea>
<div spellcheck="true"  contentEditable="true" id="txtE" name="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()' >
<?php
echo $body;
?>
</div>  
<input type="text" value="<?php
            echo $tag;
?>" name="u" id="u" placeholder="tag1,tag2" autocomplete="off" style="width:73%"><input type="button" title="" value="Link Tags"  onclick="linktag()"  /><input type="button" title="" value="Link <?php echo $forumName;?>" onclick="linkCat(<?php echo "'".$forumName."','".$cnfHome.$strLink.$forumReal.$strLinkEnd."'";?>)"  />
<br style="clear:both">
<input type="submit" name="submit" id="submit" value="<?php
            echo $lngCreate;
?>"/>
<?php
            if ($_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod)
              {
                echo '<input type="checkbox" name="d" id="d" value="1">' . $lngBlock . '<input type="checkbox" name="f" id="f" value="1">' . $lngPin;
                if ($_SESSION['iduserx'] == $cnfAdm)
                  {
					echo ' | ' . $lngIn . ' <input type="text" style="width:20px" name="ti" id="ti" value="0"> ' . $lngHours;	
                    if ($blogMode == 0){
                        echo '<input type="checkbox" name="po" id="po" value="1" checked>' . $lngIndex;
					}
						
						if($cnfAdsense != ""){
						echo '
						<input type="button" alt="ctrl+4" value="adsense" onclick="addtag(\'adsense\')" style="width:65px;margin-left:10px; font-weight:bold;" />
						<input type="button" alt="ctrl+5" value="Ads After Files" onclick="addfiles()" style="width:115px;margin-left:10px; font-weight:bold;" />
						<input type="button" alt="ctrl+6" value="Ads After Titles" onclick="addtit()" style="width:110px;margin-left:10px; font-weight:bold;" />
						';
						//echo '<textarea id="adsenseCode" name="adsenseCode" style="display:none;" />'.$cnfAdsense.'</textarea>';
					
						}
						
                  }
				 echo '<input type="hidden" name="poimg" id="poimg" value="">'; 
              }
?>
<br><?php
            echo $lngPreView;
?>
<div id="e" name="e" contenteditable="true" onkeyup="feChange2()"></div>
</form>
<?php
          }
      }
/***************** T A G ************************/
/***************** T A G ************************/
/***************** T A G ************************/
/***************** T A G ************************/
/***************** T A G ************************/
/***************** T A G ************************/
  }
else if ($opcion == 1)
  {
    if ($posVarCat !== false)
      {
      }
    else
      {
        $strCategoria = $_GET[$cnfSubject];
      }
    $strCategoria = filter_var($strCategoria, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $strCategoria = urldecode($strCategoria);
    $notag        = 0;
    $strEnlaces   = "";
    if ($handle = opendir('.'))
      {
        $xtag = 0;
        if (!isset($strFilesT))
            $strFilesT = "";
        while (false !== ($file = readdir($handle)))
          {
            $fechaRss = gmdate("D, d M Y H:i:s O", (int) filectime($file));
            if (filemtime($file) !== FALSE)
                $fechaRss = gmdate("D, d M Y H:i:s O", (int) filemtime($file));
            $fecha = gmdate("<b>d-M-Y</b> G:i", (int) filectime($file));
            if (filemtime($file) !== FALSE)
                $fecha = gmdate("<b>d-M-Y</b> G:i", (int) filemtime($file));
            $posVarTag = strrpos($file, "_T_");
            if ($file != "cache" && $file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml' && strlen($file) > 6 && strpos($strFilesT, utf8_encode($file) . ";", 0) === false && ($posVarTag === false||($_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod)))
              {
				  //die($strCategoria);
                if ($cnfUltraSearch == 'checked')
                  {
                    $xml = simplexml_load_file($file);
                    if (strpos(strtolower($xml->p[0]->b), $strCategoria, 0) !== false || strpos(strtolower($xml->p[0]->t), $strCategoria, 0) !== false || strpos(strtolower($xml->p[0]->g), $strCategoria, 0) !== false || strpos(strtolower($file), $strCategoria, 0) !== false)
                      {
                        $notag = 1;
                        $file  = basename($file, ".xml") . PHP_EOL;
						
						 $thumbnail=thumbnail($file,$cnfHome,$cnfLogo,$cnfThumbnail);
				 
                        $strEnlaces .="<a class='aFloatMessage' href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . "'>";						
						$strEnlaces .='<div class="boxRelated"><div class="hexagon75" style="background-image:url('.$thumbnail.');">
						<div class="hexTop75"></div>			
						<div class="hexBottom75"></div>
						</div>';						
						$strEnlaces .="<h5 class='h5Left'>" . str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . "</h5>".$lngModified.":&nbsp;&nbsp;<time class='entry-date' datetime='" . $fechaRss . "'>" . $fecha . "</time></div></div></a>";
						
                        //$strEnlaces .= "<div class='boxTag'><h5 class='h5Left'><a class='aFloatMessage' href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . "'>" . str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . "</a></h5>&nbsp;&nbsp;Última respuesta:&nbsp;&nbsp;<time class='entry-date' datetime='" . $fechaRss . "'>" . $fecha . "</time></div>";
                      }
                  }
                else if (strpos(strtolower(utf8_encode($file)), $strCategoria, 0) !== false)
                  {
                    $notag = 1;
					 $thumbnail=thumbnail($file,$cnfHome,$cnfLogo,$cnfThumbnail);
				 
                        $strEnlaces .="<a class='aFloatMessage' href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . "'>";						
						$strEnlaces .='<div class="boxRelated"><div class="hexagon75" style="background-image:url('.$thumbnail.');">
						<div class="hexTop75"></div>			
						<div class="hexBottom75"></div>
						</div>';						
						$strEnlaces .="<h5 class='h5Left'>" . str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . "</h5>".$lngModified.":&nbsp;&nbsp;<time class='entry-date' datetime='" . $fechaRss . "'>" . $fecha . "</time></div></a>";
						
                    //$strEnlaces .= "<div class='boxTag'><h5 class='hr5Left'><a class='aFloatMessage' href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . "'>" . str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . "</a></h5>&nbsp;&nbsp;Última respuesta:&nbsp;&nbsp;<time class='entry-date' datetime='" . $fechaRss . "'>" . $fecha . "</time></div>";
                    $xtag++;
                    if ($cnfAdsense != "" && $xtag >= intval($cnfAdsTag))
                      {
                        $xtag = 0;
                        $strEnlaces .= "<center>";
                        if ($cnfAdsTitle != "")
                            $strEnlaces .= "<br><i>" . $cnfAdsTitle . "</i><br>";
                        $strEnlaces .= $cnfAdsense;
                        $strEnlaces .= "</center>";
                      }
                  }
                //
              }
          }
      }
    if ($notag == 0)
      {
        $_SESSION['error'] = $strCategoria;
        die(include("../404.php"));
      }
    else
      {
        echo '<h2 class="h1GrayCenter">' . $lngResultsFor . ' "' . $strCategoria . '":</h2>';
        if ($blogMode == 0)
          {
            //echo '<div class="boxRelated">' . $strEnlaces . '</div>';
			echo $strEnlaces ;
            //echo fCategorias($arrForums,$cnfHome);
          }
        else
          {
            echo '<div class="boxBlog">' . $strEnlaces . '</div>';
          }
      }
  }
else//***********************POST
  {
	$posVarTemporal = strrpos($w, "_T_");
	if($posVarTemporal!==false&&$_SESSION['iduserx'] != $cnfAdm && $_SESSION['iduserx'] != $forumMod)die(header("refresh:0;url=".$cnfHome));
    $rss = '<a title="RSS" target="_blank" href="' . $cnfHome . 'feed.php?s=' . $pathForumTotal . "/" . $strLink . $w . $strLinkEnd . '&t=1" ><b>RSS</b> </a>';
    $w   = utf8_decode($w);
    if (!file_exists($w . ".xml"))
      {
        $_SESSION['error'] = $w;
       if ($blogMode == 0)die(include("../404.php"));
	   else die(include("404.php"));
      }
    $xml         = simplexml_load_file($w . ".xml");
    $t           = time();
    $interval    = $t - $xml->p[0]->a;
    $interMinuts = $interval / 60;
    if ($interMinuts < 15 && $xml->p[0]->u == $_SESSION['iduserx'])
      {
        $last15                  = 1;
        $_SESSION['allowdelete'] = 1;
      }
?>
<nav id="catNav" >
<a href="<?php
    echo $cnfHome;
?>">HOME</a> >>
<?php
    if ($blogMode == 0)
      {
?>
<a href="<?php
        echo $cnfHome . $pathForumTotal;
?>"><?php
        echo $forumName;
?></a> >
<?php
      }
?>
<?php
    echo $titleFile;
?>
<nav id="catNav">
<?php
    $xr = 0;
    if ($cnfPermaLink == 0)
      {
        $posVarP  = strrpos($_SERVER['REQUEST_URI'], "/p/");
        $posVarPP = strrpos($_SERVER['REQUEST_URI'], "/", $posVarP + strlen("/p/"));
        $lenPP    = $posVarPP - ($posVarP + strlen("/p/"));
        if ($posVarP !== false)
            $_GET['p'] = substr($_SERVER['REQUEST_URI'], $posVarP + strlen("/p/"), $lenPP);
      }
    if (!isset($_GET['p']))
        $_GET['p'] = 0;
    if (isset($_GET['p']))
        if (!is_numeric($_GET['p']))
            $_GET['p'] = 0;
    $next  = intval($_GET['p']) + intval($cnfNumberPage);
    $nnext = intval($next) + 1;
    $desde = $_GET['p'] + 1;
    $back  = $_GET['p'] - $cnfNumberPage;
    if ($back >= 0)
      {
        if ($cnfPermaLink == 0)
            echo "<a href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . $w . "/p/" . $back . "/'/><b><<</b></a>";
        else
            echo "<a href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . $w . "&p=" . $back . "'/><b><<</b> </a>";
      }
    $hasta = count($xml->p->children());
    if ($hasta > $next)
        $hasta = $next;
    echo "<span id='pagination'>Respuestas :[" . $desde . " al " . $hasta . "]</span>";
    echo '<br style="clear:both;">';
    if ($nnext > 0 && isset($xml->p[$next]))
      {
        if ($cnfPermaLink == 0)
            echo "<a href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . $w . "/p/" . $next . "/'/><b>>></b></a>";
        else
            echo "<a href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . $w . "/&p=" . $next . "'/><b>>></b></a>";
      }
    $xr     = 0;
    $alg    = 0;
    $author = 0;
    $adsx   = 0;
	$strUsersVoted="";
    foreach ($xml->p as $repuesta)
      {
        if(isset($_SESSION['iduserx']))if ($xr == 0 && $repuesta->u == $_SESSION['iduserx'])
          {
            $author = 1;
          }
        if ($xr >= $_GET['p'] && $xr < $next)
          {
            $alg            = 1;
            $subStyle       = '';
            $subStyle       = 'style="background-color:#000;"';
            $boxPostPortada = 'boxPostPortadaR';
			$headerCat="";
            if ($xr % 2 != 0)
              {
                $subStyle = 'style="background-color:#000;"';
              }
            if ($xr != 0 && $xr == $xml->p[0]->f)
                $subStyle = 'style="background-color:#D6FFAE;"';
            if ($xr == 0){
                $boxPostPortada = 'boxPostCat';
				$headerCat = 'headerCat';
			}
            echo "\r\n";
?>
<article class="<?php
            echo $boxPostPortada;
?>" <?php
            echo $subStyle;
?> id="<?php
            echo $xr;
?>">
<header class="<?php echo $headerCat;?>">
<?php
            if ($xr == 0)
                echo "<h1 class='h2Title'>" . $repuesta->t . "</h1>\r\n";	
	
			  
            if ($xr != 0 && $xr == $xml->p[0]->f)
                echo " <h5>✔<i>" . $lngFavorite . "</i></h5>\r\n";
?>
<p class="pSubLine"><?php
            echo "<a href='" . $cnfHome . "user.php" . $strLinkUser . $repuesta->u . $strLinkEnd . "'  title='Ver perfil de " . $repuesta->u . "'>" . $repuesta->u . "</a> ";	
?>
<time class="entry-date" datetime="<?php
            echo gmdate("D, d M Y H:i:s O", (int) $repuesta->a);
?>"><?php
            echo gmdate("<b>d-M-Y</b> G:i", (int) $repuesta->a);
?></time></p>

</header>

<br style="clear:both">
	
<section class="sectionPortada" id="<?php
            echo "sec" . $xr;
?>"><?php




            if ($xr != 0 && $repuesta->m == 0)
                echo $repuesta->b;
            else if ($xr != 0 && $repuesta->u == $_SESSION['iduserx'])
                echo "<i><b>Hola " . $_SESSION['iduserx'] . " " . $lngModYour . ".</b></i>";
            else if ($xr != 0 && $_SESSION['iduserx'] != $cnfAdm && $_SESSION['iduserx'] != $forumMod)
                echo "<i>" . $lngNotMod . ".</i>";
            else
                echo $repuesta->b;
?>
</section>
<div id="divPost">
<?php
            if ($xr == 0 && $xml->p[0]->f != 0)
              {
                echo "<a href='#" . $xml->p[0]->f . "'>✔ " . $lngFavOne . "</a><br>";
              }
            if ($xr != 0 && $author == 1 && $xr != $xml->p[0]->f && $_SESSION['iduserx'] != $xml->p[$xr]->u)
              {
                echo '<form  method="post" action="' . $cnfHome . "w.php?q=7&w=" . $pathForumTotal . '/' . utf8_encode($w) . '&t=' . $xr . '&y=' . $repuesta->u . '"> 
				<input class="favButton" type="submit" id="likeButton" name="likeButton" value="Puedes elegirla como la mejor respuesta"></form>';
              }
            if ($cnfVoteLevel == "")
                $cnfVoteLevel = 0;
            //echo $cnfVoteLevel;
            if ($repuesta->v != "")
              {
				$intVotes=0;
                $intVotes = count(explode(",", $repuesta->v)) - 1;	
				
				//blitzhivemod
				if($cnfVoteMoney!="0"&&$cnfVoteMoney!=""){
					$intVotesMoney=0;					
					$intVotesMoney=$intVotes*$cnfVoteMoney;
				
				$strMoney="";				
				if($xr==0)$strMoney="<br><h3><b><a target='_blank' href='" . $cnfHome . "user.php" . $strLinkUser . $repuesta->u . $strLinkEnd . "'  title='Ver ganancias de " . $repuesta->u . "'>" . $repuesta->u . "</a></b> ha ganado <b><a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank' title='ganar dinero por contenido'>".$intVotesMoney." $</a></b> con este post</h3></div>";
				else $strMoney="<br><h3><b><a target='_blank' href='" . $cnfHome . "user.php" . $strLinkUser . $repuesta->u . $strLinkEnd . "'  title='Ver ganancias de " . $repuesta->u . "'>" . $repuesta->u . "</a></b> ha ganado <b><a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank' title='ganar dinero por contenido'>".$intVotesMoney." $</a></b> con esta respuesta</h3></div>";
				}
				
                if ($repuesta->v != "")
                    $strUsersVoted = "(" . substr($repuesta->v, 1) . ")";
                echo "<span id='lbVotes'>" . $lngLikeTo . ":</span><div id='votes'><b>" . $intVotes 
					. "</b>" . $strUsersVoted .$strMoney."</div>";
					
				
              }
            else if (isset($_SESSION['iduserx']))
              {
                if ($_SESSION['iduserx'] != $repuesta->u && intval($_SESSION['level']) >= intval($cnfVoteLevel))
                  {
                    echo "<span  id='lbVotes'>" . $lngFirstLike . "</span>";
                  }
              }
            $strButtonVote = "";
            if (isset($_SESSION['iduserx']) && $_SESSION['iduserx'] != $repuesta->u 
			&& strpos($repuesta->v, ',' . $_SESSION['iduserx'], 0) === false 
			&& $repuesta->v!=$_SESSION['iduserx']
			&& intval($_SESSION['level']) >= intval($cnfVoteLevel))
              {				  
?>
<form method="post" action="<?php
                echo $cnfHome . "w.php?q=3&w=" . $pathForumTotal . '/' . utf8_encode($w);
?>&t=<?php
                echo $xr;
?>&y=<?php
                echo $repuesta->u;
?> ">
<?php
                //$strButtonVote = '  <input class="likeButton" type="submit" id="likeButton" name="likeButton" value="+"></form>';
				//blitzhivemod
				if($cnfVoteMoney!="0"&&$cnfVoteMoney!="")$strButtonVote = '  <input class="likeButton" type="submit" id="likeButton" name="likeButton" title="Donar '.$cnfVoteMoney.'$ gratis " value="Donar '.$cnfVoteMoney.'$ gratis"></form>';
              }
            else if($_SESSION['iduserx'] != $repuesta->u )
              {
                if (isset($_SESSION['level']))
                    if (intval($_SESSION['level']) < intval($cnfVoteLevel))
                        $strButtonVote = '<span  id="lbVotes"><i>' . $lngAllowLevel . ' (' . $_SESSION['level'] . '<' . $cnfVoteLevel . ') ' . $lngToVote . '</i></span><br style="clear:both;">';
                    else
                        echo '<br style="clear:both;">';
              }
            echo $strButtonVote."<br>";
            if ($xr == 0 && ($cnfFacebook == "checked" || $cnfTwitter == "checked" || $cnfGooglePlus == "checked" || $cnfPinterest == "checked" || $cnfLinkedin == "checked"))
              {
                if ($cnfHashtags != "checked")
                  {
                    $hashtags     = "";
                    $datahashtags = "";
                  }
?>
<br style="clear:both">
<span id="lbVotes"><?php
                echo $lngShareIs;
?> :)</span>
<?php
                $sxr = "";
                if ($xr != 0)
                    $sxr = "#" . $xr;
                if ($cnfFacebook == "checked")
                  {
?>
<?php
echo "<a href='https://www.facebook.com/sharer/sharer.php?u=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$sxr."' title='Share in Facebook' target='_blank' ><img  title='Share in Facebook' src='".$cnfHome."hexagon-facebook.png' /></a>";
?>
<!--<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" data-href="<?php
                    echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . $sxr;
?>"></div>-->
<?php
                  }
                if ($cnfTwitter == "checked")
                  {
?>
<?php
echo "<a href='https://twitter.com/intent/tweet?text=".urlencode($repuesta->t)." ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . $sxr."&via=".$cnfTwFollow."'
target='_blank' title='Share in Twitter'>
<img src='".$cnfHome."hexagon-twitter.png' title='Share in Twitter' />
</a>";
?>
<!--
<span> </span><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php
                    echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . $sxr;
?>" data-via="<?php
                    echo $cnfTwFollow;
?>" <?php
                    echo 'data-hashtags="' . $datahashtags . '"';
?>>Tweet</a>-->
<?php
                  }
                if ($cnfGooglePlus == "checked")
                  {
?>
<!--<div class="g-plusone" data-href="<?php
                    echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . $sxr;
?>" data-annotation="inline" data-width="120"></div>-->

<?php
echo "<a href='https://plus.google.com/share?url=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . $sxr."'
target='_blank' title='Share in Google Plus'>
<img src='".$cnfHome."hexagon-googleplus.png' title='Share in Google Plus' />
</a>";
?>

<?php
                  }
                if ($cnfPinterest == "checked")
                  {
					  
echo "<a href='https://pinterest.com/pin/create/button/?url=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . $sxr."&media=&description='
target='_blank' title='Share in Pinterest'>
<img src='".$cnfHome."hexagon-pinterest.png' title='Share in Pinterest' />
</a>";					  
?>
<!--<a href="//es.pinterest.com/pin/create/button/?url=<?php
                    echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . $sxr;
?>&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
-->

<?php
                  }
                if ($cnfLinkedin == "checked")
                  {
echo "<a href='https://www.linkedin.com/shareArticle?mini=true&url=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . $sxr."&title=&summary=&source='
target='_blank' title='Share in linkedin'>
<img src='".$cnfHome."hexagon-linkedin.png' title='Share in linkedin' />
</a>";						  
					  
					  
?>
<!--<script type="IN/Share" data-url="http://google.com" data-counter="right"></script>
-->
<?php
                  }
?>
<?php

				//$cnfWhatsapp="checked";
                if ($cnfWhatsapp == "checked")
                  {
					  echo "<a href='whatsapp://send?text= ".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . $sxr."'
target='_blank' title='Share in Whatsapp'>
<img src='".$cnfHome."hexagon-whatsapp.png' title='Share in Whatsapp' />
</a>";		
?>

<!--<a href="whatsapp://send?text= <?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . $sxr;
?>" data-action="share/whatsapp/share"><img border="0" src="<?php echo $cnfHome;?>whatsapp.png" width="32px" height="32px"></a>-->
<?php
                  }
?>


<?php
              }
?>
</div>
<br style="clear:both">
<div class="boxTags">
<?php
            $strTags      = $repuesta->g;
            $hashtags     = "";
            $datahashtags = "";
            $footerTag    = "";
            $strTags      = $xml->p[0]->g;
            if ($xr == 0 && $strTags != "0" && $strTags != "")
              {
                if ($blogMode == 0)
                    echo '<a class="portadaLink" href="' . $cnfHome . $pathForumTotal . '"><b>' . $pathForumTotal . ':</b></a>';
                $strTags = explode(",", strtolower($strTags));
                for ($xf = 0; $xf < count($strTags); $xf++)
                  {
                    if ($strTags[$xf] != "")
                      {
                        echo '<a  class="portadaLink"  href="' . $cnfHome . $pathForumTotal . $auxBlog . $strLink . $cnfSubject . $strLinkCat . $strTags[$xf] . $strLinkEnd . '" title="' . $strTags[$xf] . '">' . $strTags[$xf] . '</a>';
                        $datahashtags .= "" . $strTags[$xf] . " #";
                      }
                  }
                if (substr($datahashtags, -1) == "#")
                    $datahashtags = substr($datahashtags, 0, -1);
              }
            echo "<span>" . $footerTag . "</span><br>";
?>
</div>
<?php
if($cnfRelatedSubject!=""
&& ($xr == 0 && $strTags != "0" && $strTags != "")
){
?>
<div class="RelatedTags">
<?php

    $notag        = 0;
    $strEnlaces   = "";
	if (!isset($strFilesT))
            $strFilesT = "";
    if ($handle = opendir('.'))
      {
        $xtag = 1;
        
	for ($xf = 0; $xf < count($strTags); $xf++)
      {
	$strCategoria = filter_var($strTags[$xf], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $strCategoria = urldecode($strTags[$xf]);
        while (false !== ($file = readdir($handle)))
          {
            $fechaRss = gmdate("D, d M Y H:i:s O", (int) filectime($file));
            if (filemtime($file) !== FALSE)
                $fechaRss = gmdate("D, d M Y H:i:s O", (int) filemtime($file));
				$fecha = gmdate("<b>d-M-Y</b> G:i", (int) filectime($file));
            if (filemtime($file) !== FALSE)
                $fecha = gmdate("<b>d-M-Y</b> G:i", (int) filemtime($file));
				$posVarTag = strrpos($file, "_T_");
				
            if ($file!=$w.".xml" && $file != "cache" && $file != "." && $file != ".." && 
			strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml' && strlen($file) > 6 
			&& strpos($strFilesT, utf8_encode($file) . ";", 0) === false && ($posVarTag === false||($_SESSION['iduserx'] == $cnfAdm 
			|| $_SESSION['iduserx'] == $forumMod)))
              {
				  //die($strCategoria);
                if (strpos(strtolower(utf8_encode($file)), $strCategoria, 0) !== false)
                  {           

 $thumbnail=thumbnail($file,$cnfHome,$cnfLogo,$cnfThumbnail);
				 
                        /*echo "<div class='boxForum2in'>";						*/
						echo '<a href="' . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . '"><div class="boxRelated">						
						<div class="hexagon75" style="background-image:url('.$thumbnail.');">
						<div class="hexTop75"></div>			
						<div class="hexBottom75"></div>
						</div>
						&nbsp;&nbsp;&nbsp;'. str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . '
						</div></a>';						
						
			  
/*                    echo "<a class='aFloatMessage'
href='" . $cnfHome . $pathForumTotal . $auxBlog . $strLink . utf8_encode(basename($file, ".xml") . PHP_EOL) . $strLinkEnd . "'><div class='boxRelated'><h5 class='h5Left'>". str_replace("-", " ", utf8_encode(basename($file, ".xml") . PHP_EOL)) . " </h5>&nbsp;&nbsp;Última respuesta:&nbsp;&nbsp;<time class='entry-date' datetime='" . $fechaRss . "'>" . $fecha . "</time></div></a>";*/
						$xtag++;
						//echo $xtag.">".intval($cnfRelatedSubject)."<br>";
                     if($xtag>intval($cnfRelatedSubject))break 2;
					
                  }
                //
              }
			  
			//if($xtag>intval($cnfRelatedSubject))break; 
          }//while
		  // if($xtag>intval($cnfRelatedSubject))break;
		}//for
      }
?>
</div>
<?php
}
?>
</article>
<br style="clear:both">
<?php
            $adsx++;
            if ($cnfAdsense != "" && $adsx >= intval($cnfAdsMes))
              {
                $adsx = 0;
?>
<center>
<?php
                if ($cnfAdsTitle != "")
                    echo "<br><i>" . $cnfAdsTitle . "</i><br>";
?>
<?php
                echo $cnfAdsense;
?>
</center>
<?php
              }
?>
<hr class="hrMin" />
<?php
            $strReMe = "el mensaje";
            if ($xr != 0)
                $strReMe = "la respuesta";
            if (isset($last15) && $last15 == 1 && $_SESSION['iduserx'] != $cnfAdm && $_SESSION['iduserx'] != $forumMod)
              {
				  
                $howMuch = 15 - $interMinuts;
                echo '<a id="cf' . $xr . '" title="Eliminar ' . $strReMe . '" OnMouseUP="fConfirmDelete(this.id,' . $xr . ',\'' . $cnfHome . 'w.php?q=4&t=' . $xr . '&w=' . $pathForumTotal . $auxBlog . utf8_encode(str_replace("&#39;", "\\'", $w)) . '\')"  href="#">Tienes ' . round($howMuch) . ' minutos si deseas eliminar ' . $strReMe . '</a>';
                echo '<div style="float:left;" id="dc' . $xr . '" ></div>';
              }
            else if (isset($_SESSION['iduserx']))
              {
                if ($_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod)
                  {
					  //die(addslashes($w));
                    echo '<div name="admTool" id="admTool">';
                    if ($xr == 0)
                      {
                        echo '<form class="formLite" name="formMove" method="post" action="' . $cnfHome . 'w.php?q=6&y=' . $repuesta->i . '&w=' . $pathForumTotal . $auxBlog . utf8_encode($w) . '" enctype="multipart/form-data">' . $forumSel;
                      }
                    $pin = 0;
                    if (isset($repuesta->i) && $repuesta->i == 1)
                        $pin = 1;
                    $block = 0;
                    if (isset($repuesta->l) && $repuesta->l == 1)
                        $block = 1;
                    $_SESSION['allowdelete'] = 1;
					
					//$w= addslashes($w);
					//$w=addcslashes($w, "'");
					//die("prueba ".addslashes($w));
                    echo '<a class="aTool" id="cf' . $xr . '" title="Eliminar ' . $strReMe . '" OnMouseUP="fConfirmDelete(this.id,' . $xr . ',\'' . $cnfHome . 'w.php?q=4&t=' . $xr . '&w=' . $pathForumTotal . $auxBlog . fReconvert(utf8_encode($w),1) . '&y=' . $pin . '\')"  >Eliminar ' . $strReMe . '</a>';
                    echo '<div style="float:left;" id="dc' . $xr . '" ></div>';
                    echo '<a class="aTool" id="ed' . $xr . '" title="Editar ' . $strReMe . '" OnMouseUP="fEdit(\''.fReconvert($repuesta->t,1).'\',' . $pin . ',' . $block . ',\'' . fReconvert($repuesta->g,1) . '\',this.id,' . $xr . ',\'' . $cnfHome . 'w.php?q=5&t=' . $xr . '&w=' . $pathForumTotal . $auxBlog . utf8_encode(fReconvert($w,1)) . '\')"  > Editar ' . $strReMe . '</a> ';
                    echo '</div>';
                  }
              }
          }
        $xr++;
      }
    if ($alg == 0)
        header("refresh:0;url=" . $cnfHome . $pathForumTotal . "/?" . $w);
    if (isset($_SESSION['iduserx']))
      {
        if ($repuesta->l != "1" || $_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod)
          {
?>
<form class="formBlitz2" name="answer" id="answer" method="post" action="<?php
            echo $cnfHome . "w.php?q=2&t=" . $xr . "&w=" . $pathForumTotal . $auxBlog . utf8_encode($w);
?>" enctype="multipart/form-data">
<?php
            echo $strBoxPost;
?>
<br />
<?php
            if ($_SESSION['iduserx'] == $cnfAdm || $_SESSION['iduserx'] == $forumMod)
              {
                echo '<div id="blopin" style="display:none"><input type="checkbox" name="d" id="d" value="1" >Bloqueado<input type="checkbox" name="f" id="f" value="1" >' . $lngPin . '</div>';
                if ($_SESSION['iduserx'] == $cnfAdm)
                  {
					echo ' | ' . $lngIn . ' <input type="text" style="width:20px" name="ti" id="ti" value="0"> ' . $lngHours;	
                    if ($blogMode == 0){
                        echo '<input type="checkbox" name="po" id="po" value="1" checked>' . $lngIndex;
					    
						}
						
						if($cnfAdsense != ""){
						echo '<input type="button" alt="ctrl+4" title="ctrl+4" value="adsense" onclick="addtag(\'adsense\')" style="width:65px;margin-left:10px; font-weight:bold;" />';
						//echo '<textarea id="adsenseCode" name="adsenseCode" style="display:none;" />'.$cnfAdsense.'</textarea>';
						}
                  }
				  echo ' | Index Image:<input type="text" name="poimg" id="poimg" value="">';
              }
?>
<!--<input type="text" name="w" id="w" placeholder="Title" autocomplete="off" style="width:73%;display:none;">-->
<div id="dvCont">0</div>
<!--<textarea id="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()' name="txtE"><?php
            if (isset($_SESSION['answer']))
                echo $_SESSION['answer'];
?></textarea>-->
<textarea name="hide" id="hide" style="display:none;"></textarea>
<div spellcheck="true"  contentEditable="true" id="txtE" name="txtE" onKeyUp='feChange(event)' onKeyDown='keyDownTextarea(event)' onMouseUp='feSel()' >
<?php
if (isset($_SESSION['answer']))echo $_SESSION['answer'];
?>
</div>  
<br>
<input type="text" name="u" id="u" placeholder="tag1,tag2" autocomplete="off" style="width:73%;display:none">
<input type="submit" name="submit" id="submit" value="Responder"/>
<br><?php
            echo $lngPreView;
?>
<div id="e" name="e"><?php
            if (isset($_SESSION['answer']))
                echo $_SESSION['answer'];
?></div>
<br style="clear:both">
</form>
<?php
          }
      }
?>

<?php
//$cnfFbComments="5";
if($cnfFbComments != ""&&$cnfFbComments != "0"){
	?>
	<br style="clear:both">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
<?php
//echo '<b>Comentar usando Facebook</b><br>';
echo '<div class="fb-comments" data-colorscheme="light" data-href="'.$cnfHome.$_SERVER['REQUEST_URI'].'" data-numposts="'.$cnfFbComments.'"  ></div>';

}
?>
<!--<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/hashtag/swarmintelligence" data-widget-id="115213123123">Tweets sobre #swarmintelligence</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>-->

<p><time pubdate datetime="<?php
    echo gmdate("D, d M Y H:i:s O", (int) $xml->p[$xr - 1]->a);
?>"></time></p>
<?php
  }
?>
<?php
if ($cnfGoogleSearch != "" && $cnfXGoogle == "")
  {
?>
<script>/*<![CDATA[*/(function(){var a="<?php
    echo $cnfGoogleSearch;
?>";var c=document.createElement("script");c.type="text/javascript";c.async=true;c.src=(document.location.protocol=="https:"?"https:":"http:")+"//www.google.com/cse/cse.js?cx="+a;var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(c,b)})();/*]]>*/</script>
<?php
  }
?>
<!--<?php
if ($cnfFacebook == "checked")
  {
?>
<div id="fb-root"></div>
<script>/*<![CDATA[*/(function(e,a,f){var c,b=e.getElementsByTagName(a)[0];if(e.getElementById(f)){return}c=e.createElement(a);c.id=f;c.src="//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";b.parentNode.insertBefore(c,b)}(document,"script","facebook-jssdk"));/*]]>*/</script>
<?php
  }
if ($cnfTwitter == "checked")
  {
?>
<script>!function(f,a,g){var e,b=f.getElementsByTagName(a)[0],c=/^http:/.test(f.location)?"http":"https";if(!f.getElementById(g)){e=f.createElement(a);e.id=g;e.src=c+"://platform.twitter.com/widgets.js";b.parentNode.insertBefore(e,b)}}(document,"script","twitter-wjs");</script>
<?php
  }
if ($cnfGooglePlus == "checked")
  {
?>
<script type="text/javascript">window.___gcfg={lang:"en-GB"};(function(){var a=document.createElement("script");a.type="text/javascript";a.async=true;a.src="https://apis.google.com/js/platform.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();</script>
<?php
  }
if ($cnfPinterest == "checked")
  {
?>
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
<?php
  }
if ($cnfLinkedin == "checked")
  {
?>
<script src="//platform.linkedin.com/in.js" type="text/javascript">lang:en_US;</script>
<?php
  }
?>-->

<?php

if($forumTl!=""&&$forumTl!="0")
 {
	 //echo "-->".$forumTl;
$twLine = explode("_", $forumTl);
$posTlHashTag  = strrpos($twLine[0], "#");
$posTlSearch  = strrpos($twLine[0], "search?q");
$posTlLikes  = strrpos($twLine[0], "/likes");
//$posTlUser  = strrpos($twLine, "search?q");
if($posTlSearch!==false) echo '<center><a class="twitter-timeline" href="https://twitter.com/search?q='.$twLine[0].'" data-widget-id="'.$twLine[1].'">Tweets sobre '.$twLine[0].'</a></center>';
else if($posTlHashTag!==false) echo '<center><a class="twitter-timeline" href="https://twitter.com/hashtag/'.$twLine[0].'" data-widget-id="'.$twLine[1].'">Tweets sobre #'.$twLine[0].'</a></center>';
else if($posTlLikes!==false) echo '<center><a class="twitter-timeline" href="https://twitter.com/'.$twLine[0].'/likes" data-widget-id="'.$twLine[1].'">Tweets sobre #'.$twLine[0].'</a></center>';
else echo '<center><a class="twitter-timeline" href="https://twitter.com/'.$twLine[0].'" data-widget-id="'.$twLine[1].'">Tweets de @'.$twLine[0].'</a></center>';
?>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php
}

?>
<footer>
<?php
echo $rss . ' ' . $cnfFooterText;
?>
<?php
if (isset($repuesta))
  {
    if (!isset($_SESSION['iduserx']) && $repuesta->l != "1")
      {
		  
        echo "<a href='" . $cnfHome . "login.php'>" . $lngConToWri . "</a><br>";
      }
    else if ($repuesta->l == "1")
      {
        echo $lngNotAllAns;
      }
  }
if ($blogMode == 0)include('../footer.php');
else include('footer.php');
?>
</footer>
</body>
</html>
<?php
if ($blogMode == 0)
  {
	  
    if ((($cnfCatCacheTime != "" && $cnfCatCacheTime != "0") || ($cnfForumCacheTime != "" && $cnfForumCacheTime != "0") || ($cnfMessageCacheTime != "" && $cnfMessageCacheTime != "0")) && !isset($_SESSION['iduserx']))
      {
        $cache->CacheEnd();
      }
  }
else
  {
    if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
        $cache->CacheEnd();
  }
?>