<?
include("config.php");
updatecookie();

include("languages/$lang");
$title="Smilie's for the Shoutbox";
include("header.php");

connectdb();
bigtitle();


$a = array(':)',':(',':o',':D',';)',':P',':cool:',':roll:',':mad:',':eek:',':confused:');
$r = count($a);

echo "\n\n<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=3>\n";
echo "<TR>\n";

for ($i=0;$i<$r;$i++)
{
		echo "<TD width=100 height=50 align=center valign=middle><B>$a[$i]</B></TD>\n";
		echo "<TD width=50 height=50 align=center valign=middle>" . x_tag($a[$i]) . "</TD>\n";
		if (($i+1) % 3 == 0) echo "</TR><TR>\n";
}
echo "</TR>\n";
echo "</TABLE>\n\n";

echo "<BR><BR>";
if(empty($username))
{
  TEXT_GOTOLOGIN();
}
else
{
  TEXT_GOTOMAIN();
}

include("footer.php");

function x_tag($sbt)
	{
		$itag1 = "<IMG BORDER=0 SRC='images/";
		$itag2 = "'>";
		$sbt = str_replace(":)",$itag1 . "smile.gif" . $itag2,$sbt);
		$sbt = str_replace(":(",$itag1 . "cry.gif" . $itag2,$sbt);
		$sbt = str_replace(":o",$itag1 . "redface.gif" . $itag2,$sbt);
		$sbt = str_replace(":D",$itag1 . "biggrin.gif" . $itag2,$sbt);
		$sbt = str_replace(";)",$itag1 . "wink.gif" . $itag2,$sbt);
		$sbt = str_replace(":P",$itag1 . "razz.gif" . $itag2,$sbt);
		$sbt = str_replace(":cool:",$itag1 . "cool.gif" . $itag2,$sbt);
		$sbt = str_replace(":roll:",$itag1 . "rolleyes.gif" . $itag2,$sbt);
		$sbt = str_replace(":mad:",$itag1 . "mad.gif" . $itag2,$sbt);
		$sbt = str_replace(":eek:",$itag1 . "eek.gif" . $itag2,$sbt);
		$sbt = str_replace(":confused:",$itag1 . "confused.gif" . $itag2,$sbt);
	return $sbt;
	}
?>