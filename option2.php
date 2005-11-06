<?

include("config.php");
connectdb();
if(checklogin())
{
  die();
}
$title = "$l_opt2_title";

if($intrf == "N")
{
  $interface = "main.php";
  setcookie("interface", "main.php");
}
else
{
  $intrf = "O";
  $interface = "maintext.php";
  setcookie("interface", "maintext.php");
}

if (($newpass1MD5 != "") AND ($newpass1 == ""))
	{
	$newpass1 = substr(md5($newpass1MD5),0,$maxlen_password);
	$newpass2 = substr(md5($newpass2MD5),0,$maxlen_password);
	$oldpass = substr(md5($oldpassMD5),0,$maxlen_password);
	}

if (($newpass1MD5 == "") AND ($newpass1 != ""))
	{
	$newpass1 = substr($newpass1,0,$maxlen_password);
	$newpass2 = substr($newpass2,0,$maxlen_password);
	$oldpass = substr($oldpass,0,$maxlen_password);
	}

//-------------------------------------------------------------------------------------------------

if($newpass1 == $newpass2 && $password == $oldpass && $newpass1 != "")
{
  $userpass = $username."+".$newpass1;
  SetCookie("userpass",$userpass,time()+(3600*24)*365,$gamepath,$gamedomain);
  setcookie("id",$id);
}
if(!preg_match("/^[\w]+$/", $newlang)) 
{
   $newlang = $default_lang;
}
$lang=$newlang;
SetCookie("lang",$lang,time()+(3600*24)*365,$gamepath,$gamedomain);
include("languages/$lang" . ".inc");

include("header.php");
bigtitle();

if($newpass1 == "" && $newpass2 == "")
{
  echo $l_opt2_passunchanged;
}
elseif($password != $oldpass)
{
  echo $l_opt2_srcpassfalse;
}
elseif($newpass1 != $newpass2)
{
  echo $l_opt2_newpassnomatch;
}
else
{
  $res = $db->Execute("SELECT ship_id,password FROM $dbtables[ships] WHERE email='$username'");
  $playerinfo = $res->fields;
  if($oldpass != $playerinfo[password])
  {
    echo $l_opt2_srcpassfalse;
  }
  else
  {
    $res = $db->Execute("UPDATE $dbtables[ships] SET password='$newpass1' WHERE ship_id=$playerinfo[ship_id]");
    if($res)
    {
    	if ($newpass1MD5 != "") echo "(Server Side MD5)<BR>";
    	if ($newpass1MD5 == "") echo "(Client Side MD5)<BR>";
      echo $l_opt2_passchanged;
    }
    else
    {
      echo $l_opt2_passchangeerr;
    }
  }
}

$res = $db->Execute("UPDATE $dbtables[ships] SET interface='$intrf' WHERE email='$username'");
if($res)
{
  echo $l_opt2_userintup;
}
else
{
  echo $l_opt2_userintfail;
}

$res = $db->Execute("UPDATE $dbtables[ships] SET lang='$lang' WHERE email='$username'");
foreach($avail_lang as $curlang)
{
  if($lang == $curlang[file])
  {
    $l_opt2_chlang = str_replace("[lang]", "$curlang[name]", $l_opt2_chlang);
    
    echo $l_opt2_chlang;
    break;
  }
}

if($dhtml != 'Y')
  $dhtml = 'N';

$res = $db->Execute("UPDATE $dbtables[ships] SET dhtml='$dhtml' WHERE email='$username'");
if($res)
{
  echo $l_opt2_dhtmlup;
}
else
{
  echo $l_opt2_dhtmlfail;
}

if($shoutbox != 'Y')
  $shoutbox = 'N';

$res = $db->Execute("UPDATE $dbtables[ships] SET shoutbox='$shoutbox' WHERE email='$username'");
if($res)
{
  echo "Shoutbox updated!<BR><BR>";
}
else
{
  echo "Shoutbox update failed!<BR><BR>";
}

//-------------------------------------------------------------------------------------------------

TEXT_GOTOMAIN();

include("footer.php");

?>
