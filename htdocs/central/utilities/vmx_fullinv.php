<?php
set_time_limit(0);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/soporte.php");
include("../lang/admin.php");
include("../config.php");
include("../common/header.php");
echo "<script src=../dataentry/js/lr_trim.js></script>";

function Confirmar(){
global $msgstr;
	echo "<input type=hidden name=confirmar>\n";
	echo "<h4>".$msgstr["mnt_gli"]."</h4>";
	echo "<input type=button name=continuar value=\"".$msgstr["continuar"]."\" onclick=Confirmar()>";
	echo "&nbsp; &nbsp;<input type=button name=cancelar value=\"".$msgstr["cancelar"]."\" onclick=Regresar()>";
	echo "</form></body></html>";

}

?>
<script>
function OpenWindow(){
	msgwin=window.open("","test","width=800,height=200");
	msgwin.focus()
}

function Confirmar(){
	document.maintenance.confirmar.value="OK";
	document.getElementById('loading').style.display='block';
	document.maintenance.submit()
}

function Regresar(){
<?php
if (isset($arrHttp["encabezado"]))
	echo "document.maintenance.action=\"../dbadmin/menu_mantenimiento.php\"\n";
else
	echo "document.maintenance.action=\"javascript:self.close()\"\n";
?>
	document.maintenance.submit()
}
</script>
<?php
echo "<body>\n";
if (isset($arrHttp["encabezado"])) include("../common/institutional_info.php");
$base=$arrHttp["base"];
$bd=$db_path.$base;
$uctab="";
$actab="";
$cipar="";
if (file_exists($bd."/data/stw.tab"))
	$stw=" stw=@".$bd."/data/stw.tab";
else
	if (file_exists($db_path."stw.tab"))
		$stw=" stw=@".$db_path."stw.tab";
	else
		$stw="";

if (file_exists($bd."/data/$base.cip"))
	$cipar=" cipar=".$bd."/data/$base.cip";
else
	if (file_exists($db_path."$base.cip"))
		$cipar=" cipar=".$db_path."$base.cip";
	else
		$ciapar="";

if (!file_exists($mx_path)){
	echo $mx_path.": ".$msgstr["misfile"];
	die;
}

if (file_exists($db_path."cipar.par")){

}else{
	if (file_exists($db_path.$arrHttp["base"]."/data/isisuc.tab")){
		$uctab=$db_path.$arrHttp["base"]."/data/isisuc.tab";
	}else{
		if (file_exists($db_path."isisuc.tab"))
			$uctab=$db_path."isisuc.tab";
	}
	if ($uctab=="")  $uctab="ansi";


	if (file_exists($db_path.$arrHttp["base"]."/data/isisac.tab")){
		$actab=$db_path.$arrHttp["base"]."/data/isisac.tab";
	}else{
		if (file_exists($db_path."isisuc.tab"))
			$actab=$db_path."isisac.tab";
	}
	if ($actab=="")  $actab="ansi";
}

$parameters= "Command line:<br>";
$parameters.= "database: ".$bd."/data/".$base."<br>";
$parameters.= "fst: @".$bd."/data/".$base.".fst<br>";
$parameters.= "mx: $mx_path"." <a href=mx_test.php target=test onclick=OpenWindow()>Test</a><br>";
if ($stw!="") $parameters.= "stw: $stw<br>";
if ($uctab!="") $parameters.= "uctab: $uctab<br>";
if ($uctab!="") $parameters.= "actab: $actab<br>";
if ($cipar!="") $parameters.= "cipar: $cipar<br>";
//' 2>&1'.  Para que muestre todo el resultado



echo "
	<div class=\"sectionInfo\">
	----------
			<div class=\"breadcrumb\">".
				$msgstr["mnt_gli"]. ": " . $arrHttp["base"]."
			</div>
			<div class=\"actions\">

	";
if (isset($arrHttp["encabezado"])){
	echo "<a href=\"../dbadmin/menu_mantenimiento.php?base=".$arrHttp["base"]."&encabezado=S\" class=\"defaultButton backButton\">";
echo "<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
	<span><strong>". $msgstr["back"]."</strong></span></a>";
}
echo "</div>
	<div class=\"spacer\">&#160;</div>
	</div>";
?>
<div class="helper">
	<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/menu_mantenimiento.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
 	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/menu_mantenimiento.html target=_blank>".$msgstr["edhlp"]."</a>";
echo "&nbsp; &nbsp; <a href=http://abcdwiki.net/wiki/es/index.php?title=Generar_lista_invertida_utilizando_el_MX target=_blank>AbcdWiki</a>";
echo "<font color=white>&nbsp; &nbsp; Script: utilities/vmx_fullinv.php";
?>
</font>
</div>
<div id="loading">
  <img id="loading-image" src="../dataentry/img/preloader.gif" alt="Loading..." />
</div>
<div class="middle form">
	<div class="formContent">
<form name=maintenance>

<?php
foreach ($_REQUEST as $var=>$value){
	echo "<input type=hidden name=$var value=\"$value\">\n";
}
$strINV=$mx_path."  ".$bd."/data/".$base. " $cipar fst=@".$bd."/data/".$base.".fst uctab=$uctab actab=$actab $stw fullinv=".$bd."/data/".$base." -all now tell=100";
echo "<font face=courier size=2>".$parameters."<hr>";
echo "Query: $strINV"."</font><br>";
if (!isset($arrHttp["confirmar"]) or (isset($arrHttp["confirmar"]) and $arrHttp["confirmar"]!="OK")){
	Confirmar();
	die;
}

exec($strINV, $output,$t);
$straux="";
for($i=0;$i<count($output);$i++)
{
$straux.=$output[$i]."<br>";
}
?>
<table cellspacing=5 align=center>
	<tr>
		<td>

		<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
             <br>

          <?php

		  ?>
           <br>
			<?php

			if($straux!="")
echo ("<h3>process Output: ".$straux."<br>process Finished OK</h3><br>");
else
echo ("<h2>Out: <br>process NOT EXECUTED</h2><br>");
if($base=="")
{
echo"NO database selected";
}
?></li>


			</ul>

		</td>
</table></form>
<form name=admin method=post action=administrar_ex.php onSubmit="Javascript:return false">
<input type=hidden name=base>
<input type=hidden name=cipar>
<input type=hidden name=Opcion>
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>"?>
</form>
</div>
</div>
<?
include("../common/footer.php");
echo "</body></html>";
?>

