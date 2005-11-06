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

if ($playerinfo['vote'] == 0)
	{
		//Save Ansewer
		$VOTE_TEXT = strip_tags($VOTE_TEXT);
		$result = $db->Execute("INSERT INTO $dbtables[vote] (vote_text) VALUES ('" . $VOTE_TEXT . "')");

		//Save Vote for me
		$result = $db->Execute("SELECT max(vote_id) as myvote FROM $dbtables[vote]");
		$row = $result->fields;
		$VOTE = $row['myvote'];
		$result = $db->Execute("UPDATE $dbtables[ships] SET vote = $VOTE WHERE email='$username'");

		echo "<B><BIG>Vote answer saved</BIG></B><BR><BR>";
	}

echo "<A href='#' onClick='window.close();'>Close Window</A></CENTER>";
?>