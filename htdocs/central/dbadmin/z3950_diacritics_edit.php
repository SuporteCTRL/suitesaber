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
    ix=0;
	nuevo=""
	LeerValores()
	for (i=0;i<valores_ac.length;i++){
		nuevo+="\n<br><br> <div class=\"form-group\"><div class=\"col-md-5\"><input type=text  name=\"ac"+i+"\" id=\"iac"+i+"\" value=\""+valores_ac[i]+"\"  class=\"form-control\" ></div><div class=\"col-md-1\"><i class=\"fa fa-arrow-right\"></i></div>	<div class=\"col-md-6\"><input type=text name=\"nac"+i+"\" id=\"inac"+i+"\" value=\""+valores_nac[i]+"\"  class=\"form-control\"></div></div>";
		ix=i;
	}

	if (agregar>0){
		for (i=0;i<agregar;i++){
			ix++;
			nuevo+="\n<br><br> <div class=\"form-group\"><div class=\"col-md-5\"><input type=text name=\"ac"+ix+"\" id=\"iac"+ix+"\" value=\"\" class=\"form-control\"></div><div class=\"col-md-1\"><i class=\"fa fa-arrow-right\"></i></div>	<div class=\"col-md-6\"><input type=text name=\"nac"+ix+"\" id=\"inac"+ix+"\" value=\"\"  class=\"form-control\"></div></div>";
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
        		if (campo!="" && Trim(campo1)=="" || campo=="" && Trim(campo1)!=""){
        			alert("<?php echo $msgstr["ft_l"]?>"+": "+campo+"/"+campo1+" <?php echo $msgstr["specvalue"];?>")
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

<h2><label><?php echo $msgstr["z3950"].". ".$msgstr["z3950_diacritics"] ;?></label></h2>


	



<form name="eacfrm" method="post" action="z3950_diacritics_update.php" onsubmit="javascript:return false" >
<?php
unset($fp);
$file=$db_path."cnv/marc-8_to_ansi.tab";
if (file_exists($file))
	$fp=file($file);
else
	$fp[]="  ";
$ix=-1;
echo "<div id=accent>";
echo "Marc-8 &nbsp; &nbsp; ANSI";
foreach ($fp as $value){
	if (trim($value)!=""){
		$ix=$ix+1;
		$v=explode(" ",$value);
?>
<br><br>
 <div class="form-group">
	<div class="col-md-5">
		<input type="text" class="form-control" name="<?php echo "ac".$ix; ?>" id="<?php echo "iac".$ix?>" value="<?php echo $v[0] ?>">
	</div>
	<div class="col-md-1">
		<i class="fa fa-arrow-right"></i>
	</div>
	<div class="col-md-6">
		<input type="text" class="form-control" name="<?php echo "nac".$ix; ?>" id="<?php echo "inac".$ix?>" value="<?php echo $v[1]?>">
	</div>
</div>	

<?php
	}
}
$ix=$ix+1;
for ($i=$ix;$i<$ix+1;$i++){
?>
<br><br>
 <div class="form-group">
	<div class="col-md-5">
		<input type="text" class="form-control" name="<?php echo "ac".$ix; ?>" id="<?php echo "iac".$ix?>" >
	</div>
	<div class="col-md-1">
		<i class="fa fa-arrow-right"></i>
	</div>
	<div class="col-md-6">
		<input type="text" class="form-control" name="<?php echo "nac".$ix; ?>" id="<?php echo "inac".$ix?>" >
	</div>
</div>	
</div>
<?php
}
?>
				<br><br>
				<div class="form-group form-inline">
				<label>
					<?php echo $msgstr["add"];?>
				</label>
					<input type="number" name="agregar" class="form-control"> 
				<label>
				<?php echo $msgstr["lines"];?>				
				</label>
				<a class="btn btn-success" href="javascript:Agregar('accent')" >Adicionar</a>
				
				<?php
				if (isset($arrHttp["encabezado"]))
					echo "<input  type=hidden name=encabezado value=s>\n";
				?>
				</div>

			<input class="btn btn-primary" type="submit" value="<?php echo $msgstr["update"]?>" onclick="javascript:Enviar()">
			<input type="hidden" name="ValorCapturado">

		</form>
	</body>

</html>