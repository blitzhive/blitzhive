<?php
if (!isset($_SESSION)) { session_start(); }
include('config.php');
include('header.php');
$strFail="";
if (isset($_POST['submitFile']))
  {
    $xv = 0;
    foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name)
      {
        if ($_FILES["file"]["name"][$key] != "")
          {
            $temp      = explode(".", $_FILES["file"]["name"][$key]);
            $extension = strtolower (end($temp));
            $maxSize   = intval($cnfMax);
            if (($_FILES["file"]["size"][$key] < $maxSize || $maxSize==1) && (strpos(strtolower($cnfExt), $extension, 0) !== false))
              {
                if ($_FILES["file"]["error"][$key] > 0)
                  {
                    echo $_FILES["file"]["name"][0]."Error código: " . $_FILES["file"]["error"][$key] . "<br>";
                  }
                else
                  {
                    $originales                   = ' ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
                    $modificadas                  = '-aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
                    $_FILES["file"]["name"][$key] = preg_replace("/[?¿!¡|*\\/:]/", "", $_FILES["file"]["name"][$key]);
                    $_FILES["file"]["name"][$key] = utf8_decode($_FILES["file"]["name"][$key]);
                    $_FILES["file"]["name"][$key] = strtr($_FILES["file"]["name"][$key], utf8_decode($originales), $modificadas);
                    $xc                           = 0;
					$cnfUploadFolder=$cnfUploads;
                    if (!is_dir($cnfUploads))
                        mkdir($cnfUploads, 0777, true);
					
					if($cnfUploadsImage!=""&&!is_dir($cnfUploadsImage))mkdir($cnfUploadsImage, 0777, true);
					if($cnfUploadsVideo!=""&&!is_dir($cnfUploadsVideo))mkdir($cnfUploadsVideo, 0777, true);
					if($cnfUploadsFile!=""&&!is_dir($cnfUploadsFile))mkdir($cnfUploadsFile, 0777, true);
					
					$extUp = strtolower(pathinfo($_FILES["file"]["name"][$key], PATHINFO_EXTENSION));
					$posVarVid  = strpos("mp4,avi,mov,ogg,mpg,wmv,", $extUp.",");
					$posVarAudio  = strpos("mp3,wav,wma,", $extUp.",");
					$posVarFile  = strpos("zip,rar,pdf,doc,docx,xls,xlsx,txt,", $extUp.",");
					$posVarImage  = strpos("jpg,jpeg,gif,png,bmp", $extUp.",");
					
					if($posVarVid!==false&&$cnfUploadsVideo!="")$cnfUploadFolder=$cnfUploadsVideo;
					else if($posVarAudio!==false&&$cnfUploadsAudio!="")$cnfUploadFolder=$cnfUploadsAudio;
					else if($posVarFile!==false&&$cnfUploadsFile!="")$cnfUploadFolder=$cnfUploadsFile;
					else if($posVarImage!==false&&$cnfUploadsImage!="")$cnfUploadFolder=$cnfUploadsImage;
					
					
                    while (file_exists($cnfUploadFolder . "/" . $_FILES["file"]["name"][$key]))
                      {
                        $_FILES["file"]["name"][$key] = $xc . "-" . $_FILES["file"]["name"][$key];
                        $xc++;
                      }
                    if (move_uploaded_file($_FILES["file"]["tmp_name"][$key], $cnfUploadFolder . "/" . $_FILES["file"]["name"][$key]))
                      {
                        //$_FILES["file"]["name"][$key] = $_FILES["file"]["name"][$key];
                        //echo "Archivo subido :) " . $_FILES["file"]["name"][$key] . "<br>";
                
				if(!isset($_SESSION['uploadedText']))$_SESSION['uploadedText']="<span style='color:#8CD63C;float:left;margin:5px;'> ";
				else $_SESSION['uploadedText'].="<span style='color:#8CD63C;float:left;margin:5px;'> ";
				
				if($posVarVid===false&&$posVarFile===false)$_SESSION['uploadedText'] .= '<img title="Insert in text!" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',1)" src="' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '" class="imgUpload"/>';/*Use <b><input type="button" value="[pic]" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',1)"/></b> ';*/
				else if($posVarFile===false)$_SESSION['uploadedText'] .= '<video title="Insert in text!" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',2)" class="vidUpload" src="' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '" controls>Not supported</video>';/* Click <b><input type="button" value="[vid]" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',2)"/></b> ';*/
				else $_SESSION['uploadedText'] .= '<a title="Insert in text!" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',3)"><b><u>'.$_FILES["file"]["name"][$key].'</u></b></a>(↓)';/* Use <b><input type="button" value="[file]" onclick="parent.addtag(\'' . $cnfHome . $cnfUploadFolder .'/' . $_FILES["file"]["name"][$key] . '\',3)"/></b> ';*/
				
				$_SESSION['uploadedText'].="</span>";	  
					  }
                    $xv++;
                  }
              }
            else
              {
				  
                $strFail.= "<br><span style='color:#E87474;float:left;margin:5px;'>Archivo invalido <b>" . $_FILES["file"]["name"][$key]."</b> ";
                if ($_FILES["file"]["size"][$key] >= $maxSize)
                  {
                $strFail .= $lngMaxSize . ": " . $maxSize;
                  }	
                else if (strpos($cnfExt, $extension, 0) === false)
                  {
                $strFail .= $lngExt . " : " . $cnfExt ;
                  }
				  $strFail .='</span>';
              }
          }
      }    
  }
  
?>
<style type="text/css">
body{font-family:"Segoe UI",Tahoma,Helvetica,freesans,sans-serif;font-size:90%;margin:5px;color:#333;background-color:#fff}h1,h2{font-size:1.5em;font-weight:400}h2{font-size:1.3em}legend{font-weight:700;color:#333}#filedrag{display:none;font-weight:700;text-align:center;padding:1em 0;margin:1em 0;color:#555;border:2px dashed #555;border-radius:7px;cursor:default;width:100%}#filedrag.hover{color:red;border-color:red;border-style:solid;box-shadow:inset 0 3px 4px #888}img{max-width:100%}pre{width:95%;height:8em;font-family:monospace;font-size:.9em;padding:1px 2px;margin:0 0 1em auto;border:1px inset #666;background-color:#eee;overflow:auto}#messages{width:98%;margin-top:15px;height:100px;padding:0 10px;font-size:.9em;float:left;overflow-y:scroll}#progress p{display:block;width:240px;padding:2px 5px;margin:2px 0;border:1px inset #446;border-radius:5px;background:#eee url(progress.png) 100% 0 repeat-y}#progress p.success{background:#0c0 none 0 0 no-repeat}#progress p.failed{background:#c00 none 0 0 no-repeat}fieldset{float:left;width:40%}#submitFile{float:left;height:25px;margin-top:5px;margin-left:10px;font-size:150%}




</style>
</head>
<body>
<form id="upload" action="upload.php" method="POST" enctype="multipart/form-data">
<fieldset>
	
	<input type="file" id="fileselect" name="file[]" multiple="multiple" />
	<!--<div id="filedrag">or drop files here</div>-->
<div id="submitbutton">
	<button type="submit">Upload Files</button>
</div>
</fieldset>
<input type="submit" name="submitFile"  id="submitFile" value="Subir ↑">
</form>
<div id="messages">
<h3>Click to use!</h3>
<?php echo $strFail.$_SESSION['uploadedText'];?>
</div>
<script LANGUAGE="JavaScript">
function $id(a){return document.getElementById(a)}function Output(a){var b=$id("messages");b.innerHTML=a+b.innerHTML}function FileDragHover(a){a.stopPropagation();a.preventDefault();a.target.className="dragover"==a.type?"hover":""}function FileSelectHandler(a){FileDragHover(a);a=a.target.files||a.dataTransfer.files;$id("submitFile").style.backgroundColor="lightblue";for(var b=0,c;c=a[b];b++)ParseFile(c)}
function ParseFile(a){Output("File information: <strong>"+a.name+"</strong> type: <strong>"+a.type+"</strong> size: <strong>"+a.size+"</strong> bytes\t")}
function Init(){var a=$id("fileselect"),/*b=$id("filedrag"),*/c=$id("submitbutton");a.addEventListener("change",FileSelectHandler,!1);(new XMLHttpRequest).upload&&(/*b.addEventListener("dragover",FileDragHover,!1),b.addEventListener("dragleave",FileDragHover,!1),b.addEventListener("drop",FileSelectHandler,!1),b.style.display="block",*/c.style.display="none")}window.File&&window.FileList&&window.FileReader&&Init();
</script>
</body>
</html>