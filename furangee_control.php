<?
include("config.php");
updatecookie();

include("languages/$lang");

$title="Furangee Control";
include("header.php");

connectdb();

function CHECKED($yesno)
{
  return(($yesno == "Y") ? "CHECKED" : "");
}

function YESNO($onoff)
{
  return(($onoff == "ON") ? "Y" : "N");
}

$module = $menu;

if($swordfish != $adminpass)
{
  echo "<FORM ACTION=furangee_control.php METHOD=POST>";
  echo "Password: <INPUT TYPE=PASSWORD NAME=swordfish SIZE=20 MAXLENGTH=20><BR><BR>";
  echo "<INPUT TYPE=SUBMIT VALUE=Submit><INPUT TYPE=RESET VALUE=Reset>";
  echo "</FORM>";
}
else
{
  // ******************************
  // ******** MAIN MENU ***********
  // ******************************
  if(empty($module))
  {

?> 
<p><font size="3">Welcome to the BlackNova Traders Furangee&sup2; Control module</font><br>
</p>
<strong><font size="3">Edit Furangees</font></strong> 
<hr align="left" width="600" size="1">
<table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr height="20" align="center" valign="middle"> 
    <td width="2%">&nbsp;</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Name</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Status</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Orders</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Experience</td>
    <td width="16%" bgcolor="<?=$color_line2?>">Behavior</td>
    <td width="16%" bgcolor="<?=$color_line1?>">Avg. Level</td>
    <td width="2%">&nbsp;</td>
  </tr>
  <?php
	 $rownumber = 0;
     $res = $db->Execute("SELECT * FROM $dbtables[ships] JOIN $dbtables[furangee] WHERE email=furangee_id AND active=1");
	 if($res->_numOfRows==0){
	 ?>
  <tr height="20" align="center" valign="middle"> 
    <td height="30" colspan="8">There are no furangees in the database</td>
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
      <?php if($row[ship_destroyed]=="N") echo "Active"; else echo "<font color='FF0000'>Dead</font>"; ?>
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
    <FORM ACTION=furangee_control.php METHOD=POST>
        <input type=hidden name=menu value=instruct>
        <INPUT TYPE=HIDDEN NAME=swordfish VALUE="<?=$swordfish?>">
		<input type=hidden name=menu value=furangeeedit>
		<input type=hidden name=user value=<?=$row[email]?>>
	<td>		
        <INPUT name="SUBMIT" TYPE=SUBMIT VALUE="Edit">
      
	</td>
	</FORM>
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
<br>
<strong><font size="3">Create Furangee</font></strong> 
<?php

        // Create Furangee Name
        $Sylable1 = array("Ak","Al","Ar","B","Br","D","F","Fr","G","Gr","K","Kr","N","Ol","Om","P","Qu","R","S","Z");
        $Sylable2 = array("a","ar","aka","aza","e","el","i","in","int","ili","ish","ido","ir","o","oi","or","os","ov","u","un");
        $Sylable3 = array("ag","al","ak","ba","dar","g","ga","k","ka","kar","kil","l","n","nt","ol","r","s","ta","til","x");
        $sy1roll = rand(0,19);
        $sy2roll = rand(0,19);
        $sy3roll = rand(0,19);
        $character = $Sylable1[$sy1roll] . $Sylable2[$sy2roll] . $Sylable3[$sy3roll];
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        $resultnm = $db->Execute ("select character_name from $dbtables[ships] where character_name='$character'");
        $namecheck = $resultnm->fields;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $nametry = 1;
        // If Name Exists Try Again - Up To Nine Times
        while (($namecheck[0]) and ($nametry <= 9)) {
          $sy1roll = rand(0,19);
          $sy2roll = rand(0,19);
          $sy3roll = rand(0,19);
          $character = $Sylable1[$sy1roll] . $Sylable2[$sy2roll] . $Sylable3[$sy3roll];
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          $resultnm = $db->Execute ("select character_name from $dbtables[ships] where character_name='$character'");
          $namecheck = $resultnm->fields;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $nametry++;
        }
        // Create Ship Name
        $shipname = "Furangee-" . $character; 
        // Select Random Sector
        $sector = rand(1,$sector_max); 
        // Display Confirmation Form
?>
<hr align="left" width="600" size="1">
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
  <FORM ACTION=furangee_control.php METHOD=POST>
    <input type=hidden name=menu value=createnew>
    <INPUT TYPE=HIDDEN NAME=swordfish VALUE="<?=$swordfish?>">
    <TR> 
      <TD width="125">Furangee Name:</TD>
      <TD width="134"><INPUT TYPE=TEXT SIZE=20 NAME=character VALUE=<?=$character?>></TD>
    <TR> 
      <TD>Ship Name </TD>
      <TD><input type=TEXT size=20 name=shipname value=<?=$shipname?>></TD>
    </TR>
    <TR> 
      <TD>Orders</TD>
      <TD> <SELECT NAME=orders SIZE=1 id="orders">
          <OPTION VALUE=0>Sentinel</OPTION>
          <OPTION VALUE=1>Roam</OPTION>
          <OPTION VALUE=2 selected>Roam and Trade</OPTION>
          <OPTION VALUE=3>Roam and Hunt</OPTION>
        </SELECT></TD>
    </TR>
    <TR> 
      <TD>Behavour</TD>
      <TD><select name=behavior size=1 id="behavior">
          <option value="0">Peaceful</option>
          <option value="1" selected>Aggressive</option>
        </select></TD>
    </TR>
    <TR> 
      <TD>Experience</TD>
      <TD><select name=experience size=1 id="experience">
          <option value="0">Beginner</option>
          <option value="1" selected>Average</option>
          <option value="2">Advanced</option>
          <option value="3">Expert</option>
        </select></TD>
    </TR>
    <TR> 
      <TD>Average Level:</TD>
      <TD><input type=TEXT size=5 name=furlevel value=3> </TD>
    </TR>
    <TR> 
      <TD>Sector </TD>
      <TD><input type=TEXT size=5 name=sector value=<?=$sector?>></TD>
    </TR>
    <TR> 
      <TD colspan="2"><input name="SUBMIT" type=SUBMIT id="SUBMIT" value="Create Furangee"> 
        <input type=CHECKBOX name=active value=ON checked ><input type=HIDDEN name=operation value=createfurangee>
        Active? </TD>
    </TR>
  </FORM>
</TABLE>
<br>
<br>
<br>
<strong><font size="3">Furangee Tools</font></strong><br>
<hr align="left" width="600" size="1">
<table border="0" cellspacing="0" cellpadding="5">
  <tr> 
			<FORM ACTION=furangee_control.php METHOD=POST>
			<input type=hidden name=menu value="clearlog">
			<INPUT TYPE=HIDDEN NAME=swordfish VALUE="<?=$swordfish?>">  
      <td> 
        <INPUT name="SUBMIT" TYPE=SUBMIT VALUE="Clear Logs">
      </td>
			</FORM>
			<FORM ACTION=furangee_control.php METHOD=POST>
			<input type=hidden name=menu value=instruct>
			<INPUT TYPE=HIDDEN NAME=swordfish VALUE="<?=$swordfish?>">	  
      <td> 
        <INPUT name="SUBMIT" TYPE=SUBMIT VALUE="Read Instructions">
      </td>
			  </FORM>
			  <FORM ACTION=furangee_control.php METHOD=POST>
			  <input type=hidden name=menu value=dropfurangee>
			  <INPUT TYPE=HIDDEN NAME=swordfish VALUE="<?=$swordfish?>">
      <td> 
        <INPUT name="SUBMIT" TYPE=SUBMIT VALUE="Reinstall Table">
      </td>
	  </FORM>
  </tr>
</table>
<br>
<?php	
  }else{
    $button_main = true;
    // ***********************************************
    // ********* START OF INSTRUCTIONS SUB ***********
    // ***********************************************
    if($module == "instruct"){
	
	?>
	
		<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><strong><font size="3">Furangee Creation</font></strong></td>
  </tr>
  <tr> 
    <td><p><br>
        Furangees are created from the main menu, There are only a few fields 
        for you to edit. However, with these fields you will determine not only 
        how your Furangee will be created, but how he will act in the game. We 
        will now go over these fields and what they will do. The starting <B>Sector</B> 
        number will also be randomly generated. You can change this to any sector. 
        However, you should take care to use a valid sector number. Otherwise 
        the creation will fail. <BR>
        <br>
        <B>Level</B><br>
        This field refers to the starting tech level of all ship stats. So a default 
        Furangee will have it's Hull, Beams, Power, Engine, etc... all set to 
        3 unless this value is changed. All appropriate ship stores will be set 
        to the maximum allowed by the given tech level. So, starting levels of 
        energy, fighters, armour, torps, etc... are all affected by this setting. 
        <BR>
        <br>
        <B>Active<br>
        </B>This box refers to if the Furangee AI system will see this Furangee 
        and execute it's orders. If this box is not checked then the Furangee 
        AI system will ignore this furangee. No turns will be played but the furangee 
        will still be inside the game and may be attacked by other players. <BR>
        <br>
        <B>Orders<br>
        </B>Your orders selection will determine what tasks the furangee will 
        carryout. Each of these orders and what they mean will be detailed below. 
        <BR>
        <br>
        <B>Behavior<br>
        </B> A furangee's behavour setting determines how they will react when 
        they come into contact with other players and planets. Each of these orders 
        and what they mean will be detailed below. <BR>
        <br>
        Pressing the <B>Create</B> button will create the furangee and take you 
        to a summary page.</p>
      </td>
  </tr>
</table>
<br>
<hr align="left" width="600" size="1">
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><strong><font size="3">Furangee Orders</font></strong></td>
  </tr>
  <tr> 
    <td><br>
      Here are the Furangee Order options and what the Furangee AI system will 
      do for each: 
      <UL>
        SENTINEL<BR>
        This Furangee will stay in place. His only interactions will be with those 
        who are in his sector at the time he takes his turn. The behavior level 
        will determine what those player interactions are.
      </UL>
      <UL>
        ROAM<BR>
        This Furangee will warp from sector to sector looking for players and 
        planets to interact with. The behavior level will determine what those 
        player interactions are. 
      </UL>
      <UL>
        ROAM AND TRADE<BR>
        This Furangee will warp from sector to sector looking for players to interact 
        with and ports to trade with. The Furangee will trade at a port if possible 
        before looking for player interactions. The behavior level will determine 
        what those player interactions are.
      </UL>
      <UL>
        ROAM AND HUNT<BR>
        This Furangee has a taste for blood and likes the sport of a good hunt. 
        Ocassionally (around 1/4th the time) this Furangee has the urge to go 
        hunting. He will randomly choose one of the top ten players to hunt. If 
        that player is in a sector that allows attack, then the Furangee warps 
        there and attacks. When he is not out hunting this Furangee acts just 
        like one with ROAM orders.</UL>
      </td>
  </tr>
</table>
<br>
<hr align="left" width="600" size="1">
<br>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td><strong><font size="3">Furangee Behavior</font></strong></td>
  </tr>
  <tr> 
    <td><P> <br>
        Here are the Furangee behavior levels and what the Furangee AI system 
        will do for each: 
      <UL>
        PEACEFUL<BR>
        This Furangee will not attack players. He will continue to roam or trade 
        as ordered but will not launch any attacks. If this Furangee is a hunter 
        then he will still attack players on the hunt but never otherwise.
      </UL>
      <UL>
        AGGRESSIVE<BR>
        This Furangee enjoys attacking other players and should be concidered 
        armed and dangerous. He is intelligent and will evaluate the likelyhood 
        of success before launching an attack. 
      </UL>
      </td>
  </tr>
</table>

<br>
<?php

    }
    // ***********************************************
    // ********* START OF FURANGEE EDIT SUB ***********
    // ***********************************************
    elseif($module == "furangeeedit")
    {
      if(empty($user)) 	echo "ERROR: no user data was sent to the frangeeedit function";
      else {
        if(empty($operation)){
		
          $res = $db->Execute("SELECT * FROM $dbtables[ships] JOIN $dbtables[furangee] WHERE email=furangee_id AND email='$user'");
          $row = $res->fields;
		  ?>
		<font size="3"><strong><span style="">Furangee Editor</span></strong></font><BR>
		  <FORM ACTION=furangee_control.php METHOD=POST>		  
          
  <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
    <TR> 
      <TD>Furangee name</TD>
      <TD><INPUT TYPE=TEXT NAME=character_name VALUE="<?=$row[character_name]?>"></TD>
    </TR>
    <TR> 
      <TD>Active?</TD>
      <TD><INPUT TYPE=CHECKBOX NAME=active VALUE=ON <?=CHECKED($row[active])?>></TD>
    </TR>
    <TR> 
      <TD>E-mail</TD>
      <TD> 
        <?=$row[email]?>
      </TD>
    </TR>
    <TR> 
      <TD>ID</TD>
      <TD> 
        <?=$row[ship_id]?>
      </TD>
    </TR>
    <TR> 
      <TD>Ship</TD>
      <TD><INPUT TYPE=TEXT NAME=ship_name VALUE="<?=$row[ship_name]?>"></TD>
    </TR>
    <TR> 
      <TD>Destroyed?</TD>
      <TD><INPUT TYPE=CHECKBOX NAME=ship_destroyed VALUE=ON <?CHECKED($row[ship_destroyed])?>></TD>
    </TR>
    <TR> 
      <TD>Orders</TD>
      <TD> <SELECT SIZE=1 NAME=orders>
          <?php
            $oorder0 = $oorder1 = $oorder2 = $oorder3 = "VALUE";
            if ($row[orders] == 0) $oorder0 = "SELECTED=0 VALUE";
            if ($row[orders] == 1) $oorder1 = "SELECTED=1 VALUE";
            if ($row[orders] == 2) $oorder2 = "SELECTED=2 VALUE";
            if ($row[orders] == 3) $oorder3 = "SELECTED=3 VALUE";
			?>
          <OPTION <?=$oorder0?>=0>Sentinel</OPTION>
          <OPTION <?=$oorder1?>=1>Roam</OPTION>
          <OPTION <?=$oorder2?>=2>Roam and Trade</OPTION>
          <OPTION <?=$oorder3?>=3>Roam and Hunt</OPTION>
        </SELECT></TD>
    </TR>
    <TR> 
      <TD>Behavior</TD>
      <TD> 
        <?php
            $oaggr0 = $oaggr1 = $oaggr2 = "VALUE";
            if ($row[behavior] == 0) $oaggr0 = "SELECTED=0 VALUE";
            if ($row[behavior] == 1) $oaggr1 = "SELECTED=1 VALUE";
			?>
        <SELECT SIZE=1 NAME=behavior>
          <OPTION <?=$oaggr0?>=0>Peaceful</OPTION>
          <OPTION <?=$oaggr1?>=1>Aggresssive</OPTION>
        </SELECT></TD>
    </TR>
    <TR> 
      <TD>Experience</TD>
      <TD>
        <?php
            $oexp0 = $oexp1 = $oexp2 = $oexp3 = "VALUE";
            if ($row[experience] == 0) $oexp0 = "SELECTED=0 VALUE";
            if ($row[experience] == 1) $oexp1 = "SELECTED=1 VALUE";
			if ($row[experience] == 2) $oexp2 = "SELECTED=2 VALUE";
			if ($row[experience] == 3) $oexp3 = "SELECTED=3 VALUE";			
		?>
        <select name=experience size=1 id="experience">
          <option <?=$oexp0?>="0">Beginner</option>
          <option <?=$oexp1?>="1">Average</option>
          <option <?=$oexp2?>="2">Advanced</option>
          <option <?=$oexp3?>="3">Expert</option>
        </select></TD>
    </TR>
    <TR> 
      <TD>Levels</TD>
      <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
          <TR> 
            <TD>Hull</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=hull VALUE="<?=$row[hull]?>"></TD>
            <TD>Engines</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=engines VALUE="<?=$row[engines]?>"></TD>
            <TD>Power</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=power VALUE="<?=$row[power]?>"></TD>
            <TD>Computer</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=computer VALUE="<?=$row[computer]?>"></TD>
          </TR>
          <TR> 
            <TD>Sensors</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=sensors VALUE="<?=$row[sensors]?>"></TD>
            <TD>Armour</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=armour VALUE="<?=$row[armour]?>"></TD>
            <TD>Shields</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=shields VALUE="<?=$row[shields]?>"></TD>
            <TD>Beams</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=beams VALUE="<?=$row[beams]?>"></TD>
          </TR>
          <TR> 
            <TD>Torpedoes</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=torp_launchers VALUE="<?=$row[torp_launchers]?>"></TD>
            <TD>Cloak</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=cloak VALUE="<?=$row[cloak]?>"></TD>
          </TR>
        </TABLE></TD>
    </TR>
    <TR> 
      <TD>Holds</TD>
      <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
          <TR> 
            <TD>Ore</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_ore VALUE="<?=$row[ship_ore]?>"></TD>
            <TD>Organics</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_organics VALUE="<?=$row[ship_organics]?>"></TD>
            <TD>Goods</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_goods VALUE="<?=$row[ship_goods]?>"></TD>
          </TR>
          <TR> 
            <TD>Energy</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_energy VALUE="<?=$row[ship_energy]?>"></TD>
            <TD>Colonists</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_colonists VALUE="<?=$row[ship_colonists]?>"></TD>
          </TR>
        </TABLE></TD>
    </TR>
    <TR> 
      <TD>Combat</TD>
      <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
          <TR> 
            <TD>Fighters</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=ship_fighters VALUE="<?=$row[ship_fighters]?>"></TD>
            <TD>Torpedoes</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=torps VALUE="<?=$row[torps]?>"></TD>
          </TR>
          <TR> 
            <TD>Armour Pts</TD>
            <TD><INPUT TYPE=TEXT SIZE=8 NAME=armour_pts VALUE="<?=$row[armour_pts]?>"></TD>
          </TR>
        </TABLE></TD>
    </TR>
    <TR> 
      <TD>Devices</TD>
      <TD><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
          <TR> 
            <TD>Beacons</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=dev_beacon VALUE="<?=$row[dev_beacon]?>"></TD>
            <TD>Warp Editors</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=dev_warpedit VALUE="<?=$row[dev_warpedit]?>"></TD>
            <TD>Genesis Torpedoes</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=dev_genesis VALUE="<?=$row[dev_genesis]?>"></TD>
          </TR>
          <TR> 
            <TD>Mine Deflectors</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=dev_minedeflector VALUE="<?=$row[dev_minedeflector]?>"></TD>
            <TD>Emergency Warp</TD>
            <TD><INPUT TYPE=TEXT SIZE=5 NAME=dev_emerwarp VALUE="<?=$row[dev_emerwarp]?>"></TD>
          </TR>
          <TR> 
            <TD>Escape Pod</TD>
            <TD><INPUT TYPE=CHECKBOX NAME=dev_escapepod VALUE=ON <?=CHECKED($row[dev_escapepod])?>></TD>
            <TD>FuelScoop</TD>
            <TD><INPUT TYPE=CHECKBOX NAME=dev_fuelscoop VALUE=ON <?=CHECKED($row[dev_fuelscoop])?>></TD>
          </TR>
        </TABLE></TD>
    </TR>
    <TR> 
      <TD>Credits</TD>
      <TD><INPUT TYPE=TEXT NAME=credits VALUE="<?=$row[credits]?>"></TD>
    </TR>
    <TR> 
      <TD>Turns</TD>
      <TD><INPUT TYPE=TEXT NAME=turns VALUE="<?=$row[turns]?>"></TD>
    </TR>
    <TR> 
      <TD>Current sector</TD>
      <TD><INPUT TYPE=TEXT NAME=sector VALUE="<?=$row[sector]?>"></TD>
    </TR>
  </TABLE>
          <BR>
          <INPUT TYPE=HIDDEN NAME=user VALUE=<?=$user?>>
          <INPUT TYPE=HIDDEN NAME=operation VALUE=save>
          <INPUT TYPE=SUBMIT VALUE=Save>
          <INPUT TYPE=HIDDEN NAME=menu VALUE=furangeeedit>
          <INPUT TYPE=HIDDEN NAME=swordfish VALUE=<?=$swordfish?>>
          </FORM>";		  
          <?php
		  //******************************
          //*** SHOW FURANGEE LOG DATA ***
          //******************************
		  ?>
          <HR>
          <span style=\"font-family : courier, monospace; font-size: 12pt; color: #00FF00;\">Log Data For This Furangee</span><BR>
		  <?php
          $logres = $db->Execute("SELECT * FROM $dbtables[logs] WHERE ship_id=$row[ship_id] ORDER BY time DESC, type DESC");   
          while(!$logres->EOF)
          {
            $logrow = $logres->fields;
            $logtype = "";
            switch($logrow[type])
            {
              case LOG_FURANGEE_ATTACK:
                $logtype = "Launching an attack on ";
                break;
              case LOG_ATTACK_LOSE:
                $logtype = "We were attacked and lost against ";
                break;
              case LOG_ATTACK_WIN:
                $logtype = "We were attacked and won against ";
                break;
            }
            $logdatetime = substr($logrow[time], 4, 2) . "/" . substr($logrow[time], 6, 2) . "/" . substr($logrow[time], 0, 4) . " " . substr($logrow[time], 8, 2) . ":" . substr($logrow[time], 10, 2) . ":" . substr($logrow[time], 12, 2);
            echo "$logdatetime $logtype$logrow[data] <BR>";
            $logres->MoveNext();
          }
        }
        elseif($operation == "save")
        {
		  echo "<FORM ACTION=furangee_control.php METHOD=POST>";
          // update database
          $_ship_destroyed = empty($ship_destroyed) ? "N" : "Y";
          $_dev_escapepod = empty($dev_escapepod) ? "N" : "Y";
          $_dev_fuelscoop = empty($dev_fuelscoop) ? "N" : "Y";
          $_active = empty($active) ? "N" : "Y";
          $result = $db->Execute("UPDATE $dbtables[ships] SET character_name='$character_name',ship_name='$ship_name',ship_destroyed='$_ship_destroyed',hull='$hull',engines='$engines',power='$power',computer='$computer',sensors='$sensors',armour='$armour',shields='$shields',beams='$beams',torp_launchers='$torp_launchers',cloak='$cloak',credits='$credits',turns='$turns',dev_warpedit='$dev_warpedit',dev_genesis='$dev_genesis',dev_beacon='$dev_beacon',dev_emerwarp='$dev_emerwarp',dev_escapepod='$_dev_escapepod',dev_fuelscoop='$_dev_fuelscoop',dev_minedeflector='$dev_minedeflector',sector='$sector',ship_ore='$ship_ore',ship_organics='$ship_organics',ship_goods='$ship_goods',ship_energy='$ship_energy',ship_colonists='$ship_colonists',ship_fighters='$ship_fighters',torps='$torps',armour_pts='$armour_pts' WHERE email='$user'");
          if(!$result) {
            echo "Changes to Furangee ship record have FAILED Due to the following Error:<BR><BR>";
            echo $db->ErrorMsg() . "<br>";
          } else {
            echo "Changes to Furangee ship record have been saved.<BR><BR>";
            $result2 = $db->Execute("UPDATE $dbtables[furangee] SET active='$_active',orders='$orders',behavior='$behavior',experience='$experience' WHERE furangee_id='$user'");
            if(!$result2) {
              echo "Changes to Furangee activity record have FAILED Due to the following Error:<BR><BR>";
              echo $db->ErrorMsg() . "<br>";
            } else {
              echo "Changes to Furangee activity record have been saved.<BR><BR>";
            }
          }
          echo "<INPUT TYPE=SUBMIT VALUE=\"Return to Furangee editor\">";
          $button_main = false;
		  echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE=$swordfish>";
          echo "</FORM>";
        }
        else
        {
          echo "Invalid operation";
        }
      }
    }
    // ***********************************************
    // ******** START OF DROP FURANGEE SUB ***********
    // ***********************************************
    elseif($module == "dropfurangee")
    {
      echo "<H1>Drop and Re-Install Furangee Database</H1>";
      echo "<H3>This will DELETE All Furangee records from the <i>ships</i> TABLE then DROP and reset the <i>furangee</i> TABLE</H3>";
      echo "<FORM ACTION=furangee_control.php METHOD=POST>";
      if(empty($operation))
      {
        echo "<BR>";
        echo "<H2><FONT COLOR=Red>Are You Sure?</FONT></H2><BR>";
        echo "<INPUT TYPE=HIDDEN NAME=operation VALUE=dropfur>";
        echo "<INPUT TYPE=SUBMIT VALUE=Drop>";
      }
      elseif($operation == "dropfur")
      {
        // Delete all furangee in the ships table
        echo "Deleting furangee records in the ships table...<BR>";
        $db->Execute("DELETE FROM $dbtables[ships] WHERE email LIKE '%@furangee'");
        echo "deleted.<BR>";
        // Drop furangee table
        echo "Dropping furangee table...<BR>";
        $db->Execute("DROP TABLE IF EXISTS $dbtables[furangee]");
        echo "dropped.<BR>";
        // Create furangee table
        echo "Re-Creating table: furangee...<BR>";
        $db->Execute("CREATE TABLE $dbtables[furangee](" .
            "furangee_id char(40) NOT NULL," .
            "active enum('Y','N') DEFAULT 'Y' NOT NULL," .
            "behavior smallint(5) DEFAULT '0' NOT NULL," .
            "orders smallint(5) DEFAULT '0' NOT NULL," .
            "experience smallint(5) DEFAULT '0' NOT NULL," .
            "PRIMARY KEY (furangee_id)," .
            "KEY furangee_id (furangee_id)" .
            ")");
					
        echo "created.<BR>";
      }
      else
      {
        echo "Invalid operation";
      }
      echo "<INPUT TYPE=HIDDEN NAME=menu VALUE=dropfurangee>";
      echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE=$swordfish>";
      echo "</FORM>";
    }
    // ***********************************************
    // ***** START OF CLEAR FURANGEE LOG SUB *********
    // ***********************************************
    elseif($module == "clearlog")
    {
      echo "<H1>Clear All Furangee Logs</H1>";
      echo "<H3>This will DELETE All Furangee log files</H3>";
      echo "<FORM ACTION=furangee_control.php METHOD=POST>";
      if(empty($operation))
      {
        echo "<BR>";
        echo "<H2><FONT COLOR=Red>Are You Sure?</FONT></H2><BR>";
        echo "<INPUT TYPE=HIDDEN NAME=operation VALUE=clearfurlog>";
        echo "<INPUT TYPE=SUBMIT VALUE=Clear>";
      }
      elseif($operation == "clearfurlog")
      {
        $res = $db->Execute("SELECT email,ship_id FROM $dbtables[ships] WHERE email LIKE '%@furangee'");
        while(!$res->EOF)
        {
          $row = $res->fields;
          $db->Execute("DELETE FROM $dbtables[logs] WHERE ship_id=$row[ship_id]");
          echo "Log for ship_id $row[ship_id] cleared.<BR>";
          $res->MoveNext();
        }
      }
      else
      {
        echo "Invalid operation";
      }
      echo "<INPUT TYPE=HIDDEN NAME=menu VALUE=clearlog>";
      echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE=$swordfish>";
      echo "</FORM>";
    }
    // ***********************************************
    // ******** START OF CREATE FURANGEE SUB **********
    // ***********************************************
    elseif($module == "createnew")
    {
      echo "<B>Create A New Furangee</B>";
      echo "<BR>";
      echo "<FORM ACTION=furangee_control.php METHOD=POST>";
      if(empty($operation)) echo "ERROR - No data was submitted to the createnew module";
      else if($operation == "createfurangee"){
        // update database
        $_active = empty($active) ? "N" : "Y";
        $errflag=0;
        if ( $character=='' || $shipname=='' ) { echo "Ship name, and character name may not be blank.<BR>"; $errflag=1;}
        // Change Spaces to Underscores in shipname
        $shipname = str_replace(" ","_",$shipname);
        // Create emailname from character
        $emailname = str_replace(" ","_",$character) . "@furangee";
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        $result = $db->Execute ("select email, character_name, ship_name from $dbtables[ships] where email='$emailname' OR character_name='$character' OR ship_name='$shipname'");
        if ($result>0)
        {
          while (!$result->EOF)
          {
            $row= $result->fields;
            if ($row[0]==$emailname) { echo "ERROR: E-mail address $emailname, is already in use.  "; $errflag=1;}
            if ($row[1]==$character) { echo "ERROR: Character name $character, is already in use.<BR>"; $errflag=1;}
            if ($row[2]==$shipname) { echo "ERROR: Ship name $shipname, is already in use.<BR>"; $errflag=1;}
            $result->MoveNext();
          }
        }
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if ($errflag==0)
        {
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
          if ($furlevel=='') $furlevel=0;
          $maxenergy = NUM_ENERGY($furlevel);
          $maxarmour = NUM_ARMOUR($furlevel);
          $maxfighters = NUM_FIGHTERS($furlevel);
          $maxtorps = NUM_TORPEDOES($furlevel);
          $stamp=date("Y-m-d H:i:s");
// *****************************************************************************
// *** ADD FURANGEE RECORD TO ships TABLE ... MODIFY IF ships SCHEMA CHANGES ***
// *****************************************************************************
          $result2 = $db->Execute("INSERT INTO $dbtables[ships] VALUES('','$shipname','N','$character','$makepass','$emailname',$furlevel,$furlevel,$furlevel,$furlevel,$furlevel,$furlevel,$furlevel,$maxtorps,$furlevel,$furlevel,$maxarmour,$furlevel,$start_credits,$sector,0,0,0,$maxenergy,0,$maxfighters,$start_turns,'','N',0,0,0,0,'N','N',0,0, '$stamp',0,0,0,0,'N','127.0.0.1',0,0,0,0,'Y','N','N','Y','','$default_lang','Y','N',-1,'N','N',0,'N')");
          if(!$result2) {
            echo $db->ErrorMsg() . "<br>";
          } else {
            echo "Furangee has been created.<BR><BR>";
            echo "Password has been set.<BR><BR>";
            echo "Ship Records have been updated.<BR><BR>";
          }
          $result3 = $db->Execute("INSERT INTO $dbtables[furangee] (furangee_id,active,behavior,orders,experience) VALUES('$emailname','$_active','$behavior','$orders','$experience')");
          if(!$result3) {
            echo $db->ErrorMsg() . "<br>";
          } else {
            echo "Furangee Records have been updated.<BR><BR>";
          }
        }
        echo "<INPUT TYPE=SUBMIT VALUE=\"Return to Furangee Creator \">";
        $button_main = false;
      }
      else
      {
        echo "Invalid operation";
      }
//      echo "<INPUT TYPE=HIDDEN NAME=menu VALUE=createnew>";
      echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE=$swordfish>";
      echo "</FORM>";
    }
    else
    {
      echo "Unknown function";
    }

    if($button_main)
    {
      echo "<BR><BR>";
      echo "<FORM ACTION=furangee_control.php METHOD=POST>";
      echo "<INPUT TYPE=HIDDEN NAME=swordfish VALUE=$swordfish>";
      echo "<INPUT TYPE=SUBMIT VALUE=\"Return to main menu\">";
      echo "</FORM>";
    }
  }
}
  
include("footer.php");

?> 
