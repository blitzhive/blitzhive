<?php
if(basename(__FILE__)== basename($_SERVER["SCRIPT_FILENAME"]))die(); 
if($cnfAnalytics!='')
{?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $cnfAnalytics; ?>', 'auto');
  ga('send', 'pageview');
</script>
<?php
}
if($cnfAdvCookie!=""&&!isset($_SESSION['iduserx'])&&!isset($_SESSION['cookieAdv'])){
	
	echo '<a href="#" onclick="fCookieAdv(\''.addcslashes($_SERVER['REQUEST_URI'],"'").'\',\''.$cnfHome.'\')">'.$cnfAdvCookie.'</a>';	
}
if($cnfTrack!=""){	
	if($tr==0)include('track.php');
	else include('../track.php');
}
?>