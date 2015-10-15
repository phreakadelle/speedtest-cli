<?php
include_once("config.inc.php");

mysql_connect(DB_HOST,DB_USER, DB_PASSWORD) OR die(mysql_error());
mysql_select_db(DB_NAME) OR die(mysql_error());

$sqlData = "SELECT 
				stats_ts, 
				stats_upload / 10 as stats_upload, 
				stats_download / 10 as stats_download 
			FROM 
				stats_speedtest";
if(isset($_GET['apikey']) && $_GET['apikey'] !== "All") {
  $sqlData .= " WHERE stats_apikey = '".$_GET['apikey']."'";
}
$sqlData .= " ORDER BY stats_ts DESC";

$oldDate = "bar";
$oldDay = "foo";

$mMultiplier = 2; // Just to Scale the Chart

$sqlDataAPIKey = "SELECT DISTINCT stats_apikey FROM stats_speedtest";
$qAPIKeys = mysql_query($sqlDataAPIKey);

?>
<html>
	<head>
		<title>Speedtest Performance</title>
		<style type="text/css">
			td {
				padding: 0px;
				padding-right: 10px;
				font-size: 11px;
			}
			
			span { font-size: 9px; }
			li { display: inline; padding-right: 15px;}
			
			.skala { position: absolute; height: 1000px; text-align: right; vertical-align: top; border-left: 1px dashed black; font-size: 11px;}
			.green, .red { font-size: 10px; text-align: right; float: left; height: 15px; }
			.green { background-color: green; }
			.red { background-color: red; }
		</style>
	</head>
	<body>
	<ul>
		<li><a href="index.php">All</a></li>
		 <?php 
		 while($row = mysql_fetch_assoc($qAPIKeys)) { 
			echo "<li><a href=\"?apikey=".$row['stats_apikey']."\">".$row['stats_apikey']."</a></li>";
		 }
		 ?>
	</ul>
	
	<?php
	for($i = 0; $i<=1000; $i += 50) {
		echo "<div id=\"kb".$i."\" class=\"skala\" style=\"margin-left: ".(100+($i+33))."px;\">".($i*$mMultiplier/100)."Mbit</div>";
	}
	?>

	<br/>
	<table style="border-collapse: collapse; padding: 0px;">
      <?php 
	  $q = mysql_query($sqlData);
	  while($row = mysql_fetch_assoc($q)) { 
	  ?>
        <?php $newDay = date("D", $row['stats_ts']); $newDate = date("d.m.Y", $row['stats_ts']); ?>
      	<tr>
      		<td><?php echo ($newDay != $oldDay ? $newDay : "");?></td>
      		<td><?php echo ($newDate != $oldDate ? $newDate : "");?></td>
      		<td><?php echo date("H:i", $row['stats_ts']);?></td>
      		<td style="width: 100px;">
				<?php if($row['stats_download'] > $row['stats_upload']) { ?>
					<div class="green" style="width: <?php echo ($row['stats_download']/$mMultiplier);?>px;"><div class="red" style="width: <?php echo ($row['stats_upload']/$mMultiplier);?>px;"><?php echo $row['stats_upload'];?></div><?php echo $row['stats_download'];?></div>
				<? } else {?>
					<div class="red" style="width: <?php echo ($row['stats_upload']/$mMultiplier);?>px;"><div class="green" style="width: <?php echo ($row['stats_download']/$mMultiplier);?>px;"><?php echo $row['stats_download'];?></div><?php echo $row['stats_upload'];?></div>
				<? }?>
				
            </td>
        </tr>
        <?php $oldDay = date("D", $row['stats_ts']); $oldDate = date("d.m.Y", $row['stats_ts']); ?>
      <?php }?>
    </table>
</body>
</html>