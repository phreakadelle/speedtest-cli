<?php
include_once("config.inc.php");

$mFilename = "content.csv";
$mFileContent = "";
$mResultId = -1;

$mSQLFields = "";
$mSQLValues = "";

// Start Database Handling

// "Known"-Fields
$allObjects = array(
	"download" => array("stats_download", 0),
	"timestamp" => array("stats_ts", time()),
	"date" => array("stats_date", date("'d.m.Y'")),
	"time" => array("stats_time", date("'H:i:s'")),
	"ping" => array("stats_ping", 0),
	"upload" => array("stats_upload", 0),
	"promo" => array("stats_promo", 0),
	"startmode" => array("stats_startmode", 0),
	"pingselect" => array("stats_pingselect", 0),
	"recommendedserverid" => array("stats_recommendedserverid", 0),
	"accuracy" => array("stats_accuracy", 0),
	"serverid" => array("stats_serverid", 0),
	"hash" => array("stats_hash", 0),
	"apikey" => array("stats_apikey", "'anonymous'")
);

// Iterate over all "known"-fields
foreach($allObjects as $key => $val) {
	
	// Check if Request contains a "known"-field
	if(isset($_REQUEST[$key])) {
		$val[1] = $_REQUEST[$key];
		if(!is_numeric($val[1])) {
			$val[1] = "'".$val[1]."'";
		}
	}
	$mSQLFields .= ($mSQLFields != "" ? ", ".$val[0] : $val[0]);
	$mSQLValues .= ($mSQLValues != "" ? ", ".$val[1] : $val[1]);
	$mFileContent .= $val[1].";";
}

mysql_connect(DB_HOST,DB_USER, DB_PASSWORD) OR die(mysql_error());
mysql_select_db(DB_NAME) OR die(mysql_error());
mysql_query("INSERT INTO stats_speedtest(".$mSQLFields.") VALUES(".$mSQLValues.");");
$mResultId = mysql_insert_id();
mysql_close();
// End Database Handling

// Start File Handling
$handle = fopen($mFilename, "a");
fwrite($handle, $mFileContent."\n");
fclose($handle);
// End File Handling

// Return Response
header('Content-Type: text/plain');
echo "resultid=".$mResultId."&";
echo "query="."INSERT INTO stats_speedtest(".$mSQLFields.") VALUES(".$mSQLValues.");";
?>