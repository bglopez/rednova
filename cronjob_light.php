<?
include("config.php");
include("languages/$lang");
$title="RPP - JavaScript Cron Job :)";
// include("header.php");
connectdb();
?>
<HTML>
<HEAD><TITLE>RPP - JavaScript Cron Job :)</TITLE></HEAD>
<BODY>
<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=5 WIDTH=300 ALIGN=CENTER>
<TR>
<?
if(($sched_type==1)||($sched_type==2))
{ 
   $lastrun = time(); 
   $res = $db->Execute("SELECT last_run FROM $dbtables[scheduler] LIMIT 1"); 
   $result = $res->fields;
   $sched_counter = floor((TIME()-$result[last_run])/ ($sched_ticks*60)); 
   if ($sched_counter < 0) $sched_counter = 0; 
   if ($sched_counter > 15) $sched_counter = 15;
   $mySched = $sched_counter;
   if ($sched_counter > 0) 
   { 
      $secs = $sched_counter * $sched_ticks * 60; 
      $db->Execute("UPDATE $dbtables[scheduler] SET last_run=last_run+$secs"); 
      $sched_temp=$swordfish; 
      $swordfish=$adminpass; 
      for (; $sched_counter > 0; $sched_counter--) 
      { 
         echo "<TD align=center>" . $sched_counter . "</TD>";
         include("scheduler.php"); 
      } 
      $swordfish=$sched_temp; 
      unset($sched_temp); 
   } else {
      echo "<TD align=center>No Update</TD>";
   }

} 
$res = $db->Execute("SELECT last_run FROM $dbtables[scheduler] LIMIT 1"); 
$result = $res->fields;
$mySEC = ($sched_ticks * 60) - (TIME()-$result[last_run]);
while ($mySEC < 0) $mySEC = $mySEC + ($sched_ticks * 60);
?>
</TR>
<TR><TD align=center colspan=<?=$mySched?>>Seconds until update: <B ID=myx><?=$mySEC?></B></TD></TR>
</TABLE>
</BODY>
</HTML>