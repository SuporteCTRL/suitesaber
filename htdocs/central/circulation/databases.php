<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      databases.php
 * @desc:      Select the bibliographic database to be configured
 * @author:    Guilda Ascencio
 * @since:     20091203
 * @version:   1.0
 *
 * == BEGIN LICENSE ==
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as
 *    published by the Free Software Foundation, either version 3 of the
 *    License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * == END LICENSE ==
*/
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");

include("../lang/prestamo.php");


//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");
?>
<script>
function Continuar(){
	ix=document.forma1.base.selectedIndex
	if (ix<1){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
    document.forma1.submit()
}
function Deshabilitar(){
	ix=document.forma1.base.selectedIndex
	if (ix<1){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
	document.forma1.action="disable_db.php"
    document.forma1.submit()
}
</script>
<?php
$encabezado="";
include("../common/institutional_info.php");
echo "
		<div class=\"sectionInfo\">
			<div class=\"breadcrumb\"><h2><label>".
				$msgstr["sourcedb"]."
			</div></label></h2>
			<div class=\"actions\">\n";

				
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
 	
echo "<font script: circulation/databases.php </font>";

echo " </div>
		<div class=\"middle form\">
			<div class=\"formContent\">";
if (file_exists($db_path."loans.dat")){
	$loans_dat=" checked";
}else{
	$loans_dat="";
}
echo "<form name=forma1 action=databases_configure.php >\n";
echo "<label>".$msgstr["loan_option"].":</label> ";

echo "<input type=radio name=loan_option value=copies><label>".$msgstr["with_copies"]." </label>";
echo "<br>";
echo "<input type=radio name=loan_option value=nocopies $loans_dat><label>".$msgstr["no_copies"]." </label>";
echo "<br><br>";
echo "<label>".$msgstr["seldbdoc"]."</label>";

echo "<br><br>";
echo "<label> ".$msgstr["database"]."</label>";
echo ": <select class=\"form-control\" name=base>
<option value=''>\n";
$fp=file($db_path."bases.dat");
$bases_p=array();
$ya_elegida="";
foreach ($fp as $value){
	$value=trim($value);
	if ($value!=""){
		$b=explode('|',$value);
		if ($b[0]=="trans" or $b[0]=="suspml" or $b[0]=="copies" or $b[0]=="users" or $b[0]=="reserve" or $b[0]=="suggestions" or $b[0]=="purchaseorder" or $b[0]=="loanobjects"){

		}else{
			$archivo="";
			if (!isset($b[2])) $b[2]="N";
			echo "<option value=".$b[0]."|".$b[2],">".$b[1]."\n";
			if (file_exists($db_path.$b[0]."/loans/".$_SESSION["lang"]."/loans_display.pft")){
				if ($ya_elegida=="")
					$ya_elegida= $b[1]." (".$b[0].")<br>";
				else
					$ya_elegida.= $b[1]." (".$b[0].")<br>";
			}
		}
	}
}
echo "</select></td>";
echo "<br><br>";
echo "<label>".$msgstr["alreadysel"].":</label> $ya_elegida";
echo "<br>";

echo "<a class=\"btn btn-primary\" href=javascript:Continuar()>".$msgstr["continue"]."</a> " ;
echo " <a class=\"btn btn-primary\" href=javascript:Deshabilitar()>".$msgstr["disable"]."</a> ";

include("../common/footer.php");
echo "</body></html>" ;

?>