<?php
session_start();
unset($_SESSION["DCIMPORT"]);
unset($_SESSION["CISIS"]);
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}else{
	if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and !isset($_SESSION["permiso"]["CENTRAL_CRDB"])){
		header("Location: ../common/error_page.php") ;
	}
}
unset($_SESSION["FDT"]);
unset($_SESSION["PFT"]);
unset($_SESSION["FST"]);
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];



include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");

//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php")
?>
<script src=../dataentry/js/lr_trim.js></script>
<script languaje=javascript>

function VerificarTipo(){
	ix=document.forma1.base_sel.selectedIndex
	tipo=document.forma1.base_sel.options[ix].value
	element=document.getElementById("dbtype");
	if (tipo=="~~NewDb"){
		element.style.display="block";
	}else{
		element.style.display="none";
	}
}

function Validar(){
	dbn=Trim(document.forma1.nombre.value)
	if (dbn==""){
		alert("<?php echo $msgstr["falta"]." ".$msgstr["dbn"]?>")
		return
	}
	var alphaExp = /^[a-zA-Z_0123456789-]+$/;
    if(dbn.match(alphaExp)){

    }else{
        alert("<?php echo $msgstr["invalidfilename"]?>");
        document.forma1.nombre.focus();
        return
    }
    document.forma1.base.value=dbn.toLowerCase()
    document.forma1.nombre.value=dbn.toLowerCase()
	ix=document.forma1.base_sel.options.length
	for (i=1;i<ix;i++){
		if (document.forma1.base_sel.options[i].value==dbn){
			alert("<?php echo $msgstr["dbexists"]?>")
			return
		}
	}
	desc=Trim(document.forma1.desc.value)
	if (desc==""){
		alert("<?php echo $msgstr["falta"]." ".$msgstr["descripcion"]?>")
		return
	}
	ix=document.forma1.base_sel.selectedIndex
	if (ix<1){
		alert("<?php echo $msgstr["falta"]." ".$msgstr["cpdb"]?>")
		return
	}
	switch(ix){
		case 1:

			document.forma1.desc.value=desc
			document.forma1.action="fdt.php"
			document.forma1.Opcion.value="new"
			document.forma1.submit()
			break
		case 2:
			document.forma1.action="winisis.php"
			document.forma1.submit()
			break
		default:
			document.forma1.action="crearbd_ex_copy.php"
			document.forma1.submit()
			break
	}

}
</script>
	</head>
	<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="s";
}else{
	$encabezado="";
}
?>
				<h2><i class="fa fa-database fa-2x" aria-hidden="true"></i>  <label><?php echo $msgstr["createdb"];?></label></h2>
	
		
	<form method="post" name="forma1" onsubmit="javascript:return false">
		<input type="hidden" name="Opcion">
		<input type="hidden" name="base">
		<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>\n"?>
		<div class="middle form">
			<div class="formContent">
	<!--			<h4>New Database</h4>-->

				<div id="formRow01" class="formRow">
					<label for="field01"><?php echo $msgstr["dbn"];?></label>
					<div class="frDataFields">
						<input class="form-control" type="text" name="nombre"  id="field01" value="">
					
					</div>
					
				</div>
				<div id="formRow02" class="formRow">
					<label for="field02"><?php echo $msgstr["descripcion"];?></label>
					<div class="frDataFields">
						<input class="form-control" type=text name="desc" id="field02">
						<p>
					</div>
			
				</div>

				<div id="formRow3" class="formRow formRowFocus">
					<label for="field3"><?php echo $msgstr["createfrom"];?>:</label>
					<div class="frDataFields">
						<select class="form-control" name="base_sel" id="field3"  onchange="VerificarTipo()" class="textEntry singleTextEntry">
							<option value=""></option>
							<option value="~~NewDb"><?php echo $msgstr["newdb"];?></option>
							<option value="~~WinIsis"><?php echo $msgstr["winisisdb"];?></option>
<?php

$fp = file($db_path."bases.dat");
$bdatos=array();
foreach ($fp as $linea){
	if (trim($linea)!="") {
		$bdatos[]=$linea;
		$b=explode('|',$linea);
		$llave=$b[0];
		if ($llave!="acces") echo "<option value=$b[0]>".$b[1];
	}

}
?>
						</select>
					
<div name="dbtype" id="dbtype" style="display:none">
<!--
CISIS type <select name='cisis'>
<option value=''>default</option>
<option value='bigisis'>bigisis</option>
<option value='ffi'>ffi</option>
<option value='unicode'>unicode</option>
</select>
<br><br>Import documents?<select name='dcimport'>
<option value='yes'>Yes</option>
<option value='no' selected>No</option>
</select>
-->
</div>
					</div>
				
				</div>

			</div>
		</div>
		<div class="formFoot">
			<div class="pagination">
				<a href="javascript:Validar()" class="btn btn-primary">
				
						<?php echo $msgstr["continuar"];?>
						
					</a>
				
			</div>
			
		</div>
	</div>
	</form>
<?php include("../common/footer.php");?>
	</body>
</html>
