<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
include ("../config.php");



include ("../common/header.php");
include ("../lang/soporte.php");
include ("../lang/admin.php");
include ("../lang/reports.php");
include ("configure.php");
?>
<body>
<script language=Javascript src=../dataentry/js/lr_trim.js></script>
<script>
function Enviar(){
	document.forma1.target=""
	if (document.forma1.output[0].checked){
		msgwin=window.open("","display","width=400,height=400,scrollbars,menubar,toolbar,resizable");
		document.forma1.target="display"
		msgwin.focus()
	}
	document.forma1.submit()
}
function PorNumeroClasificacion(){
	classification_from=Trim(document.forma1.classification_from.value)
	classification_to=Trim(document.forma1.classification_to.value)
    if (Trim(classification_from)=="" || Trim(classification_to)==""){
    	alert("<?php echo $msgstr["range_classification_invalid"]?>")
    	return
    }
    if (classification_to<classification_from){
    	alert("<?php echo $msgstr["range_classification_invalid"]?>")
    	return
    }
    document.forma1.base.value="<?php echo $arrHttp["base"]?>"
    document.forma1.Opcion.value="clasificacion"
    document.forma1.copies.value="<?php echo $copies?>"
	Enviar()
}

function PorNumeroControl(){
	control_from=Trim(document.forma1.control_from.value)
	control_to=Trim(document.forma1.control_to.value)
    if (Trim(control_from)=="" || Trim(control_to)=="" ||Trim(control_from)==0 || Trim(control_to)==0){
    	alert("<?php echo $msgstr["range_control_invalid"]?>")
    	return
    }
    if (control_to<control_from){
    	alert("<?php echo $msgstr["range_control_invalid"]?>")
    	return
    }
    document.forma1.base.value="<?php echo $arrHttp["base"]?>"
    document.forma1.Opcion.value="control"
    document.forma1.copies.value="<?php echo $copies?>"
	Enviar()
}

function PorNumeroInventario(){
    inventory_from=Trim(document.forma1.inventory_from.value)
	inventory_to=Trim(document.forma1.inventory_to.value)
    if (Trim(inventory_from)=="" || Trim(inventory_to)==""){
    	alert("<?php echo $msgstr["range_inventory_invalid"]?>")
    	return
    }
    if (inventory_to<inventory_from){
    	alert("<?php echo $msgstr["range_inventory_invalid"]?>")
    	return
    }
    document.forma1.base.value="<?php echo $arrHttp["base"]?>"
    document.forma1.Opcion.value="inventario"
    document.forma1.copies.value="<?php echo $copies?>"
	Enviar()
}

function PorFechaIngreso(){
    date_from=Trim(document.forma1.date_from.value)
	date_to=Trim(document.forma1.date_to.value)
    if (Trim(date_from)=="" || Trim(date_to)==""){
    	alert("<?php echo $msgstr["range_date_invalid"]?>")
    	return
    }
    if (date_to<date_from){
    	alert("<?php echo $msgstr["range_date_invalid"]?>")
    	return
    }
    document.forma1.base.value="<?php echo $arrHttp["base"]?>"
    document.forma1.Opcion.value="date"
    document.forma1.copies.value="<?php echo $copies?>"
	Enviar()
}

function PorRangoMfn(){
	mfn_from=Trim(document.forma1.mfn_from.value)
	mfn_to=Trim(document.forma1.mfn_to.value)
    if (Trim(mfn_from)=="" || Trim(mfn_to)=="" || mfn_from==0 || mfn_to==0 ){
    	alert("<?php echo $msgstr["range_mfn_invalid"]?>")
    	return
    }
    if (mfn_to<mfn_from){
    	alert("<?php echo $msgstr["range_mfn_invalid"]?>")
    	return
    }
    document.forma1.base.value="<?php echo $arrHttp["base"]?>"
    document.forma1.Opcion.value="mfn"
    document.forma1.copies.value="<?php echo $copies?>"
	Enviar()
}

Ctrl_activo=""
function AbrirIndiceAlfabetico(xI,Prefijo,Subc,Separa,db,cipar,tag,postings,Repetible,Formato){
	Ctrl_activo=xI
	baseactiva="<?php echo $arrHttp["base"]?>"
	lang="<?php echo $_SESSION["lang"]?>"
    document.forma1.Indice.value=xI
    Separa="&delimitador="+Separa
    Prefijo=Separa+"&tagfst="+tag+"&prefijo="+Prefijo
    myleft=screen.width-600
	url_indice="../dataentry/capturaclaves.php?opcion=autoridades&base="+db+"&cipar="+cipar+"&Tag="+tag+Prefijo+"&postings="+postings+"&lang="+lang+"&repetible="+Repetible+"&Formato="+Formato+"&subcampos="+Subc+"&baseactiva="+baseactiva
	msgwin=window.open(url_indice,"Indice","width=650, height=530,  scrollbars, status, resizable location=no, left="+myleft)
	msgwin.focus()
	return
}
</script>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<h4><?php echo $msgstr["barcode"].": ".$arrHttp["base"];?></h4>
	</div>

	<div class="actions">

		
	</div>

	</div>

<?php
$ayuda="barcode.html";
if (isset($arrHttp["encabezado"])){
	if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_MODIFYDB"])){
		$retorno="../dbadmin/pft.php";
		
	}else{
		
	}
}
?>

</div>


</div>

<div class="middle form">
	<div class="formContent">
<?php
if ($msg_err!=""){
	echo $msg_err;
	echo "<h4><label><a href=barcode_conf.php?base=".$arrHttp["base"].">".$msgstr["configure"]."</a>";
	echo "</div></div>";
	include("../common/footer.php");
	die;
}else{
	echo "<a href=barcode_conf.php?base=".$arrHttp["base"].">".$msgstr["configure"]."</a>";
}
echo "<form name=forma1 action=barcode_ex.php method=post onSubmit='javascript:return false'>";
echo "<label>".$msgstr["sendto"].": "."</label>";
echo "<table class=\"table\">";
echo "<td>";
echo "<input type=radio name=output value=display checked >".$msgstr["display"];
echo "</td>";
echo "<!-- td>";
echo "<input type=radio name=output value=excel>".$msgstr["excel"];
echo "</td -->";
echo "<td>";
echo "<input type=radio name=output value=calc>".$msgstr["calc"];
echo "</td>";
echo "<td>";
echo "<input type=radio name=output value=odt>".$msgstr["odt"];
echo "</td>";
echo "<!-- td>";
echo "<input type=radio name=output value=doc>".$msgstr["doc"];
echo "</td -->";
echo "<td>";
echo "<input type=radio name=output value=txt>".$msgstr["txt"];
echo "</td>";
echo "<td>";
echo "<input type=radio name=output value=csv>".$msgstr["csv"];
echo "</td>";
echo "</table>";
echo "<label>".$msgstr["r_recsel"]."</label>";
echo "<table class=\"table\">";

echo "<input type=hidden name=Indice>";
echo "<input type=hidden name=base>";
echo "<input type=hidden name=Opcion>";
echo "<input type=hidden name=copies>";
echo "<tr><td><label>".$msgstr["byclass_num"]." (".$base_classification.")</label></td>";
echo "<td><label>".$msgstr["cg_from"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.classification_from,\"$pref_classification\",\"\",\"\",\"$base_classification\",\"$base_classification.par\",\"classification_from\",\"1\",\"0\",\"".urlencode("@".$fe_classification)."\")'></a>";
echo "<input type=text name=classification_from class=\"form-control\">";
echo "</td>";
echo "<td><label>".$msgstr["cg_to"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.classification_to,\"$pref_classification\",\"\",\"\",\"$base_classification\",\"$base_classification.par\",\"classification_to\",\"1\",\"0\",\"".urlencode("@".$fe_classification)."\")'></a>";
echo "<input type=text name=classification_to class=\"form-control\">";
echo "</td>";
echo "<td><input type=submit name=classification value=".$msgstr["entrar"]." onClick=javascript:PorNumeroClasificacion()></td>";
echo "</tr>";

echo "<tr><td><label>".$msgstr["bycontrol_num"]." (".$base_control.")</label></td>";
echo "<td><label>".$msgstr["cg_from"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.control_from,\"$pref_control\",\"\",\"\",\"$base_control\",\"$base_control.par\",\"control_from\",\"1\",\"0\",\"".urlencode($fe_control)."\")'></a>";
echo "<input type=text name=control_from class=\"form-control\">";
echo "</td>";
echo "<td><label>".$msgstr["cg_to"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.control_to,\"$pref_control\",\"\",\"\",\"$base_control\",\"$base_control.par\",\"control_to\",\"1\",\"0\",\"".urlencode($fe_control)."\")'></a>";
echo "<input type=text name=control_to class=\"form-control\">";
echo "</td>";
echo "<td><input type=submit name=control value=".$msgstr["entrar"]." onClick=javascript:PorNumeroControl()></td>";
echo "</tr>";

echo "<tr><td><label>".$msgstr["byinventory_num"]." (".$base_inventory.")</label></td>";
echo "<td><label>".$msgstr["cg_from"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.inventory_from,\"$pref_inventory\",\"\",\"\",\"$base_inventory\",\"$base_inventory.par\",\"inventory_from\",\"1\",\"0\",\"".urlencode($fe_inventory)."\")'></a>";
echo "<input type=text name=inventory_from class=\"form-control\">";
echo "</td>";
echo "<td><label>".$msgstr["cg_to"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.inventory_to,\"$pref_inventory\",\"\",\"\",\"$base_inventory\",\"$base_inventory.par\",\"inventory_to\",\"1\",\"0\",\"".urlencode($fe_inventory)."\")'></a>";
echo "<input type=text name=inventory_to class=\"form-control\">";
echo "</td>";
echo "<td><input type=submit name=inventory value=".$msgstr["entrar"]." onClick=javascript:PorNumeroInventario()></td>";
echo "</tr>";

echo "<tr><td><label>".$msgstr["byinput_date"]." (".$base_date.")</swf_labelframe(name)l></td>";
echo "<td><label>".$msgstr["cg_from"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.date_from,\"$pref_date\",\"\",\"\",\"$base_date\",\"$base_date.par\",\"date_from\",\"1\",\"0\",\"".urlencode($fe_date)."\")'></a>";
echo "<input type=text name=date_from class=\"form-control\">";
echo "</td>";
echo "<td><label>".$msgstr["cg_to"]."</label></td>";
echo "<td>";
echo "<a href='javascript:AbrirIndiceAlfabetico(document.forma1.date_to,\"$pref_date\",\"\",\"\",\"$base_date\",\"$base_date.par\",\"date_to\",\"1\",\"0\",\"".urlencode($fe_date)."\")'></a>";
echo "<input type=text name=date_to class=\"form-control\">";
echo "</td>";
echo "<td>";
echo "<input type=submit name=date value=".$msgstr["entrar"]." onClick=Javascript:PorFechaIngreso()></td>";
echo "</tr>";

echo "<tr><td><label>".$msgstr["bymfn_range"]." (".$base_inventory.")</label></td>";
echo "<td><label>".$msgstr["cg_from"]."</label></td>";
echo "<td><input type=text name=mfn_from class=\"form-control\">";
echo "</td>";
echo "<td><label>".$msgstr["cg_to"]."</label></td>";
echo "<td><input type=text name=mfn_to class=\"form-control\">";
echo "</td>";
echo "<td><input type=submit name=mfn value=".$msgstr["entrar"]." onClick='javascript:PorRangoMfn()'></td>";
echo "</tr>";
echo "</tr>";
echo "</form>";

echo "</table>";

?>
	</div>
</div>
<?php
Include("../common/footer.Php");
?>
</Body>
</Html>
