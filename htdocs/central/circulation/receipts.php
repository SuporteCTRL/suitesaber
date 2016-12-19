<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include ("../config.php");
include ("../lang/dbadmin.php");
include ("../lang/prestamo.php");
include("../common/header.php");
include("../common/institutional_info.php");
$ayuda="receipts.html";
$archivo="";
$pr_loan="r_loan.pft";
$pr_return="r_return.pft";
$pr_fine="r_fine.pft";
$pr_statment="r_statment.pft";
$pr_solvency="r_solvency.pft";
$chk_loan="";
$chk_return="";
$chk_fine="";
$chk_statment="";
$chk_solvency="";
if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/receipts.lst")){
	$archivo=$db_path."trans/pfts/".$_SESSION["lang"]."/receipts.lst";
}else{
	if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/receipts.lst"))
		$archivo=$db_path."trans/pfts/".$lang_db."/receipts.lst";
}
if ($archivo!=""){
	$fp=file($archivo);
	foreach ($fp as $value){
		$value=trim($value);
		switch($value){
			case "pr_loan":
				$chk_loan=" checked";
				break;
			case "pr_return":
				$chk_return=" checked";
				break;
			case "pr_fine":
				$chk_fine=" checked";
				break;
			case "pr_statment":
				$chk_statment=" checked";
				break;
			case "pr_solvency":
				$chk_solvency=" checked";
				break;
		}
	}
}
?>
<script language="javascript1.2" src="../dataentry/js/lr_trim.js"></script>
<script>
function CheckName(fn,Ctrl){
	res= /^[a-z][\w]+$/i.test(fn)
	if (res==false){
		alert("<?php echo $msgstr["errfilename"]?>");
		Ctrl.focus()
		return false
	}
}
function Guardar(){

	document.receipts.submit()
}

function Editar(Pft){
	ix=Pft.indexOf(".pft")
	Pft=Pft.substr(0,ix)
	switch (Pft){
		case "r_fine":
			document.editar.base.value="suspml"
			document.editar.cipar.value="suspml.par"
			break
		default:
			document.editar.base.value="trans"
			document.editar.cipar.value="trans.par"
			break
	}
	msgwin=window.open("","editar","width=600,height=600,resizable,scrollbars")
	document.editar.archivo.value=Pft
	document.editar.submit()
	msgwin.focus()
}

</script>
<body>
<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["receipts"]?>
	</div>
	

<div class="middle form">
	<div class="formContent">
	<form name="receipts" action="receipts_ex.php" method="post">
	<table>
<?php

//echo "<tr><td>".$msgstr["receipts_select"]."</td></tr>\n";

echo "<tr><td>".$msgstr["loan"]."</td><td><input type=checkbox name=pr_loan value='$pr_loan' $chk_loan>$pr_loan";
echo "<td><a class=\"btn btn-warning\" href=javascript:Editar('$pr_loan') value=".$msgstr["edit"]."><i class=\"fa fa-pencil-square-o\"></i></a>";
echo "</td>\n";

echo "<tr><td>".$msgstr["return"]."</td><td><input type=checkbox name=pr_return value='$pr_return' $chk_return>$pr_return</td>";
echo "<td><a class=\"btn btn-warning\" href=javascript:Editar('$pr_return') value=".$msgstr["edit"]."><i class=\"fa fa-pencil-square-o\"></i></a>";
echo "</td>\n";

echo "<tr><td>".$msgstr["fine"]."</td><td><input type=checkbox name=pr_fine value='$pr_fine' $chk_fine>$pr_fine</td>";
echo "<td><a class=\"btn btn-warning\" href=javascript:Editar('$pr_fine') value=".$msgstr["edit"]."><i class=\"fa fa-pencil-square-o\"></i> </a>";
echo "</td>\n";

echo "<tr><td>".$msgstr["statment"]."</td><td><input type=checkbox name=pr_statment value='$pr_statment' $chk_statment>$pr_statment</td>";
echo "<td><a class=\"btn btn-warning\" href=javascript:Editar('$pr_statment') value=".$msgstr["edit"]."><i class=\"fa fa-pencil-square-o\"></i></a>";
echo "</td>\n";

echo "<tr><td>".$msgstr["solvency"]."</td><td><input type=checkbox name=pr_solvency value='$pr_solvency' $chk_solvency>$pr_solvency</td>";
echo "<td><a class=\"btn btn-warning\" href=javascript:Editar('$pr_solvency') value=".$msgstr["edit"]."><i class=\"fa fa-pencil-square-o\"></i> </a>";
echo "</td>";
?>
	</table>
    </form>
	</div>
</div>
</center>
<?php
include("../common/footer.php");
?>
</body>
</html>
<form name="editar" action="../dbadmin/leertxt.php" target="editar" method="post">
<input type="hidden" name="base" value="trans">
<input type="hidden" name="cipar" value="trans.par">
<input type="hidden" name="archivo">
<input type="hidden" name="desde" value="recibos">
</form>

