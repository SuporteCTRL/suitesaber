<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
include("../lang/dbadmin.php");

include("../lang/soporte.php");
include("../common/header.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["z3950"].". ".$msgstr["z3950_diacritics"] ?>
	</div>

	
<div class="middle form">
			<div class="formContent">
<?php
$fp=fopen($db_path."cnv/marc-8_to_ansi.tab","w");
$accents=explode("\n",$arrHttp["ValorCapturado"]);
foreach ($accents as $val){
	$val=trim($val);
	if($val!=""){
		$a=explode('|',$val);
		fwrite($fp,$a[0]." ".$a[1]."\n");
	}
}
fclose($fp);
echo "<h4>marc-8_to_ansi.tab : ".$msgstr["updated"]."</h4>";
?>
</div>
</div>
<?php include("../common/footer.php");?>
</body>
</html>

