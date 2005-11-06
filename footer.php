<BR><BR><CENTER>
<?
global $db,$dbtables;
connectdb();

// Load default Playerinfo
$result = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $result->fields;

// Players Online
$res = $db->Execute("SELECT COUNT(*) as loggedin from $dbtables[ships] WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP($dbtables[ships].last_login)) / 60 <= 5 and email NOT LIKE '%@furangee'");
$row = $res->fields;
$online = $row[loggedin];

// Time Left
$res = $db->Execute("SELECT last_run FROM $dbtables[scheduler] LIMIT 1");
$result = $res->fields;
$mySEC = ($sched_ticks * 60) - (TIME()-$result[last_run]);

// Admin News
$res = $db->Execute("SELECT * FROM $dbtables[adminnews] ORDER BY an_id DESC");
$result = $res->fields;
$adminnews = $result[an_text];
//if ($adminnews == "") $adminnews = "---";

// Vote System
if(!empty($username))
	{
		if ($playerinfo['vote'] == -1) {
			$vote_text = "Inactive!";
		} else if ($playerinfo['vote'] == -2) {
			$vote_text =  "<a href='javascript:OpenVote()'>Not Allowed</A>";
		} else if ($playerinfo['vote'] < -2) {
			$vote_text =  "<a href='javascript:OpenVote()'>Vote Ended</A>";
		} else if ($playerinfo['vote'] > 0) {
			$vote_text =  "<a href='javascript:OpenVote()'>View Result</A>";
		} else if ($playerinfo['vote'] == 0) {
			$res = $db->Execute("SELECT * FROM $dbtables[vote] WHERE vote_id = 0");
			$row = $res->fields;
			$vote_text =  "<a href='javascript:OpenVote()'>$row[vote_text]</A>";
		}
	} else {
		$vote_text =  "Login!";
	}
?>

<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 xwidth="100%">
	<TR>
		<TD nowrap xwidth="10%" align=center bgcolor='<? echo $color_header; ?>'>&nbsp;&nbsp;Time Zone&nbsp;&nbsp;</TD>
		<TD nowrap xwidth="10%" align=center bgcolor='<? echo $color_line2; ?>'>&nbsp;&nbsp;Server Time&nbsp;&nbsp;</TD>
		<TD nowrap xwidth="10%" align=center bgcolor='<? echo $color_header; ?>'>&nbsp;&nbsp;Next Update in&nbsp;&nbsp;</TD>
		<TD nowrap xwidth="10%" align=center bgcolor='<? echo $color_line2; ?>'>&nbsp;&nbsp;Players Online&nbsp;&nbsp;</TD>
		<TD nowrap xwidth="35%" align=center bgcolor='<? echo $color_header; ?>'>&nbsp;&nbsp;Vote&nbsp;&nbsp;</TD>
	</TR>
	<TR>
		<TD nowrap align=center>&nbsp;<? echo $servertimezone; ?>&nbsp;</TD>
		<TD nowrap align=center><IMG NAME=h1 SRC='./images/0c.gif' BORDER=0><IMG NAME=h2 SRC='./images/0c.gif' BORDER=0><IMG NAME=c1 SRC='./images/cc.gif' BORDER=0><IMG NAME=m1 SRC='./images/0c.gif' BORDER=0><IMG NAME=m2 SRC='./images/0c.gif' BORDER=0><IMG NAME=c2 SRC='./images/cc.gif' BORDER=0><IMG NAME=s1 SRC='./images/0c.gif' BORDER=0><IMG NAME=s2 SRC='./images/0c.gif' BORDER=0></TD>
		<TD nowrap align=center><SPAN ID="MyTimer"><IMG NAME=t1 SRC='./images/8c.gif' BORDER=0><IMG NAME=t2 SRC='./images/8c.gif' BORDER=0><IMG NAME=t3 SRC='./images/8c.gif' BORDER=0></SPAN></TD>
		<TD nowrap align=center>&nbsp;<B><? echo $online; ?></B>&nbsp;</TD>
		<TD nowrap align=center>&nbsp;<? echo $vote_text; ?>&nbsp;</TD>
	</TR>
	<TR>
		<TD colspan=6 bgcolor='<? echo $color_line1; ?>'><IMG height=1 width=1 SRC='images/spacer.gif'></TD>
	</TR>

<?
if (isSet($adminnews)) {
?>
	<TR>
		<TD colspan=6 align=center><? echo $adminnews; ?></TD>
	</TR>
	<TR>
		<TD colspan=6 bgcolor='<? echo $color_line1; ?>'><IMG height=1 width=1 SRC='images/spacer.gif'></TD>
	</TR>
<?
}
?>


	<TR>
		<TD colspan=6 align=center>
			<font color=silver size=-4>

<a href="news.php" style="text-decoration:none;">Local BlackNova News</a>
&nbsp;&nbsp;&nbsp;<IMG SRC="images/star.gif">&nbsp;&nbsp;&nbsp;
<a href="halloffame.php" style="text-decoration:none;">Hall of Fame</a>
&nbsp;&nbsp;&nbsp;<IMG SRC="images/star.gif">&nbsp;&nbsp;&nbsp;
<a target="_blank" href="http://www.sourceforge.net/projects/blacknova" style="text-decoration:none;">BlackNova Traders</a>
&nbsp;&nbsp;&nbsp;<IMG SRC="images/star.gif">&nbsp;&nbsp;&nbsp;
BNT © 2000-2002 <a href="mailto:webmaster@blacknova.net" style="text-decoration:none;">Ron Harwood</a>
&nbsp;&nbsp;&nbsp;<IMG SRC="images/star.gif">&nbsp;&nbsp;&nbsp;
RPP © 2002-2005 <a href="mailto:indiana at rednova.de" style="text-decoration:none;">Indiana</a>

			</font>
		</TD>
	</TR>
</TABLE>


<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 

var myi = <?=$mySEC?> + 1;
var mSekunde = <?=date("s")?>;
var mMinute = <?=date("i")?>;
var mStunde = <?=date("H")?>;


var c0 = newImage("./images/0c.gif");
var c1 = newImage("./images/1c.gif");
var c2 = newImage("./images/2c.gif");
var c3 = newImage("./images/3c.gif");
var c4 = newImage("./images/4c.gif");
var c5 = newImage("./images/5c.gif");
var c6 = newImage("./images/6c.gif");
var c7 = newImage("./images/7c.gif");
var c8 = newImage("./images/8c.gif");
var c9 = newImage("./images/9c.gif");
var cc = newImage("./images/cc.gif");


function newImage(arg) {
	if (document.images) {
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}

function getTime() {
	if (--myi <= 0) document.getElementById("MyTimer").innerHTML = "<FONT COLOR=RED><B>UPDATE!</B></FONT>";
	else {
		document.images["t1"].src = eval("c"+Math.floor(myi/100)+".src");
		document.images["t2"].src = eval("c"+(Math.floor(myi/10)%10)+".src");
		document.images["t3"].src = eval("c"+(myi%10)+".src");
	}

	mSekunde++;

	if (mSekunde > 59) { mSekunde=1; mMinute++; }
	if (mMinute  > 59) {             mMinute=0; mStunde++; }
	if (mStunde  > 23) {                        mStunde=0; }

	if (mSekunde <= 9) {
		document.images["s1"].src = c0.src;
		document.images["s2"].src = eval("c"+mSekunde+".src");
		} else {
		document.images["s1"].src = eval("c"+Math.floor(mSekunde/10)+".src");
		document.images["s2"].src = eval("c"+(mSekunde%10)+".src");
		}

	if (mMinute <= 9) {
		document.images["m1"].src = c0.src;
		document.images["m2"].src = eval("c"+mMinute+".src");
		} else {
		document.images["m1"].src = eval("c"+Math.floor(mMinute/10)+".src");
		document.images["m2"].src = eval("c"+(mMinute%10)+".src");
		}

	if (mStunde <= 9) {
		document.images["h1"].src = c0.src;
		document.images["h2"].src = eval("c"+mStunde+".src");
		} else {
		document.images["h1"].src = eval("c"+Math.floor(mStunde/10)+".src");
		document.images["h2"].src = eval("c"+(mStunde%10)+".src");
		}

	window.setTimeout("getTime()", 999);
	}





	function OpenVote()
		{
			f1 = open("vote.php","f1","width=250,height=350");
		}
	function OpenSB()
		{
			f2 = open("shoutbox.php","f2","width=600,height=400,scrollbars=yes");
		}

getTime();

// END -->
</SCRIPT>

<?
if (($playerinfo[shoutbox]=='Y')&&($title!="SHOUTBOX"))
	{
	echo "<BR>";
	include("shoutbox.php");
	}
	else
	{
	echo "<BR><a href='JavaScript:OpenSB()'>View SHOUTBOX!</A>";
	}

include("banner.php");

?>

</CENTER>
</BODY>
</HTML>