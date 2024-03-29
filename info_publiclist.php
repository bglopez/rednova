<? 
include("config.php");
//include("languages/$lang");
connectDB();

$info = array();

$info["GAMENAME"] = $game_name;
$info["GAMEID"] = md5($game_name . $bnt_ls_key);


	$xsql = "SELECT min(sb_date) as x FROM $dbtables[shoutbox]";
	$res = $db->Execute($xsql);
	$row = $res->fields;
$info["START-DATE"] = $row[x];
$info["G-DURATION"] = $GameDuration;

	$xsql = "SELECT count(*) as x FROM $dbtables[ships]";
	$res = $db->Execute($xsql);
	$row = $res->fields;
$info["P-ALL"] = $row[x];

	$xsql = "SELECT count(*) as x FROM $dbtables[ships] WHERE ship_destroyed = 'N' ";
	$res = $db->Execute($xsql);
	$row = $res->fields;
$info["P-ACTIVE"] = $row[x];

	$xsql = "SELECT count(*) as x FROM $dbtables[ships] WHERE ship_destroyed = 'N' AND email NOT LIKE '%@furangee'";
	$res = $db->Execute($xsql);
	$row = $res->fields;
$info["P-HUMAN"] = $row[x];

	$xsql = "SELECT COUNT(*) as x FROM $dbtables[ships] WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_login)) / 60 <= 5 and email NOT LIKE '%@furangee'";
	$res = $db->Execute($xsql);
	$row = $res->fields;
$info["P-ONLINE"] = $row[x];

	$res = $db->Execute("SELECT AVG(hull) AS a1 , AVG(engines) AS a2 , AVG(power) AS a3 , AVG(computer) AS a4 , AVG(sensors) AS a5 , AVG(beams) AS a6 , AVG(torp_launchers) AS a7 , AVG(shields) AS a8 , AVG(armour) AS a9 , AVG(cloak) AS a10 FROM $dbtables[ships] WHERE ship_destroyed='N' and email LIKE '%@furangee'");
	$row = $res->fields;
	$dyn_furangee_lvl = $row[a1] + $row[a2] + $row[a3] + $row[a4] + $row[a5] + $row[a6] + $row[a7] + $row[a8] + $row[a9] + $row[a10];
	$dyn_furangee_lvl = $dyn_furangee_lvl / 10;
$info["P-AI-LVL"] = $dyn_furangee_lvl;

	$xsql = "SELECT character_name, score  FROM $dbtables[ships] WHERE ship_destroyed = 'N' ORDER BY score DESC LIMIT 3 ";
	$res = $db->Execute($xsql);
	while(!$res->EOF)
		{
			$row = $res->fields;
			$tmp = $res->CurrentRow() + 1;
$info["P-TOP{$tmp}-NAME"] = $row[character_name];
$info["P-TOP{$tmp}-SCORE"] = $row[score];
			$res->MoveNext();
		}

$info["G-TURNS-START"] = $start_turns;
$info["G-TURNS-MAX"] = $max_turns;

$info["G-SCHED-TICKS"] = $sched_ticks;
$info["G-SCHED-TYPE"] = $sched_type;

$info["G-SPEED-TURNS"] = $sched_turns;
$info["G-SPEED-PORTS"] = $sched_ports;
$info["G-SPEED-PLANETS"] = $sched_planets;
$info["G-SPEED-IGB"] = $sched_IGB;

$info["G-SIZE-SECTOR"] = $sector_max;
$info["G-SIZE-UNIVERSE"] = $universe_size;
$info["G-SIZE-PLANETS"] = $max_planets_sector;
$info["G-SIZE-PLANETS-TO-OWN"] = $min_bases_to_own;

$info["G-COLONIST-LIMIT"] = $colonist_limit;
$info["G-DOOMSDAY-VALUE"] = $doomsday_value;

$info["G-MONEY-IGB"] = $ibank_interest;
$info["G-MONEY-PLANET"] = round($interest_rate - 1,4);

$info["G-PORT-LIMIT-ORE"] = $ore_limit;
$info["G-PORT-RATE-ORE"] = $ore_delta;
$info["G-PORT-DELTA-ORE"] = $ore_delta;

$info["G-PORT-LIMIT-ORGANICS"] = $organics_limit;
$info["G-PORT-RATE-ORGANICS"] = $organics_rate;
$info["G-PORT-DELTA-ORGANICS"] = $organics_delta;

$info["G-PORT-LIMIT-GOODS"] = $goods_limit;
$info["G-PORT-RATE-GOODS"] = $goods_rate;
$info["G-PORT-DELTA-GOODS"] = $goods_delta;

$info["G-PORT-LIMIT-ENERGY"] = $energy_limit;
$info["G-PORT-RATE-ENERGY"] = $energy_rate;
$info["G-PORT-DELTA-ENERGY"] = $energy_delta;

$info["G-SOFA"] = ($sofa_on===true?"1":"0");
$info["G-KSM"] = ($ksm_allowed?"1":"0");

$info["S-CLOSED"] = ($server_closed?"1":"0");
$info["S-CLOSED-ACCOUNTS"] = ($account_creation_closed?"1":"0");

$info["ALLOW_FULLSCAN"] = ($allow_fullscan?"1":"0");
$info["ALLOW_NAVCOMP"] = ($allow_navcomp?"1":"0");
$info["ALLOW_IBANK"] = ($allow_ibank?"1":"0");
$info["ALLOW_GENESIS_DESTROY"] = ($allow_genesis_destroy?"1":"0");


$info["INVENTORY_FACTOR"] = $inventory_factor;
$info["UPGRADE_COST"] = $upgrade_cost;
$info["UPGRADE_FACTOR"] = $upgrade_factor;
$info["LEVEL_FACTOR"] = $level_factor;


$info["DEV_GENESIS_PRICE"] = $dev_genesis_price;
$info["DEV_BEACON_PRICE"] = $dev_beacon_price;
$info["DEV_EMERWARP_PRICE"] = $dev_emerwarp_price;
$info["DEV_WARPEDIT_PRICE"] = $dev_warpedit_price;
$info["DEV_MINEDEFLECTOR_PRICE"] = $dev_minedeflector_price;
$info["DEV_ESCAPEPOD_PRICE"] = $dev_escapepod_price;
$info["DEV_FUELSCOOP_PRICE"] = $dev_fuelscoop_price;
$info["DEV_LSSD_PRICE"] = $dev_lssd_price;

$info["FIGHTER_PRICE"] = $fighter_price;
$info["TORPEDO_PRICE"] = $torpedo_price;
$info["ARMOUR_PRICE"] = $armour_price;
$info["COLONIST_PRICE"] = $colonist_price;

$info["BASEDEFENSE"] = $basedefense;

$info["COLONIST_PRODUCTION_RATE"] = $colonist_production_rate;
$info["COLONIST_REPRODUCTION_RATE"] = $colonist_reproduction_rate;
$info["ORGANICS_CONSUMPTION"] = $organics_consumption;
$info["STARVATION_DEATH_RATE"] = $starvation_death_rate;

$info["CORP_PLANET_TRANSFERS"] = ($corp_planet_transfers?"1":"0");
$info["MAX_TEAM_MEMBERS"] = $max_team_members;

$info["SERVERTIMEZONE"] = $servertimezone;

$info["ADMIN_MAIL"] = $admin_mail;
$info["LINK_FORUMS"] = $link_forums;



foreach ($info as $key => $value) {
	echo $key . ":" . $value . "<BR>";
}

?>
