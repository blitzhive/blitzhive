<?php
include_once('config.php');
include_once('header.php');
if (!isset($_SESSION)) {session_start();}
?>
<head>
<title><?php echo $cnfTitle;?> | 404</title>
</head>
<body>
<center>
<html>
<nav>
<a href="<?php echo $cnfHome;?>"><?php echo "Volver al inicio ";?></a> >
Página 404
</nav>
<article class="article" >
  <header>
	<h1  class='h1GrayCenter'>Página no encontrada</h1>
  </header>
  <section>
<h4>No hemos encontrado nada referente a <?php echo $_SESSION['error'];?></h4>
   </section>
</article>
<footer>
<?php
echo $cnfFooterText;
include('footer.php');
?>
</footer>
</center>
</body>
</html>