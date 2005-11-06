<?

  if (preg_match("/sched_planets.php/i", $PHP_SELF)) {
      echo "You can not access this file directly!";
      die();
  }

  $expoprod = mypw($colonist_reproduction_rate + 1, $multiplier);
  $expoprod *=$multiplier;

  $expocreds = mypw($interest_rate, $multiplier);

  echo "<B>PLANETS</B><p>";

// Starvation
	$planetupdate = "UPDATE $dbtables[planets] SET " .
		"colonists = GREATEST( colonists - ( colonists * $starvation_death_rate * $multiplier ) , 0 ) " .
		"WHERE organics <= 0 AND prod_organics < " . ($organics_consumption * 100 / $organics_prate);
	$db->Execute($planetupdate);

	$res = $db->Execute("SELECT owner,sector_id FROM $dbtables[planets] WHERE organics <= 0 AND prod_organics < " . ($organics_consumption * 100 / $organics_prate));
	$starvation = "Many";
	while(!$res->EOF)
	{
		$row = $res->fields;
		playerlog($row[owner], LOG_STARVATION, "$row[sector_id]|$starvation");
		$res->MoveNext();
	}

	echo "Starvation ... DONE<BR>";


// Replaced because of Organic Planet Bug
// "organics=organics + GREATEST(((LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $organics_prate * prod_organics / 100.0 * $expoprod) - LEAST(colonists, $colonist_limit) * $colonist_production_rate * $organics_consumption * $expoprod,0)," .
  $planetupdate = "UPDATE $dbtables[planets] SET " .
    "organics=GREATEST((organics + ((LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $organics_prate * prod_organics / 100.0 * $expoprod) - LEAST(colonists, $colonist_limit) * $colonist_production_rate * $organics_consumption * $expoprod),0)," . 
    "ore=ore + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $ore_prate * prod_ore / 100.0 * $expoprod," .
    "goods=goods + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $goods_prate * prod_goods / 100.0 * $expoprod," .
    "energy=energy + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $energy_prate * prod_energy / 100.0 * $expoprod," .
    "colonists=LEAST(colonists + (colonists - (colonists * $starvation_death_rate)) * $colonist_reproduction_rate * $expoprod, $colonist_limit)," .
    "credits=credits * $expocreds + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $credits_prate * (100.0 - prod_organics - prod_ore - prod_goods - prod_energy - prod_fighters - prod_torp) / 100.0 * $expoprod " . 
    "WHERE organics > 0 OR prod_organics >= " . ($organics_consumption * 100 / $organics_prate);
  $db->Execute($planetupdate);

  $planetupdate = "UPDATE $dbtables[planets] SET " .
    "fighters=fighters + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $fighter_prate * prod_fighters / 100.0 * $expoprod," .
    "torps=torps + (LEAST(colonists, $colonist_limit) * $colonist_production_rate) * $torpedo_prate * prod_torp / 100.0 * $expoprod " .
    "WHERE owner!=0 AND " . 
    "( organics > 0 OR prod_organics >= " . ($organics_consumption * 100 / $organics_prate) . " )";
  $db->Execute($planetupdate);


  $multiplier = 0;

  echo "Planets updated.<BR><BR>";
  echo "<BR>";

?>

