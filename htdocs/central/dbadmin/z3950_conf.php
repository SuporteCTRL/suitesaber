<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      z3950_conf.php
 * @desc:      Configure Z39.50 client
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
include("../common/get_post.php");
include ("../config.php");

include("../lang/dbadmin.php");

include("../lang/soporte.php");
if (!isset($_SESSION["permiso"])) die;
$lang=$_SESSION["lang"];
$Permiso=$_SESSION["permiso"];
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
$sep='^';
$db=explode($sep,$arrHttp["base"]);
$db=substr($db[1],1);
include("../common/header.php");
?>
<script>
function Edit(){
	if  (document.forma1.cnv.selectedIndex<1){
		alert('<?php echo $msgstr["selcnvtb"]?>')
		return
	}
	document.enviar.action="z3950_conversion.php";
	document.enviar.Table.value=document.forma1.cnv.options[document.forma1.cnv.selectedIndex].value
	document.enviar.descr.value=document.forma1.cnv.options[document.forma1.cnv.selectedIndex].text
	document.enviar.Opcion.value="edit"
	document.enviar.submit()
}
function Delete(){
	if  (document.forma1.cnv.selectedIndex<1){
		alert('<?php echo $msgstr["selcnvtb"]?>')
		return
	}
	document.enviar.action="z3950_conversion_delete.php";
	document.enviar.Table.value=document.forma1.cnv.options[document.forma1.cnv.selectedIndex].value
	document.enviar.Opcion.value="delete"
	document.enviar.submit()
}
</script>
<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<label><?php echo $msgstr["z3950"]." (".$db.")" ?></label>
	</div>
</div>


	<a class="btn btn-primary" href=../dataentry/browse.php?base=servers&return=../dbadmin/z3950_conf.php|base=^a<?php echo $db.$encabezado?>><?php echo $msgstr["z3950_servers"]?></a></label></a>


<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $msgstr["z3950_cnv"];?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li>
    <a href=z3950_conversion.php?base=<?php echo $db.$encabezado?>><?php echo $msgstr["new"];?>
<?php
if (file_exists($db_path.$db."/def/z3950.cnv")){
	echo  "<a href=javascript:Edit()>".$msgstr["edit"]."</a> 
	<a href=javascript:Delete()>".$msgstr["delete"]."</a>";
	$fp=file($db_path.$db."/def/z3950.cnv");
	echo "<select name=cnv>
	<option value=''>\n";
	foreach ($fp as $var=>$value){
		$o=explode('|',$value);
		echo "<option value='".$o[0]."'>".$o[1]."\n";
	}
	echo "</select>";
}
?>
</li></a>
<li><a href=z3950_diacritics_edit.php?base=<?php echo $db.$encabezado;?>>
     <?php echo $msgstr["z3950_diacritics"]?>
</a></li>
  </ul>
 </div>

	
	<br><br>
	<a class="btn btn-success" href=../dataentry/z3950.php?base=<?php echo $db.$encabezado?>&test=Y target=_blank><?php echo $msgstr["test"]?></a></li>
	</ul>
	</td>
	
	</form>
 	</div>
</div>
<form name=enviar method=post>
<input type=hidden name=base value=<?php echo $db?>>
<input type=hidden name=Opcion>
<input type=hidden name=Table>
<input type=hidden name=descr>
<?php if ($encabezado!="")
echo "<input type=hidden name=encabezado value=s>\n";
?>
<form>
<?php include("../common/footer.php");?>
</body>
</html>