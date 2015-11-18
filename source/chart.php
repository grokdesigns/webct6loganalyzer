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
<title>WebCT Log Analyzer - Chart View</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

  <!-- saved from url=(0013)about:internet -->
    <!-- amline script-->
    <script type="text/javascript" src="chart/swfobject.js"></script>
    <div id="flashcontent1"> <strong>You need to upgrade your Flash Player</strong> </div>
    <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("chart/amline.swf", "amline", "1024", "450", "8", "#F0F0F0");
		so.addVariable("path", "chart/");
		so.addVariable("settings_file", escape("chart/amline_settings.xml"));

<?php  
$ct6log = 'logs/' . $_GET['ct6log'];
$csvoutput = 'csv/' . $_GET['ct6log'] . ".csv";
putenv("TZ=America/Phoenix");
$matches=array("timestamp=", "Current user count:");  
$matchvar="missing";  

$out = fopen($csvoutput, "w");
$fh = fopen($ct6log, "r") or exit("Unable to open file!");  
while(! feof($fh)) {  
$newline=fgets($fh);
	foreach($matches as $match){  
		if(strstr($newline, $match)){   
			switch ($match){  
			case "timestamp=":  
			$newline1 = explode($match, $newline);  
			$substring = explode("time=", $newline1[1]); 
			fputs($out,date("m-d-y H:i:s", substr($substring[0],1,-5)) . ",");  
			break;  
			case "Current user count:":  
			$substring = explode($match, $newline);  
			fputs($out,ereg_replace("[^0-9]","",$substring[1]) . "\r");  
			break;  
			}  
		}  
	}  
}  

fclose($fh); 
echo "so.addVariable(\"data_file\", escape(\"" . $csvoutput . "\"));";

?> 
		so.addVariable("preloader_color", "#000000");
		so.write("flashcontent1");
		// ]]>
	</script>
    <!-- end of amline script -->
    <br>
    <br>
    <a href="javascript: self.close()">Close Results Window</a> <br>
    <br>
    <font class='style2'><?php echo "&copy " . date("Y"); ?>, Grok Designs. Maintained by <a class="alinks" href="mailto:chris@grokdesigns.com">Chris Caraccioli</a></font><br>
          <img src="icons/valid-html401.png" width="88" height="31" alt="valid_html"><br>
          <img src="icons/php-power-micro.png" alt="PHP Powered">
</body>
</html>