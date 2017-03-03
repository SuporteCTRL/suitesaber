<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
include("../lang/dbadmin.php");
include("../lang/admin.php");
include("../lang/soporte.php");
include("../common/header.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";
?>
<script src=../dataentry/js/lr_trim.js></script>
<script>

document.onkeypress =
	function (evt) {
			var c = document.layers ? evt.which
	       		: document.all ? event.keyCode
	       		: evt.keyCode;
			if (c==13) Agregar()
			return true;
	}

var valores_ac=new Array()
var valores_nac=new Array()

function LeerValores(){
	val=""
	j=-1;
	for (i=0;i<document.eacfrm.elements.length;i++){
        	tipo=document.eacfrm.elements[i].type
        	nombre=document.eacfrm.elements[i].name
        	if (nombre.substr(0,2)=="ac" || nombre.substr(0,3)=="nac"){

        		if (nombre.substr(0,2)=="ac"){
        			j++
        			valores_ac[j]= document.eacfrm.elements[i].value
        		}
        		if (nombre.substr(0,3)=="nac"){
        			valores_nac[j]= document.eacfrm.elements[i].value
        		}
        	}

 	}
}

function returnObjById( id )
{
    if (document.getElementById)
        var returnVar = document.getElementById(id);
    else if (document.all)
        var returnVar = document.all[id];
    else if (document.layers)
        var returnVar = document.layers[id];
    return returnVar;
}

function getElement(psID) {
	if(!document.all) {
		return document.getElementById(psID);

	} else {
		return document.all[psID];
	}
}

function Agregar(IdSec){
	agregar=document.eacfrm.agregar.value
    ix=0
	nuevo=""
	LeerValores()
	for (i=0;i<valores_ac.length;i++){
		nuevo+="<table class=\"table table-striped\"><td><input type=text name=\"ac"+i+"\" id=\"iac"+i+"\" value=\""+valores_ac[i]+"\" class=\"form-control\"></td><td><input type=text name=\"nac"+i+"\" id=\"inac"+i+"\" value=\""+valores_nac[i]+"\" class=\"form-control\"></td>";
		ix=i
	}
	if (agregar>0){
		for (i=1;i<=agregar;i++){
			ix++
			nuevo+="<td><input type=text name=\"ac"+ix+"\" id=\"iac"+ix+"\" value=\"\" class=\"form-control\"></td><td><input type=text name=\"nac"+ix+"\" id=\"inac"+ix+"\" value=\"\" class=\"form-control\"></td>";
		}
	}
	seccion=returnObjById( IdSec )
	seccion.innerHTML = nuevo;
}

function Enviar(){
	j=-1;
	ValorCapturado=""
	for (i=0;i<document.eacfrm.elements.length;i++){
        tipo=document.eacfrm.elements[i].type
        nombre=document.eacfrm.elements[i].name
        if (nombre.substr(0,2)=="ac" || nombre.substr(0,3)=="nac"){
        	val=""

        	if (nombre.substr(0,2)=="ac"){
        		j++
        		val=Trim( document.eacfrm.elements[i].value)
        		campo=val
        		campo1=""
        		valores_ac[j]=val
        	}
        	if (nombre.substr(0,3)=="nac"){
        		valores_nac[j]= document.eacfrm.elements[i].value
        		campo1=valores_nac[j]
        		if (val!="" && Trim(valores_nac[j]=="")){
        			alert("Línea: "+i+" Debe especificar el valor")
        			return
        		}
        	}
        	if (campo!="" && campo1!="")
        		ValorCapturado+=campo+"|"+ campo1+"\n"
        }
 	}
	document.eacfrm.ValorCapturado.value=ValorCapturado
	document.eacfrm.submit()
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
<h2><label><?php echo $msgstr["sortkeycreate"]." (".$arrHttp["base"].")" ?></label></h2>
	</div>


			
</div>

<div class="middle form">
			<div class="formContent">
<form name="eacfrm" method="post" action="sortkey_update.php" onsubmit="javascript:return false">
<?php
unset($fp);
$file=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/sort.tab";
if (!file_exists($file)) $file=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/sort.tab";
if (file_exists($file))
	$fp=file($file);
else
	$fp[]="  ";
$ix=-1;
echo "<table class=\"table table-striped\"><label><thead>";
echo "<th><label> ".$msgstr['r_desc']." </label></th> 
 	 <th><label> ".$msgstr['pftex']."</label></th></thead>";

echo "</label><div id=accent>";
foreach ($fp as $value){
	if (trim($value)!=""){
		$ix=$ix+1;
		$v=explode('|',$value);
		echo "<br><tbody>";
		echo "<td><input class=\"form-control\" name=ac$ix id=iac$ix value=\"".$v[0]."\"></td>";
		echo "<td><input class=\"form-control\" name=nac$ix id=inac$ix value=\"".$v[1]."\"></td>";
	}
}
$ix=$ix+1;
for ($i=$ix;$i<$ix+5;$i++){
	echo "<br>";
	echo "<td><input class=\"form-control\" name=ac$i id=iac$i value=\"\"></td>";
	echo "<td><input class=\"form-control\" name=nac$i id=inac$i value=\"\"></td></tbody>";
}

echo "</div><br>";


echo "<label> ".$msgstr["add"]." :</label> 
<input name=agregar class=\"form-control\"><label> ".$msgstr["lines"]."</label>";
echo " <a class=\"btn btn-primary\" href='javascript:Agregar(\"accent\")'><i class=\"fa fa-plus\" value=".$msgstr["add"]."></i></a>";

if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=s>\n";
?>
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<br>
<button type="submit" class="btn btn-primary" onclick='javascript:Enviar()''><i class="fa fa-refresh" value="<?php echo $msgstr["update"];?>"></i></button>
<input type="hidden" name="ValorCapturado">
</table>
</form>

</body>
</html>