<?php
if (!isset($_SESSION)) { session_start(); }
$_SESSION['return']=$_SERVER["REQUEST_URI"];
include('config.php');
include('header.php');
$tr = 0;
if ($cnfHomeCacheTime != "")
  {
    include "cache.php";
  }
$strLink     = "";
$strLinkEnd  = "";
$strLinkUser = "?user=";
if ($cnfPermaLink == 0)
  {
    $strLinkUser = "user.php/";
    $strLink     = "user.php/";
    $strLinkEnd  = "/";
    $posVar      = strrpos($_SERVER['REQUEST_URI'], "user.php/");
  }
else if ($cnfPermaLink == 1)
  {
    $strLink    = "?m=";
    $strLinkEnd = "";
    $posVar     = strrpos($_SERVER['REQUEST_URI'], "?");
  }
else
  {
    $strLink    = "?";
    $strLinkEnd = "";
    $posVar     = strrpos($_SERVER['REQUEST_URI'], "?");
  }
function fUser($points, $user)
  {
    $strPerfil = "";
	
	if ($points == 0 )
	  {
		$strPerfil .= "Crisálida º";
		
	  }
	else if ($points < 2 )
	  {
		$strPerfil .= "Zángano 0";
		
	  }
    else if ($points < 60)
      {
        $strPerfil .= "Ninfa ()";
      }
    else if ($points < 120)
      {
        $strPerfil .= "Obrero (|)";
      }    
    else if ($points < 600)
      {
        $strPerfil .= "Soldado ([])";
      }
    else if ($points < 1000)
      {
        $strPerfil .= "Matriarca <->";       
      } 
    else if ($points >= 1000)
      {
        $strPerfil .= "Consorte <*>";        
        //echo $user."@blitzhive.com";
      }
    return $strPerfil;
  }
$strTitle = "";
if (!isset($_SESSION['enviadorecover']) && isset($_SESSION['timeSec']) && isset($_SESSION['hashSec']) && !isset($_SESSION['iduserx']))
  {
    $titulo    = '' . $lngCode . '';
    $mensaje   = $lngIntoCode . ":" . $_SESSION['hashSec'];
    $cabeceras = 'From: ' . $cnfEmail . "\r\n" . 'Reply-To: ' . $cnfEmail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    //if(mail($_SESSION['emaila'], $titulo, $mensaje, $cabeceras)){
    //if(isset($_SESSION['emaila']))
		mail($_SESSION['emaila'], $titulo, $mensaje, $cabeceras);    
    $_SESSION['enviadorecover'] = 1;
    die($lngIntoCodeEmail . ':<form id="form1" name="form1" method="post" action="' . $cnfHome . $strLink . 'user.php?w=1"  enctype="multipart/form-data">	
<input type="text" name="code" id="code" value="" /><input type="submit" name="submitCode" id="submitCode" value="' . $lngConfmCode . '" /></form>');
    /*}else{
    die("<h1>No hemos podido enviar el email :(. Pruebe más tarde por favor.</h1>");
    }*/
  }
if (isset($_POST["submitCode"]) && isset($_SESSION['timeSec']) && isset($_SESSION['hashSec']) && !isset($_SESSION['iduserx']))
  {
    if ($_SESSION['timeSec'] + 10 * 60 < time())
      {
        unset($_SESSION['timeSec']);
        unset($_SESSION['hashSec']);
        unset($_SESSION['tempUser']);
        die($lngExpCode);
      }
    else if ($_SESSION['hashSec'] == $_POST["code"])
      {
        unset($_SESSION['timeSec']);
        unset($_SESSION['hashSec']);
        $_SESSION['iduserx']  = $_SESSION['sheep'];
        $_SESSION['tempUser'] = 1;
		//unset($_SESSION['enviadorecover']);
		
        die(header("refresh:0;url=" . $cnfHome . $strLink . $_SESSION['sheep'] . $strLinkEnd));
      }
    else
      {
        unset($_SESSION['enviadorecover']);
        echo "<h1>" . $lngNotCode . "</h1>";
        die(header("refresh:2;url=" . $cnfHome . $strLink . $_SESSION['sheep'] . $strLinkEnd));
      }
  }
if (isset($_GET["user"]))
    $user = strtolower(filter_var($_GET["user"], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
else
  {
    $user = substr(rawurldecode($_SERVER['REQUEST_URI']), $posVar + 9);
    if (substr($user, -1) == "/")
        $user = substr($user, 0, -1);
  }
$strMailCheck   = "";
$strEmailChange = "";
if (isset($_POST['submitEmail']) && isset($_POST['contact']) && $_POST['contact'] != "" && isset($_SESSION['iduserx']))
  {
    $titulo    = $lngMesFrom . ' ' . $_SESSION['iduserx'];
    $mensaje   = $lngTheUser . ' ' . $cnfHome . '/user.php?user=' . $_SESSION['iduserx'] . ' ' . $lngEmailMes . ': ' . $_POST['contact'];
    $cabeceras = 'From: ' . $cnfEmail . "\r\n" . 'Reply-To: ' . $cnfEmail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    if (mail($_SESSION['emaila'], $titulo, $mensaje, $cabeceras))
      {
        $strMailCheck = "<h4>" . $lngEmailSend . " :)</h4>";
      }
    else
      {
        $strMailCheck = "<h4  class='h4Bad'>" . $lngEmailNotSend . " :(. " . $lngTry . ".</h4>";
      }
    unset($_SESSION['emaila']);
  }
else if (isset($_POST['submitCambio']))
  {
    if ($_POST['email'] != $_SESSION['emaila'])
      {
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false)
          {
            $strEmailChange = "<h4 class='h4Bad'>" . $lngNotFormat . ".</h4>";
          }
        else
          {
            if (file_exists($cnfUsers . "/" . $user[0] . ".php"))
              {
                ini_set('memory_limit', '-1');
                $contenido = file_get_contents($cnfUsers . "/" . $user[0] . ".php");
                $contenido = str_replace("," . $_SESSION['emaila'] . ",", "," . $_POST['email'] . ",", $contenido);
                file_put_contents($cnfUsers . "/" . strtolower($user[0]) . ".php", $contenido);
                $strEmailChange = "<h4>Email cambiado :)</h4>";
              }
            else
              {
                $strEmailChange = "<h4 class='h4Bad'>" . $lngUserNot . " :(</h4>";
              }
          }
      }
    else if ((isset($_SESSION['tempUser']) || $_POST["password"] != "") && $_POST["password1"] != "" && $_POST["password2"] != "")
      {
        if ($_POST["password1"] != $_POST["password2"])
          {
            $strEmailChange = "<h4 class='h4Bad'>" . $lngNotPassPair . " :(</h4>";
          }
        else
          {
            if (!isset($_SESSION['tempUser']))
                $oldPassword = sha1($_SESSION['iduserx'] . $_POST["password"]);
            ini_set('memory_limit', '-1');
            $contenido = file_get_contents($cnfUsers . "/" . $user[0] . ".php");
            if (!isset($_SESSION['tempUser']))
              {
                $posA = strpos($contenido, "," . $oldPassword . ";", 0);
              }
            else
              {
                $posAA       = strpos($contenido, $_SESSION['iduserx'] . "=", 0);
                $posAAA      = strrpos($contenido, ",", $posAA);
                $posAAAA     = strpos($contenido, ";", $posAAA);
                $lenPASS     = $posAAAA - $posAAA;
                $oldPassword = substr($contenido, $posAAA + 1, $lenPASS - 1);
                $posA        = strpos($contenido, "," . $oldPassword . ";", 0);
              }
            if ($posA !== false)
              {
                $newPassword = sha1($_SESSION['iduserx'] . $_POST["password1"]);
                $contenido   = file_get_contents($cnfUsers . "/" . $user[0] . ".php");
                $contenido   = str_replace("," . $oldPassword . ";", "," . $newPassword . ";", $contenido);
                file_put_contents($cnfUsers . "/" . strtolower($user[0]) . ".php", $contenido);
                $strEmailChange = "<h4>" . $lngChangedPass . " :)</h4>";
                unset($_SESSION['timeSec']);
                unset($_SESSION['hashSec']);
                unset($_SESSION['tempUser']);
              }
            else
              {
                $strEmailChange = "<h4 class='h4Bad'>" . $lngUserPassNot . " :(</h4>";
              }
          }
      }
    else if (!isset($_SESSION['tempUser']) && $_POST["password"] != "" && $_POST["password1"] != "")
      {
        $strEmailChange = "<h4 class='h4Bad'>" . $lngFillPass . " :(</h4>";
      }
  }
if (file_exists($cnfUsers . "/" . $user[0] . ".php"))
  {
    ini_set('memory_limit', '-1');
    $contenido = file_get_contents($cnfUsers . "/" . $user[0] . ".php");
    $posA      = strpos($contenido, $user . "=", 0);
	$intVotes=0;
    if ($posA !== false)
      {
        $posB               = strpos($contenido, "=", $posA);
        $posC               = strpos($contenido, ",", $posB);
        $len                = $posC - $posB;
        $intVotes           = substr($contenido, $posB + 1, $len - 1);
        $posD               = strpos($contenido, ",", $posC + 1);
        $lenDC              = $posD - $posC;
        $intPost            = substr($contenido, $posC + 1, $lenDC - 1);
        $posE               = strpos($contenido, ",", $posD + 1);
        $lenED              = $posE - $posD;
        $intHijos           = substr($contenido, $posD + 1, $lenED - 1);
        $posF               = strpos($contenido, ",", $posE + 1);
        $lenFE              = $posF - $posE;
        $_SESSION['emaila'] = substr($contenido, $posE + 1, $lenFE - 1);
        $posG               = strpos($contenido, ",", $posF + 1);
        $lenGF              = $posG - $posF;
        $intDate            = substr($contenido, $posF + 1, $lenGF - 1);
        $posH               = strpos($contenido, ",", $posG + 1);
        $lenHG              = $posH - $posG;
        $strPadre           = "0";
        $strPadre           = substr($contenido, $posG + 1, $lenHG - 1);
        if ($strPadre == "0")
          {
            $strPadre = "Sin padre";
          }
        $posJ   = strpos($contenido, ",", $posH + 1);
        $lenJH  = $posJ - $posH;
        $strDes = "0";
        $strDes = substr($contenido, $posH + 1, $lenJH - 1);
        if ($strDes == "0")
          {
            $strDes = "Sin descripción";
          }
		//blitzhivemod
		
		if($cnfVoteMoney!="0"&&$cnfVoteMoney!=""){
		$intVotesMoney=0;
		$intVotesMoney=$intVotes*$cnfVoteMoney;		
		$strVotesMoney="(<a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank'>Ha ganado ".$intVotesMoney."$ escribiendo y respondiendo artículos</a>)";
		}else{
		$strVotesMoney="(<a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank'>Ha ganado ".$intVotesMoney." votos escribiendo y respondiendo artículos</a>)";
		}
		
		if($cnfHijosMoney!="0"&&$cnfHijosMoney!=""){
		$intHijosMoney=0;
		$intHijosMoney=$intHijos*$cnfHijosMoney;		
		$strHijosMoney="(<a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank'>Ha ganado ".$intHijosMoney."$ mediante referidos(mayores de nivel 25)</a>)";		
		}else{
		$strHijosMoney="(<a href='http://blitzhive.com/dinero-por-contenido.php' target='_blank'>Tiene ".$intHijos." hijos(mayores de nivel 25)</a>)";
		}
		
		
		
				
		  
        $strTitle .= "<label>" . $lngSince . ":</label>" . gmdate("<b>d-M-Y</b> G:i", $intDate) . "<br>";
        $strTitle .= "<label>" . $lngVotes . ":</label><b>" . $intVotes . $strVotesMoney."</b><br>";
        $strTitle .= "<label>" . $lngMess . ":</label>" . $intPost . "<br>";
        $strTitle .= "<label>" . $lngChildren . ":</label><b>" . $intHijos . $strHijosMoney."</b><br>";
			if($cnfHijosMoney!="0"&&$cnfHijosMoney!=""){
		$strTitle .='<br><i>Link de referidos en cualquier enlace que envíes se añade automáticamente tu referencia<input type="text" onClick="this.select();" name="linkRefer" id="linkRefer" value="'.$cnfHome .'?p='.$user.'" /></i><br>';
		}
        $strTitle .= "<label>" . $lngParent . ":</label>" . $strPadre . "<br>";
        $strTitle .= "<label>" . $lngDes . ":</label>" . $strDes . "<br>";
        //&&intval($_SESSION['level'])>60
		//if (intval($_SESSION['level']) < intval($cnfVoteLevel))
			
		
		if($cnfEmailLevel=="")$cnfEmailLevel=0;
        if (isset($_SESSION['iduserx']) && $_SESSION['iduserx'] != $user && intval($_SESSION['level'])>=$cnfEmailLevel)
          {
            $strTitle .= '<form id="form1" name="form1" method="post" action="user.php?user=' . $user . '"  enctype="multipart/form-data">	
	<input type="text" name="contact" id="contact" value="Hola ' . $lngIam . ' ' . $_SESSION['iduserx'] . '" /><input type="submit" name="submitEmail" id="submitEmail" value="' . $lngSendEmail . '" /></form>' . $strMailCheck;
          }
        else if (isset($_SESSION['iduserx']) && $_SESSION['iduserx'] == $user)
          {
            $strTitle .= '<form id="form1" name="form1" method="post" action="user.php?user=' . $user . '"  enctype="multipart/form-data">	
	<label>Cambiar email:</label><input type="text" name="email" id="email" value="' . $_SESSION['emaila'] . '" /><br>';
            if (!isset($_SESSION['tempUser']))
              {
                $strTitle .= '<label>' . $lngCurrPass . '</label><input placeholder="Contraseña actual" id="password"  name="password" type="password" value=""/>
	<br>';
              }
			  
			  
            $strTitle .= '<label>' . $lngNewPass . '</label><input 
 placeholder="' . $lngNewPass . '"	id="password1"  name="password1" type="password" value=""/>
	<input  id="password2" placeholder="Repetir contraseña" name="password2" type="password" value=""/><br>
	<input type="submit" name="submitCambio" id="submitCambio" value="' . $lngChange . '" /></form>' . $strEmailChange;

          }
        /*else{
        $strTitle.='<i>Necesitas nivel <a href="'.$cnfHome.'swarm.php" title="'.$lngMoreInfoLevel.'">Omega(+320)</a> '.$lngAllowEmail.'</i>';
        }*/
      }
    else
      {
        $strTitle .= "<title>" . $user . " | " . $cnfTitle . "</title></head><body>";
        $strTitle .= "<h1>" . $lngUserNot . " :(</h1>";
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
    echo $user . " | " . $cnfTitle;
?></title>
	<?php
  }
else
  {
    $strTitle .= "<title>" . $user . " | " . $cnfTitle . "</title></head><body>";
    $strTitle .= "<h1>" . $lngUserNot . " :(</h1>";
  }
?>
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
    echo "<h4 class='h4hello'>" . $lngHi ." ". $_SESSION['iduserx'] . "</h4><a class='aLogin' href='". $cnfHome ."logout.php'>¿Salir?</a>";
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
<article class="boxPost" >
  <header >
	<?php
echo '<div style="float: left; width: 120px;">
    <div class="box-8"></div>
    <div class="box-9"><br>' . $user .'<br>
	<p class="pSubLine">';
	
if (isset($intVotes))
    echo fUser($intVotes, $user);
	echo '</div></p>
    <div class="box-10"></div>
</div>';
	?>
<br>
	<br style="clear:both;"> 
  </header>
  <section><?php
echo $strTitle;
?></section>
  <aside>
<br style="clear:both;"> 
<span>
 
 </span>
</aside>
</article>
<footer>
<?php
echo $cnfFooterText;
include('footer.php');
?>
</footer>
<?php
if ($cnfGoogleSearch != "")
  {
?>
<script>
  (function() {
    var cx = '<?php
    echo $cnfGoogleSearch;
?>';
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
<body>
<html>