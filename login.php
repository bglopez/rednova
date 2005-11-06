<?

include("config.php");

if(empty($lang))
  $lang=$default_lang;

$found = 0;
if(!empty($newlang))
{
  if(!preg_match("/^[\w]+$/", $lang)) 
  {
     $lang = $default_lang;

  }
  foreach($avail_lang as $key => $value)
  {
    if($newlang == $value[file])
    {
      $lang=$newlang;
      SetCookie("lang",$lang,time()+(3600*24)*365,$gamepath,$gamedomain);
      $found = 1;
      break;
    }
  }

  if($found == 0)
    $lang = $default_lang;

  $lang = $lang . ".inc";
}

include("languages/$lang");

$title=$l_login_title;

include("header.php");

?>

<SCRIPT language="JavaScript" SRC="md5.js"></SCRIPT>

<CENTER>


<?php
bigtitle();
?>

<form action="login2.php" method="post" onSubmit="md5onsubmit()">
<BR><BR>

<TABLE CELLPADDING="4">
<TR>
	<TD align="right"><? echo $l_login_email; ?></TD>
	<TD align="left"><INPUT TYPE="TEXT" NAME="email" SIZE="20" MAXLENGTH="40" VALUE="<?php echo "$username" ?>"></TD>
</TR>
<TR>
	<TD align="right"><? echo $l_login_pw;?></TD>
	<TD align="left"><INPUT TYPE="PASSWORD" NAME="passMD5" SIZE="20" MAXLENGTH=<? echo $maxlen_password; ?> VALUE="<?php // echo "$password" ?>">
	<INPUT TYPE="HIDDEN" NAME="pass"></TD>
</TR>
<TR><TD colspan=2><center>Forgot your password?  Enter it blank and press login.</center></TD></TR></TABLE>

<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
// <!--

function md5onsubmit()
{
	document.forms(0).pass.value = calcMD5(document.forms(0).passMD5.value);
	document.forms(0).passMD5.value = "";
	return true;
}


var swidth = 0;
if(self.screen)
{
  swidth = screen.width;
  document.write("<INPUT TYPE=\"HIDDEN\" NAME=\"res\" VALUE=\"" + swidth + "\"></INPUT>");
}
if(swidth != 640 && swidth != 800 && swidth != 1024)
{
  document.write("<TABLE><TR><TD COLSPAN=2>");
  document.write("<? echo $l_login_chooseres;?><BR>");
  document.write("<CENTER><INPUT TYPE=\"RADIO\" NAME=\"res\" VALUE=\"640\">640x480</INPUT>");
  document.write("<INPUT TYPE=\"RADIO\" NAME=\"res\" CHECKED VALUE=\"800\">800x600</INPUT>");
  document.write("<INPUT TYPE=\"RADIO\" NAME=\"res\" VALUE=\"1024\">1024x768</INPUT></CENTER>");
  document.write("</TD></TR></TABLE>");
}
// -->
</SCRIPT>
<NOSCRIPT>
<TABLE><TR>
	<TD COLSPAN="2">
	<? echo $l_login_chooseres;?><BR>
	<INPUT TYPE="RADIO" NAME="res" VALUE="640">640x480</INPUT>
	<INPUT TYPE="RADIO" NAME="res" CHECKED VALUE="800">800x600</INPUT>
	<INPUT TYPE="RADIO" NAME="res" VALUE="1024">1024x768</INPUT></CENTER>
	</TD>
</TR>
</NOSCRIPT>
</TABLE>
<BR>
<INPUT TYPE="SUBMIT" VALUE="<? echo $l_login_title;?>">
<BR><BR>
<? echo $l_login_newp;?>
<BR><BR>
<? echo $l_login_prbs;?> <A HREF="mailto:<?php echo "$admin_mail"?>"><? echo $l_login_emailus;?></A>
</FORM>

<?php
if(!empty($link_forums))
  echo "<A HREF=\"$link_forums\" TARGET=\"_blank\">$l_forums</A> - ";
?>
<A HREF="ranking.php"><? echo $l_rankings;?></A><? echo " - "; ?>
<A HREF="settings.php"><? echo $l_login_settings;?></A>
<BR><BR>
<form action=login.php method=POST>
<?

echo "$l_login_lang&nbsp;&nbsp;<select name=newlang>";

foreach($avail_lang as $curlang)
{
  if($curlang['file'].".inc" == $lang)
    $selected = "selected";
  else
    $selected = "";

  echo "<option value=$curlang[file] $selected>$curlang[name]</option>";
}

echo "</select>&nbsp;&nbsp;<input type=submit value=$l_login_change>";
?>

</form>
</CENTER>

<?php
include("footer.php");
?>
