<?php
include_once("config.inc.php");

mysql_connect(DB_HOST,DB_USER, DB_PASSWORD) OR die(mysql_error());
mysql_select_db(DB_NAME) OR die(mysql_error());

$sqlData = "SELECT 
				stats_ts,
				stats_date,
				stats_time,
				stats_upload / 10 as stats_upload, 
				stats_download / 10 as stats_download 
			FROM 
				stats_speedtest
			ORDER BY 
				stats_ts DESC";
$q = mysql_query($sqlData);

$mMultiplier = 2; // Just to Scale the Chart

?>
<html>
	<head>
		<title>Speedtest Performance</title>
		<style type="text/css">
			td {
				padding-right: 10px;
				font-size: 11px;
			}
			
			span {
				font-size: 9px;
			}
			
			.skala { position: absolute; height: 1000px; text-align: right; vertical-align: top; border-left: 1px solid black;}
		</style>
	</head>
	<body>
		<?php
		for($i = 0; $i<=1000; $i += 50) {
			echo "<div id=\"kb".$i."\" class=\"skala\" style=\"margin-left: ".(100+($i+33))."px;\">".($i*$mMultiplier/100)."Mbit</div>";
		}
		?>
	<br/>
	<br/>
<table>
      <?php while($row = mysql_fetch_assoc($q)) { ?>
    	<tr>
          	<td style="min-width: 118px;">
				<?php echo $row['stats_date'];?>
				<?php echo $row['stats_time'];?>
			</td>
          	<!-- <td><?php echo $row['stats_download'];?> kb/s</td>-->
          	<!-- <td><?php echo $row['stats_upload'];?> kb/s</td>-->
            <td>
            	<img src="green.png" style="height: 10px; width: <?php echo $row['stats_download']/$mMultiplier;?>px;" title="<?php echo $row['stats_download'];?>" />
            	<span><?php echo $row['stats_download'];?></span><br/>
            	<img src="red.png" style="height: 10px; width: <?php echo $row['stats_up']/$mMultiplier;?>px;" title="<?php echo $row['stats_upload'];?>" />
            	<span><?php echo $row['stats_upload'];?></span>
            </td>
    	</tr>
      <?php }?>
</table>
</body>
</html>