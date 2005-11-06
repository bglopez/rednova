<? 
include("config.php");
include("languages/$lang");

$title=".:: Red Nova Trader :.: Log view ::.";
include("header.php");
bigtitle();
connectDB();

if (!isSet($day)) $day = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));

echo "\n\n";
/*
SetCookie("rednova","",time()-3600,$gamepath,$gamedomain);
if ($rednova != '' and $rednova != '#') {
  $rednovaIP = substr($rednova, 0, strpos($rednova, "#"));
  $rednovaNAME = substr($rednova, strpos($rednova, "#")+1);
echo "(". $rednovaIP . ")";
echo "(". $rednovaNAME . ")";
}
*/

for($i=-5;$i<=5;$i++)
	{
	$j = mktime(0,0,0,date("m",$day),date("d",$day)+$i,date("Y",$day));
	if($j!=$day)
		{
		echo "<A HREF='?day=$j'>" . date("d",$j) . "</A>&nbsp;";
		} else {
		echo "<FONT COLOR=BLUE>" . date("d",$j) . "</FONT>&nbsp;";
		}
	}
echo "<BR><BR>\n\n";

$DateMin = mktime(0,0,0,date("m",$day),date("d",$day),date("Y",$day));
$DateMax = mktime(23,59,59,date("m",$day),date("d",$day),date("Y",$day));
//$DateMin = 0;
//$sql = "SELECT * FROM $dbtables[adm_logs] WHERE LogTime >= $DateMin AND LogTime <= $DateMax ORDER BY LogTime";
$sql = "SELECT * FROM $dbtables[adm_logs] LEFT JOIN $dbtables[ships] ON LogPlayer = ship_id  WHERE LogTime >= $DateMin AND LogTime <= $DateMax ORDER BY LogTime";
$res = $db->Execute($sql); 


	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1>";
	echo "<TR bgcolor=$color_header>";
	echo "<TH>&nbsp;Player&nbsp;</TH>";
	echo "<TH>&nbsp;IP&nbsp;</TH>";
	echo "<TH>&nbsp;TIME&nbsp;</TH>";
	echo "<TH>&nbsp;EVENT&nbsp;</TH>";
	echo "<TH>&nbsp;DATA1&nbsp;</TH>";
	echo "<TH>&nbsp;DATA2&nbsp;</TH>";
	echo "</TR>";
	while(!$res->EOF)
		{
		$row = $res->fields;
		if ($color_act == $color_line2) $color_act = $color_line1;
		else $color_act = $color_line2;
		echo "<TR bgcolor=$color_act>";

		echo "<TD>&nbsp;$row[character_name]</TD>";
		echo "<TD ALIGN=CENTER>&nbsp;&nbsp;$row[LogIP]&nbsp;&nbsp;</TD>";
		echo "<TD ALIGN=CENTER><FONT COLOR=#FFFFC0>" . date("H:i:s",$row[LogTime]) . "</FONT></TD>";
		echo "<TD>&nbsp;&nbsp;$row[LogEvent]&nbsp;&nbsp;</TD>";


if(($row[LogEvent]=="PC")&&($row[LogData2]!=0))
	{
	echo "<TD>PlanetID ($row[LogData1])&nbsp;</TD>";
	echo "<TD ALIGN=RIGHT>" . NUMBER($row[LogData2]) . "&nbsp;</TD>";

	$sql = "SELECT * FROM $dbtables[adm_logs] LEFT JOIN $dbtables[ships] ON LogPlayer = ship_id  WHERE LogTime < $row[LogTime] AND LogEvent = 'PC' AND LogData1 = '$row[LogData1]' ORDER BY LogTime DESC LIMIT 1";
	$res2 = $db->Execute($sql);
	echo "<TD>";
	if($res2->fields[character_name]==null)
		{
		$sql = "SELECT * FROM $dbtables[adm_logs] LEFT JOIN $dbtables[ships] ON LogPlayer = ship_id  WHERE LogTime < $row[LogTime] AND LogEvent = 'PGC' AND LogData2 = '$row[LogData1]' ORDER BY LogTime DESC LIMIT 1";
		$res2 = $db->Execute($sql);
		if($res2->fields[character_name]!=null) echo "(G) ";
		else echo "<I>unknown</I>";
		} else
		echo "(C) ";
	echo $res2->fields[character_name];
	echo "</TD>";
	}
else
	{
	echo "<TD>$row[LogData1]&nbsp;</TD>";
	echo "<TD>$row[LogData2]</TD>";
	}





		echo "</TR>";

		$res->MoveNext();
		}
	echo "</TABLE>";

?>

<PRE><TT><FONT SIZE=+1>
Events (8 chars): 12345678
1P = Planet
  2G = Genesis
    3C = Created (SectorID, PlanetID)
    3D = Destroyed (SectorID, PlanetID)
  2P = Problem
    3N = not in same sector (PlayerSector, PlanetID)
  2C = Captured (PlanetID,CreditsOnPlanet, From who?)
  2S = SOFA (PlanetID,MoreDetailsAsText)
  2A = Attack (PlanetID,MoreDetailsAsText)
    3K = Planet Self Kill (PlanetID,SectorID)
    3D = Planet Defeaded (PlanetID,SectorID)
1S = Ship
  2L = Login
    3O = Old ID found (ShipID+Timestamp,none)
    3N = New ID created (ShipID+Timestamp,none)
    3K = Key not found (ShipID+Timestamp,none)
  2P = Problem
    3K = Key wront! Multi detected! (?,?)
    3I = IP! Multi detected! (?,?)
  2S = 2Ship
    3C = Combat on planets! (TargetPlayerID,MoreText)
    3A = Attack in Sapce! (TargetPlayerID,MoreText)
</FONT></TT></PRE>

<?

include("footer.php");

function InBox($text)
	{
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1><TR><TD>";
	echo $text;
	echo "</TD></TR></TABLE>";
	}
?>