<html>
<title>Hello, tomorinao</title>
<body>
<div id="status">
<p>Tomorinao works!</p>
</div>

<div id="projects">
<p>study project4: <a href="../cgi-bin/hello_clang.cgi">Hello world, C Lang. CGI, 2018/06/21</a></p>
<p>study project3: <a href="../cgi-bin/hello_python.cgi">Hello world, Python CGI, 2018/06/21</a></p>
<p>study project2: <a href="./rockpaperscissors/rps.php">Rock, Paper, Scissors, CGI (Common Gateway Interface), 2018/05/26</a></p>
<p>study project1: bitcoin button, php banner, 2018/05/26</p>
</div>

<div id="twitter-button">
<p>
<a class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=Hello,%20tomorinao">
Tweet</a>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</p>
</div>

<div id="bitcoin-button">
<?php
$bitcoinwallet = "362DCC5G98gfFS9aQeUREJE27xUhgZAGsY";  //  <== Delete ********** and paste your Bitcoin Address for donations!
echo "<a onclick=\"myFunction()\"><img src=\"./img/bcchan_donate_256x89.png\" width=\"176\" height=\"72\" alt=\"Read book\"></a>\n"
."<p id=\"demo\"></p>\n"
."<script>\n"
."function myFunction()\n"
."{\n"
."var x;\n"
."var person=prompt(\"To donate, please copy-paste my BitCoin address to your BitCoin software.\",\"$bitcoinwallet\");\n"
."if (person!=null)\n"
  ."{\n"
  ."x=\"Thank you for donation!\";\n"
  ."document.getElementById(\"demo\").innerHTML=x;\n"
  ."}\n"
."}\n"
."</script>\n";
?>
<p>bitcoin donation button source code: <a href="https://sourceforge.net/projects/bitcoin-button/">Bitcoin donation button and script download | SourceForge.net</a></p>
<p>bitcoin donation button image: <a href="http://www.bitcoin-chan.com/buttons/">Bitcoin-chan Project » Accepted/Donate Button</a></p>
</div>

</body>
</html>
