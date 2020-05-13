<?php
date_default_timezone_set('Asia/Tokyo'); //基準時間を設定
set_time_limit(0); //実行時間を無限に設定

$uploaddir = '/var/www/html/file/';

//１時間以上たったら削除
$expire = strtotime("1 hour ago");

$list = scandir($uploaddir);
foreach($list as $value)
{
	$file = $uploaddir . $value;
	if(!is_file($file)) continue;
	$mod = filemtime($file);
	if($mod < $expire)
	{
		//chmod($file, 0666);
		unlink($file);
	}
}

#$c0 = 'rm '.$uploaddir.'/*';
#exec($c0, $o0, $r0);

$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{
	echo "<p><b>Upload success.</b></p>";
	#echo "<p>".$_FILES['userfile']['tmp_name']."</p>";
	#echo "<p>".$uploadfile."</p>";
	#$decodefile = $uploadfile.".wav";
	$path_parts = pathinfo($uploadfile);
	#$decodefile = $path_parts.['dirname'].'/'.$path_parts['filename'].'_decode.wav';
	$filename = $path_parts['filename'].'_decode.wav';
        $decodefile = $uploaddir.$filename;
	$cmd = 'python /var/www/html/fmradiodeco.py '.$uploadfile.' '.$decodefile;
	#echo "<p>".$cmd."</p>";
	#exec ("python /var/www/html/file/fmradiodeco.py /var/www/html/file/fmiq.wav /var/www/html/file/fmiq_d.wav", $out, $ret);
	exec($cmd, $out, $ret);
	#$ret = 0;
	if ($ret === 0)
	{
		echo "<p>decode ok</p>";
		#$filename = $path_parts['filename'].'_decode.wav';
		#echo "<p>".$filename."</p>";
        	#$path_parts = pathinfo($uploadfile);
        	#$decodefile = $path_parts['filename'].'_decode.wav';
		echo "<p><a href=\"http://133.130.97.142/file/".$filename."\">".$filename."</a></p>";
	}
	else
	{
		echo "<p>decode failed</p>".$ret;
	}
	#exec ("/var/www/html/file/fmd.sh", $out1, $ret1);
}
else
{
	echo "<p><b>Upload failed.</b></p>";
}
// DEBUG
// print_r($_FILES);
?>
