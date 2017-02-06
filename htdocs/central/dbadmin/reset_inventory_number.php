<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}else{
	if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and !isset($_SESSION["permiso"]["CENTRAL_RESETLIN"])){
		header("Location: ../common/error_page.php") ;
	}
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/acquisitions.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";
$inv_val="";
$file=$db_path."copies/data/control_number.cn";
if (file_exists($file)){
	$fp=file($file);
	$inv_val=implode("",$fp);
}

include("../common/header.php");
echo "<script src=../dataentry/js/lr_trim.js></script>"
?>
<script>
function Enviar(){
	control=Trim(document.forma1.control_n.value)
	if (control=="" || control=="0"){
		if (confirm("The inventory number of the copies database will be restored to 0 \n\n Is that correct? ")){
			if (confirm("are you sure?")){
			}else{
				return
			}
		}else{
			return
		}
	}
	document.forma1.submit()
}

</script>
<?php
echo "<body>\n";
if (isset($arrHttp["encabezado"])) {
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}


?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><label><?php echo $msgstr["resetinv"];?></label></h2>
	</div>
	<div class="actions">

<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<font color=white>&nbsp; &nbsp; Script: dbadmin/reset_inventory_number.php</font>\n";
echo "
	</div>
<div class=\"middle form\">
	<div class=\"formContent\">";
echo "<form name=forma1 action=resetautoinc_update.php method=post onsubmit=\"javascript:return false\">
 <input type=hidden name=Opcion value=inventory>\n";
if (isset($arrHttp["encabezado"]))  echo "<input type=hidden name=encabezado value=s>\n";
echo "<table>";
echo "<tr><td>".$msgstr["lastinv"]."</td><td><input class=\"form-control\" type=textbox name=control_n value=$inv_val></td>";
echo "</table>";
echo "<p><input class=\"btn btn-success\" type=submit name=send value=".$msgstr["update"]." onclick=Enviar()>";

echo "</form></div></div>";
include("../common/footer.php");
echo "</body></html>";
?>