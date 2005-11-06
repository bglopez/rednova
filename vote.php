<?
include("config.php");
updatecookie();
include("languages/$lang");
$title="Vote";
include("header.php");
connectdb();
if(checklogin())
{
  die();
}

$result = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $result->fields;

echo "<CENTER>";

if (($playerinfo['vote'] > 0)||($playerinfo['vote'] < -1))
	{
		$res = $db->Execute("SELECT * FROM $dbtables[vote] WHERE vote_id = 0");
		$row = $res->fields;
		echo "<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=2 ALIGN=CENTER>";
		echo "<TR><TH colspan=2><B><BIG>$row[vote_text]</BIG></B></TH></TR>";
		echo "<TR><TH>Vote Text</TH><TH ALIGN=RIGHT>Number</TH></TR>";

$res = $db->Execute("SELECT count(*) as notvote FROM $dbtables[ships] WHERE vote = -1 OR vote = -2 OR vote = -3");
$row = $res->fields;
echo "<TR><TD><FONT COLOR=RED>No Voice</FONT></TD><TD ALIGN=LEFT>$row[notvote]</TD></TR>";

$res = $db->Execute("SELECT count(*) as notvote FROM $dbtables[ships] WHERE vote = 0 OR vote = -4");
$row = $res->fields;
echo "<TR><TD><FONT COLOR=RED>Vote Pending</FONT></TD><TD ALIGN=LEFT>$row[notvote]</TD></TR>";

		$res = $db->Execute("SELECT vote, vote_text , count(ship_id) as myvote FROM $dbtables[ships], $dbtables[vote] WHERE vote = vote_id GROUP BY vote_text, vote ORDER BY myvote DESC");
		while(!$res->EOF)
			{
			$row = $res->fields;
			if ($row['vote'] > 0)
				{
					echo "<TR><TD>" . $row['vote_text'] . "</TD><TD ALIGN=LEFT>";
					if ($row['myvote'] < 15) echo "<IMG WIDTH=" . ($row['myvote'] * 5) . " HEIGHT=10 SRC='images/aqua.gif'>";
					echo "&nbsp;" . $row['myvote'] . "</TD></TR>";
				}
			$res->MoveNext();
			}

		echo "</TABLE>";
}


if ($playerinfo['vote'] == 0)
	{
		echo "<FORM ACTION='vote2.php'><TABLE>";
		$res = $db->Execute("SELECT * FROM $dbtables[vote] ORDER BY `vote_id`");
		while(!$res->EOF)
			{
			$row = $res->fields;
				if ($row[vote_id] == 0) {
				echo "<TR><TD colspan=2><B><BIG>" . $row[vote_text] . "</BIG></B></TD></TR>";
				} else {
				echo "<TR><TD><INPUT TYPE=RADIO NAME=VOTE VALUE=" . $row[vote_id] . "></TD><TD>" . $row[vote_text] . "</TD>\n";
				}
			$res->MoveNext();
			}
		echo "</TABLE><A HREF='vote_add.php'>Add a answer!</A><BR>";
		echo "<INPUT TYPE=SUBMIT VALUE=VOTE>";
		echo "<INPUT TYPE=RESET VALUE=RESET>";
		echo "</FORM>";

}


echo "<A href='#' onClick='window.close();'>Close Window</A></CENTER>";

?>