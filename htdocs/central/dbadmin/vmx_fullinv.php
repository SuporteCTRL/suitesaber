<?php 
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/acquisitions.php");
include("../config.php");
include("../common/header.php");
echo "<script src=../dataentry/js/lr_trim.js></script>";
echo "<body>\n";
if (isset($arrHttp["encabezado"])) {
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}
include("../common/institutional_info.php");
$base=$_POST['base'];
echo "<a href=\"menu_mantenimiento.php?base=".$base."&encabezado=s\" class=\"defaultButton backButton\">";
echo "<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
					<span><strong> back </strong></span>
				</a>";
	
				
		

$bd=$db_path.$base;
$strINV=$mx_path."mx.exe ".$bd."/data/".$base." fst=@".$bd."/data/".$base.".fst"." fullinv=".$bd."/data/".$base." -all now tell=100";

exec($strINV, $output,$t);
$straux="";
for($i=0;$i<count($output);$i++)
{
$straux.=$output[$i]."<br>";
}


?>
<br>
<br>
<div class="middle form">
	<div class="formContent">
<form name="maintenance">

	
		<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
        
          <?php
		  echo "<h3>Query: $strINV"."</h3><br>"; 
		  ?>
           
			<?php 
			
			if($straux!="")
echo ("<h3>process Output: ".$straux."<br>process Finished OK</h3><br>");
else
echo ("<h2>Out: <br>process NOT EXECUTED</h2><br>");
if($base=="")
{
echo"NO database selected";
}
?>

</form>
<form name="admin" method="post" action="administrar_ex.php" onSubmit="Javascript:return false">
<input type="hidden" name="base">
<input type="hidden" name="cipar">
<input type="hidden" name="Opcion">
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>"?>
</form>
</div>
</div>
<?
include("../common/footer.php");
echo "</body></html>";
?>

