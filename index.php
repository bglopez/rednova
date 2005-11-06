<? 
  include("config.php");

  if(empty($lang))
    $lang = $default_lang;
  include("languages/$lang");
	$title="Login"; 
  $no_body=1;
	include("header.php");
//  include("config.php");
?>

<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>

<BODY bgcolor="#666666" text="#c0c0c0" link="#000000" vlink="#990033" alink="#FF3333" onLoad="MM_preloadImages('images/login_.gif','images/mail_.gif')">
<center>
<img src="images/BNT-header.jpg" width="517" height="189" border="0" alt="BlackNova Traders">

<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <td colspan="3"><img name="div1" src="images/div1.gif" width="600" height="21" border="0" alt=""></td>
    <td><img src="images/spacer.gif" width="1" height="21" border="0" name="undefined_2" alt=""></td>
  </tr>
  <tr>
    <td colspan="3"><img name="bnthed" src="images/bnthed.gif" width="600" height="61" border="0" alt="BlackNova Traders"></td>
    <td><img src="images/spacer.gif" width="1" height="61" border="0" name="undefined_2" alt=""></td>
  </tr>
  <tr>
    <td colspan="3"><img name="div2" src="images/div2.gif" width="600" height="21" border="0" alt=""></td>
    <td><img src="images/spacer.gif" width="1" height="21" border="0" name="undefined_2" alt=""></td>
  </tr>
  <tr>
    <td colspan=3 align=center><a href=<? echo "login.php"; ?> onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('login','','images/login_.gif',1);" ><img name="login" src="images/login.gif" width="146" height="58" border="0" alt="Login"></a></td>
    <td><img src="images/spacer.gif" width="1" height="58" border="0" name="undefined_2" alt=""></td>
  </tr>
  <tr>
    <td colspan=3 align=center><a href=<? echo "\"mailto:$admin_mail\""; ?> onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('mail','','images/mail_.gif',1);" ><img name="mail" src="images/mail.gif" width="146" height="58" border="0" alt="Mail"></a></td>
    <td><img src="images/spacer.gif" width="1" height="58" border="0" name="undefined_2" alt=""></td>
  </tr>
  <tr>
  <td colspan=3 align=center><a href="faq.html"><? echo "$l_faq"; ?></a></td>
  </tr>
  <tr>
  <td colspan=3 align=center><BR><a href="http://www.rednova.de/" style="text-decoration:none;"><IMG SRC="./images/rnt.gif" BORDER=0><BR>Patch Pack v0.2</a></td>
  </tr>
  </table></center>
<?
	include("footer.php");
?>
