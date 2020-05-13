<?php
set_time_limit(0);
$uploaddir = '/var/www/html/file/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{
	echo "<p><b>Upload success.</b></p>";
	exec( "python /var/www/html/file/hello.py", $o1, $r1);
	if ($r1 === 0)
	{
		foreach($o1 as $k1 => $v1)
		{
			echo "<p>".$v1."</p>";
		}
	}			
	else
	{
		echo "<p>hello, failed</p>";
	}
	#exec ("cat /var/www/html/file/fmiq.wav > /var/www/html/file/fmiq_r.wav", $o2, $r2);
	exec ("/var/www/html/fmd.sh", $o3, $r3);
	if($r3===0){echo "<p>shell,ok</p>";}
	exec ("python /var/www/html/file/fmradiodeco.py /var/www/html/file/fmiq.wav /var/www/html/file/fmiq_d.wav", $out, $ret);
	if ($ret === 0)
	{
		echo "<p>python ok</p>";
	}
	else
	{
		echo "<p>python failed</p>".$ret;
	}
	#exec ("/var/www/html/file/fmd.sh", $out1, $ret1);
	exec ("ls /var/www/html/file", $output, $return_var); 
	if ($return_var === 0)
	{
		foreach($output as $key => $val)
		{
			//echo "<p>".$val."</p>";
			//echo "<p><a href=\"http://google.co.jp\">".$val."</a></p>";
			echo "<p><a href=\"http://133.130.97.142/file/".$val."\">".$val."</a></p>";
		}
	}
	else
	{
		echo "exec error : ".$return_var;
	}
}
else
{
	echo "<p><b>Upload failed.</b></p>";
}
// DEBUG
// print_r($_FILES);
?>
