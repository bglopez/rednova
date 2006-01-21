<?

  if (preg_match("/sched_turns.php/i", $PHP_SELF)) {
      echo "You can not access this file directly!";
      die();
  }

  echo "<B>PAY Users</B><BR><BR>";

	$sql = "SELECT ship_id, pay_id, UNIX_TIMESTAMP(pay_start) as pay_start, UNIX_TIMESTAMP(pay_end) as pay_end, pay FROM $dbtables[ships] as b LEFT JOIN $dbtables[rpp_pay] as a ON (a.pay_email=b.email) ";
	$res = $db->Execute($sql);
	if($res)
	while(!$res->EOF)
		{
		$row = $res->fields;
		if($row[ship_id]>0) {
			if($row[pay_id]>0) {
				if( ( $row[pay_start] <= time() ) && ( $row[pay_end] >= time() )) {
					$pay_change = "Y";
				} else {
					$pay_change = "N";
				}
			} else {
				$pay_change = "N";
			}
			if($pay_change != $row[pay]) {
				$db->Execute("UPDATE $dbtables[ships] SET pay='".$pay_change."' WHERE ship_id=$row[ship_id]");
				echo "Ship ID: " . $row[ship_id] . " ==> " . $pay_change . "<BR>\n";
			}
		}
		$res->MoveNext();
		}




  echo "<B>TURNS</B><BR><BR>";
  echo "Adding turns...";
  QUERYOK($db->Execute("UPDATE $dbtables[ships] SET turns=turns+(1*$multiplier) WHERE turns < ( $max_turns * 2 ) "));
  echo "Ensuring maximum turns are $max_turns for free accounts...";
  QUERYOK($db->Execute("UPDATE $dbtables[ships] SET turns=$max_turns WHERE pay = 'N' AND turns>$max_turns"));
  $max_turns*=2;
  echo "Ensuring maximum turns are $max_turns for payed accounts...";
  QUERYOK($db->Execute("UPDATE $dbtables[ships] SET turns=$max_turns WHERE pay = 'Y' AND turns>$max_turns"));
  $max_turns/=2;
  echo "<BR>";
  $multiplier = 0;

?>
