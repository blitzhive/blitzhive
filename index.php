<?php
if (!isset($_SESSION)) { session_start(); }
$_SESSION['return']=$_SERVER["REQUEST_URI"];
include('config.php');
include('header.php');
$tr = 0;
if (isset($_GET['c']))
  {
    $_SESSION['cookieAdv'] = 1;
    //die(fReconvert($_GET['r']);
    die(header("refresh:0;url=" . $_GET['r']));
  }
if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
  {
    include "cache.php";
  }
$strLinkUser = "?user=";
$strLinkEnd  = "";
$strLink     = "";
$strLinkCat  = "=";
if ($cnfPermaLink == 0)
  {
    $strLinkUser = "/";
    $strLinkEnd  = "/";
    $strLinkCat  = "/";
    $strLink     = "index.php/";
  }
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
echo $cnfLanguage;
?>" />
<meta property="article:publisher" content="https://www.facebook.com/<?php
echo $cnfFbFan;
?>" />
<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="<?php
echo $cnfTwFollow;
?>"/>
<meta name="twitter:creator" content=""/>
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
</head>
<body>
<div class="box1" >
<img class="logo" title="<?php echo $cnfHeaderText;
?>" src="<?php
echo $cnfHome . $cnfLogo;
?>" />

<div class="boxAlignVertical">
<h1 class='h1Vertical'><?php
echo $cnfHeaderText;
?></h1>
<?php
if (isset($_SESSION['iduserx']))
  {
	 $_SESSION['return']="index.php";	 
    echo "<h4 class='h4hello'>" . $lngHi . " <a title='" . $lngSeeProfile . "' href='" . $cnfHome . "user.php" . $strLinkUser . $_SESSION['iduserx'] . $strLinkUser . "'>" . $_SESSION['iduserx'] . "</a></h4><a class='aLogin' href='logout.php'>¿Salir?</a>";
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
if ($arrLinks != "")
  {
    echo "<ul>";
    foreach (explode(";", $arrLinks) as $line)
      {
        $item = explode("*", $line);
        if (isset($item[1]))
          {
           // echo "<li class='liLinks'><a class='linkNav' href='" . $item[0] . "' title='" . $item[0] . "'>" . $item[1] . "</a></li>";
		   echo '
	<li class="liLinks"><div id="hexLink" class="hexagon-wrapper">		   
		<div id="color1" class="hexagon2">
	</div>
	</div><a class="linkNav" href="' . $item[0] . '" title="' . $item[0] . '">' . $item[1] . '</a></li>
	';
          }
      }
    echo "</ul>";
  }
echo '<br style="clear:both;">';
$strPaginacion="";
if (file_exists("i.xml"))
  {
    $xmlP = new DOMDocument();
    $xmlP = simplexml_load_file("i.xml");
    $rr   = 0;
    $rrr  = 0;
    if (!isset($_GET['p']))
        $_GET['p'] = 0;
    if (isset($_GET['p']))
        if (!is_numeric($_GET['p']))
            $_GET['p'] = 0;
    if ($cnfNewsFeed != "")
      {
        $next = 0;
        if (isset($_GET['p']))
            $next = $_GET['p'] + $cnfNewsFeed;
        $nnext = intval($next) + 1;
        $back  = 0;
        if (isset($_GET['p']))
            $back = $_GET['p'] - $cnfNewsFeed;
        $desde = $_GET['p'] + 1;
        if ($back >= 0 && $_GET['p'] != 0)
          {
            $strPaginacion.= "<a href='" . $cnfHome . "?p=" . $back . "'/><b><<</b> </a>";
          }
        $strPaginacion.= "<span class='pagination'>Mensajes del :[" . $desde . " al " . $next . "]</span>";
        if ($nnext > 0 && $nnext <= count($xmlP->h))
          {
            $strPaginacion.= "<a href='" . $cnfHome . "?p=" . $next . "'/><b>>></b></a>";
          }
      }
    $adsx  = 0;
    $class = 0;
    $strPaginacion.= '<br style="clear:both;">';
	echo $strPaginacion;
    foreach ($xmlP->h as $programado)
      {
		$posVarTag = strrpos($programado->t, "_T_");
        //if ($posVarTag === false
        if ($posVarTag === false &&  $rr >= $_GET['p'] && $rrr < $cnfNewsFeed)
          {
			
            $rrr++;
            $posVar         = strpos($programado->t, "/");
            $pathForumTotal = substr($programado->t, 0, $posVar);
            $pathForum      = str_replace("-", " ", $programado->t);
            if (file_exists(utf8_decode($programado->t) . ".xml"))
              {
                $xml = simplexml_load_file(utf8_decode($programado->t) . ".xml");
                if ($class == 0)
                  {
                    echo '<article class="boxPostPortada" id="0"><header class="headerIndex">';
                    $class = 1;
                  }
                else
                  {
                    echo '<article class="boxPostPortada2" id="0">
					<header class="headerIndex">';
                    $class = 0;
                  }	
				  $imgPortada="0";
				  if(isset($programado->i))$imgPortada=$programado->i;
				  //echo $lol;
				 //if($imgPortada!="0")echo '<a  title="' . $lngToMes . '" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '"/><img  class="imgPortada" src="' . $imgPortada. '" /></a>';						
				 if($imgPortada!="0"){
	
				 echo '<div class="hexagon" style="background-image:url('.$imgPortada.');">
				<div class="hexTop"></div>			
				<div class="hexBottom"></div>
				</div>';
				 }else{
				echo '<div class="hexagon" style="background-image:url('.$cnfHome . "" . $cnfLogo.');">
				<div class="hexTop"></div>			
				<div class="hexBottom"></div>
				</div>';	 
					 
				 }
				
                echo '<h2 class="h1Left"><a class="aGrayBox" title="' . $lngToMes . '" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '"/>' . $xml->p[0]->t . '</a></h2>';						
                echo '<p class="pSubLine"><b>' . $xml->p[0]->u . '</b> <time class="entry-date" datetime="' . gmdate("Y-m-d G:i", (int) $xml->p[0]->a) . '">' . gmdate("Y-m-d G:i", (int) $xml->p[0]->a) . '</time></p>';
				echo '<div class="boxTags" >';
				echo '<a class="portadaLinkIndex" href="' . $cnfHome . $pathForumTotal . '"><b>' . $pathForumTotal . ':</b></a>';
					$strTags = $xml->p[0]->g;
                   if ($strTags != "0" && $strTags != "")
                  { 
					$strTags = explode(",", strtolower($strTags));
                    for ($xf = 0; $xf < count($strTags); $xf++)
                      {
                        if ($strTags[$xf] != "")
                            echo '<a  class="portadaLinkIndex"  href="' . $cnfHome . $pathForumTotal . '/' . $strLink . $cnfSubject . $strLinkCat . $strTags[$xf] . $strLinkEnd . '" title="' . $strTags[$xf] . '">' . $strTags[$xf] . '</a>';
                      }
				  }
                    echo '</div>';
                  
                echo '</header>';
                echo '<section class="sectionPortada">';
                if ($cnfNewShort == ""){
                    echo '<p>' . $xml->p[0]->b . '</p>';
				}
                else	
				{
					echo '<p>' . substr(strip_tags ($xml->p[0]->b), 0, intval($cnfNewShort)) . '</p><center><a style="color:#848484;" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '">...' . $lngToRead . '</a></center>';	
				/*	$count1=substr_count(substr($xml->p[0]->b, 0, intval($cnfNewShort)), '<'); // 2
					$count2=substr_count(substr($xml->p[0]->b, 0, intval($cnfNewShort)), '>'); // 2
					//echo $count1."-".$count2;
					$sumCount=$count1-$count2;
					
					if($sumCount==0){	
					echo '<p>' . substr($xml->p[0]->b, 0, intval($cnfNewShort)) . '</p><center><a style="color:#848484;" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '">...' . $lngToRead . '</a></center>';	
					}else{
					$addTagFin="";	
					//echo $sumCount;
						for($re=0;$re<$sumCount;$re++)$addTagFin.="\">";
						echo '<p>' . substr($xml->p[0]->b, 0, intval($cnfNewShort)).$addTagFin.'</p><center><a style="color:#848484;" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '">...' . $lngToRead . '</a></center>';		

					}*/
					
					//else  echo '<p>' . substr($xml->p[0]->b, 0, intval($cnfNewShort)) . '</p><center><a style="color:#848484;" href="' . str_replace("/", "/" . $strLink, $programado->t) . $strLinkEnd . '">...' . $lngToRead . '</a></center>';	

				}	
                echo '</section>';          
				
				
                $adsx++;
                if ($cnfAdsense != "" && $adsx >= intval($cnfAdsIndex))
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
?>
</article>
<?php
              }
          }
        $rr++;
      }
  }
else
  {
    //no mensajes
    echo '<h6>' . $cnfSubject . '</h6>';
    if ($arrForums == "")
      {
        echo $lngNotCat;
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
            echo '<a  title="' . $arrayOfArrays[$x][1] . '" href="' . $cnfHome . fCleanChar($arrayOfArrays[$x][0]) . '"><b><h2>' . $arrayOfArrays[$x][0] . '</h2></b></a><br>';
          }
      }
  }
echo $strPaginacion;

if($cnfTwLineIndex!="")
 {
$twLine = explode("_", $cnfTwLineIndex);
echo '<center><a class="twitter-timeline" href="https://twitter.com/search?q='.$twLine[0].'" data-widget-id="'.$twLine[1].'">Tweets sobre '.$twLine[0].'</a></center>';
?>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php
}
?>

<footer>
<?php
if ($cnfNumberFeed > 0)
  {
    echo '<a title="RSS" target="_blank" href="' . $cnfHome . 'feed.php"><b>RSS</b></a> ' . $cnfFooterText;
  }
include('footer.php');
?>
</footer>
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
</body>
</html>
<?php
if ($cnfHomeCacheTime != "" && $cnfHomeCacheTime != "0" && !isset($_SESSION['iduserx']))
  {
    $cache->CacheEnd();
  }
?>