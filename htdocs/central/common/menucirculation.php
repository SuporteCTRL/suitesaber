<?php
//if (file_exists($db_path."logtrans/data/logtrans.mst") and $_SESSION["MODULO"]!="loan"){
//	include("../circulation/grabar_log.php");
//	$datos_trans["operador"]=$_SESSION["login"];
//	GrabarLog("P",$datos_trans,$Wxis,$xWxis,$wxisUrl,$db_path);

//}
$_SESSION["MODULO"]="catalog";
global $arrHttp,$msgstr,$db_path,$valortag,$lista_bases;
?>

<li>
    <a><i class="fa fa-database"></i><?php echo $msgstr["trans"]?><span class="fa fa-chevron-down"></span></a>	
 	<ul class="nav child_menu">
					
<?php

if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_LOAN"])){
?>
						<li>
						 <a href="../circulation/prestar.php?encabezado=s" target="content" class="menuButton">
						   <?php echo $msgstr["loan"]?></a></li>
<?php
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_LOAN"])){
	if (!isset($reserve_active) or isset($reserve_active) and $reserve_active=="Y"){
?>
						<li>
						<a href="../circulation/estado_de_cuenta.php?encabezado=s&reserve=S" target="content" class="menuButton">
							<?php echo $msgstr["reserve"]?></li></a>
<?php
	}
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_RETURN"])){
?>
						<li>
						<a href="../circulation/devolver.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["return"]?></a></li>
<?php
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_RENEW"])){
?>
						<li>
						<a href="../circulation/renovar.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["renew"]?></a></li>
<?php
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_SALA"])){
?>
						<li>
						<a href="../circulation/sala.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["sala"]?></a></li>
<?php }
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_SUSPEND"])){
?>
						<li>
						<a href="../circulation/sanctions.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["suspend"]."/".$msgstr["fine"]?> </a></li>
<?php
 }
 ?>
						<li>
						<a href="../circulation/situacion_de_un_objeto.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["ecobj"]?></a></li>
						<li>	
						<a href="../circulation/estado_de_cuenta.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["statment"]?> </a></li>
						<li>	
                        <a href="../circulation/borrower_history.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["bo_history"]?> </a></li>
				<!--		<a href="circulation/item_history.php?encabezado=s" class="menuButton newButton">
							<img src="images/mainBox_iconBorder.gif" alt="" title="" />
							<span><strong><?php echo $msgstr["co_history"]?></strong></span>
						</a>  -->
						<li>
						<a href="../output_circulation/menu.php" target="content" class="menuButton">
							<?php echo $msgstr["reports"]?>
						</a></li>


	</ul>
</li>	

<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCDATABASES"])){
?>
	
<li>
    <a><i class="fa fa-database"></i><?php echo $msgstr["basedatos"]?><span class="fa fa-chevron-down"></span></a>	
 	<ul class="nav child_menu">			
					
					    <li>
						<a href="../dataentry/browse.php?base=users&modulo=loan" target="content" class="menuButton">
							<?php echo $msgstr["users"]?>
						</a></li>

						<li>
						<a href="../dataentry/browse.php?base=trans&modulo=loan" target="content" class="menuButton">
							<?php echo $msgstr["trans"]?>
						</a></li>
						<li>
						<a href="../dataentry/browse.php?base=suspml&modulo=loan" target="content" class="menuButton">
							<?php echo $msgstr["suspen"]."/".$msgstr["multas"]?>
						</a></li>
<?php
	if (!isset($reserve_active) or isset($reserve_active) and $reserve_active=="Y"){
?>
						<li>
						<a href="../dataentry/browse.php?base=reserve&modulo=loan" target="content" class="menuButton">
							<?php echo $msgstr["reservas"]?>
						</a></li>
<?php
 }
  ?>
					
<?php
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCFG"])
	or isset($_SESSION["permiso"]["CIRC_CIRCREPORTS"]) or isset($_SESSION["permiso"]["CIRC_CIRCSTAT"])){
?>

            <?php echo $msgstr["admin"]?>
<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_CIRADMIN"])){
?>                      <!--a href="javascript:VerificarInicializacion()" class="menuButton databaseButton" on>
						<a href="../circulation/menu_mantenimiento.php" class="menuButton databaseButton" on>
							<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
							<span><strong><?php echo $msgstr["basedatos"]?></strong></span>
						</a-->
<?php
}

if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCFG"])){
?>
						<li>
						<a href="../circulation/configure_menu.php?encabezado=s" target="content" class="menuButton">
							<?php echo $msgstr["configure"]?></a></li>
<?php
}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCALL"]) or isset($_SESSION["permiso"]["CIRC_CIRCSTAT"])){
?>
			<li>
			<a href="../statistics/tables_generate.php?base=users&encabezado=s" target="content" class="menuButton">
				<?php echo $msgstr["stat_users"]?>
			</a></li>
			<li>
			<a href="../statistics/tables_generate.php?base=trans&encabezado=s" target="content" class="menuButton">
				<?php echo $msgstr["stat_trans"]?> </a></li>
			<li>	
			<a href="../statistics/tables_generate.php?base=suspml&encabezado=s" target="content" class="menuButton statisticsanctionsButton">
				<?php echo $msgstr["stat_suspml"]?>
			</a></li>
	</ul>
</li>
			
<?php

}
?>
			
<?php
 }
 ?>
<script>
function VerificarInicializacion(){
	if (confirm("Quiere inicializar las transacciones de préstamo")){
		self.location.href="../circulation/initialize_trans.php?encabezado=s"
	}
}
</script>