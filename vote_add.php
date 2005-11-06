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
		echo "<B><BIG>Add a vote answer</BIG></B><BR>";
		echo "<FORM ACTION='vote_add2.php'><INPUT TYPE=TEXT NAME=VOTE_TEXT><BR>";
		echo "<INPUT TYPE=SUBMIT VALUE=VOTE>";
		echo "<INPUT TYPE=RESET VALUE=RESET>";
		echo "</FORM>";
	}

echo "<A href='#' onClick='window.close();'>Close Window</A></CENTER>";

?>