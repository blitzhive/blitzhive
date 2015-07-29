<?php
include('config.php');
include('header.php');
/*ini_set('display_startup_errors',1);
ini_set('display_errors',1);	*/
if ($_SESSION['iduserx'] != $cnfAdm)
    die(header("refresh:0;index.php"));
fProgramadas();
?>
</head>
<body>
<form id="form1" name="form1" method="post" action="admin.php"  enctype="multipart/form-data">
<?php
$file      = 'config.php';
$contenido = htmlentities(file_get_contents('config.php'));
function fUpdate($contenido, $confStr, $newStr)
  {
    $posA      = strpos($contenido, $confStr, 0);
    $posC      = strpos($contenido, "'", $posA);
    $posB      = strpos($contenido, "'", $posC + 1);
    $len       = $posB - $posC;
    $strNeww   = substr($contenido, $posC + 1, $len - 1);
    $contenido = substr_replace($contenido, $newStr, $posC + 1, $len - 1);
    return $contenido;
  }
if (isset($_POST['submitFileCss']) || isset($_POST['submitFileLogo']) || isset($_POST['submitFileFav']) || isset($_POST['submitFileJava']))
  {
    $uplFile = "";
    if (isset($_POST['submitFileCss']))
        $uplFile = "fileCss";
    else if (isset($_POST['submitFileLogo']))
        $uplFile = "fileLogo";
    else if (isset($_POST['submitFileFav']))
        $uplFile = "fileFav";
    else if (isset($_POST['submitFileJava']))
        $uplFile = "fileJava";
    //$allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp      = explode(".", $_FILES[$uplFile]["name"]);
    $extension = end($temp);
    if ($_FILES[$uplFile]["error"] > 0)
      {
        echo "Return Code: " . $_FILES[$uplFile]["error"] . "<br>";
      }
    else
      {
        /*$originales = ' ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas ='-aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $_FILES[$uplFile]["name"] = preg_replace("/[?¿!¡|*\\/:]/", "", $_FILES[$uplFile]["name"]);
        $_FILES[$uplFile]["name"]= utf8_decode($_FILES[$uplFile]["name"]);
        $_FILES[$uplFile]["name"]= strtr($_FILES[$uplFile]["name"], utf8_decode($originales), $modificadas);*/
        $_FILES[$uplFile]["name"] = fCleanChar($_FILES[$uplFile]["name"], 1);
        $xc                       = 0;
        while (file_exists($_FILES[$uplFile]["name"]))
          {
            $_FILES[$uplFile]["name"] = $xc . "-" . $_FILES[$uplFile]["name"];
            $xc++;
          }
        move_uploaded_file($_FILES[$uplFile]["tmp_name"], $_FILES[$uplFile]["name"]);
        if (isset($_POST['submitFileCss']))
            $_POST['cnfStyle'] = $_FILES[$uplFile]["name"];
        else if (isset($_POST['submitFileLogo']))
            $_POST['cnfLogo'] = $_FILES[$uplFile]["name"];
        else if (isset($_POST['submitFileFav']))
            $_POST['cnfFav'] = $_FILES[$uplFile]["name"];
        else if (isset($_POST['submitFileJava']))
            $_POST['cnfJava'] = $_FILES[$uplFile]["name"];
      }
  }
else if (isset($_POST['nameForum']) && $_POST['nameForum'] != "")
  {
    if (!isset($_POST['posForum']) || $_POST['posForum'] == "")
        $_POST['posForum'] = "0";
    $foroLimpio = fCleanChar($_POST['nameForum'], 0);
    if (!mkdir($foroLimpio, 0777, true))
      {
        echo 'No hemos podido crear el foro ' . $_POST['nameForum'];
      }
    else
      {
        if (!chmod($foroLimpio, 0777))
          {
            echo 'Foro ' . $_POST['nameForum'] . $lngNotGrant;
          }
        else
          {
            echo 'Foro ' . $_POST['nameForum'] . $lngCreated;
            $modSec = "0";
            if (isset($_POST['modForum']) && $_POST['modForum'] != "")
                $modSec = $_POST['modForum'];
            $arrForums .= $_POST['nameForum'] . "*" . $_POST['desForum'] . "*" . $_POST['posForum'] . "*" . $modSec . "*" . str_replace("-", " ", $foroLimpio) . ";";
            $contenido = fUpdate($contenido, 'arrForums', $arrForums);
            file_put_contents("config.php", html_entity_decode($contenido));
          }
        $fp = fopen($foroLimpio . "/index.php", "w+");
        fwrite($fp, '<?php include("../cat.php"); ?>');
        chmod($foroLimpio . "/index.php", 0600);
        fclose($fp);
      }
    unset($_POST['nameForum']);
  }
else if (isset($_GET['delete']))
  {
    echo '<a href="admin.php?delete2=' . $_GET['delete'] . '">' . $lngConDel . '</a>';
    unset($_GET['delete']);
  }
else if (isset($_GET['delete2']))
  {
    if (count(scandir(str_replace(" ", "-", $_GET['delete2']))) > 2)
      {
        rename(str_replace(" ", "-", $_GET['delete2']), str_replace(" ", "-", $_GET['delete2']) . '-deleted');
      }
    else
      {
        if (!rmdir(str_replace(" ", "-", utf8_decode($_GET['delete2']))))
            echo $lngNotDelCat . " " . str_replace(" ", "-", $_GET['delete2']);
        else
            echo $lngCatDel;
        //sdie($arrForums);
        $posA      = strpos($arrForums, $_GET['delete2'], 0);
        $posC      = strpos($arrForums, ";", $posA);
        $len       = $posC - $posA;
        $strNeww   = substr($arrForums, $posA, $len + 1);
        $arrForums = str_replace($strNeww, "", $arrForums);
        $contenido = fUpdate($contenido, 'arrForums', $arrForums);
      }
    unset($_GET['delete2']);
  }
else if (isset($_GET['aprove']))
  {
    if (file_exists("p.xml"))
      {
        $xmlP = simplexml_load_file("p.xml");
        foreach ($xmlP->h as $programado)
          {
            //die($programado->t."==".$_GET['aprove']);
            if ($programado->t == $_GET['aprove'])
              {
                rename($programado->t, str_replace("_T_", "", $programado->t));
                //die($programado->t.".xml");
                unset($programado[0][0]);
                break;
              }
          }
        if (count($xmlP) == 0)
            unlink("p.xml");
        else
            $xmlP->asXml("p.xml");
      }
  }
else if (isset($_GET['unaprove']))
  {
    if (file_exists("p.xml"))
      {
        $xmlP = simplexml_load_file("p.xml");
        foreach ($xmlP->h as $programado)
          {
            //echo $programado->t."==".$_GET['unaprove'];
            if ($programado->t == $_GET['unaprove'])
              {
                unlink($programado->t);
                unset($programado[0][0]);
                break;
              }
          }
        if (count($xmlP) == 0)
            unlink("p.xml");
        else
            $xmlP->asXml("p.xml");
      }
  }
else if (isset($_GET['aprovea']))
  {
    if (file_exists("m.xml"))
      {
        $xmlP = simplexml_load_file("m.xml");
        foreach ($xmlP->h as $programado)
          {
            if ($programado->t == $_GET['aprovea'])
              {
                $idAnswer             = intval($_GET['answer']);
                $xml                  = simplexml_load_file(utf8_decode($_GET['aprovea']) . ".xml");
                $xml->p[$idAnswer]->m = 0;
                $xml->asXml(utf8_decode($_GET['aprovea']) . ".xml");
                unset($programado[0][0]);
                break;
              }
          }
        if (count($xmlP) == 0)
            unlink("m.xml");
        else
            $xmlP->asXml("m.xml");
      }
  }
else if (isset($_GET['unaprovea']))
  {
    if (file_exists("m.xml"))
      {
        $xmlP = simplexml_load_file("m.xml");
        foreach ($xmlP->h as $programado)
          {
            if ($programado->t == $_GET['unaprovea'])
              {
                $idAnswer = intval($_GET['answer']);
                $xml      = simplexml_load_file(utf8_decode($_GET['unaprovea']) . ".xml");
                unset($xml->p[$idAnswer]);
                $xml->asXml(utf8_decode($_GET['unaprovea']) . ".xml");
                unset($programado[0][0]);
                break;
              }
          }
        if (count($xmlP) == 0)
            unlink("m.xml");
        else
            $xmlP->asXml("m.xml");
      }
  }
if (isset($_GET['rename']) && $_GET['rename'] != "")
  {
    $arrRename = explode("*", $_GET['rename']);
    //}
    echo '<input type="hidden" name="rename" id="rename" value="' . $_GET['rename'] . '" /><input type="text" name="rename2" id="rename2" value="' . $arrRename[0] . '" />
<input type="text" name="rename3" id="rename3" value="' . $arrRename[1] . '" /><input type="text" name="rename4" id="rename4" value="' . $arrRename[2] . '" /><input type="text" name="rename5" id="rename5" value="' . $arrRename[3] . '" /><input type="submit" name="submit" id="submit" value="' . $lngConfRen . '" />';
  }
if (isset($_POST['rename']))
  {
    $arrRename  = explode("*", $_POST['rename']);
    //die($_POST['rename']);
    $foroLimpio = fCleanChar($_POST['rename2'], 1);
    if ($_POST['rename2'] != $arrRename[0])
      {
        rename($arrRename[0], str_replace("-", " ", $foroLimpio));
        file_put_contents($arrRename[0] . "/index.php", '<?php header("HTTP/1.1 301 Moved Permanently"); header("Location: ' . $cnfHome . '/' . str_replace("-", " ", $foroLimpio) . '"); ?>');
        if (!mkdir(str_replace("-", " ", $foroLimpio), 0777, true))
          {
            echo $lngNotCreCat;
          }
        else
          {
            if (!chmod(str_replace("-", " ", $foroLimpio), 0777))
              {
                echo 'Foro ' . $_POST['nameForum'] . ' creado sin permisos';
              }
            else
              {
                echo 'Foro ' . $_POST['nameForum'] . ' creado';
              }
          }
      }
    //die( $_POST['rename']);
    //die($arrForums);
    //die($foroLimpio);
    //die($_POST['rename']."<br>");
    $arrForums = str_replace($_POST['rename'], $_POST['rename2'] . "*" . $_POST['rename3'] . "*" . $_POST['rename4'] . "*" . $_POST['rename5'] . "*" . str_replace("-", " ", $foroLimpio), $arrForums);
    //die($arrForums);
    $contenido = fUpdate($contenido, 'arrForums', $arrForums);
    file_put_contents("config.php", html_entity_decode($contenido));
    unset($_POST['rename']);
    unset($_POST['rename2']);
    unset($_POST['rename3']);
    unset($_POST['rename4']);
    unset($_POST['rename5']);
  }
else if (isset($_POST['submitBan']))
  {
    $strBanuser = $_POST['banUser'];
    $fileP      = $cnfUsers . "/" . $strBanuser[0] . ".php";
    if (file_exists($fileP))
      {
        $contenido = htmlentities(file_get_contents($fileP));
        $posA      = strpos($contenido, $strBanuser, 0);
        if ($posA === true)
          {
            $posB      = strpos($contenido, ',', $posA);
            $len       = $posB - $posA;
            $contenido = substr_replace($contenido, '"', $posA, $len);
            file_put_contents($fileP, html_entity_decode($contenido));
            echo "El usuario " . $strBanuser . " " . $lngDelAll;
          }
        else
          {
            echo "El usuario " . $strBanuser . " no existe";
          }
      }
    else
      {
        echo "El usuario " . $strBanuser . " no existe";
      }
  }
else if (isset($_POST['urlLink']) && $_POST['urlLink'] != "" && isset($_POST['anchorLink']) && $_POST['anchorLink'] != "")
  {
    if (strpos($arrLinks, $_POST['urlLink'] . "*", 0) !== false)
      {
        echo ("<h3>" . $lngStillLink . " :(</h3>");
      }
    else
      {
        if ($_POST['titleLink'] == "")
            $_POST['titleLink'] = "0";
        $arrLinks .= $_POST['urlLink'] . "*" . $_POST['anchorLink'] . "*" . $_POST['titleLink'] . ";";
        $contenido = fUpdate($contenido, 'arrLinks', $arrLinks);
        file_put_contents("config.php", html_entity_decode($contenido));
      }
  }
else if (isset($_GET['delLink']))
  {
    $arrLinks  = str_replace($_GET['delLink'] . ";", "", $arrLinks);
    $arrLinks  = str_replace($_GET['delLink'], "", $arrLinks);
    $contenido = fUpdate($contenido, 'arrLinks', $arrLinks);
    file_put_contents("config.php", html_entity_decode($contenido));
  }
else if (isset($_POST['urlLink']))
  {
    //}else {
    //die("nada");
    /**CHECKED**/
    /*$cnfModAnswerAll="";
    $cnfModAnswerLink="";
    $cnfFacebook="";
    $cnfTwitter="";
    $cnfGooglePlus="";
    $cnfPinterest="";
    $cnfLinkedin="";
    $cnfHashtags="";
    $cnfUltraSearch="";
    $cnfXGoogle="";*/
    /*if(isset($_POST['cnfHome']))$cnfHomeXX=filter_var($_POST['cnfHome'], FILTER_SANITIZE_URL);
    echo $cnfHomeXX."--".$cnfHome;
    if(isset($_POST['cnfUsers']))$cnfUsers=filter_var($_POST['cnfUsers'], FILTER_SANITIZE_URL);
    if(isset($_POST['cnfUploads']))$cnfUploads=filter_var($_POST['cnfUploads'], FILTER_SANITIZE_URL);*/
    if (isset($_POST['cnfStyle']))
        $cnfStyle = filter_var($_POST['cnfStyle'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfLogo']))
        $cnfLogo = filter_var($_POST['cnfLogo'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfFav']))
        $cnfFav = filter_var($_POST['cnfFav'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfJava']))
        $cnfJava = filter_var($_POST['cnfJava'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfLanguage']))
        $cnfLanguage = filter_var($_POST['cnfLanguage'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfTitle']))
        $cnfTitle = filter_var($_POST['cnfTitle'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfHeaderText']))
        $cnfHeaderText = filter_var($_POST['cnfHeaderText'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfFooterText']))
        $cnfFooterText = filter_var($_POST['cnfFooterText'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfDescription']))
        $cnfDescription = filter_var($_POST['cnfDescription'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfSubject']))
        $cnfSubject = filter_var($_POST['cnfSubject'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfNewShort']))
        $cnfNewShort = filter_var($_POST['cnfNewShort'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfNewsFeed']))
        $cnfNewsFeed = filter_var($_POST['cnfNewsFeed'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfNewsLevel']))
        $cnfNewsLevel = filter_var($_POST['cnfNewsLevel'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfNumberPage']))
        $cnfNumberPage = filter_var($_POST['cnfNumberPage'], FILTER_SANITIZE_SPECIAL_CHARS);
    /*if(isset($_POST['cnfNews']))$cnfNews=filter_var($_POST['cnfNews'], FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($_POST['cnfQuestions']))$cnfQuestions=filter_var($_POST['cnfQuestions'], FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($_POST['cnfTutorials']))$cnfTutorials=filter_var($_POST['cnfTutorials'], FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($_POST['cnfFiles']))$cnfFiles=filter_var($_POST['cnfFiles'], FILTER_SANITIZE_SPECIAL_CHARS);
    if(isset($_POST['cnfJustAdminNews']))$cnfJustAdminNews=filter_var($_POST['cnfJustAdminNews'], FILTER_SANITIZE_SPECIAL_CHARS);*/
    if (isset($_POST['cnfNumberFeed']))
        $cnfNumberFeed = filter_var($_POST['cnfNumberFeed'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfPermaLink']))
        $cnfPermaLink = filter_var($_POST['cnfPermaLink'], FILTER_SANITIZE_SPECIAL_CHARS);
    //security
    if (isset($_POST['cnfError']) && $_POST['cnfError'] == "checked")
        $cnfError = "checked";
    else
        $cnfError = "";
    if (isset($_POST['cnfModAnswerAll']) && $_POST['cnfModAnswerAll'] == "checked")
        $cnfModAnswerAll = "checked";
    else
        $cnfModAnswerAll = "";
    if (isset($_POST['cnfModAnswerLink']) && $_POST['cnfModAnswerLink'] == "checked")
        $cnfModAnswerLink = "checked";
    else
        $cnfModAnswerLink = "";
    if (isset($_POST['cnfCookie']) && $_POST['cnfCookie'] == "checked")
        $cnfCookie = "checked";
    else
        $cnfCookie = "";
    if (isset($_POST['cnfAdvCookie']))
        $cnfAdvCookie = filter_var($_POST['cnfAdvCookie'], FILTER_SANITIZE_SPECIAL_CHARS);
    //else $cnfAdvCookie="";
    //if(isset($_POST['cnfModAnswerLink']))$cnfModAnswerLink=filter_var($_POST['cnfModAnswerLink'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfModAnswerLevel']))
        $cnfModAnswerLevel = filter_var($_POST['cnfModAnswerLevel'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfSpamKey']))
        $cnfSpamKey = filter_var($_POST['cnfSpamKey'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfVoteLevel']))
        $cnfVoteLevel = filter_var($_POST['cnfVoteLevel'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfQuestion1']))
        $cnfQuestion1 = filter_var($_POST['cnfQuestion1'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAnswer1']))
        $cnfAnswer1 = filter_var($_POST['cnfAnswer1'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfQuestion2']))
        $cnfQuestion2 = filter_var($_POST['cnfQuestion2'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAnswer2']))
        $cnfAnswer2 = filter_var($_POST['cnfAnswer2'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdm']))
        $cnfAdm = filter_var($_POST['cnfAdm'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfMax']))
        $cnfMax = filter_var($_POST['cnfMax'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfExt']))
        $cnfExt = filter_var($_POST['cnfExt'], FILTER_SANITIZE_SPECIAL_CHARS);
    //if(isset($_POST['cnfMod']))$cnfMod=filter_var($_POST['cnfMod'], FILTER_SANITIZE_SPECIAL_CHARS);
    //email
    if (isset($_POST['cnfEmail']))
        $cnfEmail = filter_var($_POST['cnfEmail'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfEmailNombre']))
        $cnfEmailNombre = filter_var($_POST['cnfEmailNombre'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfRegMailSubjetct']))
        $cnfRegMailSubjetct = filter_var($_POST['cnfRegMailSubjetct'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfRegMailHeader']))
        $cnfRegMailHeader = filter_var($_POST['cnfRegMailHeader'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfRegMailFooter']))
        $cnfRegMailFooter = filter_var($_POST['cnfRegMailFooter'], FILTER_SANITIZE_SPECIAL_CHARS);
    //seo
    if (isset($_POST['cnfKeywords']))
        $cnfKeywords = filter_var($_POST['cnfKeywords'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfMetaDescription']))
        $cnfMetaDescription = filter_var($_POST['cnfMetaDescription'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfFacebook']))
        $cnfFacebook = "checked";
    else
        $cnfFacebook = "";
    if (isset($_POST['cnfTwitter']) && $_POST['cnfTwitter'] == "checked")
        $cnfTwitter = "checked";
    else
        $cnfTwitter = "";
    if (isset($_POST['cnfGooglePlus']) && $_POST['cnfGooglePlus'] == "checked")
        $cnfGooglePlus = "checked";
    else
        $cnfGooglePlus = "";
    if (isset($_POST['cnfPinterest']) && $_POST['cnfPinterest'] == "checked")
        $cnfPinterest = "checked";
    else
        $cnfPinterest = "";
    if (isset($_POST['cnfLinkedin']) && $_POST['cnfLinkedin'] == "checked")
        $cnfLinkedin = "checked";
    else
        $cnfLinkedin = "";
    if (isset($_POST['cnfFbFan']))
        $cnfFbFan = filter_var($_POST['cnfFbFan'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfTwFollow']))
        $cnfTwFollow = filter_var($_POST['cnfTwFollow'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfytChannel']))
        $cnfytChannel = filter_var($_POST['cnfytChannel'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfGoogleAuthor']))
        $cnfGoogleAuthor = filter_var($_POST['cnfGoogleAuthor'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfGoogleInsignia']))
        $cnfGoogleInsignia = filter_var($_POST['cnfGoogleInsignia'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfHashtags']) && $_POST['cnfHashtags'] == "checked")
        $cnfHashtags = "checked";
    else
        $cnfHashtags = "";
    //if(isset($_POST['cnfHashtags']))$cnfHashtags=filter_var($_POST['cnfHashtags'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfHomeCacheTime']))
        $cnfHomeCacheTime = filter_var($_POST['cnfHomeCacheTime'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfForumCacheTime']))
        $cnfForumCacheTime = filter_var($_POST['cnfForumCacheTime'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfMessageCacheTime']))
        $cnfMessageCacheTime = filter_var($_POST['cnfMessageCacheTime'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfCatCacheTime']))
        $cnfCatCacheTime = filter_var($_POST['cnfCatCacheTime'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfUltraSearch']) && $_POST['cnfUltraSearch'] == "checked")
        $cnfUltraSearch = "checked";
    else
        $cnfUltraSearch = "";
    //if(isset($_POST['cnfUltraSearch']))$cnfUltraSearch=filter_var($_POST['cnfUltraSearch'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAnalytics']))
        $cnfAnalytics = filter_var($_POST['cnfAnalytics'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfTrack']))
        $cnfTrack = filter_var($_POST['cnfTrack'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfGoogleSearch']))
        $cnfGoogleSearch = filter_var($_POST['cnfGoogleSearch'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsense']))
        $cnfAdsense = filter_var($_POST['cnfAdsense'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsTitle']))
        $cnfAdsTitle = filter_var($_POST['cnfAdsTitle'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsIndex']))
        $cnfAdsIndex = filter_var($_POST['cnfAdsIndex'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsCat']))
        $cnfAdsCat = filter_var($_POST['cnfAdsCat'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsMes']))
        $cnfAdsMes = filter_var($_POST['cnfAdsMes'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfAdsTag']))
        $cnfAdsTag = filter_var($_POST['cnfAdsTag'], FILTER_SANITIZE_SPECIAL_CHARS);
    if (isset($_POST['cnfXGoogle']) && $_POST['cnfXGoogle'] == "checked")
        $cnfXGoogle = "checked";
    else
        $cnfXGoogle = "";
    //if(isset($_POST['cnfXGoogle']))$cnfXGoogle=filter_var($_POST['cnfXGoogle'], FILTER_SANITIZE_SPECIAL_CHARS);
    //$contenido = file_get_contents('config.php');
    /*$contenido=fUpdate($contenido,'cnfHome',$cnfHomeXX);
    $contenido=fUpdate($contenido,'cnfUsers',$cnfUsers);
    $contenido=fUpdate($contenido,'cnfUploads',$cnfUploads);*/
    $contenido = fUpdate($contenido, 'cnfStyle', $cnfStyle);
    $contenido = fUpdate($contenido, 'cnfLogo', $cnfLogo);
    $contenido = fUpdate($contenido, 'cnfFav', $cnfFav);
    $contenido = fUpdate($contenido, 'cnfJava', $cnfJava);
    $contenido = fUpdate($contenido, 'cnfLanguage', $cnfLanguage);
    $contenido = fUpdate($contenido, 'cnfTitle', $cnfTitle);
    $contenido = fUpdate($contenido, 'cnfHeaderText', $cnfHeaderText);
    $contenido = fUpdate($contenido, 'cnfFooterText', $cnfFooterText);
    $contenido = fUpdate($contenido, 'cnfDescription', $cnfDescription);
    $contenido = fUpdate($contenido, 'cnfSubject', $cnfSubject);
    $contenido = fUpdate($contenido, 'cnfNewShort', $cnfNewShort);
    $contenido = fUpdate($contenido, 'cnfNewsFeed', $cnfNewsFeed);
    $contenido = fUpdate($contenido, 'cnfNewslevel', $cnfNewslevel);
    $contenido = fUpdate($contenido, 'cnfNumberPage', $cnfNumberPage);
    /*$contenido=fUpdate($contenido,'cnfNews',$cnfNews);
    $contenido=fUpdate($contenido,'cnfQuestions',$cnfQuestions);
    $contenido=fUpdate($contenido,'cnfTutorials',$cnfTutorials);
    $contenido=fUpdate($contenido,'cnfFiles',$cnfFiles);
    $contenido=fUpdate($contenido,'cnfJustAdminNews',$cnfJustAdminNews);*/
    $contenido = fUpdate($contenido, 'cnfNumberFeed', $cnfNumberFeed);
    $contenido = fUpdate($contenido, 'cnfPermaLink', $cnfPermaLink);
    //security
    $contenido = fUpdate($contenido, 'cnfError', $cnfError);
    $contenido = fUpdate($contenido, 'cnfModAnswerAll', $cnfModAnswerAll);
    $contenido = fUpdate($contenido, 'cnfModAnswerLink', $cnfModAnswerLink);
    $contenido = fUpdate($contenido, 'cnfCookie', $cnfCookie);
    $contenido = fUpdate($contenido, 'cnfAdvCookie', $cnfAdvCookie);
    $contenido = fUpdate($contenido, 'cnfModAnswerLevel', $cnfModAnswerLevel);
    $contenido = fUpdate($contenido, 'cnfSpamKey', $cnfSpamKey);
    $contenido = fUpdate($contenido, 'cnfVoteLevel', $cnfVoteLevel);
    $contenido = fUpdate($contenido, 'cnfQuestion1', $cnfQuestion1);
    $contenido = fUpdate($contenido, 'cnfAnswer1', $cnfAnswer1);
    $contenido = fUpdate($contenido, 'cnfQuestion2', $cnfQuestion2);
    $contenido = fUpdate($contenido, 'cnfAnswer2', $cnfAnswer2);
    $contenido = fUpdate($contenido, 'cnfAdm', $cnfAdm);
    $contenido = fUpdate($contenido, 'cnfMax', $cnfMax);
    $contenido = fUpdate($contenido, 'cnfExt', $cnfExt);
    //email
    $contenido = fUpdate($contenido, 'cnfEmail', $cnfEmail);
    $contenido = fUpdate($contenido, 'cnfEmailNombre', $cnfEmailNombre);
    $contenido = fUpdate($contenido, 'cnfRegMailSubjetct', $cnfRegMailSubjetct);
    $contenido = fUpdate($contenido, 'cnfRegMailHeader', $cnfRegMailHeader);
    $contenido = fUpdate($contenido, 'cnfRegMailFooter', $cnfRegMailFooter);
    //seo
    $contenido = fUpdate($contenido, 'cnfKeywords', $cnfKeywords);
    $contenido = fUpdate($contenido, 'cnfMetaDescription', $cnfMetaDescription);
    //social
    $contenido = fUpdate($contenido, 'cnfFacebook', $cnfFacebook);
    $contenido = fUpdate($contenido, 'cnfTwitter', $cnfTwitter);
    $contenido = fUpdate($contenido, 'cnfGooglePlus', $cnfGooglePlus);
    $contenido = fUpdate($contenido, 'cnfPinterest', $cnfPinterest);
    $contenido = fUpdate($contenido, 'cnfLinkedin', $cnfLinkedin);
    $contenido = fUpdate($contenido, 'cnfFbFan', $cnfFbFan);
    $contenido = fUpdate($contenido, 'cnfTwFollow', $cnfTwFollow);
    $contenido = fUpdate($contenido, 'cnfytChannel', $cnfytChannel);
    $contenido = fUpdate($contenido, 'cnfGoogleAuthor', $cnfGoogleAuthor);
    $contenido = fUpdate($contenido, 'cnfGoogleInsignia', $cnfGoogleInsignia);
    $contenido = fUpdate($contenido, 'cnfHashtags', $cnfHashtags);
    $contenido = fUpdate($contenido, 'cnfHomeCacheTime', $cnfHomeCacheTime);
    $contenido = fUpdate($contenido, 'cnfForumCacheTime', $cnfForumCacheTime);
    $contenido = fUpdate($contenido, 'cnfMessageCacheTime', $cnfMessageCacheTime);
    $contenido = fUpdate($contenido, 'cnfCatCacheTime', $cnfCatCacheTime);
    $contenido = fUpdate($contenido, 'cnfUltraSearch', $cnfUltraSearch);
    $contenido = fUpdate($contenido, 'cnfAnalytics', $cnfAnalytics);
    $contenido = fUpdate($contenido, 'cnfTrack', $cnfTrack);
    $contenido = fUpdate($contenido, 'cnfGoogleSearch', $cnfGoogleSearch);
    $contenido = fUpdate($contenido, 'cnfAdsense', $cnfAdsense);
    $contenido = fUpdate($contenido, 'cnfAdsTitle', $cnfAdsTitle);
    $contenido = fUpdate($contenido, 'cnfAdsIndex', $cnfAdsIndex);
    $contenido = fUpdate($contenido, 'cnfAdsCat', $cnfAdsCat);
    $contenido = fUpdate($contenido, 'cnfAdsMes', $cnfAdsMes);
    $contenido = fUpdate($contenido, 'cnfAdsTag', $cnfAdsTag);
    $contenido = fUpdate($contenido, 'cnfXGoogle', $cnfXGoogle);
    $contenido = fUpdate($contenido, 'cnfHome', $cnfHome);
    /*echo $cnfHome;
    die($contenido);*/
    file_put_contents("config.php", html_entity_decode($contenido));
  }
?>

<input type="submit" name="submit" id="submit" value="Save" /><br>
<div class='boxConfig'>
<h3><?php
echo $lngPath;
?> </h3>
<label><?php
echo $lngHomeUrl;
?></label><input id="cnfHome" name="cnfHome"  value="<?php
echo $cnfHome;
?>" type="text" disabled/><br>
<label><?php
echo $lngUserFold;
?></label><input id="cnfUsers" name="cnfUsers"  value="<?php
echo $cnfUsers;
?>" type="text" disabled/><br>
<label><?php
echo $lngUpFold;
?></label><input id="cnfUploads" name="cnfUploads"  value="<?php
echo $cnfUploads;
?>" type="text" disabled/><br>
<h3><?php
echo $lngStyDe;
?></h3>
<label><?php
echo $lngStyCss;
?></label><input id="cnfStyle" name="cnfStyle"  value="<?php
echo $cnfStyle;
?>" type="text"/><input style="float:left;" type="file" name="fileCss" id="fileCss"><input type="submit" name="submitFileCss" id="submitFile" value="Subir Css"><br>
<label><?php
echo $lngLogo;
?></label><input id="cnfLogo" name="cnfLogo"  value="<?php
echo $cnfLogo;
?>" type="text"/><input style="float:left;" type="file" name="fileLogo" id="fileLogo"><input type="submit" name="submitFileLogo" id="submitFile" value="Subir Css"><br>
<label><?php
echo $lngFavicon;
?></label><input id="cnfFav" name="cnfFav"  value="<?php
echo $cnfFav;
?>" type="text"/><input style="float:left;" type="file" name="fileFav" id="fileFav"><input type="submit" name="submitFileFav" id="submitFile" value="Subir Css"><br>
<label><?php
echo "javascript";
?></label><input id="cnfJava" name="cnfJava"  value="<?php
echo $cnfJava;
?>" type="text"/><input style="float:left;" type="file" name="fileJava" id="fileJava"><input type="submit" name="submitFileJav" id="submitFile" value="Subir Javascript"><br>
<label><?php
echo "Idioma";
?></label><input type="radio" name="cnfLanguage" value="es-ES" <?php
if ($cnfLanguage == "es-ES")
    echo "checked";
?>>Español
<input type="radio" name="cnfLanguage"  value="en-US" <?php
if ($cnfLanguage == "en-US")
    echo "checked";
?>>English<br>

<label><?php
echo $lngTitle;
?></label><input id="cnfTitle" name="cnfTitle" value="<?php
echo $cnfTitle;
?>" type="text"/><br>
<label><?php
echo $lngHeader;
?></label><input id="cnfHeaderText" name="cnfHeaderText" value="<?php
echo $cnfHeaderText;
?>" type="text"/><br>
<label><?php
echo $lngFooter;
?></label><input id="cnfFooterText" name="cnfFooterText" value="<?php
echo $cnfFooterText;
?>" type="text"/><br>
<label><?php
echo $lngMesPer;
?></label><input type="text" name="cnfNumberPage" value="<?php
echo $cnfNumberPage;
?>" ><br><br>
<label><?php
echo $lngDesText;
?></label><input id="cnfDescription" name="cnfDescription" value="<?php
echo $cnfDescription;
?>" type="text"/><br>
<label><?php
echo $lngTagText;
?></label><input id="cnfSubject" name="cnfSubject" value="<?php
echo $cnfSubject;
?>" type="text"/><br>
<h3><?php
echo $lngIndex;
?></h3>
<label><?php
echo $lngShorTo;
?></label><input id="cnfNewShort" name="cnfNewShort" value="<?php
echo $cnfNewShort;
?>" type="text" /><br>
<label><?php
echo $lngMesIndex;
?></label><input id="cnfNewsFeed" name="cnfNewsFeed" value="<?php
echo $cnfNewsFeed;
?>" type="text" /><br><br>
<label><?php
echo $lngAllIndex;
?></label><input id="cnfNewsLevel" name="cnfNewsLevel" value="<?php
echo $cnfNewsLevel;
?>" type="text" /><br>
<?php
if (file_exists("i.xml"))
  {
    $xmlP = new DOMDocument();
    $xmlP = simplexml_load_file("i.xml");
    //$contar=$xmlP->h-count();
    //echo "-->".count($xmlP);	
    foreach ($xmlP->h as $programado)
      {
        echo $programado->t;
        echo '<a href="admin.php?unaprove=' . $programado->t . '">' . $lngDelete2 . '</a><br/>';
      }
    if (count($xmlP) == 0)
        unlink("i.xml");
  }
?>

<!--<input type="checkbox" name="cnfNews" value="checked" <?php
echo $cnfNews;
?>>Noticias
<input type="checkbox" name="cnfQuestions" value="checked" <?php
echo $cnfQuestions;
?>>Preguntas
<input type="checkbox" name="cnfTutorials" value="checked" <?php
echo $cnfTutorials;
?>>Tutoriales
<input type="checkbox" name="cnfFiles" value="checked" <?php
echo $cnfFiles;
?>>Files
<input type="checkbox" name="cnfJustAdminNews" value="checked" <?php
echo $cnfJustAdminNews;
?>>Automático solo creadas por el admin.-->
<br>
<label><?php
echo $lngPerFeed;
?></label><input type="text" name="cnfNumberFeed" value="<?php
echo $cnfNumberFeed;
?>" > <i> <?php
echo $lngDesFeed;
?></i><br>
<label><?php
echo $lngPerma;
?></label><input type="radio" name="cnfPermaLink" value="0" <?php
if ($cnfPermaLink == 0)
    echo "checked";
?>>/index.php/mensaje-1
<input type="radio" name="cnfPermaLink"  value="1" <?php
if ($cnfPermaLink == 1)
    echo "checked";
?>>/?m=mensaje-1<br>
<!--<input type="radio" name="cnfPermaLink" value="2" <?php
if ($cnfPermaLink == 0)
    echo "checked";
?>>/?mensaje-1<i>No indexa la primera palabra en Google</i>-->
<h3>Links</h3>
<?php
if ($arrLinks == "")
  {
    echo $lngNotLinks . "</br>";
  }
else
  {
    $arrayOfArrays = array();
    $xx            = 0;
    foreach (explode(";", $arrLinks) as $line)
      {
        $item = explode("*", $line);
        if (isset($item[1]))
          {
            $arrayOfArrays[] = $item;
          }
        $xx++;
      }
    /*usort($arrayOfArrays , function($a, $b) {
    return $a[2]- $b[2];
    });	*/
    for ($x = 0; $x < count($arrayOfArrays); $x++)
      {
?>
	<input id="urlLink" value='<?php
        echo $arrayOfArrays[$x][0];
?>' type="text"/><input id="anchorLink" value='<?php
        echo $arrayOfArrays[$x][1];
?>' type="text"/><input id="titleLink" value='<?php
        echo $arrayOfArrays[$x][2];
?>' type="text"/>
	<a href="admin.php?delLink=<?php
        echo $arrayOfArrays[$x][0] . "*" . $arrayOfArrays[$x][1] . "*" . $arrayOfArrays[$x][2];
?>">Delete</a><br/>
	<?php
      }
  }
?>
<label><?php
echo $lngAddLink;
?></label><input id="urlLink" name="urlLink" value='' type="text"/><input id="anchorLink" name="anchorLink" value='' type="text"/><input id="titleLink" name="titleLink" value='0' type="text"/><input type="submit" name="submit" id="submit" value="Add" /></div>
</div>
<div class='boxConfig'>
<h3>Forum</h3><br>
<!--<input value='Nombre' type="text"/><input value='Descripcion' type="text"/><input value='Position' type="text"/><br>-->
<?php
if ($arrForums == "")
  {
    echo $lngNotCat . "</br>";
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
    /*usort($arrayOfArrays , function($a, $b) {
    return $a[2]- $b[2];
    });	5.3*/
    for ($x = 0; $x < count($arrayOfArrays); $x++)
      {
?>
	<input id="forum1" value='<?php
        echo $arrayOfArrays[$x][0];
?>' type="text"/><input id="forumDes" value='<?php
        echo $arrayOfArrays[$x][1];
?>' type="text"/><input id="forumPos" value='<?php
        echo $arrayOfArrays[$x][2];
?>' type="text"/>
	<input id="forumMod" value='<?php
        echo $arrayOfArrays[$x][3];
?>' type="text"/><a href="admin.php?rename=<?php
        echo $arrayOfArrays[$x][0] . "*" . $arrayOfArrays[$x][1] . "*" . $arrayOfArrays[$x][2] . "*" . $arrayOfArrays[$x][3] . "*" . $arrayOfArrays[$x][4];
?>">Editar</a>
	<a href="admin.php?delete=<?php
        echo $arrayOfArrays[$x][0];
?>">Delete</a><br/>
	<?php
      }
  }
?>

<label>Add Forum</label><input id="nameForum" name="nameForum" value='' type="text"/><input id="desForum" name="desForum" value='' type="text"/><input id="posForum" name="posForum" value='0' type="text"/><input id="modForum" name="modForum" value='0' type="text"/><input type="submit" name="submit" id="submit" value="Add" />
<h3>Mensajes</h3>
<?php
if (file_exists("p.xml"))
  {
    $xmlP = new DOMDocument();
    $xmlP = simplexml_load_file("p.xml");
    //$contar=$xmlP->h-count();
    //echo "-->".count($xmlP);	
    foreach ($xmlP->h as $programado)
      {
        echo $programado->t . " " . $lngSchudel . " " . gmdate("d/M/Y G:i", (int) $programado->p);
        echo '<a href="admin.php?aprove=' . $programado->t . '">' . $lngAprove . '</a>';
        echo '<a href="admin.php?unaprove=' . $programado->t . '">' . $lngDelete2 . '</a><br/>';
      }
    if (count($xmlP) == 0)
        unlink("p.xml");
  }
?>
<h3>Respuestas</h3>
<?php
if (file_exists("m.xml"))
  {
    $xmlP = new DOMDocument();
    $xmlP = simplexml_load_file("m.xml");
    foreach ($xmlP->h as $programado)
      {
        //echo $programado->t." respuesta ".$programado->p;
        echo '<a href="' . $cnfHome . str_replace("/", "/index.php/", $programado->t) . "/#" . $programado->p . '" target="_blank">' . $programado->t . '</a>';
        echo '<a href="admin.php?aprovea=' . $programado->t . '&answer=' . $programado->p . '">' . $lngAprove . '</a>';
        echo '<a href="admin.php?unaprovea=' . $programado->t . '&answer=' . $programado->p . '">' . $lngDelete2 . '</a><br/>';
      }
    if (count($xmlP) == 0)
        unlink("m.xml");
  }
?>
</div>
<div class='boxConfig' >
<h3><?php
echo $lngSecMail;
?></h3>
<h4><?php
echo $lngSpam;
?></h4>
<input type="checkbox" name="cnfError" value="checked" <?php
echo $cnfError;
?>><?php
echo "Mostrar errores php";
?><br>
<input type="checkbox" name="cnfModAnswerAll" value="checked" <?php
echo $cnfModAnswerAll;
?>><?php
echo $lngModAll;
?>
<input type="checkbox" name="cnfModAnswerLink" value="checked" <?php
echo $cnfModAnswerLink;
?>><?php
echo $lngModLink;
?><br>
<label><?php
echo $lngModLevel;
?> </label><input id="cnfModAnswerLevel" name="cnfModAnswerLevel" value='<?php
echo $cnfModAnswerLevel;
?>' type="text"/><br><br>
<label><?php
echo $lngBanUser;
?>:</label> <input name="banUser" value='' type="text"/><input type="submit" name="submitBan" id="submitFile" value="BAN"><br>
<label><?php
echo $lngSpamWords;
?>:</label> <input id="cnfSpamKey" name="cnfSpamKey" value='<?php
echo $cnfSpamKey;
?>' type="text"/><br>
<label><?php
echo "Vote >(level)";
?>:</label> <input id="cnfVoteLevel" name="cnfVoteLevel" value='<?php
echo $cnfVoteLevel;
?>' type="text"/><br>
<input type="checkbox" name="cnfCookie" value="checked" <?php
echo $cnfCookie;
?>><?php
echo $lngCookies;
?><br>
<label><?php
echo $lngCookiesAdv;
?></label><input type="text" name="cnfAdvCookie" id="cnfAdvCookie" value='<?php
echo $cnfAdvCookie;
?>' />
<h4><?php
echo $lngSecurity;
?></h4>
<label><?php
echo $lngSecQues;
?> 1:</label> <input id="cnfQuestion1" name="cnfQuestion1" value='<?php
echo $cnfQuestion1;
?>' type="text"/><br>
<label><?php
echo $lngSecAns;
?> 1:</label> <input id="cnfAnswer1" name="cnfAnswer1" value='<?php
echo $cnfAnswer1;
?>' type="text"/><br>
<label><?php
echo $lngSecQues;
?> 2:</label> <input id="cnfQuestion2" name="cnfQuestion2" value='<?php
echo $cnfQuestion2;
?>' type="text"/><br>
<label><?php
echo $lngSecAns;
?> 2:</label> <input id="cnfAnswer2" name="cnfAnswer2" value='<?php
echo $cnfAnswer2;
?>' type="text"/><br><br>
<label><?php
echo $lngAdminUser;
?>:</label> <input id="cnfAdm" name="cnfAdm" value='<?php
echo $cnfAdm;
?>' type="text"/><br>
<label><?php
echo $lngUpMaxDes;
?>:</label> <input id="cnfMax" name="cnfMax" value='<?php
echo $cnfMax;
?>' type="text"/><br>
<label><?php
echo $lngUpExt;
?>:</label> <input id="cnfExt" name="cnfExt" value='<?php
echo $cnfExt;
?>' type="text"/><br>
<h4><?php
echo $lngEmailTit;
?></h4>
<label><?php
echo $lngEmailFrom;
?>:</label> <input id="cnfEmail" name="cnfEmail" value='<?php
echo $cnfEmail;
?>' type="text"/><br>
<label><?php
echo $lngEmailName;
?>:</label> <input id="cnfEmailNombre" name="cnfEmailNombre" value='<?php
echo $cnfEmailNombre;
?>' type="text"/><br>
<label><?php
echo $lngWellSub;
?>:</label> <input id="cnfRegMailSubjetct" name="cnfRegMailSubjetct" value='<?php
echo $cnfRegMailSubjetct;
?>' type="text"/><br>
<label><?php
echo $lngWwllHead;
?>:</label> <input id="cnfRegMailHeader" name="cnfRegMailHeader" value='<?php
echo $cnfRegMailHeader;
?>' type="text"/><br><br>
<label><?php
echo $lngWellFoot;
?>:</label> <input id="cnfRegMailFooter" name="cnfRegMailFooter" value='<?php
echo $cnfRegMailFooter;
?>' type="text"/><br>
</div>
<div class='boxConfig' >
<h3><?php
echo $lngSoSeo;
?></h3>
<label><?php
echo $lngKeyWords;
?>:</label> <input id="cnfKeywords" name="cnfKeywords" value='<?php
echo $cnfKeywords;
?>' type="text"/><br>
<label><?php
echo $lngMetaDes;
?>:</label> <input id="cnfMetaDescription" name="cnfMetaDescription" value='<?php
echo $cnfMetaDescription;
?>' type="text"/><br>
<label><?php
echo $lngSocBut;
?>:</label>
<input type="checkbox" name="cnfFacebook" value="checked" <?php
echo $cnfFacebook;
?>>Facebook
<input type="checkbox" name="cnfTwitter" value="checked" <?php
echo $cnfTwitter;
?>>Twitter
<input type="checkbox" name="cnfGooglePlus" value="checked" <?php
echo $cnfGooglePlus;
?>>Google Plus +1
<input type="checkbox" name="cnfPinterest" value="checked" <?php
echo $cnfPinterest;
?>>Pinterest
<input type="checkbox" name="cnfLinkedin" value="checked" <?php
echo $cnfLinkedin;
?>>Linkedin<br><br><br>
<label>Facebook Fan Page:</label> <input id="fbFanPage" name="cnfFbFan" value='<?php
echo $cnfFbFan;
?>' type="text"/><br>
<label>Twitter @follow:</label> <input id="twFollow" name="cnfTwFollow" value='<?php
echo $cnfTwFollow;
?>' type="text"/><br>
<label><?php
echo $lngYouChan;
?>:</label> <input id="ytChannel" name="cnfytChannel" value='<?php
echo $cnfytChannel;
?>' type="text"/><br>
<label><?php
echo $lngGooAuth;
?>:</label> <input id="gpAuthor" name="cnfGoogleAuthor" value='<?php
echo $cnfGoogleAuthor;
?>' type="text"/><br>
<label><?php
echo $lngGooInsi;
?>:</label> <input id="gpInsignia" name="cnfGoogleInsignia" value='<?php
echo $cnfGoogleInsignia;
?>' type="text"/><br>
<input type="checkbox" name="cnfHashtags" value="checked" <?php
echo $cnfHashtags;
?>><?php
echo $lngAddCatHash;
?>
<br>
<h4><?php
echo $lngCachOpt;
?></h4>
<label><?php
echo $lngCachHome;
?>:</label> <?php
echo $lngRefresh;
?> <input type="text" name="cnfHomeCacheTime" value="<?php
echo $cnfHomeCacheTime;
?>" > <?php
echo $lngSeconds;
?>.<i> <?php
echo $lng24Hours;
?></i>
<br>
<label><?php
echo $lngCachCat;
?>:</label> <?php
echo $lngRefresh;
?> <input type="text" name="cnfForumCacheTime" value="<?php
echo $cnfForumCacheTime;
?>" > <?php
echo $lngSeconds;
?>.<i> <?php
echo $lng1hour;
?></i>
<br>
<label><?php
echo $lngCachMes;
?>:</label> <?php
echo $lngRefresh;
?> <input type="text" name="cnfMessageCacheTime" value="<?php
echo $cnfMessageCacheTime;
?>" > <?php
echo $lngSeconds;
?>.<i> <?php
echo $lng3hours;
?></i>
<br>
<label><?php
echo $lngCachTag;
?>:</label> <?php
echo $lngRefresh;
?> <input type="text" name="cnfCatCacheTime" value="<?php
echo $cnfCatCacheTime;
?>" > <?php
echo $lngSeconds;
?>.<i> <?php
echo $lng24Hours;
?></i>
<br>
<input type="checkbox" name="cnfUltraSearch" value="checked" <?php
echo $cnfUltraSearch;
?>><?php
echo $lngHard404;
?>.<br>
<h4><?php
echo $lngExtTool;
?></h4>
<label><?php
echo $lngAnalytic;
?>:</label><input type="text" name="cnfAnalytics" value="<?php
echo $cnfAnalytics;
?>" >
<br><br>
<label><?php
echo $lngSearchCode;
?>:</label><input type="text" name="cnfGoogleSearch" value="<?php
echo $cnfGoogleSearch;
?>" ><br><br>
<input type="checkbox" name="cnfXGoogle" id="cnfXGoogle"  value="checked" <?php
echo $cnfXGoogle;
?>><?php
echo $lngExtSearch;
?>.<br>
<label><?php
echo "código adsense";
?>:</label><textarea id="cnfAdsense" onMouseUp='feSel()'  name="cnfAdsense"><?php
echo $cnfAdsense;
?></textarea><br>
<label><?php
echo "¿Título?";
?>:</label><input type="text" name="cnfAdsTitle" value="<?php
echo $cnfAdsTitle;
?>" ><br>
<label><?php
echo "En el index";
?>:</label><input type="text" name="cnfAdsIndex" value="<?php
echo $cnfAdsIndex;
?>" ><br>
<label><?php
echo "En las categorías";
?>:</label><input type="text" name="cnfAdsCat" value="<?php
echo $cnfAdsCat;
?>" ><br>
<label><?php
echo "En los mensajes";
?>:</label><input type="text" name="cnfAdsMes" value="<?php
echo $cnfAdsMes;
?>" ><br>
<label><?php
echo "En las etiquetas";
?>:</label><input type="text" name="cnfAdsTag" value="<?php
echo $cnfAdsTag;
?>" ><br>
<h4><?php
echo $lngTrack;
?></h4>
<label><?php
echo $lngFillTrack;
?>:</label><input type="text" name="cnfTrack" value="<?php
echo $cnfTrack;
?>" >


<?php
if ($cnfTrack != "")
  {
    if (!is_dir($cnfTrack))
        mkdir($cnfTrack, 0777, true);
    if ($handle = opendir($cnfTrack))
      {
        $xr             = 0;
        $xml2           = new DOMDocument();
        $xml2->encoding = 'utf-8';
        //$totalTrack="";	
        $totalWeek      = 0;
        $lengh1         = 0;
        $lengh2         = 0;
        $lengh3         = 0;
        $lengh4         = 0;
        $lengh5         = 0;
        $lengh6         = 0;
        while (false !== ($file = readdir($handle)))
          {
            if ($file != "cache" && $file != "." && $file != ".." && $file != "index.php" && $file != "track.xml" && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml')
              {
                $xml2->load($cnfTrack . "/" . $file);
                //$xml_document2=$xml2->getElementsByTagName("d")->item(0);	
                //$lengh=count($xml2);	
                $lengh   = $xml2->getElementsByTagName("t")->length;
                $textDay = "";
                $hoy     = gmdate("d-m-Y", time());
                $ayer    = date('d-m-Y', strtotime($hoy . ' - 1 days'));
                $ayer2   = date('d-m-Y', strtotime($hoy . ' - 2 days'));
                $ayer3   = date('d-m-Y', strtotime($hoy . ' - 3 days'));
                $ayer4   = date('d-m-Y', strtotime($hoy . ' - 4 days'));
                $ayer5   = date('d-m-Y', strtotime($hoy . ' - 5 days'));
                $ayer6   = date('d-m-Y', strtotime($hoy . ' - 6 days'));
                //$ayer=date('d-m-Y', strtotime($hoy. ' +1 days'));
                if ($file == $hoy . ".xml")
                  {
                    $textDay0 = $lngToday . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh0   = $lengh;
                  }
                else if ($file == $ayer . ".xml")
                  {
                    $textDay1 = $lngYesterday . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh1   = $lengh;
                  }
                else if ($file == $ayer2 . ".xml")
                  {
                    $textDay2 = $lng2DaysAgo . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh2   = $lengh;
                  }
                else if ($file == $ayer3 . ".xml")
                  {
                    $textDay3 = $ayer3 . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh3   = $lengh;
                  }
                else if ($file == $ayer4 . ".xml")
                  {
                    $textDay4 = $ayer4 . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh4   = $lengh;
                  }
                else if ($file == $ayer5 . ".xml")
                  {
                    $textDay5 = $ayer5 . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh5   = $lengh;
                  }
                else if ($file == $ayer6 . ".xml")
                  {
                    $textDay6 = $ayer6 . ": <b>" . $lengh . "</b> " . $lngUniVisit . "<br>";
                    $lengh6   = $lengh;
                  }
                $totalWeek = $lengh + $totalWeek;
                //$totalTrack.="<br>".$textDay." : <b>".$lengh."</b> visitas únicas";
              }
          }
      }
    $fileTotal      = $cnfTrack . "/track.xml";
    $xml2           = new DOMDocument();
    $xml2->encoding = 'utf-8';
    if (file_exists($fileTotal))
      {
        $totalWeek = $lengh0;
        $xml2->load($fileTotal);
        $xml_document2 = $xml2->getElementsByTagName("d")->item(0);
        $currentMonth  = 0;
        //echo $xml2->getElementByTagName("since")->textContent;
        $desde         = $xml2->getElementsByTagName("since")->item(0)->nodeValue;
        /*if($xml2->getElementsByTagName('_'.$ayer)->item(0)){
        echo $xml2->getElementsByTagName('_'.$ayer)->item(0)->nodeValue."<br>";
        }*/
        if ($xml2->getElementsByTagName('_' . $ayer)->item(0))
          {
            $textDay1  = "Ayer: <b>" . $xml2->getElementsByTagName('_' . $ayer)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer)->item(0)->nodeValue) + $totalWeek;
            if (file_exists($cnfTrack . "/" . $ayer . ".xml"))
                unlink($cnfTrack . "/" . $ayer . ".xml");
          }
        else
          {
            //die("aaa");
            $xml_ayer = $xml2->createElement("_" . $ayer);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            unlink($cnfTrack . "/" . $ayer . ".xml");
          }
        if ($xml2->getElementsByTagName('_' . $ayer2)->item(0))
          {
            $textDay2  = "Hace 2 días: <b>" . $xml2->getElementsByTagName('_' . $ayer2)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            //die($textDay2);
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer2)->item(0)->nodeValue) + $totalWeek;
          }
        else
          {
            $xml_ayer = $xml2->createElement("_" . $ayer2);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            unlink($cnfTrack . "/" . $ayer2 . ".xml");
          }
        if ($xml2->getElementsByTagName('_' . $ayer3)->item(0))
          {
            $textDay3  = $ayer3 . ": <b>" . $xml2->getElementsByTagName('_' . $ayer3)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer3)->item(0)->nodeValue) + $totalWeek;
          }
        else
          {
            $xml_ayer = $xml2->createElement("_" . $ayer3);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            if (file_exists($cnfTrack . "/" . $ayer3 . ".xml"))
                unlink($cnfTrack . "/" . $ayer3 . ".xml");
          }
        if ($xml2->getElementsByTagName('_' . $ayer4)->item(0))
          {
            $textDay4  = $ayer4 . ": <b>" . $xml2->getElementsByTagName('_' . $ayer4)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer4)->item(0)->nodeValue) + $totalWeek;
          }
        else
          {
            $xml_ayer = $xml2->createElement("_" . $ayer4);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            if (file_exists($cnfTrack . "/" . $ayer4 . ".xml"))
                unlink($cnfTrack . "/" . $ayer4 . ".xml");
          }
        if ($xml2->getElementsByTagName('_' . $ayer5)->item(0))
          {
            $textDay5  = $ayer5 . ": <b>" . $xml2->getElementsByTagName('_' . $ayer5)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer5)->item(0)->nodeValue) + $totalWeek;
          }
        else
          {
            $xml_ayer = $xml2->createElement("_" . $ayer5);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            if (file_exists($cnfTrack . "/" . $ayer5 . ".xml"))
                unlink($cnfTrack . "/" . $ayer5 . ".xml");
          }
        if ($xml2->getElementsByTagName('_' . $ayer6)->item(0))
          {
            $textDay6  = $ayer6 . ": <b>" . $xml2->getElementsByTagName('_' . $ayer6)->item(0)->nodeValue . "</b> " . $lngUniVisit . "<br>";
            $totalWeek = intval($xml2->getElementsByTagName('_' . $ayer6)->item(0)->nodeValue) + $totalWeek;
          }
        else
          {
            $xml_ayer = $xml2->createElement("_" . $ayer6);
            $xml_ayer->appendChild($xml2->createTextNode($lengh1));
            if (!$xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0))
                $xml_mes = $xml2->createElement(gmdate("M-Y", time()));
            else
                $xml_mes = $xml2->getElementsByTagName(gmdate("M-Y", time()))->item(0);
            $xml_mes->appendChild($xml_ayer);
            $xml_document2->appendChild($xml_mes);
            if (file_exists($cnfTrack . "/" . $ayer6 . ".xml"))
                unlink($cnfTrack . "/" . $ayer6 . ".xml");
          }
      }
    else
      {
        $xml_document2 = $xml2->createElement("d");
        $xml_since     = $xml2->createElement("since");
        $xml_since->appendChild($xml2->createTextNode(gmdate("d-m-Y", time())));
        $desde    = gmdate("d-m-Y", time());
        $xml_mes  = $xml2->createElement(gmdate("M-Y", time()));
        //die("--->".$ayer);
        $xml_ayer = $xml2->createElement("_" . $ayer);
        $xml_ayer->appendChild($xml2->createTextNode($lengh1));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer . ".xml");
        $xml_ayer = $xml2->createElement("_" . $ayer2);
        $xml_ayer->appendChild($xml2->createTextNode($lengh2));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer2 . ".xml");
        $xml_ayer = $xml2->createElement("_" . $ayer3);
        $xml_ayer->appendChild($xml2->createTextNode($lengh3));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer3 . ".xml");
        $xml_ayer = $xml2->createElement("_" . $ayer4);
        $xml_ayer->appendChild($xml2->createTextNode($lengh4));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer4 . ".xml");
        $xml_ayer = $xml2->createElement("_" . $ayer5);
        $xml_ayer->appendChild($xml2->createTextNode($lengh5));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer5 . ".xml");
        $xml_ayer = $xml2->createElement("_" . $ayer6);
        $xml_ayer->appendChild($xml2->createTextNode($lengh6));
        $xml_mes->appendChild($xml_ayer);
        unlink($cnfTrack . "/" . $ayer6 . ".xml");
        /*$ayer
        $ayer*/
        $xml_document2->appendChild($xml_since);
        $xml_document2->appendChild($xml_mes);
      }
    echo "<h5>Since: " . $desde . "</h5>";
    echo "<br>" . $textDay0 . $textDay1 . $textDay2 . $textDay3 . $textDay4 . $textDay5 . $textDay6;
    echo "--------------<br>";
    echo $lngWeek . ":" . $totalWeek;
    $xml2->appendChild($xml_document2);
    $xml2->save($fileTotal);
  }
?>
<br>
</div>
<!--<div class='boxConfig' >
<h3>Tools</h3>
<label>Email: To:</label> <input id="nameUser" value='user1,user2' type="text"/>All <input type="checkbox" id="all" /></br>
<label>From</label> <input id="subject" value='admin@bee.com' type="text" /></br>
<label>Subject</label> <input id="subject" value='' type="text"/></br>
<label>Body</label> <textarea id="textarea" value='' ></textarea>
</div>-->
<input type="submit" name="submit" id="submit" value="Save" /><br>
</form>
<?php
include_once('footer.php');
?>
