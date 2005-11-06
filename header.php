<? header("Cache-Control: no-cache, must-revalidate");
// Comment out the line below if you are running php 4.0.6 or earlier
ob_start("ob_gzhandler");

?>
<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<title><? echo $title; ?></title>
 <style type="text/css">
 <!--
<?
if($interface == "")
{
  $interface = "main.php";
}

if($interface == "main.php")
{ 
  echo "  a.mnu {text-decoration:none; font-size: 8Pt; font-family: verdana; color:white; font-weight:bold;}
  a.mnu:hover {text-decoration:none; font-size: 8Pt; font-family: verdana; color:#3366ff; font-weight:bold;}
  div.mnu {text-decoration:none; font-size: 8Pt; font-family: verdana; color:white; font-weight:bold;}
  span.mnu {text-decoration:none; font-size: 8Pt; font-family: verdana; color:white; font-weight:bold;}
  a.dis {text-decoration:none; font-size: 8Pt; font-family: verdana; color:silver; font-weight:bold;}
  a.dis:hover {text-decoration:none; font-size: 8Pt; font-family: verdana; color:#3366ff; font-weight:bold;}
  table.dis {text-decoration:none; font-size: 8Pt; font-family: verdana; color:silver; font-weight:bold;}
  table.dis:hover {text-decoration:none; font-size: 8Pt; font-family: verdana; color:#3366ff; font-weight:bold;}
  .portcosts1 {width:7em;border-style:none;font-family: verdana;font-size:12pt;background-color:$color_line1;color:#c0c0c0;}
  .portcosts2 {width:7em;border-style:none;font-family: verdana;font-size:12pt;background-color:$color_line2;color:#c0c0c0;}
  .headlines {text-decoration:none; font-size:8Pt; font-family:verdana,Arial,san-serif; font-weight:bold; color:white;}
  .headlines:hover {text-decoration:none; color:#3366ff;}
  .faderlines {background-color:$color_line2;}";
}
echo "\n  body {font-family: Arial, Helvetica, sans-serif; font-size: x-small;}\n";
?>
 -->
 </style>
</head>

<?

if(empty($no_body))

{

  if($interface=="main.php")
  {
  	echo "<body background=\"images/bgoutspace1.gif\" bgcolor=\"#000000\" text=\"#c0c0c0\" link=\"#00ff00\" vlink=\"#00ff00\" alink=\"#ff0000\">";
  }
  else
  {
  	echo "<body background=\"\" bgcolor=\"#000000\" text=\"#c0c0c0\" link=\"#00ff00\" vlink=\"#808080\" alink=\"#ff0000\">";
  }

}
echo "\n";
if ($GameDuration > 0) include("bnt_hof_over.php");
?>
