<?
	include("config.php");
  include("languages/$lang");

	$title=$l_mail_title;
	include("header.php");

  connectdb();

	bigtitle();

	$result = $db->Execute ("select email, password from $dbtables[ships] where email='$mail'");

	if(!$result->EOF) {
	$playerinfo=$result->fields;

	// Generate new Password. Don't send the MD5 password!
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

	// Set cleartext Password for mail
	$playerinfo[password] = $makepass;

	// Save MD5 Password in DB
	$result = $db->Execute ("UPDATE $dbtables[ships] set password = '" . substr(md5($makepass),0,$maxlen_password) . "' where email='$mail'");


	$l_mail_message=str_replace("[pass]",$playerinfo[password],$l_mail_message);
	mail("$mail", "$l_mail_topic", "$l_mail_message\r\n\r\nhttp://$SERVER_NAME","From: webmaster@$SERVER_NAME\r\nReply-To: webmaster@$SERVER_NAME\r\nX-Mailer: PHP/" . phpversion());
	echo "$l_mail_sent $mail.";
        } else {
                echo "<b>$l_mail_noplayer</b><br>";
        }

	include("footer.php");
?>

