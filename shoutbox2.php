<?
include("config.php");
updatecookie();
include("languages/$lang");
$title="SHOUTBOX SAVED";
include("header.php");

connectdb();

if(checklogin())
{
  die();
}

if ($sbw=="i")
	bigtitle();

$result = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $result->fields;

$sbt = strip_tags($sbt);
$sbt = substr($sbt,0,70);

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

$sbt = rawurlencode($sbt);

// Check Team shout or public
$sb_alli = (($playerinfo[team]<=0)?-1:$playerinfo[team]);
if (isset($SBPB)) $sb_alli = 0;

// Check double post!
$result = $db->Execute("SELECT * FROM $dbtables[shoutbox] ORDER BY sb_date DESC");
$lastshout = $result->fields;
if ($lastshout[sb_text] == $sbt)
	$sbt = "";


// Add Shout only if not empty !
if ($sbt != "")
	$res = $db->Execute("INSERT INTO $dbtables[shoutbox] (player_id,player_name,sb_date,sb_text,sb_alli) VALUES ($playerinfo[ship_id],'$playerinfo[character_name]'," . time() . ",'$sbt',$sb_alli) ");

echo "<BR>Shout saved!<BR><BR>";

if ($sbw=="i")
{
TEXT_GOTOMAIN();
include("footer.php");
}
if ($sbw=="w")
{
echo "Return to the <A HREF='shoutbox.php'>SHOUTBOX</A>";
}
?>