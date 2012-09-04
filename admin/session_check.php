<?php
@session_start();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="./main.css"/>
<link rel="stylesheet" type="text/css" href="./allgemein.css"/>
<script src="./main.js" type="text/javascript"></script>
</head>
<body>

<?php
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
phpinfo();
?>

<h1 id="Test"
 onmouseover="this.innerHTML = 'Sehen Sie?'"
 onmouseout="this.innerHTML = 'Ich bin dynamisch'">Ich bin dynamisch</h1>


<div class="quote">Nummer eins</div>
<div id="tst">Nummer eins</div>
<a href="javascript:navigation_aufklappen('tst')">ausklappen</a>

<?php
@session_start();
include("../php.php");
include("server.php");

echo datum_anpassen($_GET["tag"]);

if(@mysql_connect('127.0.0.1', $mysql_benutzer, $mysql_passwort)) {
	echo 'ja';
}else{
	echo 'nein';
}



//echo '<script>history.go(-1)</script>';
/*function gettype($filename)<br />    {<br />        $result=explode(".",$filename);<br />        return $result[(count($result)-1)];<br />    }<br />*/

echo $filename = $_GET["i"];
//echo $filename = "alt<ßer.mis ter.jpeg";
echo '<br>';
$filename = htmlspecialchars($filename);
echo $filename = str_replace(' ','_',$filename);
$resultat = explode(".",$filename);
$typ = $resultat[count($resultat)-1];
$i=1;
while($i<count($resultat)){
	$name .= $resultat[$i-1];
	$name .= '_';
	$i++;
}
echo '<br>';
echo $name.time().'.'.$typ;


echo '<hr />';

echo '<h1>$_FILES</h1>';
echo '<pre>';
var_dump($_FILES);
echo '</pre>';

echo '<h1>$_SERVER</h1>';
echo '<pre>';
var_dump($_SERVER);
echo '</pre>';


echo '<h1>$_SESSION</h1>';
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';



echo '<h1>array</h1>';
echo '<pre>';
var_dump(array("<br />","<br>"));
echo '</pre>';

echo date("13.11.1986");
echo '<br />';
echo date("13-11-1986");
echo '<br />';
echo date("13. Nov.1986");
echo '<br />';

$anz = count($_SESSION);
echo '$_SESSION = '.$_SESSION."<br />";
echo '$anz = '.$anz."<br />";
for ($b=0; $b<$anz; $b++) {
	echo $b." = ";
	echo "key: ".key($_SESSION)." / value: ".current($_SESSION)."<br>";
	next($_SESSION);
	echo "<br />";
}




if (empty($_SESSION['zaehler'])) {
  $_SESSION['zaehler'] = 1;
} else {
  $_SESSION['zaehler']++;
}
?>

<p>
Hallo Besucher, Sie haben diese Seite <?php echo $_SESSION['zaehler']; ?> mal
aufgerufen.
</p>

<?php $blumen = array("Rose", "Tulpe", "Nelke", "Sonnenblume");
$b=1;
echo $blumen[$b];
?>

<p>
Hier gehts
<a href="session_check.php?<?php echo htmlspecialchars(SID); ?>">weiter</a>.
</p>

<hr>

<a href="" target="_self">leer</a>
<?php

var_dump($_SERVER);

$bild = imagecreatetruecolor(400, 300);
$background = imagecolorallocate($bild, 0, 0, 0);
imagefill($image, 0, 0, $background);
$col_ellipse = imagecolorallocate($bild, 255, 255, 255);
imageellipse($image, 200, 150, 300, 200, $col_ellipse);
header("Content-type: image/png");
imagepng($image);


//LOAD DATA LOCAL INFILE '/users/daniel/desktop/nulli.csv' INTO TABLE bilder LINES TERMINATED BY '/r'




var_dump(gd_info());
?>
</body>
</html>