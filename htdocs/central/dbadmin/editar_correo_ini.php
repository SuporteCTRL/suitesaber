<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];
include("../common/header.php");
include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");
//foreach ($arrHttp as $var=>$value) echo "$var=>$value<br>";
if (!isset($_SESSION["login"])){
	echo "<script>
	      alert('".$msgstr["invalidright"]."')
          history.back();
          </script>";
    die;
}
$Permiso=$_SESSION["permiso"];
?>

<script src=../dataentry/js/lr_trim.js></script>
<script language="javascript" type="text/javascript">
function Enviar(){
	document.maintenance.submit()
}
</script>
<body >
<?php
	include("../common/institutional_info.php");
echo "
	<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">Distribución
			</div>
			<div class=\"actions\">

	";

if (!isset($arrHttp["Opcion"]) or $arrHttp["Opcion"]!=="actualizar"){

	

}
?>
<?php

echo " <font color=white>&nbsp; &nbsp; Script: dbadmin/editar_correo_ini.php";
$ini_vars=array("HOST","PORT","USERNAME","PASSWORD","FROM","FROMNAME","SUBJECT","TEST","PHPMAILER");
$ini=array();
if (file_exists($db_path."correo.ini")){
	$fp=file($db_path."correo.ini");
	foreach ($fp as $key=>$value){
		$value=trim($value);
		if ($value!=""){
			$x=explode('=',$value);
			$ini[$x[0]]=$x[1];
		}
	}
}
?>
</font>
</div>
<div class="middle">
	<div class="formContent" >
<form name="maintenance">
<input type="hidden" name="Opcion" value="actualizar">

<?php
if (!isset($arrHttp["Opcion"])){
	echo "<table class=\"table table-striped\">";
	foreach ($ini_vars as $key){
		echo "<tr>
		      <div class=\"col-md-6\">

		         <td><label>$key</label></td></div>
		         <div class=\"col-md-6\">
		         <td>
		   	";
		if ($key!="PHPMAILER"){
		    echo  "     <input class=\"form-control\" type=text name=ini_$key value='";
			if (isset($ini[$key])) echo $ini[$key];
			echo "'>\n";
		}else{
            echo "<input type=radio name=ini_$key value=phpmailer";
            if (isset($ini[$key]) and $ini[$key]=="phpmailer") echo " checked";
            echo ">\n";
            echo "<input type=radio name=ini_$key value=php";
            if (isset($ini[$key]) and $ini[$key]=="php") echo " checked";
            echo ">\n";
		}
		echo "</td></tr></div>\n";
	}
	echo "</table>";
}else{
    $fp=fopen($db_path."correo.ini","w");
    foreach ($arrHttp as $var=>$value){
    	if (substr($var,0,4)=="ini_"){
    		$tag=substr($var,4);
    		echo $var."=$value<br>";
    		fwrite($fp,$tag."=".trim($value)."\n");
    	}
    }
    fclose($fp);
}
?>
</form>
</div>
</div>
<?php include("../common/footer.php");?>
</body>
</html>
