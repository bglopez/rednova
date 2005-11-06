<?
if (preg_match("/shoutbox.php/i", $PHP_SELF)) {
	include("config.php");
	updatecookie();
	include("languages/$lang");
	$title="SHOUTBOX";
	include("header.php");
	connectdb();
	if(checklogin())
	{
	//  die();
	}
	echo "<CENTER>";
	$sb_key="w";
} else { $sb_key="i";}

$result = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $result->fields;
if ($playerinfo[team] == 0)
{
$cols = 2;
$withally = false;
} else {
$cols = 4;
$withally = true;
}
echo "<FORM NAME='sb' ACTION='shoutbox2.php'><TABLE BORDER=1 CELLSPACING=0 CELLPADDING=0>";
	echo "<TR BGCOLOR='$color_header'>";
		echo "<TH COLSPAN=2 NOWRAP><FONT COLOR=yellow>.:: SHOUTBOX ::.</FONT></TH>";
	echo "</TR>";

echo "<TR><TD COLSPAN=2 NOWRAP ALIGN=CENTER>";
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 width=100%>";

	echo "<TR BGCOLOR='$color_header'>";
		echo "<TD COLSPAN=2 NOWRAP ALIGN=CENTER><FONT size=-1><B>Public</B></FONT></TD>";
		if ($withally) echo "<TD COLSPAN=2 NOWRAP ALIGN=CENTER><FONT size=-1><B>Alliance</B></FONT></TD>";
	echo "</TR>";


	$res1 = $db->Execute("SELECT * FROM $dbtables[shoutbox] WHERE sb_alli = 0 ORDER BY sb_date DESC LIMIT 0,5");
	$res2 = $db->Execute("SELECT * FROM $dbtables[shoutbox] WHERE sb_alli = " . (($playerinfo[team]<=0)?-1:$playerinfo[team]) . " ORDER BY sb_date DESC LIMIT 0,5");

	for ( $i = 0 ; $i < 5 ; $i++ )
	{
		echo "<TR>";
		if (!$res1->EOF)
			{
				$row1 = $res1->fields;
				echo "<TD COLSPAN=2 NOWRAP>" . rawurldecode($row1[sb_text]) . "</TD>";
			} else { echo "<TD COLSPAN=2></TD>"; }
		if ($withally)
		if (!$res2->EOF)
			{
				$row2 = $res2->fields;
				echo "<TD COLSPAN=2 NOWRAP BGCOLOR='$color_line2'>" . rawurldecode($row2[sb_text]) . "</TD>";
			} else { echo "<TD COLSPAN=2></TD>"; }
		echo "</TR>";


		echo "<TR>";
		if (!$res1->EOF)
			{
				echo "<TD NOWRAP ALIGN=LEFT><FONT SIZE=-1><B>$row1[player_name]</B></FONT></TD>";
				echo "<TD NOWRAP ALIGN=RIGHT><FONT SIZE=-1><I>" . date("m/d/Y G:i",$row1[sb_date]) . "</I></FONT></TD>";
				$res1->MoveNext();
			} else { echo "<TD COLSPAN=2></TD>"; }
		if ($withally)
		if (!$res2->EOF)
			{
				echo "<TD NOWRAP ALIGN=LEFT BGCOLOR='$color_line2'><FONT SIZE=-1><B>$row2[player_name]</B></FONT></TD>";
				echo "<TD NOWRAP ALIGN=RIGHT BGCOLOR='$color_line2'><FONT SIZE=-1><I>" . date("m/d/Y G:i",$row2[sb_date]) . "</I></FONT></TD>";
				$res2->MoveNext();
			} else { echo "<TD COLSPAN=2></TD>"; }
		echo "</TR>";
	
		echo "</TR><TR BGCOLOR='$color_line1'>";
			echo "<TD COLSPAN=$cols NOWRAP><IMG height=1 width=1 SRC='images/spacer.gif'></TD>";
		echo "</TR>";
	}

	echo "</TABLE>";
echo "</TD></TR>";

echo "<TR BGCOLOR='$color_line2'><TD COLSPAN=2 NOWRAP ALIGN=CENTER>";
	echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 width=100%>";
	echo "<TR BGCOLOR='$color_line1'>";
	echo "<TD NOWRAP ALIGN=LEFT><INPUT TYPE=TEXT NAME='sbt' Value='' MAXLENGTH=70><INPUT TYPE=HIDDEN NAME='sbw' Value='$sb_key'></TD>";
	if ($withally) echo "<TD NOWRAP ALIGN=RIGHT>Public?&nbsp;(else Alliance)&nbsp;<INPUT TYPE=CHECKBOX NAME=SBPB></TD>";
	else echo "<TD NOWRAP ALIGN=RIGHT>Public only<INPUT TYPE=HIDDEN NAME=SBPB VALUE=''></TD>";
	echo "</TR>";
	echo "<TR BGCOLOR='$color_line1'>";
	echo "<TD NOWRAP ALIGN=LEFT><INPUT TYPE=SUBMIT VALUE='SHOUT'>&nbsp;&nbsp;<A HREF='shoutbox_smilie.php'>Smilie's</A></TD>";
	echo "<TD NOWRAP ALIGN=RIGHT><INPUT TYPE=RESET VALUE='CLEAR'></TD>";
	echo "</TR>";
	echo "</TABLE>";
echo "</TD></TR>";

echo "</TABLE></FORM>";




?></BODY></HTML>