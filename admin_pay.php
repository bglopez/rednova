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
	echo "<FORM ACTION=admin_pay.php METHOD=POST><INPUT TYPE=SUBMIT NAME=refresh Value='Refresh'>&nbsp;<INPUT TYPE=SUBMIT NAME=checkuser Value='Check'><BR><BR>";

	if(isset($adduser))
		{
		if($pay_money>0)
			{
				$tmp = Money2Month($pay_money);
				$pay_until = mktime(0,0,0,date("m") + $tmp,date("d") + 1,date("Y"));
				$sql = "INSERT INTO bnt_payuser (pay_name,pay_email,pay_ship,pay_money,pay_until) VALUES ('$pay_name','$pay_email','$pay_ship',$pay_money,$pay_until)";
				$db->Execute($sql);
		
				//Modify the Game-Ship Tables!
				SetPay($pay_email,"Y");

				echo "<FONT COLOR=LIGHTGREEN>User Added</FONT><BR>";
			} else {
				echo "<FONT COLOR=RED>Wrong Money Value</FONT><BR>";
			}
		}

	if(isset($deluser))
		{
		$tmp = trim(substr($deluser,7));

		//Get Mail
		$sql = "SELECT pay_email FROM bnt_payuser WHERE pay_id = $tmp";
		$res = $db->Execute($sql);

		//Modify the Game-Ship Tables!
		SetPay($res->fields[pay_email],"N");

		//Final Delete
		$sql = "DELETE FROM bnt_payuser WHERE pay_id = $tmp";
		$db->Execute($sql);

		echo "<FONT COLOR=LIGHTGREEN>User Deleted/Set to normal User</FONT><BR>";
		}

	if(isset($addmoney))
		{
		$tmp = trim(substr($addmoney,10));
		if($money[$tmp]>0)
			{
				$sql = "SELECT * FROM bnt_payuser WHERE pay_id = $tmp";
				$res = $db->Execute($sql); 
				$row = $res->fields;
		
				$pay_money = $row[pay_money] + $money[$tmp];
				$tmpM = Money2Month($money[$tmp]);
				if($row[pay_until]>Time())
					$pay_until = mktime(0,0,0,date("m",$row[pay_until]) + $tmpM,date("d",$row[pay_until]) + 1,date("Y",$row[pay_until]));
				else
					$pay_until = mktime(0,0,0,date("m") + $tmpM,date("d") + 1,date("Y"));
		
				$sql = "UPDATE bnt_payuser SET pay_money = $pay_money, pay_until = $pay_until WHERE pay_id = $tmp";
				$db->Execute($sql);
		
				//Modify the Game-Ship Tables!
				SetPay($row[pay_email],"Y");
		
				echo "<FONT COLOR=LIGHTGREEN>Money/Month added.</FONT><BR>";
			} else {
				echo "<FONT COLOR=RED>Wrong Money Value</FONT><BR>";
			}
		}


	echo "<TABLE><TR><TH>Name</TH><TH>Ship</TH><TH>Mail</TH><TH>Money</TH><TH>Until</TH><TH colspan=2>Add Money</TH><TH>Delete</TH></TR>";
	$sql = "SELECT * FROM bnt_payuser";
	$res = $db->Execute($sql); 
	while(!$res->EOF)
		{
		$row = $res->fields;
		echo "<TR>";
		echo "<TD>$row[pay_name]</TD>";
		echo "<TD>$row[pay_ship]</TD>";
		echo "<TD>$row[pay_email]</TD>";
		echo "<TD ALIGN=RIGHT>$row[pay_money]</TD>";

		if($row[pay_until]>Time())
			{
			echo "<TD ALIGN=CENTER><FONT COLOR=LIGHTGREEN>" . date("Y-m-d",$row[pay_until]) . "</FONT></TD>";
			if(isset($checkuser)) SetPay($row[pay_email],"Y");
			}
		else
			{
			echo "<TD ALIGN=CENTER><FONT COLOR=RED>" . date("Y-m-d",$row[pay_until]) . "</FONT></TD>";
			if(isset($checkuser)) SetPay($row[pay_email],"N");
			}

		echo "<TD><INPUT TYPE=TEXT NAME='money[$row[pay_id]]' SIZE=2 MAXLENGTH=2></TD>";
		echo "<TD><INPUT TYPE=SUBMIT NAME=addmoney Value='Add Money $row[pay_id]'></TD>";
		echo "<TD><INPUT TYPE=SUBMIT NAME=deluser Value='Delete $row[pay_id]'></TD>";

		echo "</TR>";
		$res->MoveNext();
		}
	echo "</TABLE>";


	echo "<BR><BR><TABLE>";
	echo "<TR><TH colspan=4>Add User</TH></TR>";
	echo "<TR><TD>Name</TD><TD><INPUT TYPE=TEXT NAME=pay_name></TD>";
	echo "<TD>Ship</TD><TD><INPUT TYPE=TEXT NAME=pay_ship></TD></TR>";
	echo "<TR><TD>Mail</TD><TD><INPUT TYPE=TEXT NAME=pay_email></TD>";
	echo "<TD>Money</TD><TD><INPUT TYPE=TEXT NAME=pay_money></TD></TR>";
	echo "<TR><TH colspan=4><INPUT TYPE=SUBMIT NAME=adduser Value='Add User'></TH></TR>";
	echo "</TABLE>";


	echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE='$swordfish'>";
	echo "</FORM>";
}
  
include("footer.php");

function SetPay($email,$pay)
	{
		global $db;
		//$sql = "UPDATE blitz_ships SET pay = '$pay' WHERE email = '$email'";
		//$db->Execute($sql);
		//$sql = "UPDATE long_ships SET pay = '$pay' WHERE email = '$email'";
		//$db->Execute($sql);
		$sql = "UPDATE bntdev_ships SET pay = '$pay' WHERE email = '$email'";
		$db->Execute($sql);
	}

function Money2Month($money)
	{
	$month = 0;
	if($money==1) $month = 1;
	if($money==2) $month = 2;
	if($money==3) $month = 3;
	if($money==4) $month = 4;
	if($money==5) $month = 6;
	return $month;
	}
?>