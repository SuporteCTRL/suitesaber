<?php
///////////////////////////////////////////////////////////////////////////////
//
//  MODIFICA LA CONFIGURACIÓN DE LA BASE DE DATOS
//
///////////////////////////////////////////////////////////////////////////////

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
$lang=$_SESSION["lang"];
include("../common/get_post.php");
include("../config.php");

// ARCHIVOS DE LENGUAJE
include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");
include("../lang/statistics.php");
// EXTRACCIÓN DEL NOMBRE DE LA BASE DE DATOS
if (strpos($arrHttp["base"],"|")===false){

}   else{
		$ix=strpos($arrHttp["base"],'^b');
		$arrHttp["base"]=substr($arrHttp["base"],2,$ix-2);

}
$base=$arrHttp["base"];
// VERIFICACION DE LA PERMISOLOTIA
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_MODIFYDEF"]) or isset($_SESSION["permiso"][$base."_CENTRAL_MODIFYDEF"]) or isset($_SESSION["permiso"][$base."_CENTRAL_ALL"]) ){

}else{
	echo "<h2>".$msgstr["invalidright"]. " ".$base;
	die;
}



// ENCABEZAMIENTO HTML Y ARCHIVOS DE ESTILO
include("../common/header.php");

// INCLUSION DE LOS SCRIPTS
?>
<script src=../dataentry/js/lr_trim.js></script>
<script languaje=javascript>

function Update(Option){
	if (document.update_base.base.value==""){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
	switch (Option){
		case "fdt":
			document.update_base.action="fdt.php"
			document.update_base.type.value="bd"
			<?php if (isset($arrHttp["encabezado"])) echo "document.update_base.encabezado.value=\"s\"\n"?>
			break;
		case "leader":
			document.update_base.action="fdt.php"
			document.update_base.type.value="leader.fdt"
			<?php if (isset($arrHttp["encabezado"])) echo "document.update_base.encabezado.value=\"s\"\n"?>
			break;
		case "fdt_new":
			document.update_base.action="fdt_short_a.php"
			document.update_base.type.value="bd"
			<?php if (isset($arrHttp["encabezado"])) echo "document.update_base.encabezado.value=\"s\"\n"?>
			break;
		case "fst":
			document.update_base.action="fst.php"
			break;
		case "fmt":
			document.update_base.action="fmt.php"
			break;
		case "pft":
			document.update_base.action="pft.php"
			break;
		case "typeofrecs":
			document.getElementById('loading').style.display='block';
			<?php
			$archivo=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/typeofrecs.tab";
			if (!file_exists($archivo))  $archivo=$db_path.$arrHttp["base"]."/def/".$lang_db."/typeofrecs.tab";
			if (file_exists($archivo))
				$script="typeofrecs_edit.php";
			else
				$script="typeofrecs_edit.php";
			echo "\ndocument.update_base.action=\"$script\"\n";
			?>
			break;
		case "fixedfield":
			document.update_base.action="typeofrecs_marc_edit.php"
			break;
		case "fixedmarc":
			document.update_base.action="fixed_marc.php"
			break;
		case "recval":
			document.update_base.action="typeofrecs.php"
			break;
		case "bases":
			document.update_base.action="databases_list.php"
			break;
		case "par":
			document.update_base.action="editpar.php"
			break;
					break;
		case "dr_path":
			document.update_base.Opcion.value="dr_path"
			document.update_base.action="editar_abcd_def.php"
			break;
		case "search_catalog":
			document.update_base.action="advancedsearch.php"
			document.update_base.modulo.value="catalogacion"
			break;
		case "search_circula":
			document.update_base.action="advancedsearch.php"
			document.update_base.modulo.value="prestamo"
			break;
		case "IAH":
			document.update_base.action="iah_edit_db.php"
			break
		case "stats_var":
			document.update_base.action="../statistics/config_vars.php"
			break
		case "stats_tab":
			document.update_base.action="../statistics/tables_cfg.php"
			break
		case "tooltips":
			document.update_base.action="database_tooltips.php"
			break
        case "help":
        	document.update_base.action="help_ed.php"
	}
	document.update_base.submit()
}

</script>
<body>



<?php
// ENCABEZAMIENTO DE LA PÁGINA
if (isset($arrHttp["encabezado"])) {
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}



// para verificar si en la FDT tiene el campo LDR Definido y ver si se presenta el tipo de registro MARC
if (file_exists($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/".$arrHttp["base"].".fdt"))
	$fp=file($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/".$arrHttp["base"].".fdt");
else
	$fp=file($db_path.$arrHttp["base"]."/def/".$lang_db."/".$arrHttp["base"].".fdt");
$ldr="";
foreach ($fp as $value){
	$value=trim($value);
	if (trim($value)!=""){
		$fdt=explode('|',$value);
		if ($fdt[0]=="LDR"){
			$ldr="s";
			break;
		}
	}
}

// AYUDA EN CONTEXTO E IDENTIFICACIÓN DEL SCRIPT QUE SE ESTÁ EJECUTANDO
// OPCIONES DEL MENU
 ?>


			<form name="update_base" onSubmit="return false" method="post">
			<input type="hidden" name="Opcion" value="update">
			<input type="hidden" name="type" value="">
			<input type="hidden" name="modulo">
			<input type="hidden" name="base" value="<?php echo $arrHttp['base']?>">
			<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>";?>

<nav class="navbar navbar-default">
  <div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo $msgstr["updbdef"]. ": " . $arrHttp["base"];?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estrutura de campos<span class="caret"></span></a>
          <ul class="dropdown-menu">
			<li><a href="javascript:Update('fdt')"><?php echo $msgstr["fdt"]?></a></li>
			<li><a href="javascript:Update('fdt_new')"><?php echo $msgstr["fdt"]. " (".$msgstr["wosubfields"].")"?></a></li>
			<?php
// SI ES UN REGISTRO MARC SE INCLUYE LA OPCION PARA MANEJO DE LOS TIPOS DE REGISTRO DE ACUERDO AL LEADER
			if ($ldr=="s" ){ 
            ?>
				<li><a href="javascript:Update('leader')"><?php echo $msgstr["ft_ldr"];?></a></li>
				<li><a href="javascript:Update('fixedmarc')">Type of recors. Fixed field structure</a></li>
				<li><a href="javascript:Update('fixedfield')"><?php echo $msgstr["typeofrecords"]?> - Assign worksheets</a></li>
			<?php
			}
			?>
				<li><a href="javascript:Update('fmt')"><?php echo $msgstr["fmt"]?></a></li>

			<?php
			if (!isset($ldr) or $ldr!="s" )
// SI NO ES UN REGISTRO MARC SE INCLUYE EL MANEJO DE LOS TIPOS DE REGISTRO NO MARC
			    echo "<li><a href=javascript:Update(\"typeofrecs\")>".$msgstr["typeofrecords"]."</a></li>";
			?>

			<li><a href="javascript:Update('recval')"><?php echo $msgstr["recval"]?></a></li>
			<li><a href="javascript:Update('delval')"><?php echo $msgstr["delval"]?></a></li>

          </ul>
        </li>
        <li><a href="javascript:Update('pft')"><?php echo $msgstr["pft"]?></a></li>
         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configurar pesquisas<span class="caret"></span></a>
          <ul class="dropdown-menu">
				<li><a href="javascript:Update('fst')"><?php echo $msgstr["fst"]?></a></li>	
				<li><a href="javascript:Update('search_catalog')"><?php echo $msgstr["advsearch"].": ".$msgstr["catalogacion"]?></a></li>
				<li><a href="javascript:Update('search_circula')"><?php echo $msgstr["advsearch"].": ".$msgstr["prestamo"]?></a></li>
          </ul>
        </li>
         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configurar sistema<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:Update('help')"><?php echo $msgstr["helpdatabasefields"]?></a></li>
            <li><a href="javascript:Update('tooltips')"><?php echo $msgstr["database_tooltips"]?></a></li>
            <li><a href="javascript:Update('IAH')"><?php echo $msgstr["iah-conf"]?></a></li>
            <li><a href="javascript:Update('stats_var')"><?php echo $msgstr["estadisticas"]." - ".$msgstr["var_list"]?></a></li>
            <li><a href="javascript:Update('stats_tab')"><?php echo $msgstr["estadisticas"]." - ".$msgstr["tab_list"]?></a></li>
            <?php if ($_SESSION["profile"]=="adm"){
				echo "<li><a href=javascript:Update(\"dr_path\")>dr_path.def</a></li>";
			}
			?>
			<li><a href=javascript:Update("par")><?php echo $msgstr["dbnpar"]?></a></li>
          </ul>
        </li>



      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



           

		

		



           
<?php
// PIE DE PÁGINA
include("../common/footer.php");
?>
</body>
</html>
