<?php
#----------------------------
# PHPアップローダー Ver 1.55
# 帰宅する部活
# http://www.k-php.com/
#
# UTF-8対応化
# XHTML1.0 strict対応化
# 煤
# http://susu.cc/
#----------------------------

### クラス定義
class CF_class {
## 初期設定
# 管理用パスワード
# (全ファイルのDELkey、DLkeyとして使えます)
var $master = "administrator";

# タイトル
var $title = "アップローダー";

# １ページあたりの表示件数
var $onep = "10";

# 最大保存件数
var $maxlog = "1000";

# このスクリプト名
var $script = "./index.php";

# ファイル一覧
var $alllog = "./alllog.cgi";

# 接頭語(file10010.gif,file10011.mp3とかにする場合は"file"です)
# (運営の途中で変更しないで下さい)
var $fnh = "file_";

# 最大ファイルバイト数(KBで指定)
var $max_file = "50000";

# 最大コメントバイト数(Byteで指定)
var $max_com = "80";

# 同一IPからの連続投稿規制(0にすると規制しません)
# (秒で指定)
var $wait = "10";

# カウンターを表示する(1=yes,0=no)	
var $counter = "0";

# コメント欄を入力必須にする(1=yes,0=no)	
var $com_must = "1";

# 最終投稿保存ログ
var $last_log = "./last.cgi";

# カウンターログ
var $count_log = "./count.cgi";

# ファイル保存フォルダ
var $src = "./src/";

# アップロードできる拡張子
var $upok = array("gif","bmp","png","jpg","cab","zip","lzh","txt","rar","gca","mpg","mp3","mp4","avi","swf","doc","3gp","amc","mid","pdf","ppt","xls","wmv","wav","flv","ai","psd","7z","tif","ico","ani","cur");

# 現在のページから前ページへの移動リンク
var $back = "3";

# 現在のページから次ページへの移動リンク
var $next = "4";

## 初期設定おわり


## ファンクション
# ヘッダー
function html_head() {
?>
<?php
$ua = $_SERVER['HTTP_USER_AGENT'];
if (!(ereg("Windows",$ua) && ereg("MSIE",$ua)) || ereg("MSIE 7",$ua)) { echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->title ?></title>
<link href="./style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
</head>

<body>
<table width="700" style="margin-left:auto;margin-right:auto;" summary="幅定義">
<tr>
<td>
<table class="wrap" cellspacing="1" cellpadding="1" summary="レイアウト定義">
<tr style="background-color:#ffffff">
<td>
<div class="title"><?php echo $this->title ?></div>
<div class="contents">
<?php
	}

# フッター
function html_foot() {
?>
<div class="copy">&copy;<a href="http://www.k-php.com">帰宅する部活</a></div>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>

<?php
}

# トップ表示
	function file_list($mo) {
		if($this->counter) {
			$fp = @fopen("$this->count_log","r+");
			@flock($fp,LOCK_EX);
			$l = @fgets($fp);
			$l++;
			@rewind($fp);
			@fputs($fp,$l);
			@fclose($fp);
			$disp_counter = "count:"."$l";
		}
	
		$file_list .= "<table style=\"margin-left:auto;margin-right:auto;text-align:center;background-color:#d0d0d0;\" width=\"60%\" cellpadding=\"4\" cellspacing=\"1\" summary=\"フォーム幅定義\">\n";
		$file_list .= "<tr>\n<td style=\"margin-left:auto;margin-right:auto;background-color:#eeeeee;\">\n";
		$file_list .= "<form action=\"$this->script?m=up\" method=\"post\" enctype=\"multipart/form-data\">\n";
		$file_list .= "<table style=\"width:100%;text-align:left;\" border=\"0\" summary=\"アップロードのための情報入力フォーム\">\n";
		$file_list .= "<tr>\n<td style=\"width:50px;\">ファイル</td><td colspan=\"3\"><input type=\"file\" name=\"file\" size=\"20\" />\n</td>\n</tr>\n";
		$file_list .= "<tr>\n<td>コメント</td>\n<td colspan=\"3\">\n<input type=\"text\" name=\"com\" size=\"50\" onfocus=\"if (this.value == '文字を入れてネ。') this.value = '';\" onblur=\"if (this.value == '') this.value = '文字を入れてネ。';\" value=\"文字を入れてネ。\" />\n</td>\n</tr>\n";
		$file_list .= "<tr>\n<td>DLkey</td>\n<td>\n<input type=\"text\" name=\"dlkey\" size=\"8\" maxlength=\"10\" onfocus=\"if (this.value == 'DLkey') this.value = '';\" onblur=\"if (this.value == '') this.value = 'DLkey';\" value=\"DLkey\" />\n</td>\n<td style=\"width:50px;\">Delkey</td>\n<td style=\"width:180px;\">\n<input type=\"text\" name=\"delkey\" size=\"8\" maxlength=\"10\" onfocus=\"if (this.value == 'DELkey') this.value = '';\" onblur=\"if (this.value == '') this.value = 'DELkey';\" value=\"DELkey\" /> <input type=\"submit\" value=\"アップロード\" />\n</td>\n</tr>\n";
		$file_list .= "</table>\n";
		$file_list .= "<div style=\"font-size:12px;padding:5px;color:#666666;\">\n";
		$file_list .= "サイズ：$this->max_file"."KBまで\n<br />";
		$file_list .= "拡張子：\n";
		for($i=0;$this->upok[$i];$i++) {
			$file_list .= $this->upok[$i]." ";
		}
		$file_list .= "<span style=\"float:right;\">$disp_counter <a href=\"$this->script\">[reload]</a></span>\n";
		$file_list .= "</div>\n";
		$file_list .= "</form>\n";
		$file_list .= "</td></tr>\n";
		$file_list .= "</table>\n";

		$lines = @file($this->alllog);
# 件数
		$all_row = count($lines);
# 全ページ数
		$pages = $all_row / $this->onep;
		if(preg_match("/\./","$pages")) {
			$pages = floor(($pages + 1));
		}
# 現在のページ
		$page = ($mo + $this->onep) / $this->onep;
		if($lines[0]) {
			$file_list .= "<span style=\"line-height:200%;\">ファイル数：$all_row"."件 ($page"."/$pages".")</span><br />\n";
		} else {
			$file_list .= "<br />\n";
		}
		$file_list .= "<table style=\"margin-left:auto;margin-right:auto;text-align:left;background-color:#d0d0d0;\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" summary=\"アップロードされたファイルの一覧。上が新しいファイル。\">\n";
		if(!$lines[0]) {
			$file_list .= "<tr style=\"background-color:#ffffff;\"><td>ファイルが1件もないです...</td></tr>\n";			
		} else {
			$file_list .= "<tr style=\"background-color:#eeeeee;\"><td>DL</td><td>コメント</td><td>サイズ</td><td>日付</td><td>元ファイル名</td><td>削除</td></tr>\n";
			if(!$mo) {$mo = 0;}
			for($i=0;$i!=$this->onep;$i++,$mo++) {
				if($lines[$mo]) {
					list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$lines[$mo]);
					if($dlkey != "") {
						$com = "<span style=\"color:red\">[DLkey]</span> ".$com;
						$dname = "<a href=\"$this->script?m=dp&amp;n=$this->fnh$num\">[$this->fnh$num".".$kac"."]</a>";
					} else {
						$dname = "<a href=\"$this->src$this->fnh$num.$kac\">[$this->fnh$num".".$kac"."]</a>";
					}
					$file_list .= "<tr style=\"background-color:#ffffff;\"><td>$dname</td><td>$com</td><td>".$this->format($size)."</td><td>$date</td><td>$orig</td><td><a href=\"$this->script?m=del&amp;n=$this->fnh$num\">[DEL]</a></td></tr>\n";
				}
			}
		}

		$file_list .= "</table>\n";
		if($this->onep < $all_row) {
			$file_list .= $this->move_link($page,$pages,$all_row,$mo);
		}
		$file_list .= "\n";
		return $file_list;
	}

# 移動用リンク
		function move_link($page,$pages,$all_row,$mo) {

## 移動用リンク
		$move_link .= "<div style=\"padding:10px;\">";
# 先頭ページ
		if(($page - $this->back) > 1) {
		$move_link .= "<a href=\"".$this->script."?mo=0\" class=\"disp_link\">|<<</a> ";
		}
# 前ページ表示
		if(($mo - $this->onep) > 0) {
			$xpage = $page;
			$fp = $xpage - $this->back - 1;
			for($i=0;$i<$this->back;$fp++) {
				if($fp > -1) {
					$nmo = $fp * $this->onep;
					$dfp = $fp + 1;
					$move_link .= "<a href=\"".$this->script."?mo=$nmo\" class=\"disp_link\">$dfp</a> ";
				}
			$i++;
			}
		}
# 現在のページ表示
		$move_link .= "<b style=\"color:red\" class=\"disp_ltex\">$page</b> ";
# 次ページ表示
		if($mo < $all_row) {
			$xpage = $page;
			$nmo = $mo;
			for($i=0;$i<$this->next;$i++){
				$xpage++;
				if($pages < $xpage) {break;}
				$move_link .= "<a href=\"".$this->script."?mo=$nmo\" class=\"disp_link\">$xpage</a> ";
				$nmo = $nmo + $this->onep;
			}
		}
# 末ページ
		if(($mo + $this->onep * $this->next) < $all_row) {
			$nmo = ($pages * $this->onep) - $this->onep;
			$move_link .= "<a href=\"".$this->script."?mo=$nmo\" class=\"disp_link\">>>|</a>";
		}
		$move_link .= "</div>";
		return $move_link;
	}

# ファイルUP
	function file_up() {
		$keys = array_keys($_POST);
		for($k=0;$keys[$k];$k++) {
			$_POST[$keys[$k]] = str_replace("<","&lt;",$_POST[$keys[$k]]);
		}

		$img_tmp = $_FILES["file"]["tmp_name"];
		$img_name = $_FILES["file"]["name"];
		$img_size = $_FILES["file"]["size"];

		$f = strrev($img_name);
		$ext = substr($f,0,strpos($f,"."));
		$ext = strrev($ext);

# 拡張子を大文字に
		$ext_big = strtoupper($ext);
# 拡張子を小文字に
		$ext_small = strtolower($ext);
		if(!$img_tmp) {
			echo $this->error("ファイルを入力してください");
			echo $this->html_foot();
			exit;
		} elseif(($img_size/1024) > $this->max_file) {
			echo $this->error("ファイルサイズが大きすぎます");
			echo $this->html_foot();
			exit;
		} elseif(strlen($_POST[com]) > $this->max_com) {
			echo $this->error("コメントが長すぎます");
			echo $this->html_foot();
			exit;
		} elseif(!in_array($ext_small, $this->upok) and !in_array($ext_big, $this->upok)) {
			echo $this->error("不正なファイルです");
			echo $this->html_foot();
			exit;
		} elseif($this->com_must and $_POST[com] == "") {
			echo $this->error("コメントを入力してください");
			echo $this->html_foot();
			exit;
		}

		$nip = $_SERVER['REMOTE_ADDR'];		
		if($this->wait) {
		    $now = time();
		    $last = @fopen($this->last_log, "r+") or die("最終投稿保存ログを作成してください");
			$line = fgets($last);
			list($lbt, $lip) = explode("<>", $line);
			if($nip == $lip && $lbt > $now - $this->wait){
				echo $this->error("連続投稿を規制しています。もう少し間隔をあけてお試しください。");
				echo $this->html_foot();
				exit;
			}
			rewind($last);
			fputs($last, "$now<>$nip<>");
			fclose($last);
		}
		date_default_timezone_set('Asia/Tokyo'); //タイムゾーン
		$ndate = date("Y/m/d H:i");

		if($_POST[delkey] != "") {
			$ndelkey = crypt($_POST[delkey],vi);
		}

		$fp = @fopen($this->alllog,"r+") or die("ファイル一覧用ログを作成してください");
		stream_set_write_buffer($fp,0);
		flock($fp,LOCK_EX);

		$FSTline = fgets($fp);
		list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$FSTline);
		$nnum = $num + 1;
		if($_POST[dlkey] != "") {
			$ndlkey = crypt($_POST[dlkey],vi);
			$chars = "0123456789abcdefghijklmnopqrstuvwxyz";
			$maxrange = strlen($chars);
			$nid = "";
			for($s=0;$s!=20;$s++) {$nid .= $chars[rand(0,$maxrange)];}
			$to_path = "$this->src"."$nid".".$ext";
		} else {
			$to_path = "$this->src"."$this->fnh$nnum".".$ext";
		}

		$Plines = "$nnum<>$ext<>$_POST[com]<>$img_size<>$ndate<>$img_name<>$ndlkey<>$ndelkey<>$nid<>$nip<>0<>\n";
		$i = 0;
		rewind($fp);
		while (!feof($fp)) {
			$i++;
			$Eline = fgets($fp);
			if($i > $this->maxlog - 1) {
				list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$Eline);
				if($id != "") {
					@unlink("$this->src$id.$kac");
				} else {
					@unlink("$this->src$this->fnh$num.$kac");
				}
			} else {$Plines .= $Eline;}
		}
		ftruncate($fp, 0);
		rewind($fp);
		fputs($fp,$Plines);
		flock($fp,LOCK_UN);
		fclose($fp);

		move_uploaded_file("$img_tmp","$to_path");
	}

# DLページ
	function dl_page() {
		$lines = @file($this->alllog);
		for($i=0;$lines[$i];$i++) {
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$lines[$i]);
			if("$this->fnh$num" == $_GET[n]) {
				$flag = 1;
				break;
			}
		}
		if(!$flag) {
				echo $this->error("ファイルが見つかりません");
				echo $this->html_foot();
				exit;
		}

		$dl_page .= "<table style=\"margin-left:auto;margin-right:auto;text-align:center;background-color:#d0d0d0;\" width=\"60%\" cellpadding=\"4\" cellspacing=\"1\" summary=\"フォーム幅定義\">\n";
		$dl_page .= "<tr><td style=\"margin-left:auto;margin-right:auto;background-color:#eeeeee;\">\n";
		$dl_page .= "<span style=\"color:red\">$this->fnh$num.$kac"."をDLするにはDLkeyが必要なんです。</span>\n<br />";
		$dl_page .= "<form action=\"$this->script?m=dp&amp;n=$_GET[n]\" method=\"post\">\n";
		$dl_page .= "<table style=\"margin-left:auto;margin-right:auto;text-align:left;\" cellpadding=\"2\" summary=\"ファイル情報\">\n";
		$dl_page .= "<tr><td>ファイル</td><td>$this->fnh$num.$kac</td></tr>\n";
		$dl_page .= "<tr><td>日付</td><td>$date</td></tr>\n";
		$dl_page .= "<tr><td>サイズ</td><td>".$this->format($size)."</td></tr>\n";
		$dl_page .= "<tr><td>コメント</td><td>$com</td></tr>\n";
		$dl_page .= "<tr><td>DL数</td><td>$times</td></tr>\n";
		$dl_page .= "<tr><td>DLkey</td><td><input type=\"text\" size=\"12\" name=\"dlkey\" onfocus=\"if (this.value == 'DLkey') this.value = '';\" onblur=\"if (this.value == '') this.value = 'DLkey';\" value=\"DLkey\" /></td></tr>\n";
		$dl_page .= "\n";
		$dl_page .= "</table>\n";
		$dl_page .= "<p><input type=\"hidden\" name=\"dlroot\" value=\"1\" /></p>\n";
		$dl_page .= "<p><input type=\"submit\" value=\"ダウンロード\" /></p>\n";
		$dl_page .= "</form>\n";
		$dl_page .= "<a href=\"$this->script\">[トップへ]</a>\n";
		$dl_page .= "</td></tr>\n";
		$dl_page .= "</table>\n";

		$dl_page .= "\n";
		return $dl_page;
	}

# DL実行
	function dl_do() {
		$fp = @fopen($this->alllog,"r+") or die("ファイル一覧用ログを作成してください");
		stream_set_write_buffer($fp,0);
		flock($fp,LOCK_EX);

		while (!feof($fp)) {
			$Eline = fgets($fp);
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$Eline);
			if("$this->fnh$num" == $_GET[n]) {break;}
		}

		$dlnum = $num;
		$dlid = $id;
		$dlkac = $kac;

		if($_POST[dlkey] != $this->master or strlen($_POST[dlkey]) != strlen($this->master)) {
			if($_POST[dlkey] == "" or $dlkey != crypt($_POST[dlkey],vi)) {
				flock($fp,LOCK_UN);
				fclose($fp);
				echo $this->html_head();
				echo $this->error("DLkeyが不正です");
				echo $this->html_foot();
				exit;
			}
		}

		$Plines = "";
		rewind($fp);
		while (!feof($fp)) {
			$Eline = fgets($fp);
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$Eline);
			if("$this->fnh$num" == $_GET[n]) {
				$times++;
				$Plines .= "$num<>$kac<>$com<>$size<>$date<>$orig<>$dlkey<>$delkey<>$id<>$ip<>$times<>\n";
			} else {
				$Plines .= $Eline;
			}
		}

		ftruncate($fp, 0);
		rewind($fp);
		fputs($fp,$Plines);

		flock($fp,LOCK_UN);
		fclose($fp);

		$npath = "$this->src$dlid.$dlkac";
		header("Content-Disposition: attachment; filename=$this->fnh$dlnum.$dlkac");
		header("Content-type: application/x-csv");
		readfile ($npath);
		exit;
	}

# DELページ
	function del_page() {
		$lines = @file($this->alllog);
		for($i=0;$lines[$i];$i++) {
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$lines[$i]);
			if("$this->fnh$num" == $_GET[n]) {break;}
		}
		$dl_page .= "<table style=\"margin-left:auto;margin-right:auto;text-align:center;background-color:#d0d0d0;\" width=\"60%\" cellpadding=\"4\" cellspacing=\"1\" summary=\"フォーム幅定義\">\n";
		$dl_page .= "<tr><td style=\"margin-left:auto;margin-right:auto;background-color:#eeeeee;\">\n";
		$dl_page .= "<span style=\"color:red\">$this->fnh$num.$kac"."を削除します。</span>\n<br />";
		$dl_page .= "<form action=\"$this->script?m=deldo&amp;n=$_GET[n]\" method=\"post\">\n";
		$dl_page .= "<table style=\"margin-left:auto;margin-right:auto;text-align:left;\" cellpadding=\"2\" summary=\"ファイル情報\">\n";
		$dl_page .= "<tr><td>ファイル</td><td>$this->fnh$num.$kac</td></tr>\n";
		$dl_page .= "<tr><td>DELkey</td><td><input type=\"text\" size=\"12\" name=\"delkey\" onfocus=\"if (this.value == 'DELkey') this.value = '';\" onblur=\"if (this.value == '') this.value = 'DELkey';\" value=\"DELkey\" /></td></tr>\n";
		$dl_page .= "\n";
		$dl_page .= "</table>\n";
		$dl_page .= "<p><input type=\"submit\" value=\"DELETE\" /></p>\n";
		$dl_page .= "</form>\n";
		$dl_page .= "<a href=\"$this->script\">[トップへ]</a>\n";
		$dl_page .= "</td></tr>\n";
		$dl_page .= "</table>\n";

		$dl_page .= "\n";
		return $dl_page;
	}

# DEL実行
	function del_do() {
		$fp = @fopen($this->alllog,"r+") or die("ファイル一覧用ログを作成してください");
		stream_set_write_buffer($fp,0);
		flock($fp,LOCK_EX);

		while (!feof($fp)) {
			$Eline = fgets($fp);
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$Eline);
			if("$this->fnh$num" == $_GET[n]) {break;}
		}

		if($_POST[delkey] != $this->master or strlen($_POST[delkey]) != strlen($this->master)) {
			if($_POST[delkey] == "" or $delkey != crypt($_POST[delkey],vi)) {
				flock($fp,LOCK_UN);
				fclose($fp);
				echo $this->error("DELkeyが不正です");
				echo $this->html_foot();
				exit;
			}
		}

		rewind($fp);
		while (!feof($fp)) {
			$Eline = fgets($fp);
			list($num,$kac,$com,$size,$date,$orig,$dlkey,$delkey,$id,$ip,$times) = explode("<>",$Eline);
			if("$this->fnh$num" != $_GET[n]) {
				$Plines .= $Eline;
			} else {
				if($id != "") {
					@unlink("$this->src$id.$kac");
				} else {
					@unlink("$this->src$this->fnh$num.$kac");
				}
			}
		}

		ftruncate($fp, 0);
		rewind($fp);
		fputs($fp,$Plines);
		flock($fp,LOCK_UN);
		fclose($fp);
	}

# ファイルサイズフォーマット
	function format($esize) {
		if($esize > 1023) {
			$esize = floor($esize/1024);
			$esize .= "KB";
		} else {
			$esize .= "bytes";
		}
		return $esize;
	}

# エラー
	function error($mes) {
		$error .= "<table style=\"margin-left:auto;margin-right:auto;text-align:center;background-color:#d0d0d0;\" width=\"60%\" cellpadding=\"4\" cellspacing=\"1\" summary=\"フォーム幅定義\">\n";
		$error .= "<tr><td style=\"margin-left:auto;margin-right:auto;background-color:#eeeeee;\">\n";
		$error .= "<span style=\"color:red\">ERROR</span>\n<br />";
		$error .= "$mes<br />\n";
		$error .= "<input type=\"button\" value=\"戻る\" onclick=\"javascript:history.go(-1)\" onkeypress=\"history.back()\" />\n";
		$error .= "</td></tr>\n";
		$error .= "</table>\n";

		$error .= "\n";
		return $error;
	}

} ### クラス定義終了


### 各動作
# オブジェクト生成
$c = new CF_class;

## 分岐
switch($_GET[m]) {

	case dp://DLページ、実行
	if($_POST[dlroot]) {
		$c->dl_do();
	} else {
		$c->html_head();
		echo $c->dl_page();
	}
	break;

	case up://UP
	$c->html_head();
	$c->file_up();
	echo $c->file_list(0);
	break;

	case del://DELページ
	$c->html_head();
	echo $c->del_page();
	break;

	case deldo://DEL実行
	$c->html_head();
	$c->del_do();
	echo $c->file_list(0);
	break;

	default://トップ出力
	$c->html_head();
	echo $c->file_list($_GET[mo]);
	break;
}

# フッター出力
echo $c->html_foot();

?>