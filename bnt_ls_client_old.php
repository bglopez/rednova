<?
if (preg_match("/bnt_ls_client.php/i", $PHP_SELF))
{die();} else {
if ($bnt_ls===true) {

	$gm_url = $SERVER_NAME;
	if ( ($gm_url == "localhost") || ($gm_url == "127.0.0.1") || ($gm_url == "") )
		{
			$gm_url = $gamedomain . $gamepath;
		} else {
			$gm_url = $gm_url . strrev(strstr(strrev($PHP_SELF),"/"));
		}

	$gm_speed = $sched_ports + $sched_planets + $sched_IGB;
	$gm_speed_turns = $sched_turns;
	$gm_size_sc = $sector_max;
	$gm_size_un = $universe_size;
	$gm_size_pl = $max_planets_sector;
	$gm_money_igb = $ibank_interest;
	$gm_money_pl = round($interest_rate - 1,4);
	$gm_port_limit = $ore_limit + $organics_limit + $goods_limit + $energy_limit;
	$gm_port_rate = $ore_rate + $organics_rate + $goods_rate + $energy_rate;
	$gm_port_delta = $ore_delta + $organics_delta + $goods_delta + $energy_delta;
	$gm_sofa_on = $sofa_on;
	if ($sofa_on===false) $gm_sofa_on = 0;
	
	$gm_all = "gm_speed=" . $gm_speed .
		"&gm_speed_turns=" . $gm_speed_turns .
		"&gm_size_sc=" . $gm_size_sc .
		"&gm_size_un=" . $gm_size_un .
		"&gm_size_pl=" . $gm_size_pl .
		"&gm_money_igb=" . $gm_money_igb .
		"&gm_money_pl=" . $gm_money_pl .
		"&gm_port_limit=" . $gm_port_limit .
		"&gm_port_rate=" . $gm_port_rate .
		"&gm_port_delta=" . $gm_port_delta .
		"&gm_sofa_on=" . $gm_sofa_on .
		"&gm_url=" . rawurlencode($gm_url) .
		"&gm_name=" . rawurlencode($game_name);
	
	$res = $db->Execute("SELECT COUNT(*) AS x FROM $dbtables[ships] WHERE ship_destroyed='N' and email NOT LIKE '%@furangee' AND turns_used > 0");
	$row = $res->fields;
	$dyn_players = $row[x];
	
	$res = $db->Execute("SELECT score,character_name FROM $dbtables[ships] WHERE ship_destroyed = 'N' ORDER BY score DESC");
	$row = $res->fields;
	$dyn_top_score = $row[score];
	$dyn_top_player = $row[character_name];

	$res = $db->Execute("SELECT COUNT(*) AS x FROM $dbtables[ships] WHERE ship_destroyed='N' and email LIKE '%@furangee'");
	$row = $res->fields;
	$dyn_furangee = $row[x];

	$res = $db->Execute("SELECT AVG(hull) AS a1 , AVG(engines) AS a2 , AVG(power) AS a3 , AVG(computer) AS a4 , AVG(sensors) AS a5 , AVG(beams) AS a6 , AVG(torp_launchers) AS a7 , AVG(shields) AS a8 , AVG(armour) AS a9 , AVG(cloak) AS a10 FROM $dbtables[ships] WHERE ship_destroyed='N' and email LIKE '%@furangee'");
	$row = $res->fields;
	$dyn_furangee_lvl = $row[a1] + $row[a2] + $row[a3] + $row[a4] + $row[a5] + $row[a6] + $row[a7] + $row[a8] + $row[a9] + $row[a10];
	$dyn_furangee_lvl = $dyn_furangee_lvl / 10;
	
	$dyn_all = "&dyn_players=" . $dyn_players .
		"&dyn_furangee=" . $dyn_furangee .
		"&dyn_furangee_lvl=" . $dyn_furangee_lvl .
		"&dyn_top_score=" . $dyn_top_score .
		"&dyn_top_player=" . rawurlencode($dyn_top_player) .
		"&dyn_key=" . rawurlencode($bnt_ls_key);
	
	// echo str_replace("&", "<BR>", $gm_all);
	// echo "<BR>";
	// echo str_replace("&", "<BR>", $dyn_all);

	$url = $bnt_ls_url . "bnt_ls_server.php?" . $gm_all . $dyn_all;
	if (isset($creating)) { $url = $url . "&gm_reset=1"; }
	// echo "\n\n<!--" . $url . "-->\n\n";
	
	// $page = implode("", @file($url));
	$i = file($url);
}
}
?>