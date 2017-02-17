<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
$arrHttp["base"]="users";
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/prestamo.php");

//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");

function LeerPft($pft_name,$base){
global $arrHttp,$db_path,$lang_db;
	$pft="";
	$archivo=$db_path.$base."/loans/".$_SESSION["lang"]."/$pft_name";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/loans/".$lang_db."/$pft_name";
	$fp=file_exists($archivo);
	if ($fp){
		$fp=file($archivo);
		foreach ($fp as $value){
			$pft.=$value;
		}

	}
    return $pft;
}
//se lee la configuración del usuario
$us_tab=LeerPft("loans_uskey.tab","users");
$t=explode("\n",$us_tab);
$prefijo_codigo=$t[0];
if (isset($t[1]))
	$prefijo_nombre=$t[1];
else
	$prefijo_nombre="";
if (isset($t[2]))
	$formato_nombre=$t[2];
else
	$formato_nombre="";
if ($prefijo_codigo=="") $prefijo_codigo="CO_";
if ($prefijo_nombre=="") $prefijo_nombre="NO_";
if ($formato_nombre=="") $formato_nombre="v30";
# Se lee el formato para extraer el código de usuario
$codigo=LeerPft("loans_uskey.pft","users");
?>
<script src=../dataentry/js/lr_trim.js></script>
<script>
function EnviarForma(Proceso){
	if (Trim(document.usersearch.code.value)=="" && Trim(document.inventorysearch.inventory.value)==""){
		alert("<?php echo $msgstr["falta"]." ".$msgstr["usercode"]."/".$msgstr["inventory"]?>")
		return
	}
	if (Proceso==""){
		if (Trim(document.usersearch.code.value)!=""){
			document.EnviarFrm.usuario.value=document.usersearch.usercode.value
			document.EnviarFrm.action="usuario_prestamos_presentar.php"
		}else{
			if (Trim(document.inventorysearch.inventory.value)!=""){
				document.EnviarFrm.inventory.value=document.inventorysearch.inventory.value
				document.EnviarFrm.action="numero_inventario.php"
			}
		}
	}else{
		switch (Proceso){
			case "U":
				document.EnviarFrm.usuario.value=document.usersearch.usercode.value
				document.EnviarFrm.action="usuario_prestamos_presentar.php"
				break
			case "I":
				document.EnviarFrm.inventory.value=document.inventorysearch.inventory.value
				document.EnviarFrm.action="numero_inventario.php"
				document.EnviarFrm.submit()
				break
		}
	}
	document.EnviarFrm.submit()
}

function AbrirIndiceAlfabetico(xI,Prefijo,Subc,Separa,db,cipar,tag,postings,repetible,Formato){
		Ctrl_activo=xI
		lang="en"
	    Separa="&delimitador="+Separa
	    Prefijo=Separa+"&tagfst="+tag+"&prefijo="+Prefijo
	    ancho=200
		url_indice="capturaclaves.php?opcion=autoridades&base="+db+"&cipar="+cipar+"&Tag="+tag+Prefijo+"&postings="+postings+"&lang="+lang+"&repetible=0&Formato="+Formato
		msgwin=window.open(url_indice,"Indice","width=480, height=425,scrollbars")
		msgwin.focus()
}

function AbrirIndice(TipoI,Ctrl){

	switch (TipoI){
		case "U":
			db="users"
			Formato="<?php echo trim($formato_nombre)?>,`$$$`,<?php echo str_replace("'","`",trim($codigo))?>"
			prefijo="<?php echo trim($prefijo_nombre)?>"
			AbrirIndiceAlfabetico(Ctrl,"<?php echo trim($prefijo_nombre)?>","","","users","users.par","30",1,"0",Formato)
			break
 		case "I":
			db="trans"
			prefijo="!Z"
   			AbrirIndiceAlfabetico(Ctrl,"TR_P","","","trans","trans.par","10",1,"","v10,`$$$`,v10")
			break
	}
}

function Output(code,name){
	document.getElementById('loading').style.display='block';
	if (Trim(document.usersearch.usercode.value)!="")
		document.output.user.value=document.usersearch.usercode.value
	for (i=0;i<document.inventorysearch.sort.length;i++){
		if (document.inventorysearch.sort[i].checked) {
			document.output.sort.value=document.inventorysearch.sort[i].value
		}
	}
	document.output.action="../output_circulation/rs01.php"
	document.output.code.value=code
	document.output.name.value=name
	document.output.submit()
}
</script>
<?php
$encabezado="";
echo "<body>\n";
include("../common/institutional_info.php");
$link_u="";
if (isset($arrHttp["usuario"]) and $arrHttp["usuario"]!="") $link_u="&usuario=".$arrHttp["usuario"];
if (isset($arrHttp["reserve"]) and $arrHttp["reserve"]=="S")
	$ayuda="reserve.html";
else
	$ayuda="user_statment.html"
?>

<div id="loading">
  <img id="loading-image" src="../dataentry/img/preloader.gif" alt="Loading..." />
</div>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><i class="fa fa-folder-open-o fa-2x"></i>  <label><?php if (!isset($arrHttp["reserve"]))
				echo $msgstr["statment"];
			  else
			  	echo $msgstr["reservas"];
		?>
	</div></label></h2>
	<div class="actions">
		<?php include("submenu_prestamo.php");?>
	</div>
	
</div>

<div class="middle list">
	<div class="searchBox">
	<form name="usersearch" action="" method="post" onsubmit="javascript:return false">
	<input type="hidden" name="Indice">
	
		<label for="searchExpr"><?php echo $msgstr["usercode"];?></label>

		
		<input name="usercode" id="code" value="<?php if (isset($arrHttp["usuario"])) echo $arrHttp["usuario"]?>" class="form-control">
<br>
		<button class="btn btn-primary" name="index" onClick="javascript:AbrirIndice('U',document.usersearch.usercode)"><i class="fa fa-list" value="<?php echo $msgstr["list"];?>"></i></button>

		<button name="buscar" class="btn btn-warning" onclick="javascript:EnviarForma('U')"><i class="fa fa-search" value="<?php echo $msgstr["search"]?>"></i></button>
	</form>
	</div>
	

	<form name="inventorysearch" action="numero_inventario.php" method="post" onsubmit="javascript:return false">
<?php if (!isset($arrHttp["reserve"]) and !isset($arrHttp["reserve_ex"])){
?>

	

	<label><?php echo $msgstr["ec_inv"];?></label>

		<label for="searchExpr">
			<label><?php echo $msgstr["inventory"];?></label>
		</label>
	
		<input class="form-control" name="inventory" id="searchExpr">
		<br>

		<button class="btn btn-primary" name="list" onclick="javascript:AbrirIndice('I',document.inventorysearch.inventory)"><i class="fa fa-list" value="<?php echo $msgstr["list"];?>" ></i></button> 

		<button class="btn btn-warning" name="buscar"  onclick="javascript:EnviarForma('I')"><i class="fa fa-search" value="<?php echo $msgstr["search"];?>"></i></button>




	

<?php } ?>


		<?php echo $msgstr["clic_en"]." ".$msgstr["search"]." ".$msgstr["para_c"];


		if ((isset($arrHttp["reserve"]) and $arrHttp["reserve"]=="S") or (isset($arrHttp["reserve_ex"]) and $arrHttp["reserve_ex"]=="S")) {
			echo "<h3>".$msgstr["reports"].":</h3>
			".$msgstr["orderby"].":<br>
			
         	
         	
         	<input type=radio name=sort value=name>".$msgstr["name"]."
         		
			<input type=radio name=sort value=date_reservation >".$msgstr["reserve_date"]."
			<input type=radio name=sort value=date_assigned>".$msgstr["assigned_date"]."
			<input type=radio name=sort value=date_attended>".$msgstr["loandate"]."
			

			<br><br>
            <div class=\"btn-group\">

			<button class=\"btn btn-primary\" name=rs00 onClick=\"javascript:Output('today','rs00')\" >".$msgstr["rs00"]."</button>

			<button class=\"btn btn-primary\" name=rs01 onClick=\"javascript:Output('actives','rs01')\" >".$msgstr["rs01"]."</button>

			<button class=\"btn btn-primary\" name=rs02 onClick=\"javascript:Output('assigned','rs02')\" >".$msgstr["rs02"]."</button>
			
            <button class=\"btn btn-primary\" name=rs03 onClick=\"javascript:Output('overdued','rs03')\" >".$msgstr["rs03"]."</button>
			
			<button class=\"btn btn-primary\" name=rs04 onClick=\"javascript:Output('attended','rs04')\" >".$msgstr["rs04"]."</button>

		   <button class=\"btn btn-primary\" name=rs05  onClick=\"javascript:Output('cancelled','rs05')\" >".$msgstr["rs05"]."</button>
			<br><br></div>";
		}
?>
</form>
</div>

<form name="EnviarFrm" method="post">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"]?>">
<input type="hidden" name="usuario" value="">
<input type="hidden" name="inventory">
<input type="hidden" name="ecta" value="Y">
<?php if (isset($arrHttp["reserve"])){
	echo "<input type=hidden name=reserve value=S>\n";
}
?>
</form>
<form name="output" method="post">
<input type="hidden" name="base" value="reserve">
<input type="hidden" name="code">
<input type="hidden" name="name">
<input type="hidden" name="user">
<input type="hidden" name="sort">
<input type="hidden" name="retorno" value="../circulation/estado_de_cuenta.php">
<input type="hidden" name="reserva" value="S">
</form>



<?php 
echo "</body></html>" ;
if (isset($arrHttp["error"]) and $arrHttp["inventory"]!=""){
	echo "
	<script>
	alert('".$arrHttp["inventory"].": El número de inventario no está prestado')
	</script>
	";
}
?>