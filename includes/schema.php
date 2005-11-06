<?

function create_schema()
{
/*********************************************************
If you add/remove a table, don't forget to update the
table name variables in the global_func file.
*********************************************************/

global $maxlen_password;
global $dbtables;
global $db;

// Delete all tables in the database
echo "<b>Dropping all tables </b><BR>";
foreach ($dbtables as $table => $tablename)
{
	echo "Dropping $table ";
	if (($table=="hof_game") || ($table=="hof_player") || ($table=="rpp_pay"))
	{
		echo "- table <FONT COLOR=RED>skipped</FONT>.<br>";
	} else {
		$query = $db->Execute("DROP TABLE $tablename");
		echo "- table dropped <FONT COLOR=GREEN>successfully</FONT>.<br>";
	}
}
echo "<b>All tables have been successfully dropped.</b><p>";

// Create database schema
echo "<b>Creating tables </b><BR>";
echo "Creating table: links ";
$db->Execute("CREATE TABLE $dbtables[links] (" .
             "link_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "link_start int unsigned DEFAULT '0' NOT NULL," .
             "link_dest int unsigned DEFAULT '0' NOT NULL," .
             "PRIMARY KEY (link_id)," .
             "KEY link_start (link_start)," .
             "KEY link_dest (link_dest)" .
             ")") or die (" - <FONT COLOR=RED>blerg!</FONT>"); // now that is one of the coolest error messages I have seen in a while....
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: planets ";
$db->Execute("CREATE TABLE $dbtables[planets](" .
             "planet_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "sector_id int unsigned DEFAULT '0' NOT NULL," .
             "name tinytext," .
             "organics bigint(20) DEFAULT '0' NOT NULL," .
             "ore bigint(20) DEFAULT '0' NOT NULL," .
             "goods bigint(20) DEFAULT '0' NOT NULL," .
             "energy bigint(20) DEFAULT '0' NOT NULL," .
             "colonists bigint(20) DEFAULT '0' NOT NULL," .
             "credits bigint(20) DEFAULT '0' NOT NULL," .
             "fighters bigint(20) DEFAULT '0' NOT NULL," .
             "torps bigint(20) DEFAULT '0' NOT NULL," .
             "owner int unsigned DEFAULT '0' NOT NULL," .
             "corp int unsigned DEFAULT '0' NOT NULL," .
             "base enum('Y','N') DEFAULT 'N' NOT NULL," .
             "sells enum('Y','N') DEFAULT 'N' NOT NULL," .
             "prod_organics float(5,2) unsigned DEFAULT '20.0' NOT NULL," .
             "prod_ore float(5,2) unsigned DEFAULT '20.0' NOT NULL," .
             "prod_goods float(5,2) unsigned DEFAULT '20.0' NOT NULL," .
             "prod_energy float(5,2) unsigned DEFAULT '20.0' NOT NULL," .
             "prod_fighters float(5,2) unsigned DEFAULT '10.0' NOT NULL," .
             "prod_torp float(5,2) unsigned DEFAULT '10.0' NOT NULL," .
             "defeated enum('Y','N') DEFAULT 'N' NOT NULL," .
             "PRIMARY KEY (planet_id)," .
             "KEY owner (owner)," .
             "KEY corp (corp)" .
             ")") or die (" - <FONT COLOR=RED>blerg!</FONT>");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: traderoutes ";
$db->Execute("CREATE TABLE $dbtables[traderoutes](" .
             "traderoute_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "source_id int unsigned DEFAULT '0' NOT NULL," .
             "dest_id int unsigned DEFAULT '0' NOT NULL," .
             "source_type enum('P','L','C','D') DEFAULT 'P' NOT NULL," .
             "dest_type enum('P','L','C','D') DEFAULT 'P' NOT NULL," .
             "move_type enum('R','W') DEFAULT 'W' NOT NULL," .
             "owner int unsigned DEFAULT '0' NOT NULL," .
             "circuit enum('1','2') DEFAULT '2' NOT NULL," .
             "PRIMARY KEY (traderoute_id)," .
             "KEY owner (owner)" .
             ")") or die (" - <FONT COLOR=RED>blerg!</FONT>");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: ships ";
$db->Execute("CREATE TABLE $dbtables[ships](" .
             "ship_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "ship_name char(20)," .
             "ship_destroyed enum('Y','N') DEFAULT 'N' NOT NULL," .
             "character_name char(20) NOT NULL," .
             "password char($maxlen_password) NOT NULL," .
             "email char(60) NOT NULL," .
             "hull tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "engines tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "power tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "computer tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "sensors tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "beams tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "torp_launchers tinyint(3) DEFAULT '0' NOT NULL," .
             "torps bigint(20) DEFAULT '0' NOT NULL," .
             "shields tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "armour tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "armour_pts bigint(20) DEFAULT '0' NOT NULL," .
             "cloak tinyint(3) unsigned DEFAULT '0' NOT NULL," .
             "credits bigint(20) DEFAULT '0' NOT NULL," .
             "sector int unsigned DEFAULT '0' NOT NULL," .
             "ship_ore bigint(20) DEFAULT '0' NOT NULL," .
             "ship_organics bigint(20) DEFAULT '0' NOT NULL," .
             "ship_goods bigint(20) DEFAULT '0' NOT NULL," .
             "ship_energy bigint(20) DEFAULT '0' NOT NULL," .
             "ship_colonists bigint(20) DEFAULT '0' NOT NULL," .
             "ship_fighters bigint(20) DEFAULT '0' NOT NULL," .
             "turns smallint(4) DEFAULT '0' NOT NULL," .
             "ship_damage set('engines','power','computer','sensors','torps','cloak','shields') NOT NULL," .
             "on_planet enum('Y','N') DEFAULT 'N' NOT NULL," .
             "dev_warpedit smallint(5) DEFAULT '0' NOT NULL," .
             "dev_genesis smallint(5) DEFAULT '0' NOT NULL," .
             "dev_beacon smallint(5) DEFAULT '0' NOT NULL," .
             "dev_emerwarp smallint(5) DEFAULT '0' NOT NULL," .
             "dev_escapepod enum('Y','N') DEFAULT 'N' NOT NULL," .
             "dev_fuelscoop enum('Y','N') DEFAULT 'N' NOT NULL," .
             "dev_minedeflector bigint(20) DEFAULT '0' NOT NULL," .
             "turns_used int unsigned DEFAULT '0' NOT NULL," .
             "last_login datetime," .
             "rating bigint(20) DEFAULT '0' NOT NULL," .
             "score bigint(20) DEFAULT '0' NOT NULL," .
             "team int DEFAULT '0' NOT NULL," .
             "team_invite int DEFAULT '0' NOT NULL," .
             "interface enum('N','O') DEFAULT 'N' NOT NULL," .
	 	         "ip_address tinytext NOT NULL," .
             "planet_id int unsigned DEFAULT '0' NOT NULL," .
             "preset1 int DEFAULT '0' NOT NULL," .
             "preset2 int DEFAULT '0' NOT NULL," .
             "preset3 int DEFAULT '0' NOT NULL," .
             "trade_colonists enum('Y', 'N') DEFAULT 'Y' NOT NULL," .
             "trade_fighters enum('Y', 'N') DEFAULT 'N' NOT NULL," .
             "trade_torps enum('Y', 'N') DEFAULT 'N' NOT NULL," .
             "trade_energy enum('Y', 'N') DEFAULT 'Y' NOT NULL," .
             "cleared_defences tinytext," .
             "lang varchar(30) DEFAULT 'english.inc' NOT NULL," .
             "dhtml enum('Y', 'N') DEFAULT 'Y' NOT NULL," .
             "dev_lssd enum('Y','N') DEFAULT 'Y' NOT NULL," .
             "vote tinyint(4) default '-1'," .
             "shoutbox enum('Y','N') DEFAULT 'Y' NOT NULL," .
             "cheater enum('Y','N','O') DEFAULT 'N' NOT NULL," .
             "cookie tinyint(4) default '-1'," .
             "pay enum('Y','N') DEFAULT 'N' NOT NULL," .
             "PRIMARY KEY (email)," .
             "KEY sector (sector)," .
             "KEY ship_destroyed (ship_destroyed)," .
             "KEY on_planet (on_planet)," .
             "KEY team (team)," .
             "KEY ship_id (ship_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: universe ";
$db->Execute("CREATE TABLE $dbtables[universe](" .
             "sector_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "sector_name tinytext," .
             "zone_id int DEFAULT '0' NOT NULL," .
             "port_type enum('ore','organics','goods','energy','special','none') DEFAULT 'none' NOT NULL," .
             "port_organics bigint(20) DEFAULT '0' NOT NULL," .
             "port_ore bigint(20) DEFAULT '0' NOT NULL," .
             "port_goods bigint(20) DEFAULT '0' NOT NULL," .
             "port_energy bigint(20) DEFAULT '0' NOT NULL," .
             "KEY zone_id (zone_id)," .
             "KEY port_type (port_type)," .
             "beacon tinytext," .
             "angle1 float(10,2) DEFAULT '0.00' NOT NULL," .
             "angle2 float(10,2) DEFAULT '0.00' NOT NULL," .
             "distance bigint(20) unsigned DEFAULT '0' NOT NULL," .
             "fighters bigint(20) DEFAULT '0' NOT NULL," .
             "PRIMARY KEY (sector_id)" .
             ")");

echo "Creating table: zones ";
$db->execute("CREATE TABLE $dbtables[zones](" .
             "zone_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "zone_name tinytext," .
             "owner int unsigned DEFAULT '0' NOT NULL," .
             "corp_zone enum('Y', 'N') DEFAULT 'N' NOT NULL," .
             "allow_beacon enum('Y','N','L') DEFAULT 'Y' NOT NULL," .
             "allow_attack enum('Y','N') DEFAULT 'Y' NOT NULL," .
             "allow_planetattack enum('Y','N') DEFAULT 'Y' NOT NULL," .
             "allow_warpedit enum('Y','N','L') DEFAULT 'Y' NOT NULL," .
             "allow_planet enum('Y','L','N') DEFAULT 'Y' NOT NULL," .
             "allow_trade enum('Y','L','N') DEFAULT 'Y' NOT NULL," .
             "allow_defenses enum('Y','L','N') DEFAULT 'Y' NOT NULL," .
             "max_hull int DEFAULT '0' NOT NULL," .
             "PRIMARY KEY(zone_id)," .
             "KEY zone_id(zone_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: ibank_accounts ";
$db->Execute("CREATE TABLE $dbtables[ibank_accounts](" .
             "ship_id int DEFAULT '0' NOT NULL," .
             "balance bigint(20) DEFAULT '0'," .
             "loan bigint(20)  DEFAULT '0'," .
             "loantime TIMESTAMP(14)," .
			 "PRIMARY KEY(ship_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: IGB_transfers ";
$db->Execute("CREATE TABLE $dbtables[IGB_transfers](" .
             "transfer_id int DEFAULT '0' NOT NULL auto_increment," .
             "source_id int DEFAULT '0' NOT NULL," .
             "dest_id int DEFAULT '0' NOT NULL," .
             "time TIMESTAMP(14)," .
             "PRIMARY KEY(transfer_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: teams ";
$db->Execute("CREATE TABLE $dbtables[teams](" .
             "id int DEFAULT '0' NOT NULL," .
             "creator int DEFAULT '0'," .
             "team_name tinytext," .
             "description tinytext," .
             "number_of_members tinyint(3) DEFAULT '0' NOT NULL," .
             "PRIMARY KEY(id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: news ";
$db->Execute("CREATE TABLE $dbtables[news] (" .
             "news_id int(11) DEFAULT '0' NOT NULL auto_increment," .
             "headline varchar(100) NOT NULL," .
             "newstext text NOT NULL," .
             "user_id int(11)," .
             "date timestamp(8)," .
             "news_type varchar(10)," .
             "PRIMARY KEY (news_id)," .
             "KEY news_id (news_id)," .
             "UNIQUE news_id_2 (news_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: internal messaging ";
$db->Execute("CREATE TABLE $dbtables[messages] (" .
             "ID int NOT NULL auto_increment," .
             "sender_id int NOT NULL default '0'," .
             "recp_id int NOT NULL default '0'," .
             "subject varchar(250) NOT NULL default ''," .
             "sent varchar(19) NULL," .
             "message longtext NOT NULL," .
             "notified enum('Y','N') NOT NULL default 'N'," .
             "PRIMARY KEY  (ID) " .
             ") TYPE=MyISAM");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: furangee ";
        $db->Execute("CREATE TABLE $dbtables[furangee](" .
            "furangee_id char(40) NOT NULL," .
            "active enum('Y','N') DEFAULT 'Y' NOT NULL," .
            "behavior smallint(5) DEFAULT '0' NOT NULL," .
            "orders smallint(5) DEFAULT '0' NOT NULL," .
            "experience smallint(5) DEFAULT '0' NOT NULL," .
            "PRIMARY KEY (furangee_id)," .
            "KEY furangee_id (furangee_id)" .
            ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: sector_defence ";
$db->Execute("CREATE TABLE $dbtables[sector_defence](" .
             "defence_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "ship_id int DEFAULT '0' NOT NULL," .
             "sector_id int unsigned DEFAULT '0' NOT NULL," .
             "defence_type enum('M','F') DEFAULT 'M' NOT NULL," .
             "quantity bigint(20) DEFAULT '0' NOT NULL," .
             "fm_setting enum('attack','toll') DEFAULT 'toll' NOT NULL," .
             "PRIMARY KEY (defence_id)," .
             "KEY sector_id (sector_id)," .
             "KEY ship_id (ship_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: scheduler ";
$db->Execute("CREATE TABLE $dbtables[scheduler](" .
             "sched_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "loop enum('Y','N') DEFAULT 'N' NOT NULL," .
             "ticks_left int unsigned DEFAULT '0' NOT NULL," .
             "ticks_full int unsigned DEFAULT '0' NOT NULL," .
             "spawn int unsigned DEFAULT '0' NOT NULL," .
             "file varchar(30) NOT NULL," .
             "extra_info varchar(50) NOT NULL," .
             "last_run BIGINT(20)," .
             "PRIMARY KEY (sched_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: ip_bans ";
$db->Execute("CREATE TABLE $dbtables[ip_bans](" .
             "ban_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "ban_mask varchar(16) NOT NULL," .
             "PRIMARY KEY (ban_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: logs ";
$db->Execute("CREATE TABLE $dbtables[logs](" .
             "log_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "ship_id int DEFAULT '0' NOT NULL," .
             "type mediumint(5) DEFAULT '0' NOT NULL," .
             "time TIMESTAMP(14)," .
             "data varchar(255)," .
             "PRIMARY KEY (log_id)," .
             "KEY idate (ship_id,time)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: bounty ";
$db->Execute("CREATE TABLE $dbtables[bounty] (" .
             "bounty_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "amount bigint(20) unsigned DEFAULT '0' NOT NULL," . 
             "bounty_on int unsigned DEFAULT '0' NOT NULL," .
             "placed_by int unsigned DEFAULT '0' NOT NULL," .
             "PRIMARY KEY (bounty_id)," .
             "KEY bounty_on (bounty_on)," .
             "KEY placed_by (placed_by)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: movement_log ";
$db->Execute("CREATE TABLE $dbtables[movement_log](" .
             "event_id int unsigned DEFAULT '0' NOT NULL auto_increment," .
             "ship_id int DEFAULT '0' NOT NULL," .
             "sector_id int DEFAULT '0'," .
             "time TIMESTAMP(14) ," .
             "PRIMARY KEY (event_id)," .
             "KEY ship_id(ship_id)," .
             "KEY sector_id (sector_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: vote ";
$db->Execute("CREATE TABLE $dbtables[vote](" .
             "vote_id bigint(5) NOT NULL auto_increment," .
             "vote_text longtext NOT NULL," . 
             "PRIMARY KEY (vote_id)" .
             ")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: shoutbox ";
$db->Execute("CREATE TABLE $dbtables[shoutbox](" .
			"sb_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
			"player_id BIGINT NOT NULL, " .
			"player_name MEDIUMTEXT NOT NULL, " .
			"sb_date BIGINT NOT NULL, " .
			"sb_text LONGTEXT NOT NULL, " .
			"sb_alli BIGINT NOT NULL " .
			")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: Admin News ";
$db->Execute("CREATE TABLE $dbtables[adminnews](" .
			"an_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY , " .
			"an_text LONGTEXT NOT NULL " .
			")");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: Hall of Fame (Part 1) ";
$db->Execute("CREATE TABLE $dbtables[hof_game](" .
  "GameID bigint(20) NOT NULL auto_increment, " .
  "GameName varchar(64) NOT NULL default '', " .
  "GameRound bigint(20) NOT NULL default '0', " .
  "GameStart bigint(20) default NULL, " .
  "GameEnd bigint(20) default NULL, " .
  "PRIMARY KEY  (GameID))");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: Hall of Fame (Part 2) ";
$db->Execute("CREATE TABLE $dbtables[hof_player](" .
  "PlayerID bigint(20) NOT NULL auto_increment, " .
  "PlayerGameID bigint(20) NOT NULL default '0', " .
  "PlayerName varchar(64) default NULL, " .
  "PlayerScore bigint(20) default NULL, " .
  "PlayerRank bigint(20) default NULL, " .
  "PlayerTurns bigint(20) default NULL, " .
  "PlayerEmail varchar(128) default NULL, " .
  "PRIMARY KEY  (PlayerID))");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: Advancde Logging ";
$db->Execute("CREATE TABLE $dbtables[adm_logs](" .
  "LogID int(11) NOT NULL auto_increment, " .
  "LogPlayer int(11) NOT NULL default '0', " .
  "LogIP varchar(15) NOT NULL default '', " .
  "LogTime int(11) NOT NULL default '0', " .
  "LogEvent varchar(8) NOT NULL default '', " .
  "LogData1 varchar(64) default NULL, " .
  "LogData2 LONGTEXT default NULL, " .
  "PRIMARY KEY  (LogID))");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";

echo "Creating table: RPP Pay ";
$db->Execute("CREATE TABLE $dbtables[rpp_pay] (" .
  "pay_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, " .
  "pay_email VARCHAR( 60 ) NOT NULL, " .
  "pay_name VARCHAR( 20 ) NOT NULL, " .
  "pay_ship VARCHAR( 20 ) NOT NULL, " .
  "pay_start DATETIME NOT NULL, " .
  "pay_end DATETIME NOT NULL, " .
  "PRIMARY KEY ( pay_id ))");
echo "- created <FONT COLOR=GREEN>successfully</FONT>.<BR>";




//Finished
echo "<b>Database schema creation completed <FONT COLOR=GREEN>successfully</FONT>.</b><BR>";

}

?>
