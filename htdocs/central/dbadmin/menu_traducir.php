<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];



include("../lang/prestamo.php");
include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");
include("../lang/acquisitions.php");
include("../lang/iah_conf.php");
include("../lang/profile.php");
include("../common/header.php");
$encabezado="";
echo "<body>\n";
if (isset($arrHttp["encabezado"])){
	$encabezado="&encabezado=s";
   	include("../common/institutional_info.php");
}else{
	$encabezado="";
}
echo "
	<div class=\"sectionInfo\">
	<div class=\"breadcrumb\">".
	$msgstr["translate"]."
	</div>
	<div class=\"actions\">\n";
if (isset($arrHttp["encabezado"])){
	echo "<a href=\"../common/inicio.php?reinicio=s";
	if (isset($arrHttp["base"]))echo "&base=".$arrHttp["base"];
	
}
echo "</div>
			<div class=\"spacer\">&#160;</div>
		</div>";
?>

<div class="middle homepage">
	<div class="mainBox" onmouseover="this.className = 'mainBox mainBoxHighlighted';" onmouseout="this.className = 'mainBox';">
		<div class="boxTop">
		
		</div>
		<div class="boxContent titleSection">
			<div class="sectionTitle">
				<h4><strong><?php echo $msgstr["traducir"]?></strong></h4>
			</div>
			<div class="sectionButtons">
				<ul>
				
<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=admin.tab<?php echo $encabezado?>" ><?php echo $msgstr["catalogacion"]?></a></li>
<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=dbadmin.tab<?php echo $encabezado?>"><<?php echo $msgstr["dbadmin"];?></a></li>

<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=soporte.tab<?php echo $encabezado?>"><?php echo $msgstr["maintenance"]?></a></li>
<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=prestamo.tab<?php echo $encabezado?>"><?php echo $msgstr["prestamo"]?></a></li>
<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=reports.tab<?php echo $encabezado?>"><?php echo $msgstr["reports"]?></a></li>
<li>				
 <a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=statistics.tab<?php echo $encabezado?>"><?php echo $msgstr["statistics"]?></a></li>
<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=acquisitions.tab<?php echo $encabezado?>" class="menuButton  databaseButton"><?php echo $msgstr["acquisitions"]?></a></li>

<li>
 <a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=iah_conf.tab<?php echo $encabezado?>" class="menuButton  databaseButton"><?php echo $msgstr["iah-conf"]?></a> </li>

<li>
<a href="translate.php?lang=<?php echo $_SESSION["lang"]?>&componente=profile.tab<?php echo $encabezado?>"><?php echo $msgstr["profiles"];?></a></li>
			</div>
			<div class="spacer">&#160;</div>
		</div>
		<div class="boxBottom">
			<div class="bbLeft">&#160;</div>
			<div class="bbRight">&#160;</div>
		</div>
	</div>

	<div class="mainBox" onmouseover="this.className = 'mainBox mainBoxHighlighted';" onmouseout="this.className = 'mainBox';">
		<div class="boxTop">
			<div class="btLeft">&#160;</div>
			<div class="btRight">&#160;</div>
		</div>
		<div class="boxContent titleSection">
			<div class="sectionTitle">
				<h4><strong><?php echo $msgstr["compare_trans"]?></strong></h4>
			</div>
			<div class="sectionButtons">
				<a href="../lang/compare_admin.php?table=admin.tab<?php echo $encabezado?>" class="menuButton  listButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["catalogacion"]?></strong></span>
				</a>

				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=dbadmin.tab<?php echo $encabezado?>" class="menuButton  databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["dbadmin"]?></strong></span>
				</a>
				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=soporte.tab<?php echo $encabezado?>" class="menuButton  utilsButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["maintenance"]?></strong></span>
				</a>
    				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=prestamo.tab<?php echo $encabezado?>" class="menuButton  importButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["prestamo"]?></strong></span>
				</a>
				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=reports.tab<?php echo $encabezado?>" class="menuButton  reportsButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["reports"]?></strong></span>
				</a>
				 <a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=statistics.tab<?php echo $encabezado?>" class="menuButton  statButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["statistics"]?></strong></span>
				</a>
				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=acquisitions.tab<?php echo $encabezado?>" class="menuButton  databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["acquisitions"]?></strong></span>
				</a>
                <a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=iah_conf.tab<?php echo $encabezado?>" class="menuButton  databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["iah-conf"]?></strong></span>
				</a>
				<a href="../lang/compare_admin.php?lang=<?php echo $_SESSION["lang"]?>&table=profile.tab<?php echo $encabezado?>" class="menuButton  userButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["profiles"]?></strong></span>
				</a>
			</div>
			<div class="spacer">&#160;</div>
		</div>
		<div class="boxBottom">
			<div class="bbLeft">&#160;</div>
			<div class="bbRight">&#160;</div>
		</div>
	</div>


	<!--div class="mainBox" onmouseover="this.className = 'mainBox mainBoxHighlighted';" onmouseout="this.className = 'mainBox';">
		<div class="boxTop">
			<div class="btLeft">&#160;</div>
			<div class="btRight">&#160;</div>
		</div>
		<div class="boxContent titleSection">
			<div class="sectionTitle">
				<h4><strong><?php echo $msgstr["tradyudas"]?></strong></h4>
			</div>
			<div class="sectionButtons">
				<a href="trad_ayudas_dataentry.php?><?php echo $encabezado?>" class="menuButton  listButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["catalogacion"]?></strong></span>
				</a>

				<a href="trad_ayudas_adm.php?<?php echo $encabezado?>" class="menuButton  databaseButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" />
					<span><strong><?php echo $msgstr["dbadmin"]?></strong></span>
				</a>
    			<a href="trad_ayudas_loan.php?lang=<?php echo $_SESSION["lang"]?>&componente=prestamo.php<?php echo $encabezado?>" class="menuButton  importButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" /
					<span><strong><?php echo $msgstr["prestamo"]?></strong></span>
				</a>
				<a href="trad_ayudas_statistics.php?lang=<?php echo $_SESSION["lang"]?>&componente=estadisticas.php<?php echo $encabezado?>" class="menuButton  statButton">
					<img src="../images/mainBox_iconBorder.gif" alt="" title="" /
					<span><strong><?php echo $msgstr["statistics"]?></strong></span>
				</a>

			</div>
			<div class="spacer">&#160;</div>
		</div>
		<div class="boxBottom">
			<div class="bbLeft">&#160;</div>
			<div class="bbRight">&#160;</div>
		</div>
	</div -->
<form name=admin method=post action=administrar_ex.php onSubmit="Javascript:return false">
<input type=hidden name=base>
<input type=hidden name=cipar>
<input type=hidden name=Opcion>
</form>
</div>
<?php include("../common/footer.php");?>

</body>
</html>
