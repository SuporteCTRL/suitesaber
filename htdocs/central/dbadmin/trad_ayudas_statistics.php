<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];


include("../lang/admin.php");
include("../lang/dbadmin.php");
include("../common/header.php");
?>
<script languaje=javascript>
function AbrirVentana(Html){
	msgwin=window.open("../documentacion/ayuda.php?help=<?php echo $lang?>/"+Html,"Ayuda","")
	msgwin.focus()
}
function Edit(Html){
	msgwin=window.open("../documentacion/edit.php?archivo=<?php echo $lang?>/"+Html,"Ayuda","")
	msgwin.focus()
}
</script>
<?php
if (isset($arrHttp["encabezado"])) {
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{	$encabezado="";}

	echo " <body>
	<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">"."<h5>".
				$msgstr["tradyudas"]."</h5>
			</div>
			<div class=\"actions\">

	";
echo "<a href=\"menu_traducir.php?$encabezado\" class=\"defaultButton backButton\">";
echo "
					<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
					<span><strong>". $msgstr["back"]."</strong></span>
				</a>";
echo "			</div>
			<div class=\"spacer\">&#160;</div>
	</div>";

 ?>
<div class="helper">
<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/trad_ayudas.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/trad_ayudas.html target=_blank>".$msgstr["edhlp"]."</a>";
echo "<font color=white>&nbsp; &nbsp; Script: trad_ayudas_estadisticas.php";
?>
</font>
	</div>
 <div class="middle form">
			<div class="formContent">


<body>
<?
echo "<table border=0>";
echo "<tr><td colspan=3><h3>".$msgstr["statistics"]."</h3></td>";
echo "<tr><td>".$msgstr["vistap"]."</td><td>". $msgstr["editar"]."</td><td></td>";

echo "<tr><td colspan=3 bgcolor=#cccccc>".$msgstr["inicio"]."</td>";
echo"<!--";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"homepage.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"homepage.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white>".$msgstr["inicio"]." ".$msgstr["admtit"]."</td>";

echo "<tr><td colspan=3 bgcolor=#cccccc>".$msgstr["database"]."</td>";

echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"toolbar_admin.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"toolbar_admin.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white>".$msgstr["admtit"]."</td>";

echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"admin.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"admin.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white>".$msgstr["createdb"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"upddef.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"updef.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white>".$msgstr["updbdef"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"fdt.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"fdt.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["fdt"]."</td>";

echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"picklist_tab.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"picklist_tab.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["termctrl"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"typeofrecs_marc.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"typeofrecs_marc.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["typeofrecords"]." (Marc)</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"fst.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"fst.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["fst"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"fmt.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"fmt.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["fmt"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"pft.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"pft.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["pft"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"typeofrecs.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"typeofrecs.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["typeofrecords"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"recval.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"recval.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".$msgstr["recval"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"asearch_schema.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"asearch_schema.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>". $msgstr["advsearch"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"databases_list.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"databases_list.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>". $msgstr["dblist"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"dbnpar.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"dbnpar.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>". $msgstr["dbnpar"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"helpfilesdb.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"helpfilesdb.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["helpdatabasefields"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"iah_edit_db.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"iah_edit_db.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["iah-conf"]."</td>";

echo "<tr><td colspan=3 bgcolor=#cccccc>". $msgstr["usuarios"]."</td>";
echo "<tr>
		<td width=50>
	   		<a href='javascript:AbrirVentana(\"assign_operators.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"assign_operators.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["assop"]."</td>
		<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"browse.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"browse.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["usuarios"]."</td>";

echo "<tr><td colspan=3 bgcolor=#cccccc>".$msgstr["database"].".&nbsp; ".$msgstr["mantenimiento"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"menu_mantenimiento.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"menu_mantenimiento.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["mantenimiento"]."</td>";


echo "<tr><td colspan=3 bgcolor=#cccccc>".$msgstr["translate"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"menu_traducir.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"menu_traducir.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["traducir"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"trad_ayudas.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"trad_ayudas.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["tradyudas"]."</td>";

echo "<tr><td colspan=3 bgcolor=#cccccc>".$msgstr["expbases"]."</td>";
echo "<tr>
	   	<td width=50>
	   		<a href='javascript:AbrirVentana(\"dirtree.html\")'><img src=../dataentry/img/preview.gif alt=\"ver\" border=0></a></td>
	   	<td><a href='javascript:Edit(\"dirtree.html\")'><img src=../dataentry/img/barEdit.png border=0 alt=\"editar\"></a></td>
				<td bgcolor=white><dd>".  $msgstr["expbases"]."</td>";
echo "-->";

echo "</table>";
echo "</center></div></div>";
include("../common/footer.php");
?>
</body>
</html>