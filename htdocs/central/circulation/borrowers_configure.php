<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      borrowers_configure.php
 * @desc:      Input the configuration of the borrowers (users) database
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

function LeerPft($pft_name){
global $arrHttp,$db_path,$lang_db;
	$pft="";
	$archivo=$db_path.$arrHttp["base"]."/loans/".$_SESSION["lang"]."/$pft_name";
	if (!file_exists($archivo)) $archivo=$db_path.$arrHttp["base"]."/loans/".$lang_db."/$pft_name";
	$fp=file_exists($archivo);
	if ($fp){
		$fp=file($archivo);
		foreach ($fp as $value){
			$pft.=$value;
		}

	}
    return $pft;
}



$arrHttp["base"]="users";

$uskey="";
$archivo=$db_path.$arrHttp["base"]."/loans/".$_SESSION["lang"]."/loans_uskey.tab";
if (!file_exists($archivo)) $archivo=$db_path.$arrHttp["base"]."/loans/".$lang_db."/loans_uskey.tab";
$fp=file_exists($archivo);
$ix=0;
$uskey="";
$usname="";
$uspft="";
if ($fp){
	$fp=file($archivo);
	foreach ($fp as $value){
		$ix++;
		$value=trim($value);
		switch ($ix){
			case 1:
				$uskey=$value;
		       	break;
		 	case 2:
		 		$usname=$value;
		 		break;
			case 3:
				$uspft=$value;
				break;
		}
	}
}

$pft_uskey=LeerPft("loans_uskey.pft");
$pft_ustype=LeerPft("loans_ustype.pft");
$pft_usvig=LeerPft("loans_usvig.pft");
$pft_usdisp=LeerPft("loans_usdisp.pft");


include("../common/header.php");
?>
<script>
function Guardar(){
	ix=document.forma1.base.selectedIndex
	if (ix<1){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
	document.forma1.action="borrowers_configure_update.php"
	document.forma1.target="_self";
    document.forma1.submit()
}

function Test(){
	if (document.forma1.Mfn.value==""){
		alert("<?php echo $msgstr["test_mfn_err"]?>")
		return
	}
    msgwin_t=window.open("","TestPft","")
    msgwin_t.focus()
	document.forma1.action="borrowers_configure_test.php"
	document.forma1.target="TestPft";
	document.forma1.submit()
}
</script>
</script>
<?php
$encabezado="";
include("../common/institutional_info.php");
echo "
		<div class=\"sectionInfo\">
			<div class=\"breadcrumb\"><h2><label>".$msgstr["bconf"]." (".$arrHttp["base"].")
			</div></label></h2>
			<div class=\"actions\">\n";

				
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"])) 
echo "<font Script: borrowers_configure.php </font>";
echo "</div>
		<div class=\"middle form\">
			<div class=\"formContent\"> ";
echo "<p><h5>".$msgstr["database"].": ".$arrHttp["base"]." &nbsp; &nbsp;[<a href=../dbadmin/fst_leer.php?base=".$arrHttp["base"]." target=_blank>Open FST</a>]"."  [<a href=../dbadmin/fdt_leer.php?base=".$arrHttp["base"]." target=_blank>Open FDT</a>]"."</h5>";
echo "<form name=forma1 action=borrowers_configure.php method=post>\n";
echo "<input type=hidden name=base value=".$arrHttp["base"].">\n";
echo "

<table class=\"table\">

<tr><td><label>1. ".$msgstr["uskey"]." (loans_uskey.tab)</td><td><input class=\"form-control\" type=text name=uskey value=\"".$uskey."\"></td>

<tr><td><label>2. ".$msgstr["usname"]." (loans_uskey.tab)</td><td><input class=\"form-control\" type=text name=usname value=\"".$usname."\"></td>

<tr><td><label>3. ".$msgstr["uspft"]." (loans_uskey.tab)</td><td><input class=\"form-control\" type=text name=uspft value=\"".$uspft."\"></td>

<tr><td><label>4. ".$msgstr["pft_uskey"]." (loans_uskey.pft)</td><td><textarea class=\"form-control\" name=pft_uskey>".$pft_uskey."</textarea></td>

<tr><td><label>5. ".$msgstr["pft_ustype"] ." (loans_ustype.pft)</td><td><textarea class=\"form-control\" name=pft_ustype>".$pft_ustype."</textarea></td>

<tr><td><label>6. ".$msgstr["pft_usvig"] ." (loans_usvig.pft)</td><td><textarea class=\"form-control\" name=pft_usvig>".$pft_usvig."</textarea></td>

<tr><td><label>7. ".$msgstr["pft_usdisp"] ." (loans_usdisp.pft)</td><td><textarea class=\"form-control\" name=pft_usdisp>".$pft_usdisp."</textarea></td>
</table>
";
echo "<label> Mfn: </label> <input class=\"form-control\" type=text name=Mfn>

<br>
<a class=\"btn btn-primary\" href=javascript:Test()>".$msgstr["test"]."</a>


</form></div></div>";

echo "</body></html>" ;

?>