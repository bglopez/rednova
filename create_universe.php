<script language="php">
include("config.php");
include("languages/$lang");

/*
##############################################################################
# Create Universe Script                                                     #
#                                                                            #
# ChangeLog                                                                  #
#  Nov 2, 01 - Wandrer - Rewritten mostly from scratch                       #
##############################################################################
*/

/*
##############################################################################
# Define Functions for this script                                           #
##############################################################################
*/

### Description: Create Benchmark Class

class c_Timer {
   var $t_start = 0;
   var $t_stop = 0;
   var $t_elapsed = 0;

   function start() { $this->t_start = microtime(); }

   function stop()  { $this->t_stop  = microtime(); }

   function elapsed() {
      $start_u = substr($this->t_start,0,10); $start_s = substr($this->t_start,11,10);
      $stop_u  = substr($this->t_stop,0,10);  $stop_s  = substr($this->t_stop,11,10);
      $start_total = doubleval($start_u) + $start_s;
      $stop_total  = doubleval($stop_u) + $stop_s;
      $this->t_elapsed = $stop_total - $start_total;
      return $this->t_elapsed;
}
}

function PrintFlush($Text="") {
print "$Text";
flush();
}

function GetInfoArray($html) {
	$html = explode("<BR>",$html);
	$new = array();
	
	for($i=0;$i<count($html)-1;$i++) {
		$tmp = explode(":",$html[$i],2);
		$new[$tmp[0]] = $tmp[1];
	}
	
	return $new;
}

### End defining functions.

### Start Timer
$BenchmarkTimer = new c_Timer;
$BenchmarkTimer->start();

### Set timelimit and randomize timer.

set_time_limit(0);
srand((double)microtime()*1000000);

### Include config files and db scheme.

include("includes/schema.php");

### Update cookie.
updatecookie();

$title="Create Universe";
include("header.php");

### Connect to the database.

connectdb();

### Print Title on Page.

bigtitle();

### Manually set step var if info isn't correct.

if($swordfish != $adminpass) {
$step="0";
}

if($swordfish == $adminpass && $engage == "") {
$step="1";
}

if($swordfish == $adminpass && $engage == "1") {
$step="2";
}

### Main switch statement.

switch ($step) {
   case "1":
      echo "<form action=create_universe.php method=post>";
      echo "<table>";
// Domain Check
if($bnt_ls===true) {
      echo "<tr><td colspan=2 aling=center>";
 
      echo "<table border=1 cellspacing=0 cellpadding=2 width=100%>";
      echo "<tr><td>";

      echo "<FONT COLOR=red><B>Domain Check!</B></FONT><BR>";

			echo "You have entered: $bnt_ls_gameurl<BR>Checking ... ";
			$i = @file($bnt_ls_gameurl . "/info_publiclist.php");
			$i = GetInfoArray($i[0]);
			if($i[GAMENAME]==$game_name) {
				echo "<FONT COLOR=lightgreen><B>OK!</B></FONT>";
			} else {
				echo "<FONT COLOR=red><B>ERROR!</B></FONT><BR>";
				echo "<BR>You must must enter a correct URL to your game.<BR>Or disable the public list function (\$bnt_ls)!";
				die();
			}

      echo "</td></tr>";
      echo "</table><BR><BR>";
         echo "</td></tr>";
}
// Domain Check End
// Name Check
        {
      echo "<tr><td colspan=2 aling=center>";
 
      echo "<table border=1 cellspacing=0 cellpadding=2 width=100%>";
      echo "<tr><td>";

      echo "<FONT COLOR=red><B>Game name Check!</B></FONT><BR>";
      echo "This is the display name in the Public Master Game List: ";
                $gname = "lightgreen";
                if ($game_name=="BlackNova Traders v0.41") $gname = "red";
                if (preg_match("/rednova/i", $game_name)) $gname = "red";
                if (preg_match("/red nova/i", $game_name)) $gname = "red";
                if (preg_match("/www/i", $game_name)) $gname = "red";

                echo "<FONT COLOR=$gname><B>$game_name</B></FONT><BR>";
      echo "Should not be:<BR>";
      echo " - the default value '<I>BlackNova Traders v0.41</I>'<BR>";
      echo " - your URL<BR>";
      echo " - Rednova or Red Nova! (*reserved to my games)<BR>";
      echo "Can / Should:<BR>";
      echo " - Unique identify <I>you</I><BR>";
      echo " - Show the type (*e.g. <I>2week BLITZ game</I>)<BR>";
      echo " - Show the version (*e.g. <I>[BNT0.41/RPP0.1j]</I>)<BR>";

      echo "</td></tr>";
      echo "</table><BR><BR>";
                echo "</td></tr>";
        }
// Name Check End

      echo "<tr><td><b><u>Base/Planet Setup</u></b></td><td></td></tr>";
      echo "<tr><td>Percent Special</td><td><input type=text name=special size=5 maxlength=5 value=1></td></tr>";
      echo "<tr><td>Percent Ore</td><td><input type=text name=ore size=5 maxlength=5 value=15></td></tr>";
      echo "<tr><td>Percent Organics</td><td><input type=text name=organics size=5 maxlength=5 value=10></td></tr>";
      echo "<tr><td>Percent Goods</td><td><input type=text name=goods size=5 maxlength=5 value=15></td></tr>";
      echo "<tr><td>Percent Energy</td><td><input type=text name=energy size=5 maxlength=5 value=10></td></tr>";
      echo "<tr><td>Percent Empty</td><td>Equal to 100 - total of above.</td></tr>";
      echo "<tr><td>Initial Commodities to Sell<br><td><input type=text name=initscommod size=6 maxlength=6 value=100.00> % of max</td></tr>";
      echo "<tr><td>Initial Commodities to Buy<br><td><input type=text name=initbcommod size=6 maxlength=6 value=100.00> % of max</td></tr>";
      echo "<tr><td><b><u>Sector/Link Setup</u></b></td><td></td></tr>";
      $fedsecs = intval($sector_max / 200);
      $loops = intval($sector_max / 500);
      echo "<tr><td>Number of sectors total (<b>overrides config.php</b>)</td><td><input type=text name=sektors size=5 maxlength=5 value=$sector_max></td></tr>";
      echo "<TR><TD>Number of Federation sectors</TD><TD><INPUT TYPE=TEXT NAME=fedsecs SIZE=6 MAXLENGTH=6 VALUE=$fedsecs></TD></TR>";
      echo "<tr><td>Number of loops</td><td><input type=text name=loops size=6 maxlength=6 value=$loops></td></tr>";
      echo "<tr><td>Percent of sectors with unowned planets</td><td><input type=text name=planets size=5 maxlength=5 value=10></td></tr>";
      echo "<tr><td></td><td><input type=hidden name=engage value=1><input type=hidden name=step value=2><input type=hidden name=swordfish value=$swordfish><input type=submit value=Submit><input type=reset value=Reset></td></tr>";
      echo "</table>";
      echo "</form>";
      break;
   case "2":
      $sector_max = round($sektors);
      if($fedsecs > $sector_max) {
         echo "The number of Federation sectors must be smaller than the size of the universe!";
         break;
      }
      $spp = round($sector_max*$special/100);
      $oep = round($sector_max*$ore/100);
      $ogp = round($sector_max*$organics/100);
      $gop = round($sector_max*$goods/100);
      $enp = round($sector_max*$energy/100);
      $empty = $sector_max-$spp-$oep-$ogp-$gop-$enp;
      $nump = round ($sector_max*$planets/100);
      echo "So you would like your $sector_max sector universe to have:<BR><BR>";
      echo "$spp special ports<BR>";
      echo "$oep ore ports<BR>";
      echo "$ogp organics ports<BR>";
      echo "$gop goods ports<BR>";
      echo "$enp energy ports<BR>";
      echo "$initscommod% initial commodities to sell<BR>";
      echo "$initbcommod% initial commodities to buy<BR>";
      echo "$empty empty sectors<BR>";
      echo "$fedsecs Federation sectors<BR>";
      echo "$loops loops<BR>";
      echo "$nump unowned planets<BR><BR>";
      echo "If this is correct, click confirm - otherwise go back.<BR>";
      echo "<form action=create_universe.php method=post>";
      echo "<input type=hidden name=step value=3>";
      echo "<input type=hidden name=spp value=$spp>";
      echo "<input type=hidden name=oep value=$oep>";
      echo "<input type=hidden name=ogp value=$ogp>";
      echo "<input type=hidden name=gop value=$gop>";
      echo "<input type=hidden name=enp value=$enp>";
      echo "<input type=hidden name=initscommod value=$initscommod>";
      echo "<input type=hidden name=initbcommod value=$initbcommod>";
      echo "<input type=hidden name=nump value=$nump>";
      echo "<INPUT TYPE=HIDDEN NAME=fedsecs VALUE=$fedsecs>";
      echo "<input type=hidden name=loops value=$loops>";
      echo "<input type=hidden name=engage value=2>";
      echo "<input type=hidden name=swordfish value=$swordfish>";
      echo "<input type=submit value=Confirm>";
      echo "</form>";
      echo "<BR><BR><FONT COLOR=RED>";
      echo "WARNING: ALL TABLES WILL BE DROPPED AND THE GAME WILL BE RESET WHEN YOU CLICK 'CONFIRM'!</FONT>";
      break;
   case "3":

                // Hall of Fame finished with top 10 player
                $res = $db->Execute("SELECT GameID FROM $dbtables[hof_game] WHERE GameEnd IS NULL");
                $GameID = $res->fields[GameID];

                if (isSet($GameID))
                        {
                        // Set the end Time.
                        $res = $db->Execute("UPDATE $dbtables[hof_game] SET GameEnd = " . time() . " WHERE GameID = $GameID");

                        // Update Ranking!
                        include("sched_ranking.php");

                        // Add the Top 10 to the HOF
                        $i=0;
                        $res = $db->Execute("SELECT character_name, score, turns_used, email FROM $dbtables[ships] WHERE ship_destroyed='N' and email NOT LIKE '%@furangee' ORDER BY score DESC LIMIT 10");
                        while(!$res->EOF)
                                {
                                $row = $res->fields; $i++;
                                $res2 = $db->Execute("INSERT INTO $dbtables[hof_player] (PlayerGameID, PlayerName,PlayerScore,PlayerRank,PlayerTurns,PlayerEmail) VALUES($GameID,'$row[character_name]',$row[score],$i,$row[turns_used],'$row[email]')");
                                $res->MoveNext();
                                }
                        echo "\n<BR><B>Hall of Fame generated!</B><BR><BR>\n";
                        }
                // END - Hall of Fame finished with top 10 player

      create_schema();
      echo "<form action=create_universe.php method=post>";
      echo "<input type=hidden name=step value=4>";
      echo "<input type=hidden name=spp value=$spp>";
      echo "<input type=hidden name=oep value=$oep>";
      echo "<input type=hidden name=ogp value=$ogp>";
      echo "<input type=hidden name=gop value=$gop>";
      echo "<input type=hidden name=enp value=$enp>";
      echo "<input type=hidden name=initscommod value=$initscommod>";
      echo "<input type=hidden name=initbcommod value=$initbcommod>";
      echo "<input type=hidden name=nump value=$nump>";
      echo "<INPUT TYPE=HIDDEN NAME=fedsecs VALUE=$fedsecs>";
      echo "<input type=hidden name=loops value=$loops>";
      echo "<input type=hidden name=engage value=2>";
      echo "<input type=hidden name=swordfish value=$swordfish>";
      echo "<input type=submit value=Confirm>";
      echo "</form>";
      break;
   case "4":
      print("Creating sector 0 - Sol ");
      $initsore = $ore_limit * $initscommod / 100.0;
      $initsorganics = $organics_limit * $initscommod / 100.0;
      $initsgoods = $goods_limit * $initscommod / 100.0;
      $initsenergy = $energy_limit * $initscommod / 100.0;
      $initbore = $ore_limit * $initbcommod / 100.0;
      $initborganics = $organics_limit * $initbcommod / 100.0;
      $initbgoods = $goods_limit * $initbcommod / 100.0;
      $initbenergy = $energy_limit * $initbcommod / 100.0;
      $insert = $db->Execute("INSERT INTO $dbtables[universe] (sector_id, sector_name, zone_id, port_type, port_organics, port_ore, port_goods, port_energy, beacon, angle1, angle2, distance) VALUES ('0', 'Sol', '1', 'special', '0', '0', '0', '0', 'Sol: Hub of the Universe', '0', '0', '0')");
      PrintFlush($db->ErrorMsg());
      $update = $db->Execute("UPDATE $dbtables[universe] SET sector_id=0 WHERE sector_id=1");
      print("");
      PrintFlush("- completed successfully.<BR>");
      print("Creating sector 1 - Alpha Centauri ");
      $insert = $db->Execute("INSERT INTO $dbtables[universe] (sector_id, sector_name, zone_id, port_type, port_organics, port_ore, port_goods, port_energy, beacon, angle1, angle2, distance) VALUES ('1', 'Alpha Centauri', '1', 'energy',  '0', '0', '0', '0', 'Alpha Centauri: Gateway to the Galaxy', '0', '0', '1')");
      PrintFlush($db->ErrorMsg());
      print("");
      PrintFlush("- completed successfully.<BR>");
      $remaining = $sector_max-2;
      print("Creating remaining $remaining sectors ");
      ### Cycle through remaining sectors
      ### We are going to split the amount into groups of 500 and bulk pass all the info to mysql to
      ### figure out.
      $i=0;
      while ($i < ($remaining-500)):
         $insert="INSERT INTO $dbtables[universe] (sector_id,zone_id,angle1,angle2,distance) VALUES ";
         for ($j=1; $j<=499; $j++) {
            $distance=intval(rand(1,$universe_size));
            $angle1=rand(0,180);
            $angle2=rand(0,90);
            $insert.="('','1',$angle1,$angle2,$distance),";
         }
         $insert.="('','1',$angle1,$angle2,$distance);";
         $i=$i+500;
         ### Now lets post the information to the mysql database.
         $db->Execute("$insert");
         PrintFlush($db->ErrorMsg());
      endwhile;
      ### Now lets do the remaining sectors.
      $insert="INSERT INTO $dbtables[universe] (sector_id,zone_id,angle1,angle2,distance) VALUES ";
      for ($j=$i; $j<=$remaining-1; $j++) {
         $distance=intval(rand(1,$universe_size));
         $angle1=rand(0,180);
         $angle2=rand(0,90);
          $insert.="('','1',$angle1,$angle2,$distance),";
      }
      $insert.="('','1',$angle1,$angle2,$distance);";
      $j=$j+1;
      ### Now lets post the information to the mysql database.
      $db->Execute("$insert");
      $i=$j;
      print("");
      PrintFlush("- completed successfully.<br>");
      print("Selecting $fedsecs Federation sectors ");
      $replace = $db->Execute("REPLACE INTO $dbtables[zones] (zone_id, zone_name, owner, corp_zone, allow_beacon, allow_attack, allow_planetattack, allow_warpedit, allow_planet, allow_trade, allow_defenses, max_hull) VALUES ('1', 'Unchartered space', 0, 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '0' )");
      $replace = $db->Execute("REPLACE INTO $dbtables[zones](zone_id, zone_name, owner, corp_zone, allow_beacon, allow_attack, allow_planetattack, allow_warpedit, allow_planet, allow_trade, allow_defenses, max_hull) VALUES ('2', 'Federation space', 0, 'N', 'N', 'N', 'N', 'N', 'N',  'Y', 'N', '$fed_max_hull')");
      $replace = $db->Execute("REPLACE INTO $dbtables[zones](zone_id, zone_name, owner, corp_zone, allow_beacon, allow_attack, allow_planetattack, allow_warpedit, allow_planet, allow_trade, allow_defenses, max_hull) VALUES ('3', 'Free-Trade space', 0, 'N', 'N', 'Y', 'N', 'N', 'N','Y', 'N', '0')");
      $replace = $db->Execute("REPLACE INTO $dbtables[zones](zone_id, zone_name, owner, corp_zone, allow_beacon, allow_attack, allow_planetattack, allow_warpedit, allow_planet, allow_trade, allow_defenses, max_hull) VALUES ('4', 'War Zone', 0, 'N', 'Y', 'Y', 'Y', 'Y', 'Y','N', 'Y', '0')");
      $update = $db->Execute("UPDATE $dbtables[universe] SET zone_id='2' WHERE sector_id<$fedsecs");
      print("");
      PrintFlush("- completed successfully.<BR>");
      ### Finding random sectors where port=none and getting their sector ids in one sql query
      ### For Special Ports
      print("Selecting $spp sectors for additional special ports ");
      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' order by rand(unix_timestamp()) desc limit $spp");
      $i=0;
      $j=0;
      $update="UPDATE $dbtables[universe] SET zone_id='3',port_type='special' WHERE ";
      if($sql_query)
      {
         while (!$sql_query->EOF) {
            $result = $sql_query->fields;
            if ($i>499) {
               $update.="sector_id=9999999;";
               $db->Execute("$update");
               $update="UPDATE universe SET zone_id='3',port_type='special' WHERE ";
               $i=0;
               $j++;
               PrintFlush(". ");
            } else {
               $update.="(port_type='none' and sector_id=$result[sector_id]) or ";
               $i++;
               $j++;
            }
            $sql_query->MoveNext();
         }
      }
      $update.="sector_id=9999999";
      $db->Execute("$update");
      print("");
      PrintFlush("- completed successfully.<BR>");
      ### Finding random sectors where port=none and getting their sector ids in one sql query
      ### For Ore Ports
      $initsore = $ore_limit * $initscommod / 100.0;
      $initsorganics = $organics_limit * $initscommod / 100.0;
      $initsgoods = $goods_limit * $initscommod / 100.0;
      $initsenergy = $energy_limit * $initscommod / 100.0;
      $initbore = $ore_limit * $initbcommod / 100.0;
      $initborganics = $organics_limit * $initbcommod / 100.0;
      $initbgoods = $goods_limit * $initbcommod / 100.0;
      $initbenergy = $energy_limit * $initbcommod / 100.0;
      print("Selecting $oep sectors for ore ports ");
//      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' and rand() order by rand() desc limit $oep");
      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' order by rand(unix_timestamp()) desc limit $oep");
      $update="UPDATE $dbtables[universe] SET port_type='ore',port_ore=$initsore,port_organics=$initborganics,port_goods=$initbgoods,port_energy=$initbenergy WHERE ";
      $i=0;
      $j=0;
      if($sql_query)
      {
         while (!$sql_query->EOF) {
            $result = $sql_query->fields;
            if ($i>499) {
               $update.="sector_id=9999999;";
               $db->Execute($update);
               $update="UPDATE $dbtables[universe] SET port_type='ore',port_ore=$initsore,port_organics=$initborganics,port_goods=$initbgoods,port_energy=$initbenergy WHERE ";
               $i=0;
               $j++;
            } else {
               $update.="(port_type='none' and sector_id=$result[sector_id]) or ";
               $i++;
              $j++;
            }
            $sql_query->Movenext();
         }
      }
      print("");
      PrintFlush("- completed successfully. <BR>");
      $update.="sector_id=9999999";
      $db->Execute($update);
      ### Finding random sectors where port=none and getting their sector ids in one sql query
      ### For Organic Ports
      $initsore = $ore_limit * $initscommod / 100.0;
      $initsorganics = $organics_limit * $initscommod / 100.0;
      $initsgoods = $goods_limit * $initscommod / 100.0;
      $initsenergy = $energy_limit * $initscommod / 100.0;
      $initbore = $ore_limit * $initbcommod / 100.0;
      $initborganics = $organics_limit * $initbcommod / 100.0;
      $initbgoods = $goods_limit * $initbcommod / 100.0;
      $initbenergy = $energy_limit * $initbcommod / 100.0;
      print("Selecting $ogp sectors for organic ports ");
//      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' and rand() order by rand() desc limit $ogp");
      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' order by rand(unix_timestamp()) desc limit $ogp");
      $update="UPDATE $dbtables[universe] SET port_type='organics',port_ore=$initbore,port_organics=$initsorganics,port_goods=$initbgoods,port_energy=$initbenergy WHERE ";
      $i=0;
      $j=0;
      if($sql_query)
      {
         while (!$sql_query->EOF) {
            $result = $sql_query->fields;
            if ($i>499) {
               $update.="sector_id=9999999;";
               $db->Execute($update);
               $update="UPDATE $dbtables[universe] SET port_type='organics',port_ore=$initbore,port_organics=$initsorganics,port_goods=$initbgoods,port_energy=$initbenergy WHERE ";
               $i=0;
               $j++;
            } else {
               $update.="(port_type='none' and sector_id=$result[sector_id]) or ";
               $i++;
               $j++;
            }
            $sql_query->Movenext();
         }
      }
      print("");
      PrintFlush("- completed successfully. <BR>");
      $update.="sector_id=9999999";
      $db->Execute($update);
      ### Finding random sectors where port=none and getting their sector ids in one sql query
      ### For Goods Ports
      $initsore = $ore_limit * $initscommod / 100.0;
      $initsorganics = $organics_limit * $initscommod / 100.0;
      $initsgoods = $goods_limit * $initscommod / 100.0;
      $initsenergy = $energy_limit * $initscommod / 100.0;
      $initbore = $ore_limit * $initbcommod / 100.0;
      $initborganics = $organics_limit * $initbcommod / 100.0;
      $initbgoods = $goods_limit * $initbcommod / 100.0;
      $initbenergy = $energy_limit * $initbcommod / 100.0;
      print("Selecting $gop sectors for goods ports ");
//      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' and rand() order by rand() desc limit $gop");
      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' order by rand(unix_timestamp()) desc limit $gop");
      $update="UPDATE $dbtables[universe] SET port_type='goods',port_ore=$initbore,port_organics=$initborganics,port_goods=$initsgoods,port_energy=$initbenergy WHERE ";
      $i=0;
      $j=0;
      if($sql_query)
      {
         while (!$sql_query->EOF) {
            $result = $sql_query->fields;
            if ($i>499) {
               $update.="sector_id=9999999;";
               $db->Execute($update);
               $update="UPDATE $dbtables[universe] SET port_type='goods',port_ore=$initbore,port_organics=$initborganics,port_goods=$initsgoods,port_energy=$initbenergy WHERE ";
               $i=0;
               $j++;
            } else {
               $update.="(port_type='none' and sector_id=$result[sector_id]) or ";
               $i++;
               $j++;
            }
            $sql_query->Movenext();
         }
      }
      print("");
      PrintFlush("- completed successfully. <BR>");
      $update.="sector_id=9999999";
      $db->Execute($update);
      ### Finding random sectors where port=none and getting their sector ids in one sql query
      ### For Energy Ports
      $initsore = $ore_limit * $initscommod / 100.0;
      $initsorganics = $organics_limit * $initscommod / 100.0;
      $initsgoods = $goods_limit * $initscommod / 100.0;
      $initsenergy = $energy_limit * $initscommod / 100.0;
      $initbore = $ore_limit * $initbcommod / 100.0;
      $initborganics = $organics_limit * $initbcommod / 100.0;
      $initbgoods = $goods_limit * $initbcommod / 100.0;
      $initbenergy = $energy_limit * $initbcommod / 100.0;
      print("Selecting $enp sectors for energy ports ");
//      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' and rand() order by rand() desc limit $enp");
      $sql_query=$db->Execute("select sector_id from $dbtables[universe] WHERE port_type='none' order by rand(unix_timestamp()) desc limit $enp");
      $update="UPDATE $dbtables[universe] SET port_type='energy',port_ore=$initbore,port_organics=$initborganics,port_goods=$initsgoods,port_energy=$initbenergy WHERE ";
      $i=0;
      $j=0;
      if($sql_query)
      {
         while (!$sql_query->EOF) {
            $result = $sql_query->fields;
            if ($i>499) {
               $update.="sector_id=9999999;";
               $db->Execute($update);
               $update="UPDATE $dbtables[universe] SET port_type='energy',port_ore=$initbore,port_organics=$initborganics,port_goods=$initsgoods,port_energy=$initbenergy WHERE ";
               $i=0;
               $j++;
            } else {
               $update.="(port_type='none' and sector_id=$result[sector_id]) or ";
               $i++;
               $j++;
            }
            $sql_query->movenext();
         }
      }
      print("");
      PrintFlush("- completed successfully. <BR>");
      $update.="sector_id=9999999";
      $db->Execute($update);
      echo "<form action=create_universe.php method=post>";
      echo "<input type=hidden name=step value=5>";
      echo "<input type=hidden name=spp value=$spp>";
      echo "<input type=hidden name=oep value=$oep>";
      echo "<input type=hidden name=ogp value=$ogp>";
      echo "<input type=hidden name=gop value=$gop>";
      echo "<input type=hidden name=enp value=$enp>";
      echo "<input type=hidden name=initscommod value=$initscommod>";
      echo "<input type=hidden name=initbcommod value=$initbcommod>";
      echo "<input type=hidden name=nump value=$nump>";
      echo "<INPUT TYPE=HIDDEN NAME=fedsecs VALUE=$fedsecs>";
      echo "<input type=hidden name=loops value=$loops>";
      echo "<input type=hidden name=engage value=2>";
      echo "<input type=hidden name=swordfish value=$swordfish>";
      echo "<input type=submit value=Confirm>";
      echo "</form>";
      break;
   case "5":
      PrintFlush("Selecting $nump sectors to place unowned planets in.<BR>");
      for($i=0; $i<=$sector_max; $i++)
      {
         $num = rand(0, $sector_max - 1);
         $sectors[$i] = $num;
      }
      for($i=0; $i<$nump; $i++)
      {
         $select = $db->Execute("SELECT $dbtables[universe].sector_id FROM $dbtables[universe], $dbtables[zones] WHERE $dbtables[universe].sector_id=$sectors[$i] AND $dbtables[zones].zone_id=$dbtables[universe].zone_id AND $dbtables[zones].allow_planet='N'") or die("DB error");
         if($select->RecordCount() == 0)
         {
            $insert = $db->Execute("INSERT INTO $dbtables[planets] (colonists, owner, corp, prod_ore, prod_organics, prod_goods, prod_energy, prod_fighters, prod_torp, sector_id) VALUES (2,0,0,$default_prod_ore,$default_prod_organics,$default_prod_goods,$default_prod_energy, $default_prod_fighters, $default_prod_torp,$sectors[$i])");
            #echo "$sectors[$i] - ";
         }
         else
            echo "The planet in sector $sectors[$i] was skipped<BR>";
      }
      echo "Unowned planet placement completed successfully.<BR>";
      $loopsize = round($sector_max/$loops);
      $start = 0;
      $finish = $loopsize - 1;
      for($i=1; $i<=$loops; $i++)
      {
         echo "<BR>Creating loop $i of $loopsize sectors (from sector $start to $finish) ";
         for($j=$start; $j<$finish; $j++)
         {
            $k = $j + 1;
            $update = $db->Execute("INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ($j,$k)");
            $update = $db->Execute("INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ($k,$j)");
            #echo "$j<=>$k - ";
         }
         $update = $db->Execute("INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ($start,$finish)");
         $update = $db->Execute("INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ($finish,$start)");
         #echo "$finish<=>$start";
         echo "- loop $i completed successfully.";
         $start=$finish+1;
         $finish=$finish+$loopsize;
         if ($finish>$sector_max) $finish=$sector_max;

      }
      PrintFlush("<BR>Creating $i sector warp-loops (out of $nump sectors) - completed successfully.<BR>");
      PrintFlush("<BR>Randomly One-way Linking $i Sectors (out of $sector_max sectors) ");
      $i=0;
      while ($i < ($sector_max-500)):
         $insert="INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ";
         for ($j=1; $j<=499; $j++) {
            $link1=intval(rand(1,$sector_max));
            $link2=intval(rand(1,$sector_max));
            $insert.="($link1,$link2),";
         }
         $link1=intval(rand(1,$sector_max));
         $link2=intval(rand(1,$sector_max));
         $insert.="($link1,$link2);";
         $i=$i+500;
         ### Now lets post the information to the mysql database.
         $db->Execute($insert);
         PrintFlush($db->ErrorMsg());
         # PrintFlush("Finished linking $i sectors ( out of $sector_max sectors)...<br>");
      endwhile;
      ### Now lets do the remaining sectors.
      $insert="INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ";
      for ($j=$i; $j<=$sector_max-1; $j++) {
            $link1=intval(rand(1,$sector_max));
            $link2=intval(rand(1,$sector_max));
            $insert.="($link1,$link2),";
      }
      $link1=intval(rand(1,$sector_max));
      $link2=intval(rand(1,$sector_max));
      $insert.="($link1,$link2);";
      $j=$j+1;
      ### Now lets post the information to the mysql database.
      $db->Execute($insert);
      $i=$j;
      print("");
      PrintFlush("- completed successfully.");
      PrintFlush("<BR>Randomly Two-way Linking Sectors ");
      $i=0;
      while ($i < ($sector_max-500)):
         $insert="INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ";
         for ($j=1; $j<=499; $j++) {
            $link1=intval(rand(1,$sector_max));
            $link2=intval(rand(1,$sector_max));
            $insert.="($link1,$link2),";
            $insert.="($link2,$link1),";
         }
         $link1=intval(rand(1,$sector_max));
         $link2=intval(rand(1,$sector_max));
         $insert.="($link1,$link2),";
         $insert.="($link2,$link1);";
         $i=$i+500;
         ### Now lets post the information to the mysql database.
         $db->Execute($insert);
         PrintFlush($db->ErrorMsg());
         # PrintFlush("Finished linking $i sectors ( out of $sector_max sectors)...<br>");
      endwhile;
      ### Now lets do the remaining sectors.
      $insert="INSERT INTO $dbtables[links] (link_start,link_dest) VALUES ";
      for ($j=$i; $j<=$sector_max-1; $j++) {
         $link1=intval(rand(1,$sector_max));
         $link2=intval(rand(1,$sector_max));
         $insert.="($link1,$link2),";
         $insert.="($link2,$link1),";
      }
      $link1=intval(rand(1,$sector_max));
      $link2=intval(rand(1,$sector_max));
      $insert.="($link1,$link2),";
      $insert.="($link2,$link1);";
      $j=$j+1;
      ### Now lets post the information to the mysql database.
      $db->Execute($insert);
      $i=$j;
      print("");
      PrintFlush("- completed successfully. <BR>");
      echo "<form action=create_universe.php method=post>";
      echo "<input type=hidden name=step value=7>";
      echo "<input type=hidden name=spp value=$spp>";
      echo "<input type=hidden name=oep value=$oep>";
      echo "<input type=hidden name=ogp value=$ogp>";
      echo "<input type=hidden name=gop value=$gop>";
      echo "<input type=hidden name=enp value=$enp>";
      echo "<input type=hidden name=initscommod value=$initscommod>";
      echo "<input type=hidden name=initbcommod value=$initbcommod>";
      echo "<input type=hidden name=nump value=$nump>";
      echo "<INPUT TYPE=HIDDEN NAME=fedsecs VALUE=$fedsecs>";
      echo "<input type=hidden name=loops value=$loops>";
      echo "<input type=hidden name=engage value=2>";
      echo "<input type=hidden name=swordfish value=$swordfish>";
      echo "<input type=submit value=Confirm>";
      echo "</form>";
      break;
   case "7":
      echo "<B><BR>Configuring game scheduler<BR></B>";

      echo "<BR>Update ticks will occur every $sched_ticks minutes<BR>";

      echo "Extra Turns will occur every 24h.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, 1440, 0, 'sched_turns_pay.php', '',unix_timestamp(now()))");

      echo "Turns will occur every $sched_turns minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_turns, 0, 'sched_turns.php', '',unix_timestamp(now()))");

      echo "Defenses will be checked every $sched_turns minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_turns, 0, 'sched_defenses.php', '',unix_timestamp(now()))");

      echo "Furangees will play every $sched_turns minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_turns, 0, 'sched_furangee.php', '',unix_timestamp(now()))");

      echo "Interests on IGB accounts will be accumulated every $sched_IGB minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_IGB, 0, 'sched_IGB.php', '',unix_timestamp(now()))");

      echo "News will be generated every $sched_news minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_news, 0, 'sched_news.php', '',unix_timestamp(now()))");

      echo "Planets will generate production every $sched_planets minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_planets, 0, 'sched_planets.php', '',unix_timestamp(now()))");

      echo "Ports will regenerate every $sched_ports minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_ports, 0, 'sched_ports.php', '',unix_timestamp(now()))");

      echo "Ships will be towed from fed sectors every $sched_turns minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_turns, 0, 'sched_tow.php', '',unix_timestamp(now()))");

      echo "Rankings will be generated every $sched_ranking minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_ranking, 0, 'sched_ranking.php', '',unix_timestamp(now()))");

      echo "Sector Defences will degrade every $sched_degrade minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_degrade, 0, 'sched_degrade.php', '',unix_timestamp(now()))");

      echo "The planetary apocalypse will occur every $sched_apocalypse minutes.<br>";
      $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, $sched_apocalypse, 0, 'sched_apocalypse.php', '',unix_timestamp(now()))");

      if ($bnt_ls===true)
      {
            echo "The public list updater will occur every 60min<br>";
            $db->Execute("INSERT INTO $dbtables[scheduler] VALUES('', 'Y', 0, 60, 0, 'bnt_ls_client.php', '',unix_timestamp(now()))");
            $creating=1;
            include("bnt_ls_client.php");
      }

                // Automatic Hall of Fame start entry
                $res = $db->Execute("SELECT max(GameRound) as MaxGame FROM $dbtables[hof_game]");
                $result = $res->fields;
                $xsql = "INSERT INTO $dbtables[hof_game] (GameName,GameRound,GameStart) VALUES('" . $game_name . "'," . ($result[MaxGame] + 1) . "," . time() . ")";
      $db->Execute($xsql);
                if ($GameDuration>0) {
                        $insert="INSERT INTO $dbtables[adminnews] (an_text) VALUES ('Round started: " . date("Y-m-d H:i",time()) . "<BR>Round end : " . date("Y-m-d H:i",time() + $GameDuration) . "')";
              $db->Execute($insert);
                        }

      $update = $db->Execute("INSERT INTO $dbtables[ibank_accounts] (ship_id,balance,loan) VALUES (1,0,0)");
      $password = substr($adminpass, 0, $maxlen_password);
      echo "<BR><BR><center><B>Your admin login is: <BR>";
      echo "<BR>Username: $admin_mail";
      echo "<BR>Password: $password<BR></B>";
      $stamp=date("Y-m-d H:i:s");
      $password = substr(md5($password),0,$maxlen_password);
      $db->Execute("INSERT INTO $dbtables[ships] VALUES('','WebMaster','N','WebMaster','$password','$admin_mail',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$start_turns,'','N',0,1,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','1.1.1.1',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',-1,'Y','N',-1,'N')");
      $db->Execute("INSERT INTO $dbtables[zones] VALUES('','WebMaster\'s Territory', 1, 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 0)");
      $db->Execute("INSERT INTO $dbtables[shoutbox] VALUES('',1,'WebMaster',".time().",'Welcome and enjoy the game!',0)");

                //Generate Pay-User
                $syllables="er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
                $syllable_array=explode(",", $syllables);
                srand((double)microtime()*1000000);

                $sql = "SELECT * FROM $dbtables[rpp_pay]";
                $res = $db->Execute($sql);
                while(!$res->EOF)
                        {
                                $row = $res->fields;

                                $makepass="";
                                for ($count=1;$count<=4;$count++)
                                        {
                                                if (rand()%10 == 1)
                                                        {
                                                                $makepass .= sprintf("%0.0f",(rand()%50)+1);
                                                        } else {
                                                                $makepass .= sprintf("%s",$syllable_array[rand()%62]);
                                                        }
                                        }
                                if($row[pay_until]>Time())
                                        $result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$row[pay_ship]','N','$row[pay_name]','" . substr(md5($makepass),0,$maxlen_password) . "','$row[pay_email]',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$start_turns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','1.1.1.1',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',-1,'Y','N',-1,'Y')");
                                else
                                        $result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$row[pay_ship]','N','$row[pay_name]','" . substr(md5($makepass),0,$maxlen_password) . "','$row[pay_email]',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$start_turns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','1.1.1.1',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',-1,'Y','N',-1,'N')");

                                if(!$result2) { echo $db->ErrorMsg() . "<br>"; } else { echo "<B>$row[pay_name]</B> created.<BR>";}

                                $result2 = $db->Execute("SELECT ship_id FROM $dbtables[ships] WHERE email='$row[pay_email]'");
                                $shipid = $result2->fields;

                                $l_new_message = str_replace("[pass]", $makepass, $l_new_message);
                                mail("$row[pay_email]", "$l_new_topic", "$l_new_message\r\n\r\nhttp://$gamedomain","From: $admin_mail\r\nReply-To: $admin_mail\r\nX-Mailer: PHP/" . phpversion());

                                $db->Execute("INSERT INTO $dbtables[zones] VALUES('','$row[pay_name]\'s Territory', $shipid[ship_id], 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 0)");
                                $db->Execute("INSERT INTO $dbtables[ibank_accounts] (ship_id,balance,loan) VALUES($shipid[ship_id],0,0)");


                                $res->MoveNext();
                        }

      PrintFlush("</center><BR><BR><center><BR><B>Congratulations! Universe created successfully.<BR>");
      PrintFlush("Click <A HREF=login.php>here</A> to return to the login screen.</B></center>");
      break;
   default:
      echo "<form action=create_universe.php method=post>";
      echo "Password: <input type=password name=swordfish size=20 maxlength=20>&nbsp;&nbsp;";
      echo "<input type=submit value=Submit><input type=hidden name=step value=1>";
      echo "<input type=reset value=Reset>";
      echo "</form>";
      break;
}

$StopTime=$BenchmarkTimer->stop();
$Elapsed=$BenchmarkTimer->elapsed();
PrintFlush("<br>Elapsed Time - $Elapsed");
include("footer.php");
</script>