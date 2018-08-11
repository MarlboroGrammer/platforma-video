<?php
include_once '../../../config/db.php';
$db = new Database();
$driver = $db->dbConnection();

$id = $argv[1];

try{
    $stmt = $driver->prepare("SELECT * FROM videos WHERE id = ".$id);
    if($stmt->execute()){
        $vid = $stmt->fetch(PDO::FETCH_ASSOC);
        exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Video data fetched >> ../Log/log.txt");
    }
}catch(PDOException $ex){
    echo $ex->getMessage();
    exec("echo ".get_current_datetime()." [ERROR] [".$id."] Fetch video data from database error! >> ../Log/log.txt");
    exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
}


$file_path = $vid["path"].'/'.$vid["link"].".mp4";
print_r($file_path);

if (!(file_exists("../Log/log.txt")))
{
    exec("touch ../Log/log.txt");
}
exec("echo  >> ../Log/log.txt");
exec("echo ------------------------------------------ >> ../Log/log.txt");
exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encode started... >> ../Log/log.txt");

$resolution = checkfile($file_path,$id);

duration($file_path,$driver,$vid,$id);

//originalSize($driver,$vid,$id);

if ($resolution >= 720)
{
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] HD and SD encoding started >> ../Log/log.txt");
	$status = "p";
	$hd = true;
	echo "\n\n>>> File is 720p higher. Encoding to 720p then 480p.\n\n\n";
	try {
		$stmt = $driver->prepare("UPDATE videos SET hd = :h, processed = :p, hd_encode = :p, sd_encode = :p  WHERE id = :i");
		$stmt->bindparam(":h", $hd);
		$stmt->bindparam(":p", $status);
		$stmt->bindparam(":i", $vid["id"]);
		if($stmt->execute()){
			echo "\n Video set to HD \n and is now pending";
            exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database HD and SD processing updated >> ../Log/log.txt");
		}
	} catch(PDOException $ex) {
		echo $ex->getMessage();
        exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database HD and SD processing error! >> ../Log/log.txt");
        exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
	}
	$encode_h265_480p_isdone = false;
	$encode_h265_720p_isdone = false;
	$encode_h264_480p_isdone = false;
	$encode_h264_720p_isdone = false;
	$encode_h264_720p_isdone = encode_h264_720p($file_path,$id,$driver,$vid);
	$encode_h264_480p_isdone = encode_h264_480p($file_path,$id,$driver,$vid);

	if (($encode_h264_720p_isdone) && ($encode_h264_480p_isdone))
	{
        exec("echo ".get_current_datetime()." [EVENT] [".$id."] HD and SD encoding done >> ../Log/log.txt");
		$status = "y";
		$encoded = true;
		exec("rm -f ".$file_path);
		$hdsizeh264_bytes = filesize("../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_720.mp4");
		$hdsizeh264 = round((float)($hdsizeh264_bytes),2);
		echo "\n\n>>> HD H.264 size of file is ".$hdsizeh264." Mo.";
		$sdsizeh264_bytes = filesize("../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_480.mp4");
		$sdsizeh264 = round((float)($sdsizeh264_bytes),2);
		echo "\n>>> SD H.264 size of file is ".$sdsizeh264." Mo.\n\n\n";
		try {
			$stmt = $driver->prepare("UPDATE videos SET processed = :p, hd_size = :hs, sd_size = :ss, encoded = :e WHERE id = :i");
			$stmt->bindparam(":p", $status);
			$stmt->bindparam(":hs", $hdsizeh264);
			$stmt->bindparam(":ss", $sdsizeh264);
			$stmt->bindparam(":e", $encoded);
			$stmt->bindparam(":i", $vid["id"]);
			if($stmt->execute()){
				echo "\n HD and SD Video data set";
                exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database HD and SD done updated >> ../Log/log.txt");
			}
		} catch(PDOException $ex) {
			echo $ex->getMessage();
            exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database HD and SD done error! >> ../Log/log.txt");
            exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
		}
        exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encode ended. >> ../Log/log.txt");
        exec("echo ------------------------------------------ >> ../Log/log.txt");
	}
}
if ($resolution < 720)
{
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] SD only encoding started >> ../Log/log.txt");
	$status = "p";
	$encoded = true;
	echo "\n\n>>> File is lower than 720p. Encoding to 480p only.\n\n\n";
	try {
		$stmt = $driver->prepare("UPDATE videos SET processed = :p, sd_encode = :p  WHERE id = :i");
		$stmt->bindparam(":p", $status);
		$stmt->bindparam(":i", $vid["id"]);
		if($stmt->execute()){
			echo "\n Video set to HD \n and is now pending";
            exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database SD only processing updated >> ../Log/log.txt");
		}
	} catch(PDOException $ex) {
		echo $ex->getMessage();
        exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database SD processing error! >> ../Log/log.txt");
        exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
	}
	$encode_h265_480p_isdone = false;
	$encode_h264_480p_isdone = false;
	$encode_h264_480p_isdone = encode_h264_480p($file_path,$id,$driver,$vid);
	//if (($encode_h265_480p_isdone) && ($encode_h264_480p_isdone))
	if ($encode_h264_480p_isdone)
	{
        exec("echo ".get_current_datetime()." [EVENT] [".$id."] SD only encoding done >> ../Log/log.txt");
		$status = "y";
		exec("rm -f ".$file_path);
		$sdsizeh264_bytes = filesize("../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_480.mp4");
		$sdsizeh264 = round((float)($sdsizeh264_bytes),2);
		echo "\n\n>>> SD H.264 size of file is ".$sdsizeh264." Mo.\n\n\n";
		try {
			$stmt = $driver->prepare("UPDATE videos SET processed = :p, sd_encode = :s, sd_size = :ss, encoded = :e WHERE id = :i");
			$stmt->bindparam(":p", $status);
			$stmt->bindparam(":s", $status);
			$stmt->bindparam(":ss", $sdsizeh264);
			$stmt->bindparam(":e", $encoded);
			$stmt->bindparam(":i", $vid["id"]);
			if($stmt->execute()){
				echo "\n HD Video data set";
                exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database SD only done updated >> ../Log/log.txt");
			}
		} catch(PDOException $ex) {
			echo $ex->getMessage();
            exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database SD only done error! >> ../Log/log.txt");
            exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
		}
        exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encode ended. >> ../Log/log.txt");
        exec("echo ------------------------------------------ >> ../Log/log.txt");
	}

}


function checkfile($file_path,$id)
{
	$resolution_str = exec("ffprobe -v error -select_streams v:0 -show_entries stream=height -of csv=s=x:p=0 ".$file_path);
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Resolution extracted >> ../Log/log.txt");
	return (int)$resolution_str;
}

function encode_h264_720p($file_path,$id,$driver,$vid)
{
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Scanning for h264 720p... >> ../Log/log.txt");
	$encode_h264_720p_state = false;
	//do_in_database_hd_encode="p";
	$originalfile_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
	$originalfile_maxbitrate = (int)exec("ffmpeg -i ".$file_path." 2>&1 | grep -o -P '(?<=bitrate:).*(?=kb/s)'");
	$originalfile_codec = exec("ffmpeg -i ".$file_path." 2>&1 | grep -o -P '(?<=Video: ).*(?= \(avc1)'");
	echo "\n\n>>> Extension: ".$originalfile_extension." - Bitrate: ".$originalfile_maxbitrate." kb/s - Codec: ".$originalfile_codec."\n";
	if (($originalfile_maxbitrate<2100) && ($originalfile_extension =="mp4") && (($originalfile_codec == "h264 (Main)")||($originalfile_codec == "h264 (High)")||($originalfile_codec == "h264 (Baseline)")))
	{
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] No encoding needed for h264 720p. >> ../Log/log.txt");
		echo ">>> Encoding not needed for HD, only moving.\n\n\n";
		exec("cp -f ".$file_path." ../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_720.mp4");
	}
	else
	{
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encoding for h264 720p started... >> ../Log/log.txt");
		echo ">>> Encoding needed for HD, proceeding...\n\n\n";
		exec("ffmpeg -i ".$file_path." -b 2000k -maxrate 2000k -bufsize 2000k -ab 96k -vcodec libx264 -acodec aac -strict -2 -ac 2 -ar 44100 -s 1280x720 -y ../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_720.mp4");
	}
	//Can Check error with:
	//exec("ffmpeg -v error -i ../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_720.mp4 -f null - 2>error.log");
	//then check if error.log is empty, else redo the encoding
	if (file_exists("../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_720.mp4"))
	{
	    $done="y";
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encoding for h264 720p Ended. >> ../Log/log.txt");
		echo "\n\n>>> Successful 720p h264 encoding!\n\n\n";
		try {
			$stmt = $driver->prepare("UPDATE videos SET hd_encode = :p WHERE id = :i");
			$stmt->bindparam(":p", $done);
			$stmt->bindparam(":i", $vid["id"]);
			if($stmt->execute()){
                exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database HD set to done. >> ../Log/log.txt");
			}
		} catch(PDOException $ex) {
			echo $ex->getMessage();
            exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database HD set to done error! >> ../Log/log.txt");
            exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
		}
		$encode_h264_720p_state = true;
	}
	return $encode_h264_720p_state;
	
}
function encode_h264_480p($file_path,$id,$driver,$vid)
{
	exec("echo ".get_current_datetime()." [EVENT] [".$id."] Scanning for h264 480p... >> ../Log/log.txt");
	$encode_h264_480p_state = false;
	//do_in_database_lq_encode="p";
	$originalfile_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
	$originalfile_maxbitrate = (int)exec("ffmpeg -i ".$file_path." 2>&1 | grep -o -P '(?<=bitrate:).*(?=kb/s)'");
	$originalfile_codec = exec("ffmpeg -i ".$file_path." 2>&1 | grep -o -P '(?<=Video: ).*(?= \(avc1)'");
	echo "\n\n>>> Extension: ".$originalfile_extension." - Bitrate: ".$originalfile_maxbitrate." kb/s - Codec: ".$originalfile_codec."\n";
	if (($originalfile_maxbitrate<1000) && ($originalfile_extension =="mp4") && (($originalfile_codec == "h264 (Main)")||($originalfile_codec == "h264 (High)")||($originalfile_codec == "h264 (Baseline)")))
	{
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] No encoding needed for h264 480p. >> ../Log/log.txt");
		echo ">>> Encoding not needed for SD, only moving.\n\n\n";
		exec("cp -f ".$file_path." ../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_480.mp4");
	}
	else
	{
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encoding for h264 480p started... >> ../Log/log.txt");
		echo ">>> Encoding needed for SD, proceeding...\n\n\n";
		exec("ffmpeg -i ".$file_path." -b 800k -maxrate 800k -bufsize 800k -ab 96k -vcodec libx264 -acodec aac -strict -2 -ac 2 -ar 44100 -s 854x480 -y ../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_480.mp4");
	}
	if (file_exists("../Encodes/".pathinfo($file_path, PATHINFO_FILENAME)."_h264_480.mp4"))
	{
		$done="y";
	    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Encoding for h264 480p Ended. >> ../Log/log.txt");
		echo "\n\n>>> Successful 480p h264 encoding!\n\n\n";
		try {
			$stmt = $driver->prepare("UPDATE videos SET sd_encode = :p WHERE id = :i");
			$stmt->bindparam(":p", $done);
			$stmt->bindparam(":i", $vid["id"]);
			if($stmt->execute()){
                exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database SD set to done. >> ../Log/log.txt");
			}
		} catch(PDOException $ex) {
			echo $ex->getMessage();
            exec("echo ".get_current_datetime()." [ERROR] [".$id."] Update database SD set to done error! >> ../Log/log.txt");
            exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
		}
		$encode_h264_480p_state = true;
	}
	return $encode_h264_480p_state;
}

function duration($file_path,$driver,$vid,$id)
{
	$duration_str = exec("ffmpeg -i ".$file_path." 2>&1 | grep \"Duration\"| cut -d ' ' -f 4 | sed s/,// | sed 's@\..*@@g' | awk '{ split($1, A, \":\"); split(A[3], B, \".\"); print 3600*A[1] + 60*A[2] + B[1] }'");
	$duration = (int)$duration_str;
	echo "\n\n>>> Duration of file is ".$duration." seconds.\n\n\n";
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Duration extracted >> ../Log/log.txt");
    try {
        $stmt = $driver->prepare("UPDATE videos SET duration = :d WHERE id = :i");
        $stmt->bindparam(":d", $duration);
        $stmt->bindparam(":i", $vid["id"]);
        if($stmt->execute()){
            echo "\n Duration set.";
            exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database Duration updated >> ../Log/log.txt");
        }
    } catch(PDOException $ex) {
        echo $ex->getMessage();
        exec("echo ".get_current_datetime()." [ERROR] [".$id."] Duration extraction error! >> ../Log/log.txt");
        exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
    }

}

function originalSize($file_path,$driver,$vid,$id)
{
	$originalsize_bytes = filesize($file_path);
	$originalsize = round((float)($originalsize_bytes/1000000),2);
	echo "\n\n>>> Original size of file is ".$originalsize." Mo.\n\n\n";
    exec("echo ".get_current_datetime()." [EVENT] [".$id."] Original Size extracted >> ../Log/log.txt");
    try {
        $stmt = $driver->prepare("UPDATE videos SET original_size = :os WHERE id = :i");
        $stmt->bindparam(":os", $originalsize);
        $stmt->bindparam(":i", $vid["id"]);
        if($stmt->execute()){
            echo "\n Original size set.";
            exec("echo ".get_current_datetime()." [DATABASE] [".$id."] Database Original Size updated >> ../Log/log.txt");
        }
    } catch(PDOException $ex) {
        echo $ex->getMessage();
        exec("echo ".get_current_datetime()." [ERROR] [".$id."] Original size extraction error! >> ../Log/log.txt");
        exec("echo ERROR: ".$ex->getMessage()." >> ../Log/log.txt");
    }

}

function get_current_datetime()
{
	$now = new DateTime(null, new DateTimeZone('Europe/London'));
	return ("[".($now->format('Y-m-d H:i:s'))."]");
}

?>
