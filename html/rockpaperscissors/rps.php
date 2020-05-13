<html>
<title>Rock, Paper, Scissors</title>
<body>
<div id="discrption">
<p>じゃんけーんぽーん</p>
</div>

<form method="POST" action="">
<input type="submit" value="ぐー" name="sub-rps">　
<input type="submit" value="ぱー" name="sub-rps">　
<input type="submit" value="ちょき" name="sub-rps">　
</form>

<?php
if (isset($_POST["sub-rps"])) {
  $uin = htmlspecialchars($_POST["sub-rps"], ENT_QUOTES, "UTF-8");
  $mes = "<p>あなた：　";
  switch ($uin) {
      case "ぐー":
        echo $mes."ぐー</p>";
        $b = 1;
        break;
      case "ぱー":
        echo $mes."ぱー</p>";
        $b = 2;
        break;
      case "ちょき":
        echo $mes."ちょき</p>";
        $b = 3;
        break;
      default:  echo "エラー"; exit;
  }

  //$a = 0;
  //echo '<p>'.$a.'</p>';
  $a = rand(1, 3);
  //$s = (string)$a;
  //echo '<p>'.$a.'</p>';
  switch ($a) {
    case "1":
      //echo "<p>Rock</p>";
      echo "<img src=\"./janken_gu.png\" width=\"220\" height=\"220\" alt=\"Rock\">";
      break;
    case "2":
      //echo "<p>Paper</p>";
      echo "<img src=\"./janken_pa.png\" width=\"220\" height=\"220\" alt=\"Paper\">";
      break;
    case "3":
      //echo "<p>Scissors</p>";
      echo "<img src=\"./janken_choki.png\" width=\"220\" height=\"220\" alt=\"Scissors\">";
      break;
    default:  echo "<p>Error</p>"; exit;
  }

  if($b == $a) {
    echo "<p>あいこですね。 結果をtweetする：<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=あいこやったで。\">あいこやったで。</a></p>";
  }
  else if($b == 1 && $a == 3){
    echo "<p>あなたの勝ち。 結果をtweetする：<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=勝ったやで。\">勝ったやで。</a></p>";
  }
  else if($b == 3 && $a == 1){
    echo "<p>あなたの負け。 結果をtweetする：<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=負けたやで。\">負けたやで。</a></p>";
  }
  else if($b < $a) {
    echo "<p>あなたの負け。 結果をtweetする：<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=負けたやで。\">負けたやで。</a></p>";
  }
  else if($b > $a){
    echo "<p>あなたの勝ち。 結果をtweetする：<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=勝ったやで。\">勝ったやで。</a></p>";
  }
}
?>

<div id="reference">
<p>じゃんけん画像の素材元： <a href="https://www.irasutoya.com/2013/07/blog-post_5608.html">じゃんけんのイラスト「グー・チョキ・パー」 | かわいいフリー素材集 いらすとや</a></p>
</div>

<div id="twitter-button">
<p>
<a class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=Rock,%20Paper,%20Scissors%20Game%20|%20Tomorinao%20works!!">
Tweet</a>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</p>
</div>

</body>
</html>
