<? 
include("config.php");
include("languages/$lang");

$title="Hall of Fame";
include("header.php");
bigtitle();
connectDB();
?>
 <style type="text/css">
 <!--
	a          { text-decoration: none; color: lime;}
	a:link     { text-decoration: none; color: lime;}
	a:visited  { text-decoration: none; color: lime;}
	a:hover    { text-decoration: none; color: yellow;}
 -->
 </style>
<?
	if ($act=="game") {
		$res = $db->Execute("SELECT * FROM $dbtables[hof_game] WHERE GameID = $gid AND GameEnd IS NOT NULL LIMIT 1");
		$res2 = $db->Execute("SELECT count(GameID) as GameRound, avg(GameEnd - GameStart) as GameDuration FROM $dbtables[hof_game] WHERE GameName = '" . $res->fields[GameName] . "' AND GameEnd IS NOT NULL");
		DrawTopHead($res->fields,$res2->fields);

		//$res = $db->Execute("SELECT PlayerName, avg(PlayerRank) as PlayerRank, avg(PlayerScore) as PlayerScore, count(GameID) as PlayerGames, (  ( avg(PlayerRank) * " . $res2->fields[GameRound] . " ) / count(GameID) ) as PlayerCalc FROM $dbtables[hof_player] LEFT JOIN $dbtables[hof_game] ON PlayerGameID = GameID WHERE GameName = '" . $res->fields[GameName] . "' GROUP BY PlayerName ORDER BY PlayerCalc, PlayerScore DESC, PlayerGames DESC LIMIT 10");
		$res = $db->Execute("SELECT PlayerName, avg(PlayerRank) as PlayerRank, avg(PlayerScore) as PlayerScore, count(GameID) as PlayerGames, (  avg(PlayerScore) /  ( ( avg(PlayerRank) * " . $res2->fields[GameRound] . " ) / count(GameID) ) ) as PlayerCalc FROM $dbtables[hof_player] LEFT JOIN $dbtables[hof_game] ON PlayerGameID = GameID WHERE GameName = '" . $res->fields[GameName] . "' GROUP BY PlayerName ORDER BY PlayerCalc DESC, PlayerScore DESC, PlayerGames DESC LIMIT 10");

		while(!$res->EOF)
		{
			if ($color_act == $color_line2) $color_act = $color_line1;
			else $color_act = $color_line2;
			DrawTopLine($res->fields,++$i);
			$res->MoveNext();
		}
		echo "</TABLE>";
	} else if ($act=="round") {
		$res = $db->Execute("SELECT * FROM $dbtables[hof_game] WHERE GameID = $gid LIMIT 1");
		DrawPlayerHead($res->fields);
		$res = $db->Execute("SELECT * FROM $dbtables[hof_player] WHERE PlayerGameID = $gid ORDER BY PlayerRank");
		while(!$res->EOF)
		{
			if ($color_act == $color_line2) $color_act = $color_line1;
			else $color_act = $color_line2;
			DrawPlayerLine($res->fields);
			$res->MoveNext();
		}
		echo "</TABLE>";
	} else {
		DrawGameHead();
		if (isSet($game)) $res = $db->Execute("SELECT * FROM $dbtables[hof_game] LEFT JOIN $dbtables[hof_player] ON GameID = PlayerGameID WHERE GameName = '" . rawurldecode($game) . "' AND ( PlayerRank = 1 OR PlayerRank IS NULL ) ORDER BY GameName, GameRound");
		else $res = $db->Execute("SELECT * FROM $dbtables[hof_game] LEFT JOIN $dbtables[hof_player] ON GameID = PlayerGameID WHERE PlayerRank = 1 OR PlayerRank IS NULL ORDER BY GameRound");
		if ($res->EOF) echo "<TR class=textmenu><TD colspan=6 align=center>No entry for this game found!</TD></TR>";
		while(!$res->EOF)
		{
			if ($color_act == $color_line2) $color_act = $color_line1;
			else $color_act = $color_line2;
			DrawGameLine($res->fields);
			$res->MoveNext();
		}
		echo "</TABLE>";
	}

function DrawTopHead($GameRow,$GameRowState) {
	global $color_header,$view;
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1 ALIGN=CENTER>";
		echo "<TR CLASS=textmenu BGCOLOR=$color_header><TD COLSPAN=5 NOWRAP ALIGN=CENTER><BIG><B>$GameRow[GameName]</B></BIG></TD></TR>";
		echo "<TR CLASS=textgray><TD COLSPAN=3 NOWRAP ALIGN=CENTER>Total Rounds: $GameRowState[GameRound]</TD>";
		echo "<TD COLSPAN=2 NOWRAP ALIGN=CENTER>avg. Duration: " . round($GameRowState[GameDuration] / (3600 * 24)) . " days</TD></TR>";

		echo "<TR BGCOLOR=$color_header CLASS=textmenu>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Rank</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Name</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Nr. Games</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Avg. Rank</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Avg. Score</B>&nbsp;</TD>";
			// echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Calculation</B>&nbsp;</TD>";
		echo "</TR>";
	}

function DrawTopLine($PlayerRow,$i) {
	global $color_act,$view;
	echo "<TR bgcolor=$color_act class=textgray>";
	echo "<TD NOWRAP ALIGN=RIGHT>&nbsp;$i&nbsp;</TD>";
	echo "<TD NOWRAP ALIGN=CENTER>&nbsp;$PlayerRow[PlayerName]&nbsp;</TD>";
	echo "<TD NOWRAP ALIGN=RIGHT>&nbsp;$PlayerRow[PlayerGames]&nbsp;</TD>";
	printf ("<TD NOWRAP ALIGN=RIGHT>&nbsp; %.1f &nbsp;</TD>",$PlayerRow[PlayerRank]); 
	echo "<TD NOWRAP ALIGN=RIGHT>&nbsp;" . NUMBER(round($PlayerRow[PlayerScore],1)) . "&nbsp;</TD>";
	// printf ("<TD NOWRAP ALIGN=RIGHT>&nbsp; %.1f &nbsp;</TD>",$PlayerRow[PlayerCalc]); 
	echo "</TR>";
	}

function DrawPlayerHead($GameRow) {
	global $color_header,$view;
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1 ALIGN=CENTER>";
		echo "<TR CLASS=textmenu BGCOLOR=$color_header><TD COLSPAN=3 NOWRAP ALIGN=CENTER><BIG><B>$GameRow[GameName]</B></BIG></TD></TR>";
		echo "<TR CLASS=textgray><TD COLSPAN=2 NOWRAP ALIGN=CENTER>Round: $GameRow[GameRound]</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>" . round(( $GameRow[GameEnd] -  $GameRow[GameStart] ) / (3600 * 24)) . "&nbsp;days&nbsp;</TD></TR>";

		echo "<TR BGCOLOR=$color_header CLASS=textmenu>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Rank</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Name</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER>&nbsp;<B>Score</B>&nbsp;</TD>";
		echo "</TR>";
	}

function DrawPlayerLine($PlayerRow) {
	global $color_act,$view;
	echo "<TR bgcolor=$color_act class=textgray>";
	echo "<TD NOWRAP ALIGN=RIGHT>$PlayerRow[PlayerRank]</TD>";
	echo "<TD NOWRAP ALIGN=CENTER>&nbsp;$PlayerRow[PlayerName]&nbsp;</TD>";
	echo "<TD NOWRAP ALIGN=RIGHT>" . NUMBER($PlayerRow[PlayerScore]) . "</TD>";
	echo "</TR>";
	}

function DrawGameHead() {
	global $color_header,$view;
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1 ALIGN=CENTER>";
		echo "<TR BGCOLOR=$color_header>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>Game Name</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>Round</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>Start</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>End</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>Duration</B>&nbsp;</TD>";
			echo "<TD NOWRAP ALIGN=CENTER CLASS=textmenu>&nbsp;<B>Nr. 1</B>&nbsp;</TD>";
		echo "</TR>";
	}

function DrawGameLine($GameRow) {
	global $color_act,$view;
	if (isSet($GameRow[GameEnd]))
		{
		echo "<TR bgcolor=$color_act class=textgray>";
		echo "<TD NOWRAP><A HREF='?act=game&gid=$GameRow[GameID]' CLASS=nav>$GameRow[GameName]</A></TD>";
		echo "<TD NOWRAP ALIGN=RIGHT><A HREF='?act=round&gid=$GameRow[GameID]' CLASS=nav>$GameRow[GameRound]</A></TD>";
		echo "<TD NOWRAP ALIGN=CENTER>&nbsp;" . date("m-d H:i",$GameRow[GameStart]) . "&nbsp;</TD>";
		echo "<TD NOWRAP ALIGN=CENTER>&nbsp;" . date("m-d H:i",$GameRow[GameEnd]) . "&nbsp;</TD>";
		echo "<TD NOWRAP ALIGN=RIGHT>&nbsp;" . round(( $GameRow[GameEnd] -  $GameRow[GameStart] ) / (3600 * 24)) . "&nbsp;days&nbsp;</TD>";
		echo "<TD NOWRAP ALIGN=CENTER>$GameRow[PlayerName]</TD>";
		}
	else
		{
		echo "<TR bgcolor=$color_act class=textmenu>";
		echo "<TD NOWRAP>$GameRow[GameName]</TD>";
		echo "<TD NOWRAP ALIGN=RIGHT>$GameRow[GameRound]</TD>";
		echo "<TD NOWRAP ALIGN=CENTER>&nbsp;" . date("m-d H:i",$GameRow[GameStart]) . "&nbsp;</TD>";
		echo "<TD NOWRAP ALIGN=CENTER COLSPAN=3><B>.. .. running game .. ..</B></TD>";
		}
	echo "</TR>";
	}

echo "\n<BR>\n";
echo "Click the <B>round number</B> to see the top 10 of <B>that</B> round.<BR>";
echo "Click the <B>game name</B> to see the top 10 of <B>all</B> rounds.<BR>";
if(empty($username)) TEXT_GOTOLOGIN();
else TEXT_GOTOMAIN();
include("footer.php");
?>