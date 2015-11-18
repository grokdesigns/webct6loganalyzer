<!--

Copyright Chris Caraccioli
www.grokdesigns.com
chris@grokdesigns.com

I tried to make this code as readable as possible. If you have corrections or see room for improvement, please email me!

-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-8">
<title>WebCT Log Analyzer - Table View</title>
<link href="table_style.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php  
$ct6log = 'logs/' . $_GET['ct6log'];
putenv('TZ=America/Phoenix');
$matches=array('timestamp=', 'Current user count:');  
$matchvar='missing';  

echo '<table class="match"><tr><th>Time</th><th>User Count</th></tr>'; 
$fh = fopen($ct6log, 'r') or exit('Unable to open file!');  
while(! feof($fh)) {  
$newline=fgets($fh);
	foreach($matches as $match){  
		if(strstr($newline, $match)){   
			switch ($match){  
			case 'timestamp=':  
			$newline1 = explode($match, $newline);  
			$substring = explode('time=', $newline1[1]); 
			echo '<tr><td>' . date('m-d-y H:i:s', substr($substring[0],1,-5)) . '</td>';  
			break;  
			case 'Current user count:':  
			$substring = explode($match, $newline);  
			echo '<td>' . ereg_replace('[^0-9]','',$substring[1]) . '</td></tr>';  
			break;  
			}  
		}  
	}  
}  
echo '</table>';  
fclose($fh);  
?>
<br>
<br>
<a href="javascript: self.close()">Close Results Window</a> <br>
<br>
<font class='style2'><?php echo '&copy ' . date('Y'); ?>, Grok Designs. Maintained by <a class="alinks" href="mailto:chris@grokdesigns.com">Chris Caraccioli</a></font><br>
<img src="icons/valid-html401.png" width="88" height="31" alt="valid_html"><br>
<img src="icons/php-power-micro.png" alt="PHP Powered">
</body>
</html>
