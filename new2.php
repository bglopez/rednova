<?
include("config.php");
include("languages/$lang");

$title=$l_new_title2;

include("header.php");


bigtitle();

connectdb();

if($account_creation_closed)
{
  die($l_new_closed_message);
}
$character=htmlspecialchars($character);
$shipname=htmlspecialchars($shipname);
$character=ereg_replace("[^[:digit:][:space:][:alpha:][\']]"," ",$character);
$shipname=ereg_replace("[^[:digit:][:space:][:alpha:][\']]"," ",$shipname);

$username = $HTTP_POST_VARS['username']; //This needs to STAY before the db query

if(!get_magic_quotes_gpc())
{
  $username = addslashes($username);
  $character = addslashes($character);
  $shipname = addslashes($shipname);
}

$result = $db->Execute ("select email, character_name, ship_name from $dbtables[ships] where email='$username' OR character_name='$character' OR ship_name='$shipname'");
$flag=0;
if ($username=='' || $character=='' || $shipname=='' ) { echo "$l_new_blank<BR>"; $flag=1;}

if ($result>0)
{
  while (!$result->EOF)
  {
    $row = $result->fields;
    if (strtolower($row[email])==strtolower($username)) { echo "$l_new_inuse  $l_new_4gotpw1 <a href=mail.php?mail=$username>$l_clickme</a> $l_new_4gotpw2<BR>"; $flag=1;}
    if (strtolower($row[character_name])==strtolower($character)) { echo "$l_new_inusechar<BR>"; $flag=1;}
    if (strtolower($row[ship_name])==strtolower($shipname)) { echo "$l_new_inuseship<BR>"; $flag=1;}
    $result->MoveNext();
  }
}

if($agb!="yes") { echo "You must confirm the multi-account rule!<BR>"; $flag=1; }

if ($flag==0)
{
  SetCookie("rednova","",time()-3600,$gamepath,$gamedomain);
  /* insert code to add player to database */
  $makepass="";
  $syllables="er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
  $syllable_array=explode(",", $syllables);
  srand((double)microtime()*1000000);
  for ($count=1;$count<=4;$count++) {
    if (rand()%10 == 1) {
      $makepass .= sprintf("%0.0f",(rand()%50)+1);
    } else {
      $makepass .= sprintf("%s",$syllable_array[rand()%62]);
    }
  }
  $stamp=date("Y-m-d H:i:s");
  $query = $db->Execute("SELECT MIN(turns_used + turns) AS mturns FROM $dbtables[ships]");
  $res = $query->fields;

  $mturns = $res[mturns];

  if($mturns > $max_turns) $mturns = $max_turns;


  $query = $db->Execute("SELECT count(*) AS vv FROM $dbtables[ships] WHERE vote >= 0");
  $res = $query->fields;
  if ($res[vv] > 0) $vote_stat = -2;
  else $vote_stat = -1;

  $query = $db->Execute("SELECT count(*) AS vv FROM $dbtables[ships] WHERE vote < -2");
  $res = $query->fields;
  if ($res[vv] > 0) $vote_stat = -3;

	$sql = "SELECT * FROM bnt_payuser WHERE pay_email = '$username'";
	$res = $db->Execute($sql);
	if($res)
		{
		$row = $res->fields;
		if($row[pay_until]>Time())
			{
				$result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$shipname','N','$character','" . substr(md5($makepass),0,$maxlen_password) . "','$username',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$mturns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','$ip',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',$vote_stat,'Y','N',-1,'Y')");
			} else {
				$result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$shipname','N','$character','" . substr(md5($makepass),0,$maxlen_password) . "','$username',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$mturns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','$ip',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',$vote_stat,'Y','N',-1,'N')");
			}
		} else {
			$result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$shipname','N','$character','" . substr(md5($makepass),0,$maxlen_password) . "','$username',0,0,0,0,0,0,0,0,0,0,$start_armour,0,$start_credits,0,0,0,0,$start_energy,0,$start_fighters,$mturns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','$ip',0,0,0,0,'Y','N','N','Y',' ','$default_lang', 'Y','N',$vote_stat,'Y','N',-1,'N')");
		}

  if(!$result2) {
    echo $db->ErrorMsg() . "<br>";
  } else {
    $result2 = $db->Execute("SELECT ship_id FROM $dbtables[ships] WHERE email='$username'");
    $shipid = $result2->fields;

 $l_new_message = str_replace("[pass]", $makepass, $l_new_message);
    mail("$username", "$l_new_topic", "$l_new_message\r\n\r\nhttp://$gamedomain","From: $admin_mail\r\nReply-To: $admin_mail\r\nX-Mailer: PHP/" . phpversion());

    $db->Execute("INSERT INTO $dbtables[zones] VALUES('','$character\'s Territory', $shipid[ship_id], 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 0)");
    $db->Execute("INSERT INTO $dbtables[ibank_accounts] (ship_id,balance,loan) VALUES($shipid[ship_id],0,0)");
    if($display_password)
    {
       echo $l_new_pwis . " " . $makepass . "<BR><BR>";
    }
    echo "$l_new_pwsent<BR><BR>";
    echo "<A HREF=login.php>$l_clickme</A> $l_new_login";

  }
} else {

  echo $l_new_err;
}

include("footer.php");
?>
