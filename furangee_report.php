<?php
include("config.php");
include("languages/$lang");
$title="Furangee Report";
include("header.php");

connectdb();

?>
<p align="center"><font color="#CCCCCC" size="4"><strong>Furangee Report</strong></font></p>
<table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr height="20" align="center" valign="middle"> 
    <td width="">&nbsp;</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Name</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Status</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Orders</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Experience</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Behavior</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Avg. Level</td>
  </tr>
  <?php
	 $rownumber = 0;
     $res = $db->Execute("SELECT * FROM $dbtables[ships] JOIN $dbtables[furangee] WHERE email=furangee_id AND active=1");
	 if($res->_numOfRows==0){
	 ?>
  <tr height="20" align="center" valign="middle"> 
    <td height="30" colspan="7">There are no furangees right now</td>
  </tr>	 
<?php

	 } else {
		 while(!$res->EOF){
			  $row = $res->fields;
			  $rownumber++;

?>
  <tr height="20" align="center" valign="middle"> 
    <td> 
      <?=$rownumber?>
    </td>
    <td> 
      <?=$row[character_name]?>
    </td>
    <td> 
      <?php if($row[ship_destroyed]=="N") echo "Active"; else echo "Dead"; ?>
    </td>
    <td> 
      <?php
	  	switch($row[orders]){
			case"0":
				echo "Sentinel";
			break;
			case"1":
				echo "Traveller";
			break;
			case"2":
				echo "Trader";
			break;
			case"3":
				echo "Hunter";
			break;			
		}
		?>
    </td>
    <td>
      <?php
	  	switch($row[experience]){
			case"0":
				echo "Beginner";
			break;
			case"1":
				echo "Average";
			break;
			case"2":
				echo "Advanced";
			break;
			case"3":
				echo "Expert";
			break;			
		}
		?>
    </td>
    <td> 
      <?php
	  	switch($row[behavior]){
			case"0":
				echo "Peaceful";
			break;
			case"1":
				echo "Aggressive";
			break;
		}
		?>
    </td>
    <td><?php echo round(($row[hull]+$row[engines]+$row[computer]+$row[armour]+$row[shields]+$row[beams]+$row[torp_launchers])/7,2); ?></td>
  </tr>
  <p> 
<?php 
			  $res->MoveNext();
		 }
	}
?>
</table>
<br>
<br>
<table width="55%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div align="justify"> 
        <p align="justify">Furangee are artificial intelligence based computer 
          players. Their ultimate goal is to create some interactivity within 
          the game. Each furangee play once every turn and can attack other players 
          ships, planets, sector defences, trade, travel between sectors and upgrade 
          their spaceships (depending on their orders/experience/behavour).</p>
        <p align="center"> <strong>This server is running Furangee&sup2; Patch 
          v0.2</strong></p>
        </div></td>
  </tr>
</table>
<p align="center"><a href="main.php">Click here</a> to return to the main menu</p>
<p> 
  <?php		  

	include("footer.php");

		  ?>
</p>
  </p>
