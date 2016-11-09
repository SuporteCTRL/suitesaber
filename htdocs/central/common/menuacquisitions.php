<?php
//$_SESSION["MODULO"]="acquisitions";
$_SESSION["MODULO"]="catalog";
global $arrHttp,$msgstr,$db_path,$valortag,$lista_bases;
	include ("../lang/acquisitions.php");
?>
			


<li>
    <a><i class="fa fa-database"></i><?php echo $msgstr["suggestions"]?><span class="fa fa-chevron-down"></span></a>	
 	<ul class="nav child_menu">
					<li>
						<a href="../acquisitions/overview.php?encabezado=s" target="content" class="defaultButton">
						<?php echo $msgstr["overview"]?></a>
					</li>	
<?php
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_NEWSUGGESTIONS"])){
?>
	<li>
	    <a href="../acquisitions/suggestions_new.php?encabezado=s&base=suggestions&cipar=suggestions.par" target="content" class="defaultButton">
					<?php echo $msgstr["newsugges"]?></a></li>
<?php }
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_APPROVREJECT"])){
?>
		<li>
		<a href="../acquisitions/suggestions_status.php?base=suggestions&cipar=suggestions.par&sort=TI&encabezado=s" target="content" class="defaultButton "><?php echo $msgstr["approve"]."/".$msgstr["reject"]?> </a></li>
<?php }
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_BIDDING"])){
?>
		<li>
		<a href="../acquisitions/bidding.php?base=suggestions&sort=DA&encabezado=s&menu=s" target="content" class="defaultButton">
							<?php echo $msgstr["bidding"]?> </a></li>
<?php }
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_DECISION"])){
?>
		<li>
		<a href="../acquisitions/decision.php?base=suggestions&sort=DA&encabezado=s&menu=s" target="content" class="defaultButton multiLine decisionButton"> <?php echo $msgstr["decision"]?></a></li>
<?php
 }
 ?>

						<?php echo $msgstr["purchase"]?>
					
<?php
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_CREATEORDER"])){
?>
		<li>
		<a href="../acquisitions/order_new_menu.php?base=suggestions&sort=PV&encabezado=s" target="content" class="defaultButton">
							<?php echo $msgstr["createorder"]?></a></li>

		<li>
		<a href="../acquisitions/order.php?base=suggestions&sort=PV&encabezado=s" target="content" class="defaultButton">
							<?php echo $msgstr["generateorder"]?></a></li>
<?php 
}
?>
		<li>
		<a href="../acquisitions/pending_order.php?base=purchaseorder&sort=PV&encabezado=s" target="content" class="defaultButton">
							<?php echo $msgstr["pendingorder"]?> </a></li>

<?php if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_RECEIVING"])){
?>
		<li>
		<a href="../acquisitions/receive_order.php?encabezado=s" target="content" class="defaultButton">
							<?php echo $msgstr["receiving"]?></a></li>
<?php 
}
?>
					
<?php
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_ACQDATABASES"])){
?>
			<?php echo $msgstr["basedatos"]?>
					
		<li>
		<a href="../dataentry/browse.php?base=suggestions&modulo=acquisitions" target="content" class="defaultButton">
							<?php echo $msgstr["suggestions"]?></a></li>
		<li>				
		<a href="../dataentry/browse.php?base=providers&modulo=acquisitions" target="content" class="defaultButton">
							<?php echo $msgstr["providers"]?></a></li>
        <li>
		<a href="../dataentry/browse.php?base=purchaseorder&modulo=acquisitions" target="content" class="defaultButton">
							<?php echo $msgstr["purchase"]?></a></li>
        <li>
		<a href="../dataentry/browse.php?base=copies&modulo=acquisitions" target="content" class="defaultButton">
							<?php echo $msgstr["copies"]?></a></li>

					
<?php  }
if (isset($_SESSION["permiso"]["ACQ_ACQALL"]) or isset($_SESSION["permiso"]["ACQ_RESETCN"])){
?>             
            <?php echo $msgstr["admin"]?>
			

		<li>			
		<a href="../acquisitions/resetautoinc.php?base=suggestions" target="content" class="defaultButton">
						     <?php echo $msgstr["resetctl"]. " (".$msgstr["suggestions"].")"?></a></li>
					
<?php
 }
 ?>

 	</ul>
</li>	