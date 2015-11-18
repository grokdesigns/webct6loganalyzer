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
<!-- Here is our PHP code to  parse the logs directory and put our file names into a sortable array. -->
  <?php
			// Set logs directory.
            $str_dir = 'logs/';
            
            // Open logs directory.
            $dir = opendir($str_dir);
            
            // Create array so we can sort it later.
            $files = array();
            
            // Sorting functions.
            if(!isset($_REQUEST['sortby'])){
                $sortby = 'mtime';
            }else{
                $sortby = $_REQUEST['sortby'];
            }

            switch($_REQUEST['sortmode']) 
			{  
				case 'desc':default:$sortmode = SORT_DESC;break;
				case 'asc':$sortmode = SORT_ASC;
				
			}
            
            // Read files.
			$i = 0;
            $test[$i]= 0;
            while($file = readdir($dir))
            {
                if(($file != '..') && ($file != '.') && !is_dir($file))
                {
                    $files[$i] = array(
                        'name' => $file,
                        'mtime' => filemtime('logs/' . $file),
                        );
                    
                    // doubled, to be able to sort an
                    // multidimensional array well
                    $files[$i]['0'] = $files[$i][$sortby];
                    ksort($files[$i]);
                    
                    $i++;
                }
            }
            closedir($dir);
            
            // Sort list of files.
            array_multisort($files, $sortmode);
?>

<div align="center">
<h3><a href="https://github.com/grokdesigns/webct6loganalyzer" target="_blank">Update: Source code now available!<br>Download Here!</a></h3>

  <form id="log_form" name="log_form" method="post" action="mergecsv.php" target="_blank">
    <table border="1" cellpadding="5" cellspacing="0">
      <tr>
        <th colspan="5" align="center"><strong>WebCT6 User Count Logs</strong> 
      </tr>
      <tr>
        <th>
			Filename&nbsp&nbsp
			<a class="sortlinks" href="<?php echo $_SERVER['PHP_SELF'] ?>?sortby=name&amp;sortmode=asc">
			<img src="icons/arrow_up.png" alt="filename_asc" border = "0" title="Sort Ascending"></a>
			<a class="sortlinks" href="<?php echo $_SERVER['PHP_SELF'] ?>?sortby=name&amp;sortmode=desc">
			<img src="icons/arrow_down.png" alt="filename_desc" border = "0" title="Sort Descending"></a>
		</th>
		<th>
			Last Modified&nbsp&nbsp
			<a class="sortlinks" href="<?php echo $_SERVER['PHP_SELF'] ?>?sortby=mtime&amp;sortmode=asc">
			<img src="icons/arrow_up.png" alt="modified_asc" border = "0" title="Sort Ascending"></a>
			<a class="sortlinks" href="<?php echo $_SERVER['PHP_SELF'] ?>?sortby=mtime&amp;sortmode=desc">
			<img src="icons/arrow_down.png" alt="modified_desc" border = "0" title="Sort Descending"></a>
		</th>
		<th align="center" width="72"><strong>Table</strong></th>
		<th align="center" width="72"><strong>Chart</strong></th>
        <th align="center" width="72"><strong>CSV</strong></th>
      </tr>
<?php $fixcount = count($files) - 1; for($i = 0; $i < $fixcount; $i++) { ?>
        	<tr>
				<td>
					<img border="0" src="icons/bullet_green.png" alt="bullet">
					<?php echo $files[$i]['name'];?>
				</td>
				<td>
					<?php echo date('m/d/Y - G:i:s', $files[$i]['mtime']);?>
				</td>
				<td align="center">
					<a href="analyzer.php?ct6log=<?php echo $files[$i]['name'];?>" target="_blank">
						<img border = "0" src="icons/table.png" title="Generate Table" alt="Generate Table">
					</a>
				</td>
				<td align="center">
					<a href="chart.php?ct6log=<?php echo $files[$i]['name'];?>" target="_blank">
						<img border = "0" src="icons/chart_line.png" title="Generate Graph" alt="Generate Graph">
					</a>
				</td>
				<td align="center">
					<input type="checkbox" name="csvdata[]" value="<?php echo $files[$i]['name'];?>">
				</td>
            </tr>
<?php } ?>
      <tr>
		<td colspan='4' align='center'>
			<strong>Logs Found: <?php echo $fixcount; ?></strong>
		</td>
        <td>
			<input name="submit" type="image" id="submit" value="Merge to CSV" src="icons/arrow_join.png" alt="create_csv">
		</td>
      </tr>
      <tr>
        <td colspan='5' align='center'>
			<strong>
				<font class='style2'>
					&copy <?php echo date('Y'); ?>, Grok Designs. Maintained by
						<a class="alinks" href="mailto:chris@grokdesigns.com">Chris Caraccioli
						</a>
				</font>
			</strong>
		<br>
          <img src="icons/valid-html401.png" width="88" height="31" alt="valid_html"><br>
          <img src="icons/php-power-micro.png" alt="PHP Powered">
        </td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>