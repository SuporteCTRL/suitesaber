<?php
session_start();
unset($_SESSION["Browse_Expresion"]);
$Permiso=$_SESSION["permiso"];
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
if (!isset($arrHttp["base"])) $arrHttp["base"]="";

if (strpos($arrHttp["base"],"|")===false){

}   else{
		$ix=strpos($arrHttp["base"],'^b');
		$arrHttp["base"]=substr($arrHttp["base"],2,$ix-2);
}
$db=$arrHttp["base"];
include ("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");

if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and
    !isset($_SESSION["permiso"]["CENTRAL_DBUTILS"]) and
    !isset($_SESSION["permiso"][$db."_CENTRAL_ALL"]) and
    !isset($_SESSION["permiso"][$db."_CENTRAL_DBUTILS"])
    ){
	echo "<script>
	      alert('".$msgstr["invalidright"]."')
          history.back();
          </script>";
    die;
}


//SEE IF THE DATABASE IS LINKED TO COPIES
$copies="N";
$fp=file($db_path."bases.dat");
foreach ($fp as $value){
	$value=trim($value);
	$x=explode("|",$value);
	if ($x[0]==$arrHttp["base"]){
		if (isset($x[2]) and $x[2]=="Y"){
			$copies="Y";
		}
		break;
	}
}
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");
?>

<script src=../dataentry/js/lr_trim.js></script>


<script language="javascript" type="text/javascript">

function EnviarForma(Opcion,Mensaje){

	base="<?php echo $arrHttp["base"]?>"
	if (Opcion=="eliminarbd" || Opcion=="inicializar"){
		if (base==""){
			alert("<?php echo $msgstr["seldb"]?>")
			return
		}

	}
	document.admin.base.value=base
	document.admin.cipar.value=base+".par"
	document.admin.action=""
	document.admin.target=""
	document.admin.Opcion.value=Opcion
	switch (Opcion){
	}
	document.admin.submit()
}

</script>
<body>
<?php

include("../common/institutional_info.php");
echo "
	<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">".
				$msgstr["more_utils"]. ": " . $arrHttp["base"]."
			</div>
			<div class=\"actions\">

	";

	
?>
<div class="helper">
	
<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
 	
echo " <a href='http://abcdwiki.net/wiki/es/index.php?title=Más_utilitarios' target=_blank>abcdwiki.net</a>";
echo "<font color=white>&nbsp; &nbsp; Script: utilities/more_utils.php";
?>
</font>
</div>
<div class="middle form">
	<div class="formContent">
	<table cellspacing=5 width=500 align=center>
		<tr>
			<td nowrap>
             	<br>
					<ul style="font-size:12px;line-height:20px">
					</ul>
			</td>
		</tr>
	</table>
	</div>
</div>
<?php include("../common/footer.php");?>
</body>
</html>
