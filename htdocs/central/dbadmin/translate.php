<?php
session_start();
if (!isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]) and !isset($_SESSION["permiso"]["CENTRAL_ALL"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
include("../lang/dbadmin.php");
include("../lang/admin.php");

$lang=$_SESSION["lang"];
//foreach ($arrHttp as $var => $value) 	echo "$var = $value<br>";
$encabezado="";
if (isset($arrHttp["encabezado"])) $encabezado="&encabezado=s";
include("../common/header.php");
$rotulo="";
switch ($arrHttp["componente"]){
	case "soporte.tab":
		$rotulo=$msgstr["maintenance"];
		break;
	case "dbadmin.tab":
		$rotulo=$msgstr["dbadmin"];
		break;
	case "admin.tab":
		$rotulo=$msgstr["catalogacion"];
		break;
	case "statistics.tab":
		$rotulo=$msgstr["statistics"];
		break;
}
echo "
<script>
function Enviar(){
	document.forma1.submit()
}
</script>
<body>\n";
if (isset($arrHttp["encabezado"]))include("../common/institutional_info.php");

?>
<div class="sectionInfo">

			<div class="breadcrumb">
				<?php echo "<h5>".$msgstr["traducir"].": ".$rotulo."</h5>"?>
			</div>

			<div class="actions">
				
					
					
<?php if (isset($arrHttp["encabezado"])){
?>
 				

<?php }?>
			</div>
			</div>
<?php
echo "
<div class=\"middle form\">
			<div class=\"formContent\">
";
echo "<font size=1> &nbsp; &nbsp; Script: dbadmin/translate.php</font><br>";
//error_reporting (0);
$componente=$arrHttp["componente"];

if ($componente=="" or $lang==""){
	echo $msgstr["sellang"]."<p><a href=javascript:history.back()>".$msgstr["regresar"]."</a>";
	die;
}

// Se lee el primer idioma de la tabla que es el que gobierna los mensajes en otros idiomas
echo "<h4><b>$lang/$componente</b></h4>";
if (isset($msg_path) and $msg_path!="")
   	$a=$msg_path."lang/".$_SESSION["lang"]."/$componente";
else
	$a=$db_path."lang/".$_SESSION["lang"]."/$componente";
if (file_exists($a)) {
	$fp=file($a);
	foreach($fp as $var=>$value){
		if (trim($value)!="") {
			$m=explode('=',$value,2);
			$m[0]=trim($m[0]);
			$msg[$m[0]]=trim($m[1]);
		}
	}
}
if (isset($msg_path) and $msg_path!="")
	$a=$msg_path."lang/00/$componente";
else
	$a=$db_path."lang/00/$componente";
if (file_exists($a)) {
	$fp=file($a);
	foreach($fp as $var=>$value){
		if (trim($value)!="") {
			$m=explode('=',$value,2);
			$m[0]=trim($m[0]);
			if (!isset($msg[$m[0]]))
				$msg[$m[0]]=trim($m[1]);
		}
	}
}


echo "<table class=\"table table-striped\">
<form method=post action=translate_update.php name=forma1>
<input type=hidden name=lang value=$lang>
<input type=hidden name=componente value=$componente>
\n";
if (isset($arrHttp["encabezado"]))   echo "<input type=hidden name=encabezado value=s>";
$ixmsg=-1;
foreach ($msg as $key=>$value){
	$ixmsg=$ixmsg+1;
	$v=explode('=',$value);
	$nomb=$key;
	echo "<tr><td width=20%>$ixmsg)";
	echo " $value <label>".$key."</label></td><td ><input type=text class=\"form-control\" size=100 name=msg_$key value=\"$value\">\n";
	echo "</td>";

}

echo "</table>";
?>

<div>
<br><br>
<a href="javascript:Enviar()" class="btn btn-primary" title="<?php echo $msgstr["m_guardar"]?>"><i class="fa fa-check" aria hidden="true"></i></a>

</div>
<br>
<br>
</form>
</div></div>
<?php echo include("../common/footer.php");?>
</body>
</html>
