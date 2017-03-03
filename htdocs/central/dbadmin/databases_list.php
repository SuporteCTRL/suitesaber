<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
include("../common/header.php");

?>
<!--link rel=stylesheet href=../css/styles.css type=text/css -->
<script language=Javascript src=../dataentry/js/selectbox.js></script>
<script language=Javascript src=../dataentry/js/lr_trim.js></script>
<script>
function Editar(){
	msgwin=window.open("editararchivotxt.php?archivo=bases.dat&desde=menu$encabezado&desde=menu","editpar","width=600, height=500, resizable, scrollbars")
	msgwin.focus()

}
function Enviar(){
	ValorCapturado=""
	for (i=0;i<document.forma1.lista.options.length;i++){
		a= Trim(document.forma1.lista.options[i].value)
		if (a!="") {
			if (ValorCapturado=="")
				ValorCapturado=a
			else
			    ValorCapturado+="\n"+a
		}
	}
	document.forma1.txt.value=ValorCapturado
	document.forma1.submit()
}
</script>
</head>
<body>
<?php
include("../common/institutional_info.php");
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<h2><label><i class="fa fa-align-justify fa-2x " aria-hidden="true"></i>       <?php echo $msgstr["dblist"] ?></label></h2>
	</div>


<div class="middle form">
	<div class="formContent">
<form name="forma1" action="actualizararchivotxt.php" method="post" onsubmit='javascript:return false'>
<input type="hidden" name="txt">
<input type="hidden" name="archivo" value="bases.dat">
<input type="hidden" name="retorno" value="conf_abcd.php">
<input type="hidden" name="encabezado" value="s">
<br>
<table border="0">
	<tr>
		
		<td>
			<select name="lista" class="form-control">
<?php
$fp=file($db_path."bases.dat");
foreach ($fp as $value){
	if (trim($value)!=""){
		$b=explode('|',$value);
		echo "<option value='$value'>".$b[1]." (".$b[0].")</option><br>";
	}
}

?>


			</select>
		</td>
</table>
<br>
<input class="btn btn-primary" type="submit" value="<?php echo $msgstr["update"]?>" onClick="javascript:Enviar()">
<input class="btn btn-warning" type="submit" value="<?php echo $msgstr["edit"];?>" onClick="javascript:Editar()">
<input class="btn btn-danger" type="submit" value="<?php echo $msgstr["cancel"];?>" onClick="document.cancelar.submit();return false">
</form>
<form name="cancelar" method="post" action="conf_abcd.php">
<input type="hidden" name="encabezado" value="s">

</form>
</center>
</div></div>
<?php include("../common/footer.php");?>
</body>
</html>