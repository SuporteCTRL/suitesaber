<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/acquisitions.php");

include("../common/get_post.php");
if(isset($_SESSION["permiso"]["ACQ_ACQALL"]))
	if (!isset($arrHttp["see_all"]))$arrHttp["see_all"]="Y";
include("../common/header.php");
$encabezado="";
echo "<script>
function Editar(Mfn){
	document.EnviarFrm.Mfn.value=Mfn
	document.EnviarFrm.Opcion.value=\"editar\"
	document.EnviarFrm.submit()

}
function Mostrar(Mfn){
	msgwin=window.open(\"../dataentry/show.php?base=".$arrHttp["base"]."&Mfn="."\"+Mfn,\"show\",\"width=600, height=600, scrollbars, resizable\")
	msgwin.focus()
}
</script>
";
echo "<body>\n";
include("../common/institutional_info.php");
$arrHttp["base"]="suggestions";
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";

// Se ubican todas las solicitudes que est�n pendientes (STATUS=0)
// se asigna el formato correspondiente a la clave de clasificaci�n
// se lee el t�tulo de las columnas de la tabla
switch($arrHttp["sort"]){
	case "TI":
		$index="ti.pft";
		$tit="ti_tit.tab";
		break;
	case "RB":
		$index="rb.pft";
		$tit="rb_tit.tab";
		break;
	case "DR":
		$index="dr.pft";
		$tit="dr_tit.tab";
		break;
	case "OP":
		$index="op.pft";
		$tit="op_tit.tab";
		break;
}
$Formato=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/$index" ;
if (!file_exists($Formato)) $Formato=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/$index" ;
$tit_tab=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/$tit";
if (!file_exists($tit_tab)) $tit_tab=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/$tit";
if (!file_exists($Formato)){
	echo $msgstr["missing"] ." $index";
	die;
}
if (!file_exists($tit_tab)){
	echo $msgstr["missing"] ." $tit";

}
$fp=file($tit_tab);
$tit_tab=implode("",$fp);
$Formato="@$Formato,/";
$Expresion="STA_0 ";
if (!isset($arrHttp["see_all"])) $Expresion.="and OPERADOR_".$_SESSION["login"];

$query = "&base=".$arrHttp["base"]."&cipar=$db_path"."par/".$arrHttp["base"].".par"."&Expresion=$Expresion&Formato=$Formato&Opcion=buscar";
$IsisScript=$xWxis."imprime.xis";
include("../common/wxis_llamar.php");
$recom=array();
$ix=-1;
foreach ($contenido as $value){
	$value=trim($value);
	if ($value!="")	{
		$ix=$ix+1;
		$s=explode('|',$value);
		while (strlen($ix)<4) $ix="0".$ix;
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
function Enviar(sort){
	url="suggestions_status.php?base=suggestions&sort="+sort
	if (document.sort.see_all.checked)
		url+="&see_all=Y"
	self.location.href=url

}
</script>
<?php

?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>  <label><?php echo $msgstr["suggestions"].": ".$msgstr["approve"]."/".$msgstr["reject"]?></label></h2>
	</div>
	<div class="actions">
		<?php include("suggestions_menu.php");?>
	</div>
	

	</div>
<form name=sort>
<div class="middle form">
	<div class="formContent">
         <?php echo $msgstr["pending_sort"]?>
		<div class="pagination">
		<a href=javascript:Enviar("TI") class="btn btn-primary"><?php echo $msgstr["title"];?></a>

		<a href=javascript:Enviar("RB") class="btn btn-warning"><?php echo $msgstr["recomby"];?></a>

		<a href=javascript:Enviar("DR") class="btn btn-info"><?php echo $msgstr["date_sug"];?></a>

		<a href=javascript:Enviar("OP") class="btn btn-danger"><?php echo $msgstr["operator"];?></a>

			<p align=right><input type=checkbox name=see_all
			<?php if (isset($arrHttp["see_all"])) echo " value=Y checked"?>><?php echo $msgstr["all_oper"]?>
		</div>
		</h5>
	<table class="table table-striped">
		<tr>

<?php
// se imprime la lista de recomendaciones pendientes
	echo "<th>&nbsp;</th>";
	$t=explode('|',$tit_tab);
	foreach ($t as $v)  echo "<th>".$v."</th>";

	foreach ($recom as $value){
		echo "\n<tr>";
		$r=explode('|',$value);
		$ix1="";
		foreach ($r as $cell){
			if ($ix1=="")
				$ix1=1;
			else
				if ($ix1==1){
					echo "<td nowrap><a href=javascript:Editar($cell)><i class=\"fa fa-pencil-square-o\"></a></i>
					<a href=javascript:Mostrar($cell)><i class=\"fa fa-search\"></a>
					</td>";
					$ix1=2;
				}else
	 				echo "<td>$cell</td>";
		}

	}
?>
</table>

</div>
	</div>
</div>
</form>
<form name="EnviarFrm" method="post" action="suggestions_status_ex.php">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"]?>">
<input type="hidden" name="Mfn" value="">
<input type="hidden" name="Opcion" value="">
<input type="hidden" name="sort" value="<?php echo $arrHttp["sort"]?>">
<input type="hidden" name="retorno" value=../acquisitions/suggestions_status.php>
<input type="hidden" name="encabezado" value="S">
<?php if (isset($arrHttp["see_all"])) echo "<input type=hidden name=see_all value=\"S\"> ";?>
</form>
<?php include("../common/footer.php");
echo "</body></html>" ;
?>