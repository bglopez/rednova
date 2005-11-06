<? 
include("config.php");
include("languages/$lang");
connectDB();

$xsql = "SELECT count(*) as x FROM $dbtables[ships] WHERE ship_destroyed = 'N' ";
$res = $db->Execute($xsql);
$row = $res->fields;
$players = $row[x];

$xsql = "SELECT COUNT(*) as x FROM $dbtables[ships] WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_login)) / 60 <= 5 and email NOT LIKE '%@furangee'";
$res = $db->Execute($xsql);
$row = $res->fields;
$players_on = $row[x];

echo "$players ($players_on online)";

?>
