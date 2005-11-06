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
		echo "<B><BIG>Vote Saved</BIG></B><BR><BR>";
		$result = $db->Execute("UPDATE $dbtables[ships] SET vote = $VOTE WHERE email='$username'");
	}

echo "<A href='#' onClick='window.close();'>Close Window</A></CENTER>";

?>