<? 

include("config.php");
include("languages/$lang");

$title="Game Settings";
include("header.php");

bigtitle();
connectDB();

//-------------------------------------------------------------------------------------------------
$line_color = $color_line1;

function line($item, $value)
{
  global $line_color, $color_line1, $color_line2;
  
  echo "<TR BGCOLOR=\"$line_color\"><TD>$item</TD><TD>$value</TD></TR>\n";
  if($line_color == $color_line1)
  { 
    $line_color = $color_line2; 
  }
  else
  { 
    $line_color = $color_line1; 
  }
}

  
  echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>";
   line("Game version:",$game_name);
   line("Average tech level needed to hit mines",$mine_hullsize);
   line("Averaged Tech level When Emergency Warp Degrades",$ewd_maxhullsize);
   
   $num = NUMBER($sector_max);
   line("Number of Sectors",$num);
   line("Maximum Links per sector",$link_max);
   line("Maximum average tech level for Federation Sectors",$fed_max_hull);
   
   $bank_enabled = $allow_ibank ? "Yes" : "No";
   line("Intergalactic Bank Enabled",$bank_enabled);
   
   if($allow_ibank)
   {
     $rate = $ibank_interest * 100;
     line("IGB Interest rate per update",$rate);
     $rate = $ibank_loaninterest * 100;
     line("IGB Loan rate per update",$rate);
   }  
   line("Tech Level upgrade for Bases",$basedefense);
   
   $num = NUMBER($colonist_limit);
   line("Colonists Limit",$num."&nbsp;");
   
   $num = NUMBER($max_turns);
   line("Maximum number of accumulated turns",$num);
   line("Maximum number of planets per sector",$max_planets_sector);
   line("Maximum number of traderoutes per player",$max_traderoutes_player);
   line("Colonist Production Rate",$colonist_production_rate);
   line("Unit of Energy used per sector fighter",$energy_per_fighter);
   
   $rate = $defence_degrade_rate * 100;
   line("Sector fighter degradation percentage rate",$rate);
   line("Number of planets with bases need for sector ownership&nbsp;",$min_bases_to_own);
   
   $rate = NUMBER(($interest_rate - 1) * 100 , 3);
   line("Planet interest rate",$rate);
   
   $rate = 1 / $colonist_production_rate;
   
   $num = NUMBER($rate/$fighter_prate);
   line("Colonists needed to produce 1 Fighter each turn",$num);
   
   $num = NUMBER($rate/$torpedo_prate);
   line("Colonists needed to produce 1 Torpedo each turn",$num);
   
   $num = NUMBER($rate/$ore_prate);
   line("Colonists needed to produce 1 Ore each turn",$num);
   
   $num = NUMBER($rate/$organics_prate);
   line("Colonists needed to produce 1 Organics each turn",$num);
   
   $num = NUMBER($rate/$goods_prate);
   line("Colonists needed to produce 1 Goods each turn",$num);
   
   $num = NUMBER($rate/$energy_prate);
   line("Colonists needed to produce 1 Energy each turn",$num);
   
   $num = NUMBER($rate/$credits_prate);
   line("Colonists needed to produce 1 Credits each turn",$num);

   line("Maximum members per alliance",$max_team_members);


	// Get the GameID of this game and this Round.
	$res = $db->Execute("SELECT max(GameRound) as MaxGameRound FROM $dbtables[hof_game]"); 
	$res = $db->Execute("SELECT GameID, GameStart, GameEnd FROM $dbtables[hof_game] WHERE GameRound = '". $res->fields[MaxGameRound] . "'"); 
	$GameID = $res->fields[GameID];
	$GameStart = $res->fields[GameStart];
	$GameEnd = $res->fields[GameEnd];
	
	if (isSet($GameID))
		{
		   line("Game Start",date("Y-m-d H:i:s",$GameStart));
			if (isSet($GameEnd))
				{
   				line("Game Duration (sec)",($GameEnd - $GameStart));
   				line("Game End",date("Y-m-d H:i:s",$GameEnd));
				} else {
					if ($GameDuration > 0)
						{
		   				line("Game Duration (sec)",$GameDuration);
		   				line("Game will End",date("Y-m-d H:i:s",($GameStart + $GameDuration)));
						} else {
							line("Game Duration until now (sec)",(time() - $GameStart));
							line("Game will End","unknown");
						}
				}
		} else {
			echo "No GameID found!";
		}


  echo "</TABLE><BR><BR>\n";


$title="Game Scheduler Settings";
bigtitle();
 
  $line_color = $color_line1;
  
  echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>";
   line("Ticks happen every",$sched_ticks ." minutes&nbsp;");
   line("Turns will happen every",$sched_turns ." minutes&nbsp;");
   line("Defenses will be checked every",$sched_turns ." minutes&nbsp;");
   line("Furangees will play every",$sched_turns ." minutes&nbsp;");  
   
   if($allow_ibank)
     line("Interests on IGB accounts will be accumulated every&nbsp;", $sched_IGB ." minutes&nbsp;");
   
   line("News will be generated every",$sched_news ." minutes&nbsp;");
   line("Planets will generate production every",$sched_planets ." minutes&nbsp;");
   line("Ports will regenerate every",$sched_ports ." minutes&nbsp;");
   line("Ships will be towed from fed sectors every",$sched_turns ." minutes&nbsp;");
   line("Rankings will be generated every",$sched_ranking ." minutes&nbsp;");
   line("Sector Defences will degrade every",$sched_degrade ." minutes&nbsp;");
   line("The planetary apocalypse will occur every&nbsp;",$sched_apocalypse ." minutes&nbsp;");

   line("The public list entry will updated every&nbsp;","60 minutes&nbsp;");


  echo "</TABLE>";


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

?>
