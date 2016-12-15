<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      sala.php
 * @desc:      Ask for the item to be returned
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
include("../lang/admin.php");
include("../lang/prestamo.php");
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");
include("../circulation/scripts_circulation.php");
function LeerPft($pft_name,$base){
global $arrHttp,$db_path,$lang_db;
	$pft="";
	$archivo=$db_path.$base."/loans/".$_SESSION["lang"]."/$pft_name";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/loans/".$lang_db."/$pft_name";
	$fp=file_exists($archivo);
	if ($fp){
		$fp=file($archivo);
		foreach ($fp as $value){
			$pft.=$value;
		}

	}
    return $pft;
}

function LeerNumeroClasificacion($base){
global $db_path,$lang_db,$prefix_nc,$prefix_in;
	$prefix_nc="";
	$archivo=$db_path.$base."/loans/".$_SESSION["lang"]."/loans_conf.tab";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/loans/".$lang_db."/loans_conf.tab";
	$fp=file_exists($archivo);
	if ($fp){
		$fp=file($archivo);
		foreach ($fp as $value){
			$ix=strpos($value," ");
			$tag=trim(substr($value,0,$ix));
			switch($tag){
				case "IN": $prefix_in=trim(substr($value,$ix));
					break;
				case "NC":
					$prefix_nc=trim(substr($value,$ix));
					break;
			}
		}
	}
}
if (file_exists($db_path."loans.dat"))
	echo "<script>search_in='IN='\n";
else
    echo "<script>search_in=''\n";
echo "</script>\n";

?>
<script>

function EnviarForma(){
	if (Trim(document.sala.inventory.value)=="" ){
		alert("<?php echo $msgstr["falta"]." ".$msgstr["inventory"]?>")
		return
	}
	INV=escape(document.sala.inventory.value)
	<?php if (isset($inventory_numeric) and $inventory_numeric =="Y")
		//echo "INV=parseInt(document.inventorysearch.inventory.value,10)\n";
	?>
    document.sala.invSearch.value=INV
    document.sala.submit()
}

</script>
<?php
echo "<body>\n";
include("../common/institutional_info.php");
// ARE THE COPIES IN THE COPIES DATABASE OR IN THE BIBLIOGRAPHIC DATABASE?

if (isset($arrHttp["db_inven"])){
	$dbi=explode('|',$arrHttp["db_inven"]);
	if ($dbi[0]!="loanobjects"){
		$from_copies="N";
		$x=explode('|',$arrHttp["db_inven"]);
    	$var=LeerPft("loans_conf.tab",$x[0]);
		$prefix_in=trim($x[2]);
	}else{
		$prefix_in="IN_";
		$from_copies="Y";
	}
}else{
	$prefix_in="IN_";
	$from_copies="Y";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<?php echo $msgstr["return"]?>
	</div>
	<div class="actions">
		<?php include("submenu_prestamo.php");?>
	</div>
	

<?php 
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<font Script: circulation/sala.php </font>
	</div>";
// prestar, reservar o renovar
?>
<div class="middle list">

	<div class="searchBox">
	<form name=sala action=sala_ex.php method=post onsubmit="javascript:return false">
	<input type=hidden name=invSearch>
<?php
$sel_base="N";
if (file_exists($db_path."loans.dat")){
	$fp=file($db_path."loans.dat");
	$sel_base="S";
?>

		<br><br>
		<label for="dataBases"><?php echo $msgstr["basedatos"]?></label>
				<select name=db_inven onchange=CambiarBase()>
		<option></option>
<?php
	$xselected=" selected";
	$cuenta=0;
	foreach ($fp as $value){
		if (trim($value)!=""){
			$cuenta=$cuenta+1;
			$value=trim($value);
			$v=explode('|',$value);
			//SE LEE EL PREFIJO PARA OBTENER EL NUMERO DE INVENTARIO DE LA BASE DE DATOS
			$value=$v[0].'|'.$v[1];
			LeerNumeroClasificacion($v[0]);
			$pft_ni=LeerPft("loans_inventorynumber.pft",$v[0]);

			$signa=LeerPft("loans_cn.pft",$v[0]);
            if (isset($_SESSION["library"])) $prefix_in=$prefix_in;
			$value.="|".$prefix_in."|ifp|".urlencode($signa);
			$value.='|';
			if (isset($v[2])){
				$value.=$v[2];
			}

			if (isset($_SESSION["loans_dbinven"])){

				if ($_SESSION["loans_dbinven"]==$v[0])
					$xselected=" selected";
				else
					$xselected="";
			}
			echo "<option value='$value' $xselected>".$v[1]."</option>\n";
			$xselected="";
		}
	}

echo "
		</select>
		";
}
?>
<br><br>
		
		<label for="searchExpr">
			<?php echo $msgstr["inventory"]?>
		</label>
		
		<textarea class="form-control" name="inventory" id="inventory" value="" class="textEntry" onfocus="this.className = 'textEntry';"  onblur="this.className = 'textEntry';" /></textarea>
<br>
		<input class="btn btn-primary" type="submit" name="Enviar" value="<?php echo $msgstr["return"]?>" xclass="submitAdvanced" onclick="javascript:EnviarForma()"/>

    <p><br><br>
	</form>
	</div>
<?php
echo "</div>";
include("../common/footer.php");
echo "</body></html>" ;
?>