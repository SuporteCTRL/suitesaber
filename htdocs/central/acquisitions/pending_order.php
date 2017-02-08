<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/acquisitions.php");
include("../lang/admin.php");
include("../common/get_post.php");
include("../common/header.php");
$encabezado="";
echo "<script>
xEliminar=\"\"
Mfn_eliminar=''
function Mostrar(Expresion){
	msgwin=window.open(\"../dataentry/show.php?base=suggestions&Expresion=CN_\"+Expresion,\"show\",\" width=550,height=400,resizable, scrollbars\")
	msgwin.focus()
}
function Editar(Mfn){
	document.EnviarFrm.Mfn.value=Mfn
	document.EnviarFrm.Opcion.value=\"editar\"
	document.EnviarFrm.submit()

}

function Delete(Mfn){
		if (xEliminar==\"\"){
			alert(\"".$msgstr["confirmdel"]."\")
			xEliminar=\"1\"
			Mfn_eliminar=Mfn
		}else{
			if (Mfn_eliminar!=Mfn){
				alert(\"".$msgstr["mfndelchanged"]."\")
				xEliminar=\"\"
                return
			}
			xEliminar=\"\"
			document.eliminar.Mfn.value=Mfn
			document.eliminar.submit()
		}
	}
</script>
";
echo "<body>\n";
include("../common/institutional_info.php");
$arrHttp["base"]="purchaseorder";
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";

// Se ubican todas las solicitudes que estén pendientes (STATUS=0)
// se asigna el formato correspondiente a la clave de clasificación
// se lee el título de las columnas de la tabla
$index="pv_order.pft";
$tit="pv_order_tit.tab";
$Formato_o=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/$index" ;
$tit_o=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/$tit";
if (isset($Formato)) $Formato=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/$index";
if (isset($Formato)){
	echo $msgstr["missing"] ." $Formato";
	die;
}
if (!file_exists($tit_o)) $tit_o=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/$tit" ;
if (!file_exists($tit_o)){
	echo $msgstr["missing"] ." $tit_o";

}
$fp=file($tit_o);
$tit_tab=implode("",$fp);
$Formato_order=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/pv_order.pft" ;
if (!file_exists($Formato_order)) $Formato_order=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/pv_order.pft" ;
$Formato="@$Formato_order,/";
$Expresion="";
$query = "&base=".$arrHttp["base"]."&cipar=$db_path"."par/".$arrHttp["base"].".par"."&from=1&Formato=$Formato&Opcion=buscar&Expresion=PO_$";
$IsisScript=$xWxis."imprime.xis";
include("../common/wxis_llamar.php");
$recom=array();
$ix=-1;
foreach ($contenido as $value){
	$value=trim($value);
	if ($value!="")	{
		$ix=$ix+1;
		$s=explode('|',$value);
		$key=$s[0].$ix;
		$recom[$key]=$value;
	}



}
ksort($recom);
?>
<script src=../dataentry/js/lr_trim.js></script>
<script>
document.onkeypress =
  function (evt) {
    var c = document.layers ? evt.which
            : document.all ? event.keyCode
            : evt.keyCode;
	if (c==13) EnviarForma("")
    return true;
  }
function EnviarForma(){
	sel="N"
	if (ncheck==0){
		if (document.order.oc.checked){
			alert(document.order.oc.value)
			sel="S"
		}
	}else{
		Mfn=""
		for (i=0;i<ncheck;i++){
			if (document.order.oc[i].checked){
				Mfn+=document.order.oc[i].value+"\n"
				sel="S"
			}
		}
	}
	if (sel=="N"){
		alert("<?php echo $msgstr["err_order"];?>")
		return
	}
	document.order.Mfn_sel.value=Mfn
	document.order.submit()
}

</script>
<?php

?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><i class="fa fa-inbox fa-2x" aria-hidden="true"></i>   <label><?php echo $msgstr["purchase"].": ".$msgstr["pending"];?></label></h2>
	</div>
	<div class="actions">
	<?php include("order_menu.php");?>
	</div>
	
</div>
<br><br>

<div class="middle form">
	<div class="formContent">
	<table class="table table-striped">
		<tr>

<?php
// se imprime la lista de recomendaciones pendientes
	$t=explode('|',$tit_tab);
	
	foreach ($t as $v)  echo "<th>".$v."</th>";
    $ixelem=0;
	foreach ($recom as $value){

		$r=explode('|',$value);
//		if ($r[8]=="OK")    // Se verifica si ya existe una órden para ese proveedor y ese objeto
//			$check="NO";
//		else
			$check="SI";
		$ix1=-1;
		if ($check=="SI"){
			echo "\n<tr>";
			foreach ($r as $cell){
				$ix1=$ix1+1;
				if ($ix1==1){
					echo "<td nowrap><a href=javascript:Editar($cell) class=\"btn btn-warning\"><i class=\"fa fa-pencil-square-o\"></i></a>
					<a href=javascript:Delete($cell) class=\"btn btn-warning\"><i class=\"fa fa-trash\" ></i></a>
					</td>";
				}
				if ($ix1>1){
					echo "<td>$cell</td>";
				}
			}
        }
	}
?>
</table>
</div>
	</div>
</div>
<form name="EnviarFrm" method="post" action="pending_order_ex.php">
<input type="hidden" name="base" value="purchaseorder">
<input type="hidden" name="Mfn" value="">
<input type="hidden" name="Opcion" value="">
<input type="hidden" name="encabezado" value="S">

</form>
<?php echo "<script>ncheck=$ixelem</script>\n" ;?>
<?php include("../common/footer.php");
echo "</body></html>" ;
?>
<form name="eliminar" method="post" action="../dataentry/eliminar_registro.php">
 <input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
 <input type="hidden" name="retorno" value="../acquisitions/pending_order.php">
 <input type="hidden" name="Mfn">
</form>