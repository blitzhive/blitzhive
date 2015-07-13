<?php
include('config.php');
include('header.php');
 
if(isset($_POST['submit'])){
if(isset($_POST['swarmEmail']))$_POST['swarmEmail']=filter_var($_POST['swarmEmail'], FILTER_SANITIZE_SPECIAL_CHARS);//tags
if(isset($_POST['txtE']))$_POST['txtE']=filter_var($_POST['txtE'], FILTER_SANITIZE_SPECIAL_CHARS);//tags
if(str_word_count($_POST['txtE'])>200){
if (filter_var($_POST['swarmEmail'], FILTER_VALIDATE_EMAIL)) { 
$swFile="swarm_".uniqid();
$fp = fopen($swFile.".php", "w+");
fwrite($fp, '<?php '.$_POST['swarmEmail'].'--'.$_POST['txtE'].';?>');
	chmod($swFile.".php", 0600);
	fclose($fp); 
	echo utf8_encode("<h4>Muchas gracias por enviar tu petición en 48 horas se te responderá a ".$_POST['swarmEmail']."</h4> :)");

	}else{
	echo utf8_encode("<h4>Email-Inválido</h4> :(");
	}
 } else{
    echo utf8_encode("<h4>Descripción muy corta intenta explicarnos un poco mejor tu proyecto</h4> :(");
 }
 die(header("refresh:5;url=".$cnfHome."/swarm.php"));
 }

?>
<title><?php echo $cnfTitle;?> | Register</title>
</head>
<body>
<html>
<h4>Niveles blitz:</h4>
<br>
<div class="swarmText"><?php echo utf8_encode("Blitzhive contrala la calidad de sus comunidades mediante un sistema de usuarios por niveles. Los niveles se alcanzan
por puntos y los puntos se consiguen por escribir textos de 200 caracteres o más(+1 punto) además obtendrás +1 punto
por cada voto que reciban tus mensajes o respuesta. También puedes obtener refereidos y obtener +5 puntos por ellos. Con cada nivel");?>
obtienes </div>
<br>
<table class="swarmTable">
<tr>
<td>nivel</td>
<td>puntos</td>
<td><?php echo utf8_encode("símbolo")?></td>
<td>votar</td>
<td>apadrinar</td>
<td>imagen</td>
<td>mensajes</td>
<td>firma</td>
<td>blog</td>
<td>obsequio</td>
<td>domain/email</td>
</tr>
<tr>
<td>Cocoon</td>
<td>+0</td>
<td>0</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>
<tr>  
<td>Ninfa</td>
<td>+25</td>
<td>()</td>
<td>Si</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>
<tr>    
<td>Obrero</td>
<td>+60</td>
<td>(|)</td>
<td>Si</td>
<td>Si</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>
<!--<tr>  
<td>Cazador</td>
<td>+80</td>
<td>(O)</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>-->
<tr>  
<td>Soldado</td>
<td>+120</td>
<td>([])</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>		
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>
<!--<tr>  
<td>Alpha</td>
<td>+200</td>
<td>({})</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
</tr>-->
<tr>  
<td>Omega</td>
<td>+320</td>
<td>(/\)</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>

</tr>
<tr>  
<td>Matriarca</td>
<td>+600</td>
<td><-></td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>*.blitzhive.net</td>
<td>-</td>
<td>-</td>
</tr>
<tr>  
<td>Embajador</td>
<td>+800</td>
<td><^></td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>*.blitzhive.net</td>
<td>camiseta blitz :)</td>
<td>-</td>
</tr>
<tr>  
<td>Consorte</td>
<td>+1000</td>
<td><*></td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>Si</td>
<td>*.blitzhive.net</td>
<td>Camiseta blitz :)</td>
<td>domain.com</td>
</tr>
</table>
<center>
<h1>BLITZWARM</h1>
<div id="zwarm">
<div class="divSwarm1">
<p class="zwText"><a href="#txtE">Tu</a></p>
<p class="zwText"><a href="#txtE">.com</a></p>
<p class="zwText"><a href="#txtE">gratis</a></p>
<p class="zwText"><a href="#txtE">:)</a></p>
</div>

<div class="divSwarm2">
<p class="zwText">:)</p>
<p class="zwText">Empieza</p>
<p class="zwText">tu proyecto</p>
<p class="zwText">ahora</p>
</div>
<div class="imgSwarm" ><img src="zwarm.png" usemap="#swarmMap"></img>

<map name="swarmMap">
  <area shape="circle" coords="300,420,75" href="http://forohtml5.com/" type="_blank	" title="Foro de html5 y Css3" alt="Foro de html5 y Css3">
</map>
</div>
</div>
<div>
</center>
<br style="clear:both;">	
<form class="formSwarm" id="form1" name="form1" method="post" >	
<span class="spanFormSwarm">
<?php echo  utf8_encode("*Resúmenos tu proyecto y convéncenos para obtener tu .com gratis para toda la vida. Recuerda que:<br>
1º Siempre será de tu libre uso mientras esté gestionado con el CMS blitzhive. <br>
2º Los usuarios deben tener acceso al contenido de tu comunidad siempre.<br>
3º Debe ser proyecto serio en el que realmente creas. :)");?>
</span>
<br style="clear:both;">	
<br style="clear:both;">	
Email*: <input type="text" name="swarmEmail" id="swarmEmail" placeholder="email" autocomplete="off" value="">
<br>
<?php echo utf8_encode("Tu proyecto, convéncenos:");?><textarea class="txtSwarm" id="txtE" onKeyUp='feChange()' onMouseUp='feSel()'  maxlength="2000" name="txtE"></textarea>
<input type="submit" name="submit" id="submit" value="Enviar" /><br>
</form>
<br style="clear:both;">	

</html>
</body>
<footer>
<?php

include('footer.php');

?>
</footer>