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

$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{
	echo "<p><b>Upload success.</b></p>";
	$path_parts = pathinfo($uploadfile);
	$filename = $path_parts['filename'].'_decode.wav';
        $decodefile = $uploaddir.$filename;
	$cmd = 'python /var/www/html/gr_s2p4m_fmradiodecode-1.py '.$uploadfile.' '.$decodefile;
	exec($cmd, $out, $ret);
	if ($ret === 0)
	{
		echo "<p>decode ok</p>";
		echo "<p><a href=\"http://133.130.97.142/file/".$filename."\">".$filename."</a></p>";
	}
	else
	{
		echo "<p>decode failed</p>".$ret;
	}
}
else
{
	echo "<p><b>Upload failed.</b></p>";
}
// DEBUG
// print_r($_FILES);
?>
