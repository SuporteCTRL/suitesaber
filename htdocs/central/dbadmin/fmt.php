<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
global  $arrHttp;

// ==================================================================================================
// INICIO DEL PROGRAMA
// ==================================================================================================

//
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
$base =$arrHttp["base"];
$cipar =$arrHttp["base"].".par";

//foreach ($arrHttp as $key => $value) echo "$key = $value <br>";
// se lee el archivo mm.fdt
$archivo=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/".$arrHttp["base"].".fdt";
if (file_exists($archivo))
	$fpTm=file($archivo);
else
	$fpTm=file($db_path.$arrHttp["base"]."/def/".$lang_db."/".$arrHttp["base"].".fdt");
foreach ($fpTm as $linea){
	if (trim($linea)!="") {
		$Fdt[]=$linea;
	}
}
//sort($Fdt);
if (isset($arrHttp["fmt_name"])) {
	if (file_exists($db_path.$base."/def/".$_SESSION["lang"]."/".$arrHttp["fmt_name"].".fmt"))
    	$fp = file($db_path.$base."/def/".$_SESSION["lang"]."/".$arrHttp["fmt_name"].".fmt");
    else
		$fp = file($db_path.$base."/def/".$lang_db."/".$arrHttp["fmt_name"].".fmt");
	$arrHttp["tagsel"]="";
	foreach($fp as $linea){
		if (trim($linea)!=""){
			$t=explode('|',$linea);
			$tag_s[trim($t[1])]=trim($linea);
		}
	}
}
if (isset($arrHttp["encabezado"]))
	$encabezado="&encabezado=S";
else
	$encabezado="";
include("../common/header.php");

?>

<script language="javascript1.2" src="../dataentry/js/lr_trim.js"></script>
<script language=Javascript src=../dataentry/js/selectbox.js></script>
<style type=text/css>
div#editformat{
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
div#generateformat{
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
</style>
<script languaje=javascript>

function AbrirVentana(Archivo){
	xDir="<?php echo $xSlphp.'ayudas/'?>"
	msgwin=window.open(xDir+Archivo,"Ayuda","menu=no, resizable,scrollbars")
	msgwin.focus()
}

function Genera_Fmt(){
  	formato=""
  	for (i=0;i<document.forma1.list21.options.length;i++){
	    campo=document.forma1.list21.options[i].value
	    if (document.forma1.link_fdt.checked){
	    	c=campo.split('|')
	    	tipo=campo[0]
	    	if (tipo=='H' || tipo=='L' || tipo=='S'){

	    	}else{
	    		c[18]=1
	    		campo=""
	    		for (j=0;j<c.length;j++){
	    			campo+= c[j]
	    			if (j!=c.length-1)
	    				campo+="|"
	    		}
	    	}
	  	}
	    formato+=campo+"\n"
	}
    return formato
}

function Preview(){
	formato=Genera_Fmt()
	if (formato=="" ){
	  	alert("<?php echo $msgstr["selfieldsfmt"]?>")
	  	return
	}
	var width = screen.availWidth;
    var height = screen.availHeight
	msgwin=window.open("","test_fmt","width="+width+", height="+height+" resizable=yes, scrollbars=yes, menu=yes")
	msgwin.document.close()
    msgwin.focus()
	document.preview.fmt.value=escape(formato)
	document.preview.submit()

}

function GenerarFormato(){
	formato=Genera_Fmt()
	if (formato=="" ){
	  	alert("<?php echo $msgstr["selfieldsfmt"]?>")
	  	return
	}
	document.forma1.wks.value=formato
	if (Trim(document.forma1.nombre.value)==""){
		alert("<?php echo $msgstr["misfilen"]?>")
		return
	}
	if (Trim(document.forma1.descripcion.value)==""){
		alert("<?php echo $msgstr["misformatd"]?>")
		return
	}
	fn=document.forma1.nombre.value
	bool=  /^[a-z][\w]+$/i.test(fn)
 	if (bool){

   	}
   	else {
      	alert("<?php echo $msgstr["errfilename"]?>");
      	return
   	}
	document.forma1.submit()
}
function EditarFormato(){
	if (document.forma1.fmt.selectedIndex==0 || document.forma1.fmt.selectedIndex==-1){
		alert("<?php echo $msgstr["selpfted"]?>")
		return
	}
	document.getElementById('loading').style.display='block';
	ix=document.forma1.fmt.selectedIndex
	fmt=document.forma1.fmt.options[ix].value
	fmt=fmt.split('|')
    document.forma1.fmt_name.value=fmt[0]
    document.forma1.fmt_desc.value=document.forma1.fmt.options[ix].text
    document.forma1.action="fdt.php"
    document.forma1.submit()
}

function CopiarFormato(){
	if (document.forma1.fmt.selectedIndex==0 || document.forma1.fmt.selectedIndex==-1){
		alert("<?php echo $msgstr["selpfted"]?>")
		return
	}
	ix=document.forma1.fmt.selectedIndex
	fmt=document.forma1.fmt.options[ix].value
	fmt=fmt.split('|')
    document.forma1.fmt_name.value=fmt[0]
    document.forma1.fmt_desc.value=document.forma1.fmt.options[ix].text
    document.forma1.action="fmt_saveas.php"
    document.forma1.Opcion.value="saveas";
    document.forma1.submit()
}

function EliminarFormato(){
	if (document.forma1.fmt.selectedIndex==0 || document.forma1.fmt.selectedIndex==-1){
		alert("<?php echo $msgstr["selpftdel"]?>")
		return
	}
	ix=document.forma1.fmt.selectedIndex
	fmt=document.forma1.fmt.options[ix].value
	fmt=fmt.split('|')
    document.frmdelete.fmt.value=fmt[0]
    document.frmdelete.path.value="def"
    document.frmdelete.submit()
}

</script>
<body>
<div id="loading">
  <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>

</div>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}
?>
<div class="sectionInfo">
			<div class="breadcrumb">
				<?php echo $msgstr["credfmt"].": ".$arrHttp["base"]?>
			</div>

			<div class="actions">
<?php if ($arrHttp["Opcion"]=="new"){
				echo "<a href=\"../common/inicio.php?reinicio=s\" class=\"defaultButton cancelButton\">";
	}else{
		       echo "<a href=\"menu_modificardb.php?base=".$arrHttp["base"]."$encabezado\" class=\"defaultButton cancelButton\">";
	}
?>
					
				</a>
			</div>
			
</div>
<form name="forma1" method="post" action="fmt_update.php" onsubmit="Javascript:return false" >
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<input type="hidden" name="cipar" value="<?php echo $arrHttp["cipar"];?>">
<input type="hidden" name="tagsel">
<input type="hidden" name="Opcion">
<input type="hidden" name="wks">
<input type="hidden" name="fmt_name">
<input type="hidden" name="fmt_desc">
<input type="hidden" name="ret_script" value="fmt.php">
<?php if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=s>\n"?>
 <script type="text/javascript">


</script>

<div class="helper">

<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<font> Script: fmt.php";
unset($fp);
$archivo=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/"."formatos.wks";
?></font>
	</div>
<div class="middle form">
		
  <label> <?php echo $msgstr["selfmt"];?></label>
   <select class="form-control" name="fmt">
    <option value=""> </option>
<?php

if (!file_exists($archivo)) $archivo = $db_path.$arrHttp["base"]."/def/".$lang_db."/"."formatos.wks";
if (file_exists($archivo)){
	$fp=file($archivo);
	if (isset($fp)) {
		foreach($fp as $linea){
			//echo "***$linea<br>";
			if (trim($linea)!="") {
				$linea=trim($linea);
				$l=explode('|',$linea);
				$cod=trim($l[0]);
				$nom=trim($l[1]);
				$oper="|";
				if (isset($l[2])) $oper.=$l[2];
				echo "<option value='$cod$oper'>$nom</option>\n";
			}
		}

	}
}
?>
    </select> 
    <br>
  <a class="btn btn-warning" href=javascript:EditarFormato()><i class="fa fa-pencil" value="<?php echo $msgstr['edit'];?>"></i></a><a class="btn btn-danger" href=javascript:EliminarFormato()><i class="fa fa-trash" value="<?php echo $msgstr['delete']?>"></i></a> 
  <a class="btn btn-success" href=javascript:CopiarFormato()><i class="fa fa-check" value="<?php echo $msgstr['saveas']?>"></i></a>




<div id=generateformat>
  <h4><label><?php echo $msgstr["selfields"];?>:</label></h4>
	
<div class="col-md-5">
	<select name="list11" class="form-control" multiple size="20" onDblClick="moveSelectedOptions(this.form['list11'],this.form['list21'],false)">

<?php  $t=array();
 	foreach ($Fdt as $linea){
 		$linea=trim($linea);
		$t=explode('|',$linea);
		if ($t[0]!="S"){
			if ($t[0]=="H" or $t[0]=="L"){
				if (!isset($tag_s[$linea])){
					$t[1]=$t[0];
		   			echo "<option value='".trim($linea)."'>".trim($t[2])." (".trim($t[1]).")\n";
				}
			}else{
	   			$key=trim($t[1]);
	   			if (!isset($tag_s[$key])){
		   			echo "<option value='".trim($linea)."'>".trim($t[2])." (".trim($t[1]).")\n";
				}else{
					$seleccionados[$key]=$linea;
				}
			}
		}
  	}
?>
	</select>
</div>
<div class="col-md-1">
<center>
<br><br><br>
				<a href="#" class="btn btn-primary" onClick="moveSelectedOptions(document.forms[0]['list11'],document.forms[0]['list21'],false);return false;"><i class="fa fa-angle-right"></i></a><br>

				<a href="#" class="btn btn-primary" onClick="moveAllOptions(document.forms[0]['list11'],document.forms[0]['list21'],false); return false;"><i class="fa fa-angle-double-right"></i></a><br>

				<a href="#" class="btn btn-primary" onClick="moveAllOptions(document.forms[0]['list21'],document.forms[0]['list11'],false); return false;"><i class="fa fa-angle-double-left"></i></a><br>

				<a href="#" class="btn btn-primary" onClick="moveSelectedOptions(document.forms[0]['list21'],document.forms[0]['list11'],false); return false;"><i class="fa fa-angle-left"></i></a>


</div>




			<div class="col-md-5">
				<select name="list21" class="form-control" MULTIPLE SIZE="20" onDblClick="moveSelectedOptions(this.form['list21'],this.form['list11'],false)">
<?php  $t=array();
 	foreach ($tag_s as $linea){

	   	$key=trim($linea);
	   	if (isset($seleccionados[$key])){
	   		$t=explode('|',$seleccionados[$key]);
		   	echo "<option value='".trim($seleccionados[$key])."'>".$t[2]." (".trim($t[1]).")\n";
		}else{
			$t=explode('|',$key);
			if ($t[0]=="H" or $t[0]=="L") $t[1]=$t[0];
		   	echo "<option value='".$key."'>".$t[2]." (".trim($t[1]).")\n";
		}
  	}
?>
				</select>
			</div>



			<div class="col-md-1">
				<button type="button" class="btn btn-primary"  onClick="moveOptionUp(this.form['list21'])"><i class="fa fa-angle-up" value="<?php echo $msgstr['up'];?>"></i></button>
				<br><br>

				<button type="button" class="btn btn-primary" onClick="moveOptionDown(this.form['list21'])">
				<i class="fa fa-angle-down" value="<?php echo $msgstr['down'];?>"></i></button>

<br><br>
				<a class="btn btn-primary" href=javascript:Preview()><i class="fa fa-eye"></i></a>
</div>


				
		
</div>	
<div class="col-md-6">
<br><br>
	<input type="checkbox" name="link_fdt">   <?php echo $msgstr["link_fdt_msg"];?>
	<br>
	<label><?php echo $msgstr["whendone"];?></label>
	<br><br>
		<label><?php echo $msgstr["name"]?>:</label> <input class="form-control" type="text"  name="nombre"> 
		<label><?php echo $msgstr["description"];?>:</label>
		 <input type="text" class="form-control" name="descripcion"> 
<br>
		<a class="btn btn-success" href=javascript:GenerarFormato()><i class="fa fa-floppy-o" title="Salvar"></i></a>
		
</div>



<script>
<?php if ((isset($arrHttp["fmt_name"]))) {
       echo "document.forma1.nombre.value=\"".$arrHttp['fmt_name']."\"\n";
	   echo "document.forma1.descripcion.value=\"".$arrHttp['fmt_desc']."\"\n";
   }
?>
</script>
</table>
</form>
<form name="preview" action="../dataentry/fmt_test.php" target="test_fmt" method="post">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<input type="hidden" name="fmt">
</form>

<form name="frmdelete" action="fmt_delete.php" method="post">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<input type="hidden" name="path">
<input type="hidden" name="fmt">
<?php if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=s>\n"?>
</form>
<form name="assignto" action="fmt_update.php">
<input type="hidden" name="base" value="<?php echo $arrHttp['base'];?>">
<input type="hidden" name="path">
<input type="hidden" name="sel_oper">
<input type="hidden" name="fmt">
<?php if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=s>\n"?>
</form>
</center>
</div>
</div>
</div>
<?php include("../common/footer.php");?>
</body>
</html>

