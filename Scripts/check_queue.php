<?php
include_once '../../config/db.php';
$db = new Database();
$driver = $db->dbConnection();
$vids = array();
$notProcessedVids = array();

if (!(file_exists("../Log/log_queue.txt")))
{
    exec("touch ../Log/log_queue.txt");
}

exec("echo  >> ../Log/log_queue.txt");
exec("echo ------------------------------------------ >> ../Log/log_queue.txt");
exec("echo ".get_current_datetime()." [EVENT] Checking queue started... >> ../Log/log_queue.txt");

try{
	$stmt = $driver->prepare("SELECT * FROM videos");
	if($stmt->execute()){
		$vids = $stmt->fetchAll(PDO::FETCH_ASSOC);
        exec("echo ".get_current_datetime()." [DATABASE] Database video data fetched >> ../Log/log_queue.txt");
	}
}catch(PDOException $ex){
	echo $ex->getMessage();
    exec("echo ".get_current_datetime()." [ERROR] Database video data fetching error! >> ../Log/log_queue.txt");
    exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log_queue.txt");
}

exec("echo ".get_current_datetime()." [EVENT] Videos counter: ".count($vids)." >> ../Log/log_queue.txt");

reset($notProcessedVids);
$notProcessedVids = array_values(array_filter($vids, function ($v) {
	return $v["processed"] == "n";
}));
exec("echo ".get_current_datetime()." [EVENT] Pending videos counter: ".count($notProcessedVids)." >> ../Log/log_queue.txt");

//Triggers illegal offset warning
$processingCount = count(array_filter($vids, function ($v) {
	return $v["processed"] == "p";
}));

$notProdcessedCount = count($notProcessedVids);

$id = 0;
if ($processingCount == 0 && $notProdcessedCount != 0) {
	$id = $notProcessedVids[0]["id"];
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Fetching video for processing >> ../Log/log_queue.txt");
	
} else {
	echo "No video to process.";
    exec("echo ".get_current_datetime()." [EVENT] No video to process. >> ../Log/log_queue.txt");
}

if ($id != 0)
{
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Executing Encoding... >> ../Log/log_queue.txt");
	exec("php encode_job.php ".$id);
}

exec("echo ".get_current_datetime()." [EVENT] Checking queue ended. >> ../Log/log_queue.txt");
exec("echo ------------------------------------------ >> ../Log/log_queue.txt");

function get_current_datetime()
{
    $now = new DateTime(null, new DateTimeZone('Europe/London'));
    return ("[".($now->format('Y-m-d H:i:s'))."]");
}
?>
