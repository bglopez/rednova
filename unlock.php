<? 
include("config.php");
include("languages/$lang");

$title=".:: Red Nova Trader :.";
include("header.php");
bigtitle();
connectDB();

SetCookie("rednova","",time()-3600,$gamepath,$gamedomain);

echo "<BR><BR>";
echo "Do not call this file a second time!<BR>Or your account will be locked again!";


?>