<?
if (preg_match("/bnt_hof_over.php/i", $PHP_SELF)) {
   echo "You can not access this file directly!<BR><BR>\n";
   die();
}


if (isSet($db))
	{
		// Get the GameID of this game and this Round.
		$res = $db->Execute("SELECT max(GameRound) as MaxGameRound FROM $dbtables[hof_game]"); 
		$res = $db->Execute("SELECT GameID, GameStart, GameEnd FROM $dbtables[hof_game] WHERE GameRound = '". $res->fields[MaxGameRound] . "'"); 
		$GameID = $res->fields[GameID];
		$GameStart = $res->fields[GameStart];
		$GameEnd = $res->fields[GameEnd];

		if (isSet($GameID))
			{
				if (($GameStart + $GameDuration) <= time())
					{
						$account_creation_closed=true;
						$server_closed=true;

						if (!isSet($GameEnd))
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

//mail("$admin_mail", "Reset a BNT/RPP game!", "$gamedomain $gamepath \r\n\r\n","From: $admin_mail\r\nX-Mailer: PHP/" . phpversion());
							}
					}
			}
	}
?>