<?
if (preg_match("/sched_furangee.php/i", $PHP_SELF)) {
	echo "You can not access this file directly!";
	die();
}

include_once("furangee_funcs.php");
include_once("languages/$lang");
global $targetlink;
global $furangeeisdead;
global $playerinfo;

# Grab active furangees with intact ships from database
$res = $db->Execute("SELECT * FROM $dbtables[ships] JOIN $dbtables[furangee] WHERE email=furangee_id and active='Y' and ship_destroyed='N' ORDER BY sector");

# Clear Counter Variables
$count = array(
	sentinal=>0,
	roam=>0,
	trader=>0,
	hunter=>0,
	moodchange=>0,
	upgrade=>0,
	disobeyed=>0,
	total=>0
);

# Loop through each furangee
while(!$res->EOF)
{	
	$furangeeisdead = 0;
    $playerinfo = $res->fields;
	$count[total] = $res->_numOfRows;
	
	# Regenerate Energy/Armour/Fighters ect
    furangeeregen();
	# 5% chance of visiting a special to upgrade
	if (rand(1,20) == 1) {
		upgrade($playerinfo);
		$count[upgrade]++;
	}
	# 1% chance of disobeying orders for this turn
	if (rand(1,100) == 1) {
		$playerinfo[orders]=rand(0,3);
		$count[disobeyed]++;
	}
	# 1% chance of a mood change
	if (rand(1,100) == 1) {
		$playerinfo[behavior]=rand(0,1);
		$count[moodchange]++;		
	}
	
	switch($playerinfo[orders]){
		#------------------------------------------------------------------------
		case"0": # SENTINAL FURANGEE
		#-----------------------------------------------------------------------
		
			$count[sentinal]++;
			searchSector();
	
		break;		
		#------------------------------------------------------------------------
		case"1": # ROAMING FURANGEE
		#------------------------------------------------------------------------
		
			$count[roam]++;
			# Move to a new sector
			$targetlink = $playerinfo[sector];
			furangeemove();
			
			# Search if we're still alive
			if ($furangeeisdead==0) {
				searchSector();
			}
	
		break;
		#------------------------------------------------------------------------
		case"2": # TRADER FURANGEE
		#------------------------------------------------------------------------
	
			$count[trader]++;
			# Move to a new sector
			$targetlink = $playerinfo[sector];
			furangeemove();
			
			# Trade and Search if we're still alive
			if ($furangeeisdead==0) {
				furangeetrade();
				searchSector();
			}
	
		break;
		#------------------------------------------------------------------------
		case"3": # HUNTER FURANGEE
		#------------------------------------------------------------------------				

			$count[hunter]++;
			# Are we hunting this turn? (20% Chance)
			$hunt=rand(0,3);
			if ($hunt==0){
				furangeehunter();
				if ($furangeeisdead>0) {
				  $res->MoveNext();
				  continue;
				}
			} else {
				# Move to a new sector
				$targetlink = $playerinfo[sector];
				furangeemove();
				
				# Search if we're still alive
				if ($furangeeisdead==0) {
					searchSector();
				}
			}
		
		break;		
		#------------------------------------------------------------------------		
    }
	$res->MoveNext();
}
$res->_close();

echo "$count[total] Active furangees<br>";
echo "$count[sentinal] Furangees had Sentinal orders<br>";
echo "$count[roam] Furangees had Roam orders<br>";
echo "$count[trader] Furangees had Trade orders<br>";
echo "$count[hunter] Furangees had Hunter orders<br>";
echo "$count[moodchange] Furangees had mood changes<br>";
echo "$count[disobeyed] Furangees disobeyed their normal orders<br>";
echo "$count[upgrade] Furangees upgraded their spaceships<br>";
echo "<br>";

?>
