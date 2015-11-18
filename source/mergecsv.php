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
<title>WebCT Log Analyzer - Main Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
$dir = "csv/";

// Open the CSV directory and clean up old files.
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
			if(($file != '..') && ($file != '.') && !is_dir($file)){
			$initmdate = date('U', filemtime('csv/' . $file));
			$nowdate = date('U', (time()-date('Z')));
			$datedif = $nowdate - $initmdate;
            if ($datedif >= 50000) {
			unlink('csv/' . $file);
			}
			}
        }
        closedir($dh);
    }
}

//Read data and output as table.
$csvfile = $_POST['csvdata'];
$csvcount = count($csvfile);
if ($csvcount == 0) {
	echo 'No files selected!<br>';
	echo '<a href=\'javascript: self.close()\'>Close Results Window</a>';
	exit;
} else {
$csvoutput = 'csv/mergefile_' . date('U') . '.csv';
putenv('TZ=America/Phoenix');
$matches=array('timestamp=', 'Current user count:');  
$matchvar='missing';  


//This is where we'll parse the logs for our user count info.
$out = fopen($csvoutput, 'a');
foreach ($csvfile as $file) {
$fh = fopen('logs/' . $file, 'r') or exit('Unable to open file!');  
while(! feof($fh)) {  
$newline=fgets($fh);
	foreach($matches as $match){  
		if(strstr($newline, $match)){   
			switch ($match){  
			case 'timestamp=':  
			$newline1 = explode($match, $newline);  
			$substring = explode('time=', $newline1[1]); 
			fputs($out,date('m-d-y H:i:s', substr($substring[0],1,-5)) . ',');  
			break;  
			case 'Current user count:':  
			$substring = explode($match, $newline);  
			fputs($out,ereg_replace('[^0-9]','',$substring[1]) . '\r');  
			break;  
			}  
		}  
	}  
}  
}
}


fclose($fh); 

//Detect number of logs so we use proper grammar :)
if ($csvcount == 1) {
	echo '<b><i>' . substr($csvoutput,4) . '</i></b> has been created from the following file:<br>';
	foreach ($csvfile as $file) {
	echo $file.'<br />';
	}

} else {
echo '<b><i>' . substr($csvoutput,4) . '</i></b> has been created from the following files:<br>';
foreach ($csvfile as $file) {
echo $file.'<br />';
}
}
echo '<br><a href="' . $csvoutput . '">Click here to save it.</a>';

?>
<br />
<br />
<a href="javascript: self.close()">Close Results Window</a> <br />
<br />
<font class='style2'><?php echo '&copy ' . date('Y'); ?>, Grok Designs. Maintained by <a class="alinks" href="mailto:chris@grokdesigns.com">Chris Caraccioli</a></font><br />
          <img src="icons/valid-html401.png" width="88" height="31" alt="valid_html"><br>
          <img src="icons/php-power-micro.png" alt="PHP Powered">
</body>
</html>