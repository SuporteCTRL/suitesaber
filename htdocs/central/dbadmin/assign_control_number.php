<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include("../common/get_post.php");
include ("../config.php");
include ("../lang/admin.php");
include ("../lang/dbadmin.php");





function LeerArchivos($Dir,$Ext){
// se leen los archivos con la extensión .pft
$the_array = Array();
$handle = opendir($Dir);
while (false !== ($file = readdir($handle))) {
   if ($file != "." && $file != "..") {
   		if(is_file($Dir."/".$file))
		   if (substr($file,strlen($file)-4,4)==".".$Ext) $the_array[]=$file;
   }
}
closedir($handle);
return $the_array;
}

// ==================================================================================================
// INICIO DEL PROGRAMA
// ==================================================================================================

//



//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";

if (strpos($arrHttp["base"],"|")===false){

}   else{
		$ix=strpos($arrHttp["base"],'^b');
		$arrHttp["base"]=substr($arrHttp["base"],2,$ix-2);
}
if (!isset($arrHttp["Opcion"]))$arrHttp["Opcion"]="";

if (isset($arrHttp["encabezado"]))
	$encabezado="&encabezado=S";
else
	$encabezado="";

$arrHttp["login"]=$_SESSION["login"];
$arrHttp["password"]=$_SESSION["password"];

$base =$arrHttp["base"];
$cipar =$arrHttp["base"].".par";
//GET THE MAX MFN
$IsisScript=$xWxis."administrar.xis";
$query = "&base=".$arrHttp["base"] . "&cipar=$db_path"."par/".$arrHttp["base"].".par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		$tag[$a[0]]=$a[1];
	  	}
	}
}
 echo "<script> Maxmfn=".$tag["MAXMFN"]."</script>";

//GET LAST CONTROL NUMBER
$archivo=$db_path.$arrHttp["base"]."/data/control_number.cn";
if (!file_exists($archivo)){
@	$fp=fopen($archivo,"w");
@	$res=fwrite($fp,"");
@	fclose($fp);
}else{
	$fp=file($archivo);
	$last_cn=implode("",$fp);
}

include("../common/header.php");
;?>

<script language="javascript1.2" src="../dataentry/js/lr_trim.js"></script>
<script language=Javascript src=../dataentry/js/selectbox.js></script>

<script languaje=javascript>

function BorrarRango(){
	document.forma1.Mfn.value=''
	document.forma1.to.value=''
}

function EnviarForma(vp){
	de=Trim(document.forma1.Mfn.value)
  	a=Trim(document.forma1.to.value)
  	if (de!="" || a!="") {
  		Se=""
		var strValidChars = "0123456789";
		blnResult=true
   	//  test strString consists of valid characters listed above
   		for (i = 0; i < de.length; i++){
    		strChar = de.charAt(i);
    		if (strValidChars.indexOf(strChar) == -1){
    			alert("<?php echo $msgstr["especificarvaln"]?>")
	    		return
    		}
    	}
    	for (i = 0; i < a.length; i++){
    		strChar = a.charAt(i);
    		if (strValidChars.indexOf(strChar) == -1){
    			alert("<?php echo $msgstr["especificarvaln"]?>")
	    		return
    		}
    	}
    	de=Number(de)
    	a=Number(a)
    	if (de<=0 || a<=0 || de>a ||a>Maxmfn){
	    	alert("<?php echo $msgstr["numfr"]?>")
	    	return
		}
	}

  	document.forma1.submit()
}

</script>
<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["assigncn"].": ".$arrHttp["base"]?>
	</div>

	<div class="actions">

</div>




</font>
	</div>
<form name="forma1" method="post" action="assign_control_number_ex.php" onsubmit="Javascript:return false">
<input type="hidden" name="encabezado" value="s">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"]?>">
<input type="hidden" name="cipar" value="<?php echo $arrHttp["base"]?>.par">
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>\n";
?>

<div class="middle form">
	<div class="formContent">

	
		<label><?php echo $msgstr["r_recsel"]?></label>
			
		<label><?php echo $msgstr["r_mfnr"]?></label>: 
		<?php echo $msgstr["r_desde"].": <input type=text name=Mfn class=\"form-control\" value=";
		if (isset($arrHttp["to"]))
			echo $arrHttp["to"];
		else
			echo "1";
		echo ">";
		?>
		<label><?php echo $msgstr["r_hasta"]?></label>

		<input value=""
		<?php 
		if (isset($arrHttp["to"])){
			$count=$arrHttp["to"]-$arrHttp["from"]-1;
			$count= $arrHttp["to"]+$count-1;
			if ($count>$tag["MAXMFN"])
				echo $tag["MAXMFN"];
			else
				echo $count;
		}
	
		?>" 
		type="text" name="to" class="form-control">
    	<label><?php echo  $msgstr["maxmfn"]. ": " .$tag["MAXMFN"];?></label>
		<br>

		
	

		
		
		<label> Last control number:</label>
		 <?php echo $last_cn ;?> 
		<a href='Javascript:Reset()'> <?php echo $msgstr['resetcn'] ;?></a>
		<br>
		<input class="btn btn-primary" type="submit" name="enviar" value="<?php echo $msgstr["send"]?>" onClick=javascript:EnviarForma()>
		
		 <a href="javascript:BorrarRango()" class="btn btn-warning"><?php echo $msgstr["borrar"]?></a>
		
</table>
</form>

</div>
</div>
<form name="reset_nc" method="post" action="reset_control_number.php">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"]?>">
<input type="hidden" name="encabezado" value="s">
</form>
<script>function Reset(){
	document.reset_nc.submit()
}
</script>
<?php
include("../common/footer.php");
?>
</body>
</html>
