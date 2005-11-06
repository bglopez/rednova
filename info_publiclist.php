<? 
include("config.php");
include("languages/$lang");
connectDB();

echo "GAMENAME:" . $game_name . "<BR>";

$xsql = "SELECT min(sb_date) as x FROM $dbtables[shoutbox]";
$res = $db->Execute($xsql);
$row = $res->fields;
echo "START-DATE:" . $row[x] . "<BR>"; // Start Date

echo "G-DURATION:" . $GameDuration . "<BR>";

$xsql = "SELECT count(*) as x FROM $dbtables[ships]";
$res = $db->Execute($xsql);
$row = $res->fields;
echo "P-ALL:" . $row[x] . "<BR>"; // Absolut all Players

$xsql = "SELECT count(*) as x FROM $dbtables[ships] WHERE ship_destroyed = 'N' ";
$res = $db->Execute($xsql);
$row = $res->fields;
echo "P-ACTIVE:" . $row[x] . "<BR>"; // Total Players (incl AI, excl Destryed)

$xsql = "SELECT count(*) as x FROM $dbtables[ships] WHERE ship_destroyed = 'N' AND email NOT LIKE '%@furangee'";
$res = $db->Execute($xsql);
$row = $res->fields;
echo "P-HUMAN:" . $row[x] . "<BR>"; // Total Human Players (excl AI, excl Destryed)

$xsql = "SELECT COUNT(*) as x FROM $dbtables[ships] WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_login)) / 60 <= 5 and email NOT LIKE '%@furangee'";
$res = $db->Execute($xsql);
$row = $res->fields;
echo "P-ONLINE:" . $row[x] . "<BR>"; // Players Online

$res = $db->Execute("SELECT AVG(hull) AS a1 , AVG(engines) AS a2 , AVG(power) AS a3 , AVG(computer) AS a4 , AVG(sensors) AS a5 , AVG(beams) AS a6 , AVG(torp_launchers) AS a7 , AVG(shields) AS a8 , AVG(armour) AS a9 , AVG(cloak) AS a10 FROM $dbtables[ships] WHERE ship_destroyed='N' and email LIKE '%@furangee'");
$row = $res->fields;
$dyn_furangee_lvl = $row[a1] + $row[a2] + $row[a3] + $row[a4] + $row[a5] + $row[a6] + $row[a7] + $row[a8] + $row[a9] + $row[a10];
$dyn_furangee_lvl = $dyn_furangee_lvl / 10;
echo "P-AI-LVL:" . $dyn_furangee_lvl . "<BR>"; // Players Online


$xsql = "SELECT character_name, score  FROM $dbtables[ships] WHERE ship_destroyed = 'N' ORDER BY score DESC LIMIT 3 ";
$res = $db->Execute($xsql);
while(!$res->EOF)
	{
		$row = $res->fields;
		$tmp = $res->CurrentRow() + 1;
		echo "P-TOP{$tmp}-NAME:" . $row[character_name] . "<BR>";
		echo "P-TOP{$tmp}-SCORE:" . $row[score] . "<BR>";
		$res->MoveNext();
	}




echo "G-TURNS-START:" . $start_turns . "<BR>";
echo "G-TURNS-MAX:" . $max_turns . "<BR>";
echo "G-SPEED-TURNS:" . $sched_turns . "<BR>";
echo "G-SPEED-PORTS:" . $sched_ports . "<BR>";
echo "G-SPEED-PLANETS:" . $sched_planets . "<BR>";
echo "G-SPEED-IGB:" . $sched_IGB . "<BR>";


echo "G-SIZE-SECTOR:" . $sector_max . "<BR>";
echo "G-SIZE-UNIVERSE:" . $universe_size . "<BR>";
echo "G-SIZE-PLANETS:" . $max_planets_sector . "<BR>";


echo "G-MONEY-IGB:" . $ibank_interest . "<BR>";
echo "G-MONEY-PLANET:" . round($interest_rate - 1,4) . "<BR>";


echo "G-PORT-LIMIT:" . ($ore_limit + $organics_limit + $goods_limit + $energy_limit) . "<BR>";
echo "G-PORT-RATE:" . ($ore_rate + $organics_rate + $goods_rate + $energy_rate) . "<BR>";
echo "G-PORT-DELTA:" . ($ore_delta + $organics_delta + $goods_delta + $energy_delta) . "<BR>";


echo "G-SOFA:" . $sofa_on . "<BR>"; //
echo "G-KSM:" . $ksm_allowed . "<BR>"; //


echo "S-CLOSED:" . $server_closed . "<BR>"; //
echo "S-CLOSED-ACCOUNTS:" . $account_creation_closed . "<BR>"; //

/*
$sched_type 
$min_bases_to_own
$colonist_limit

$admin_mail

$allow_fullscan = true;                // full long range scan
$allow_navcomp = true;                 // navigation computer
$allow_ibank = true;                  // Intergalactic Bank (IGB)
$allow_genesis_destroy = true;         // Genesis torps can destroy planets

$link_forums
*/

?>
