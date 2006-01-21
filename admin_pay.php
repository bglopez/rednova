<?
include("config.php");
include("languages/$lang");
updatecookie();

$title="Administration - PAY GAME";
include("header.php");

connectdb();
bigtitle();

if($swordfish != $adminpass)
{
  echo "<FORM ACTION=admin_pay.php METHOD=POST>";
  echo "Password: <INPUT TYPE=PASSWORD NAME=swordfish SIZE=20 MAXLENGTH=20>&nbsp;&nbsp;";
  echo "<INPUT TYPE=SUBMIT VALUE=Submit><INPUT TYPE=RESET VALUE=Reset>";
  echo "</FORM>";
}
else
{
	echo "<FORM ACTION=admin_pay.php METHOD=POST STYLE='display:hidden;'><INPUT TYPE=HIDDEN NAME=swordfish VALUE='$swordfish'>";
	echo "<INPUT TYPE=SUBMIT NAME=refresh Value='Refresh'><BR><BR>";
	echo "</FORM>";

	if(isset($adduser))
		{
		$xsql = "INSERT INTO $dbtables[rpp_pay] (pay_name,pay_ship,pay_email,pay_start,pay_end) VALUES ('$pay_name','$pay_ship','$pay_email','$pay_start','$pay_end')";
		$db->Execute($xsql);
		echo "<FONT COLOR=LIGHTGREEN>User Added</FONT><BR><BR>";
		}

	if(isset($deluser))
		{
		$xsql = "DELETE FROM $dbtables[rpp_pay] WHERE pay_id = $_POST[pay_id]";
		$db->Execute($xsql);
		echo "<FONT COLOR=LIGHTGREEN>User Deleted</FONT><BR><BR>";
		}

	if(isset($modifyuser))
		{
		$xsql = "SELECT * FROM $dbtables[rpp_pay] WHERE pay_id = $_POST[pay_id]";
		$res = $db->Execute($xsql);
		$row = $res->fields;
			echo "<FORM ACTION=admin_pay.php METHOD=POST STYLE='display:hidden;'><INPUT TYPE=HIDDEN NAME=swordfish VALUE='$swordfish'>";
		echo "<BR><BR><TABLE BORDER=1 CELLSPACING=0 CELLPADDING=2>";
		echo "<TR BGCOLOR=$color_header><TH colspan=5>Modify User</TH></TR>";
		echo "<TR BGCOLOR=$color_header><TD>Name</TD><TD>Ship</TD><TD>Mail</TD><TD>Start</TD><TD>End</TD></TR>";
		echo "<TR BGCOLOR=$color_line1>";
		echo "<TD><INPUT TYPE=TEXT NAME=pay_name VALUE='$row[pay_name]'></TD>";
		echo "<TD><INPUT TYPE=TEXT NAME=pay_ship VALUE='$row[pay_ship]'></TD>";
		echo "<TD><INPUT TYPE=TEXT NAME=pay_email VALUE='$row[pay_email]'></TD>";
		echo "<TD><INPUT TYPE=TEXT NAME=pay_start VALUE='$row[pay_start]'></TD>";
		echo "<TD><INPUT TYPE=TEXT NAME=pay_end VALUE='$row[pay_end]'></TD>";
		echo "</TR>";
		echo "<TR BGCOLOR=$color_line2><TD colspan=5><INPUT TYPE=HIDDEN NAME=pay_id VALUE='$row[pay_id]'><INPUT TYPE=SUBMIT NAME=modifyuser2 Value='Modify User'></TD></TR>";
		echo "</TABLE>";
			echo "</FORM>";
		}

	if(isset($modifyuser2))
		{
		$xsql = "UPDATE $dbtables[rpp_pay] SET pay_name = '$_POST[pay_name]', pay_ship = '$_POST[pay_ship]', pay_email = '$_POST[pay_email]', pay_start = '$_POST[pay_start]', pay_end = '$_POST[pay_end]' WHERE pay_id = $_POST[pay_id]";
		$db->Execute($xsql);
		echo "<FONT COLOR=LIGHTGREEN>User Modify</FONT><BR><BR>";
		}




	echo "<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=2>";
	echo "<TR BGCOLOR=$color_header><TH colspan=7>Userlist</TH></TR>";

	echo "<TR BGCOLOR=$color_header><TH>Name</TH><TH>Ship</TH><TH>Mail</TH><TH>From</TH><TH>Until</TH><TH>Modify</TH><TH>Delete</TH></TR>";
	$sql = "SELECT * FROM $dbtables[rpp_pay]";
	$res = $db->Execute($sql);
	if($res)
	while(!$res->EOF)
		{
			if($tmpcolor!=$color_line1) $tmpcolor=$color_line1;
			else $tmpcolor=$color_line2;
		$row = $res->fields;
		echo "<TR BGCOLOR=$tmpcolor>";
			echo "<TD>$row[pay_name]</TD>";
			echo "<TD>$row[pay_ship]</TD>";
			echo "<TD>$row[pay_email]</TD>";
	
			echo "<TD>$row[pay_start]</TD>";
			echo "<TD>$row[pay_end]</TD>";
	
			echo "<FORM ACTION=admin_pay.php METHOD=POST STYLE='display:hidden;'><INPUT TYPE=HIDDEN NAME=swordfish VALUE='$swordfish'><INPUT TYPE=HIDDEN NAME=pay_id VALUE='$row[pay_id]'>";
			echo "<TD><INPUT TYPE=SUBMIT NAME=modifyuser Value='Modify'></TD>";
			echo "<TD><INPUT TYPE=SUBMIT NAME=deluser Value='Delete'></TD>";
			echo "</FORM>";
		echo "</TR>";
		$res->MoveNext();
		}
	echo "</TABLE>";


	echo "<FORM ACTION=admin_pay.php METHOD=POST STYLE='display:hidden;'><INPUT TYPE=HIDDEN NAME=swordfish VALUE='$swordfish'>";
	echo "<BR><BR><TABLE BORDER=1 CELLSPACING=0 CELLPADDING=2>";
	echo "<TR BGCOLOR=$color_header><TH colspan=5>Add User</TH></TR>";
	echo "<TR BGCOLOR=$color_header><TD>Name</TD><TD>Ship</TD><TD>Mail</TD><TD>Start</TD><TD>End</TD></TR>";
	echo "<TR BGCOLOR=$color_line1>";
	echo "<TD><INPUT TYPE=TEXT NAME=pay_name></TD>";
	echo "<TD><INPUT TYPE=TEXT NAME=pay_ship></TD>";
	echo "<TD><INPUT TYPE=TEXT NAME=pay_email></TD>";
	echo "<TD><INPUT TYPE=TEXT NAME=pay_start></TD>";
	echo "<TD><INPUT TYPE=TEXT NAME=pay_end></TD>";
	echo "</TR>";
	echo "<TR BGCOLOR=$color_line2><TD colspan=5><INPUT TYPE=SUBMIT NAME=adduser Value='Add User'></TD></TR>";
	echo "</TABLE>";
	echo "</FORM>";


	$sql = "SELECT ship_id, pay_id, UNIX_TIMESTAMP(pay_start) as pay_start, UNIX_TIMESTAMP(pay_end) as pay_end, pay FROM $dbtables[ships] as b LEFT JOIN $dbtables[rpp_pay] as a ON (a.pay_email=b.email) ";
	$res = $db->Execute($sql);
	if($res)
	while(!$res->EOF)
		{
		$row = $res->fields;
		if($row[ship_id]>0) {
			if($row[pay_id]>0) {
				if( ( $row[pay_start] <= time() ) && ( $row[pay_end] >= time() )) {
					$pay_change = "Y";
				} else {
					$pay_change = "N";
				}
			} else {
				$pay_change = "N";
			}
			if($pay_change != $row[pay]) {
				$db->Execute("UPDATE $dbtables[ships] SET pay='".$pay_change."' WHERE ship_id=$row[ship_id]");
				//echo "Ship ID: " . $row[ship_id] . " ==> " . $pay_change . "<BR>\n";
			}
		}
		$res->MoveNext();
		}

}
  
include("footer.php");
?>