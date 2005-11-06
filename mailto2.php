<?
include("config.php");
updatecookie();

include("languages/$lang");
$title=$l_sendm_title;
include("header.php");

connectdb();

if(checklogin())
{
  die();
}

$res = $db->Execute("SELECT * FROM $dbtables[ships] WHERE email='$username'");
$playerinfo = $res->fields;

bigtitle();

$l_sendm_myallies = "Send Message to your Alliance"; 

if(empty($content))
{
  $res = $db->Execute("SELECT character_name FROM $dbtables[ships] ORDER BY character_name ASC");
  $res2 = $db->Execute("SELECT team_name FROM $dbtables[teams] ORDER BY team_name ASC");
  echo "<FORM ACTION=mailto2.php METHOD=POST>";
  echo "<TABLE>";
  echo "<TR><TD>$l_sendm_to:</TD><TD><SELECT NAME=to>";
  while(!$res->EOF)
  {
    $row = $res->fields;
  ?>
    <OPTION <? if ($row[character_name]==$name) echo "selected" ?>><? echo $row[character_name] ?></OPTION>
  <?
    $res->MoveNext();
  }
  while(!$res2->EOF)
  {
    $row2 = $res2->fields;
    echo "<OPTION>$l_sendm_ally $row2[team_name]</OPTION>\n";
    $res2->MoveNext();
  }

  echo "</SELECT>";
  if ($playerinfo[team]>0) echo "&nbsp;&nbsp;OR&nbsp;&nbsp;<input type=\"checkbox\" name=\"to_allies\"> $l_sendm_myallies</td></tr>\n";
  echo "<TR><TD>$l_sendm_from:</TD><TD><INPUT DISABLED TYPE=TEXT NAME=dummy SIZE=40 MAXLENGTH=40 VALUE=\"$playerinfo[character_name]\"></TD></TR>";
  if (isset($subject)) $subject = "RE: " . $subject;
  echo "<TR><TD>$l_sendm_subj:</TD><TD><INPUT TYPE=TEXT NAME=subject SIZE=40 MAXLENGTH=40 VALUE=\"$subject\"></TD></TR>";
  echo "<TR><TD>$l_sendm_mess:</TD><TD><TEXTAREA NAME=content ROWS=5 COLS=40></TEXTAREA></TD></TR>";
  echo "<TR><TD></TD><TD><INPUT TYPE=SUBMIT VALUE=$l_sendm_send><INPUT TYPE=RESET VALUE=$l_reset></TD>";
  echo "</TABLE>";
  echo "</FORM>";
}
else
{
  echo "$l_sendm_sent<BR><BR>";
  $MailCount = 0;

if (strpos($to, $l_sendm_ally)===false && !isset($to_allies))
{
  $timestamp = date("Y\-m\-d H\:i\:s");
  $res = $db->Execute("SELECT * FROM $dbtables[ships] WHERE character_name='$to'");
  $target_info = $res->fields;
  $content = htmlspecialchars($content);
  $subject = htmlspecialchars($subject);
  $db->Execute("INSERT INTO $dbtables[messages] (sender_id, recp_id, sent, subject, message) VALUES ('".$playerinfo[ship_id]."', '".$target_info[ship_id]."', '".$timestamp."', '".$subject."', '".$content."')");
  $MailCount = $db->Affected_Rows();
}
else
{
  $timestamp = date("Y\-m\-d H\:i\:s");

     if(!isset($to_allies)) {
          $to = str_replace ($l_sendm_ally, "", $to);
          $to = trim($to);
          $subject = "$to: $subject";
          $to = addslashes($to);
          $res = $db->Execute("SELECT id FROM $dbtables[teams] WHERE team_name='$to'");
          $row = $res->fields;
     } else {
          $res = $db->Execute("SELECT team AS id FROM $dbtables[ships] WHERE ship_id='$playerinfo[ship_id]'");
          $row = $res->fields;
          $subject = "$l_sendm_ally $subject";
     }
     $res2 = $db->Execute("SELECT * FROM $dbtables[ships] where team='$row[id]'");

     while (!$res2->EOF)
     {
        $row2 = $res2->fields;
        $db->Execute("INSERT INTO $dbtables[messages] (sender_id, recp_id, sent, subject, message) VALUES ('".$playerinfo[ship_id]."', '".$row2[ship_id]."', '".$timestamp."', '".$subject."', '".$content."')");
        $MailCount += $db->Affected_Rows();
        $res2->MoveNext();
     }

   }

  echo "Debug: <B><FONT COLOR=" . ($MailCount==0?"RED":"GREEN") . ">$MailCount</FONT></B> mail(s) send.<BR><BR>";

}

TEXT_GOTOMAIN();

include("footer.php");

?>
