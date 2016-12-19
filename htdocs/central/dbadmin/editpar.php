<?php
global $arrHttp;
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
include("../common/header.php");


$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";
echo "<body>\n";
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["dbnpar"].": ".$arrHttp["base"]?>
	</div>
	

<div class="middle form">
			<div class="formContent">
<?php
$par="";
$fp=file($db_path."par/".$arrHttp["base"].".par");
foreach ($fp as $value) $par.=trim($value)."\n";
echo "<form name=db action=editpar_update.php method=post>";
echo "<input type=hidden name=base value=".$arrHttp["base"].">\n";
if (isset($arrHttp["encabezado"]))  echo "<input type=hidden name=encabezado value=s>\n";
echo "<center><b>".$arrHttp["base"].".par</b><br><textarea class=\"form-control\" cols=100 rows=20 name=par>".$par."</textarea>
<br><br>
<input class=\"btn btn-primary\" type=submit value=\"". $msgstr["update"]."\">
</form>
</div>
</div>
</center>";
include("../common/footer.php");
echo "</body></html>\n";
?>