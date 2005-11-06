<?

include("config.php");
include("languages/$lang");
updatecookie();

$title="$l_opt_title"; 
include("header.php");

connectdb();

if(checklogin())
{
  die();
}

bigtitle();
?>
<SCRIPT language="JavaScript" SRC="md5.js"></SCRIPT>
<SCRIPT language="JavaScript">
function md5onsubmit()
{
	if (document.forms(0).newpass1MD5.value != "")
		{
		document.forms(0).oldpass.value = calcMD5(document.forms(0).oldpassMD5.value);
		document.forms(0).newpass1.value = calcMD5(document.forms(0).newpass1MD5.value);
		document.forms(0).newpass2.value = calcMD5(document.forms(0).newpass2MD5.value);

		document.forms(0).oldpassMD5.value = "";
		document.forms(0).newpass1MD5.value = "";
		document.forms(0).newpass2MD5.value = "";
		}
	return true;
}
</SCRIPT>
<?

//-------------------------------------------------------------------------------------------------

$res = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $res->fields;
//-------------------------------------------------------------------------------------------------

echo "<FORM ACTION=option2.php METHOD=POST onSubmit='md5onsubmit()'>";
echo "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>";
echo "<TR BGCOLOR=\"$color_header\">";
echo "<TD COLSPAN=2><B>$l_opt_chpass</B></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line1\">";
echo "<TD>$l_opt_curpass</TD>";
echo "<TD><INPUT TYPE=PASSWORD NAME=oldpassMD5 SIZE=16 MAXLENGTH=$maxlen_password VALUE=\"\"><INPUT TYPE=HIDDEN NAME=oldpass></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line2\">";
echo "<TD>$l_opt_newpass</TD>";
echo "<TD><INPUT TYPE=PASSWORD NAME=newpass1MD5 SIZE=16 MAXLENGTH=$maxlen_password VALUE=\"\"><INPUT TYPE=HIDDEN NAME=newpass1></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line1\">";
echo "<TD>$l_opt_newpagain</TD>";
echo "<TD><INPUT TYPE=PASSWORD NAME=newpass2MD5 SIZE=16 MAXLENGTH=$maxlen_password VALUE=\"\"><INPUT TYPE=HIDDEN NAME=newpass2></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_header\">";
echo "<TD COLSPAN=2><B>$l_opt_userint</B></TD>";
echo "</TR>";
$intrf = ($playerinfo['interface'] == 'N') ? "CHECKED" : "";
echo "<TR BGCOLOR=\"$color_line1\">";
echo "<TD>$l_opt_usenew</TD><TD><INPUT TYPE=CHECKBOX NAME=intrf VALUE=N $intrf></INPUT></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_header\">";
echo "<TD COLSPAN=2><B>$l_opt_lang</B></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line1\">";
echo "<TD>$l_opt_select</TD><TD><select NAME=newlang>";

foreach($avail_lang as $curlang)
{
  if($curlang['file'] == $playerinfo[lang])
    $selected = "selected";
  else
    $selected = "";

  echo "<option value=$curlang[file] $selected>$curlang[name]</option>";
}

echo "</select></td>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_header\">";
echo "<TD COLSPAN=2><B>DHTML</B></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line2\">";
$dhtml = ($playerinfo['dhtml'] == 'Y') ? "CHECKED" : "";
echo "<TD>$l_opt_enabled</TD><TD><INPUT TYPE=CHECKBOX NAME=dhtml VALUE=Y $dhtml></INPUT></TD>";
echo "</TR>";
echo "<TR BGCOLOR=\"$color_line2\">";
$sb = ($playerinfo['shoutbox'] == 'Y') ? "CHECKED" : "";
echo "<TD>SHOUTBOX in footer:</TD><TD><INPUT TYPE=CHECKBOX NAME=shoutbox VALUE=Y $sb></INPUT></TD>";
echo "</TR>";
echo "</TABLE>";
echo "<BR>";
echo "<INPUT TYPE=SUBMIT value=$l_opt_save>";
echo "</FORM>";

TEXT_GOTOMAIN();

include("footer.php");

?>

