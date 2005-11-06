<?

  if (preg_match("/sched_turns.php/i", $PHP_SELF)) {
      echo "You can not access this file directly!";
      die();
  }

  echo "<B>EXTRA TURNS</B><BR><BR>";
  echo "Adding turns...";
  QUERYOK($db->Execute("UPDATE $dbtables[ships] SET turns=turns+50 WHERE pay = 'Y'"));
  echo "<BR>";

?>
