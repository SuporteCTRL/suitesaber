<?php

//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
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



if (strpos($arrHttp["base"],"|")===false){

}else{
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
$login=$arrHttp["login"];
$password=$arrHttp["password"];

if (isset($arrHttp["Expresion"]) and $arrHttp["Expresion"]!=""){
	$Opcion="buscar";
	$Expresion=stripslashes($arrHttp["Expresion"]);
}else{
  	$Opcion="rango";
  	$Expresion="";
}

//foreach ($arrHttp as $key => $value) echo "$key = $value <br>";
if ($arrHttp["Opcion"]!="new"){
	$pft=LeerArchivos($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/","pft");
	echo "\n<script>Dir='pfts/".$_SESSION["lang"]."/'</script>\n";
	$arrHttp["Dir"]="pfts/".$_SESSION["lang"];

	$archivo=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/".$arrHttp["base"].".fdt";
	if (file_exists($archivo)){
		$fpTm=file($archivo);
	}else{
		$archivo=$db_path.$arrHttp["base"]."/def/".$lang_db."/".$arrHttp["base"].".fdt";
		if (file_exists($archivo)){
			$fpTm=file($archivo);
		}else{
			echo $msgstr["fatal"].". ".$msgstr["misfile"].": ".$db_path.$arrHttp["base"]."/".$arrHttp["base"].".fdt";
			die;
		}
	}
}else{
	$arrHttp["Dir"]="";
	$fpTm=explode("\n",$_SESSION["FDT"]);
}
foreach ($fpTm as $linea){
	if (trim($linea)!="") {
		$t=explode('|',$linea);
		if ($t[0]!="S")
   		$Fdt[]=rtrim($linea);
	}
}

include("../common/header.php");
?>
<script language="javascript1.2" src="../dataentry/js/lr_trim.js"></script>
<script language=Javascript src=../dataentry/js/selectbox.js></script>
<style type=text/css>

td{
	font-size:12px;
	font-family:Arial;
}

div#useexformat{

	display: none;
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#createformat{
<?php if ($arrHttp["Opcion"]!="new") echo "display: none;\n"?>

	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#pftedit{
	display: none;
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#testformat{
	display: none;
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#saveformat{
	<?php if ($arrHttp["Opcion"]!="new") echo "display: none;\n"?>
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#savesearch{
	display: none;
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/styles/default.min.css"> <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>
<script languaje=javascript>

TipoFormato=""
C_Tag=Array()

//IF THE TYPE OF OUTPUT IS NOT IN COLUMN, HEADINGS ARE NOT ALLOWED
function CheckType(){
	if (document.forma1.tipof[0].checked || document.forma1.tipof[1].checked){
		alert("<?php echo $msgstr["r_noheading"]?>")
		document.forma1.pft.focus()
	}

}

function CopiarExpresion(){
	Expr=document.forma1.Expr.options[document.forma1.Expr.selectedIndex].value
	document.forma1.Expresion.value=Expr

}

function CopySortKey(){
	Sort=document.forma1.sort.options[document.forma1.sort.selectedIndex].value
	document.forma1.sortkey.value=Sort
}

function CreateSortKey(){
	msgwin=window.open("","sortkey","resizable,scrollbars, width=700,height=600")
	document.sortkey.submit()
	msgwin.focus()
}

function AbrirVentana(Archivo){
	xDir=""
	msgwin=window.open(xDir+"ayudas/"+Archivo,"Ayuda","menu=no, resizable,scrollbars")
	msgwin.focus()
}

function EsconderVentana( whichLayer ){
var elem, vis;

	if( document.getElementById ) // this is the way the standards work
		elem = document.getElementById( whichLayer );
	else if( document.all ) // this is the way old msie versions work
		elem = document.all[whichLayer];
	else if( document.layers ) // this is the way nn4 works
		elem = document.layers[whichLayer];
	vis = elem.style;
	// if the style.display value is blank we try to figure it out here
	if( vis.display == '' && elem.offsetWidth != undefined && elem.offsetHeight != undefined )
	vis.display = 'none';
	vis.display =  'none';
}


function toggleLayer( whichLayer ){
	var elem, vis;

	switch (whichLayer){
		case "createformat":
<?php if ($arrHttp["Opcion"]!="new"){
		echo '
			document.forma1.fgen.selectedIndex=-1
			EsconderVentana("useexformat")
            if (save=="Y"){
			//	document.forma1.nombre.value=""
			//	document.forma1.descripcion.value=""
			}
			break
			';
}
?>
		case "useexformat":
			EsconderVentana("createformat")
			break

	}
	if( document.getElementById ) // this is the way the standards work
		elem = document.getElementById( whichLayer );
	else if( document.all ) // this is the way old msie versions work
		elem = document.all[whichLayer];
	else if( document.layers ) // this is the way nn4 works
		elem = document.layers[whichLayer];
	vis = elem.style;
	// if the style.display value is blank we try to figure it out here
	if( vis.display == '' && elem.offsetWidth != undefined && elem.offsetHeight != undefined )
		vis.display = ( elem.offsetWidth != 0 && elem.offsetHeight != 0 ) ? 'block':'none';
	vis.display = ( vis.display == '' || vis.display == 'block' ) ? 'none':'block';
}



function BorrarRango(){
	document.forma1.Mfn.value=''
	document.forma1.to.value=''
}

function SubCampos(Tag,delim,ed){
	xtag="(if p(v"+Tag+") then "
	for (ic=0;ic<delim.length;ic++){
		if(delim.substr(ic,1)=="-")
			delimiter="*"
 		else
 			delimiter=delim.substr(ic,1)
 		edicion=ed.substr(ic,1)
 		if (ic==0)
 			edicion=""
 		else
 			if (edicion!="") edicion=" "+edicion
		//if (ic!=delim.length-1)
			if (edicion!="")
				xtag+=',|'+edicion+'|v'+Tag+'^'+delimiter+','
	        else
			    xtag+="| |v"+Tag+'^'+delimiter+","
       	//else
       	//	xtag+="v"+Tag+'^'+delimiter+','

	}
	xtag+=" if iocc<>nocc(v"+Tag+") then '<br>' fi"
	xtag+=" fi/)"
	return xtag
}

function GenerarFormato(Tipo){
    if (document.forma1.list21.options.length==0){
    	alert("<?php echo $msgstr["selfieldsfmt"]?>")
    	return
    }
    <?php if ($arrHttp["Opcion"]!="new")
		echo "document.forma1.fgen.selectedIndex=-1
		";
	?>

	formato=""
	head=""    //COLUMNS HEADING
    switch (Tipo){
    	case "T":             //TABLE
    		formato="'<table border=0 width=90%>'\n"
    		for (i=0;i<document.forma1.list21.options.length;i++){
			    campo=document.forma1.list21.options[i].value
			    t=campo.split('|')
				xTag=t[1]
				xTipoE=t[0]
				if (xTag=="" || xTipoE=="L"){
					campo="'<tr><td colspan=2 valign=top><strong>"+t[2]+"</strong></td>'/\n"
		    	}else {
		    		if(Trim(t[5])!=""){
						tag=SubCampos(xTag,t[5],t[6])
					}else{
						tag="v"+xTag+"+|<br>|"
					}
			    	campo="if p(v"+xTag+ ") then '<tr><td width=20% valign=top><strong>"+t[2]+"</strong></td><td valign=top><strong>'"+tag+",'</td>' fi/\n"
				}
				formato+=campo
			}
			formato+="'</table><p>'";
    		break
    	case "PL":
    	case "P":
	    	for (i=0;i<document.forma1.list21.options.length;i++){
		    	campo=document.forma1.list21.options[i].value
		    	t=campo.split('|')
				xTag=t[1]
				xTipoE=t[0]
				if (xTag=="" || xTipoE=="L"){
					campo="'<br><b>"+t[2]+"</b></td>'/\n"
		    	}else {
		    		if (Tipo=='PL')
		    			label_f=  "<font face=arial size=2><b>"+t[2]+"</b>: "
		 			else
		 				label_f=""
					if(Trim(t[5])!=""){
						tag=SubCampos(xTag,t[5],t[6])
					}else{
						tag="v"+xTag+"+|; |"
					}

		    		campo="if p(v"+xTag+ ") then '<br>"+label_f+"<font face=arial size=2>'"+tag+", fi/\n"
				}
				formato+=campo
			}
			formato+="'<P>'/\n"
    		break
    	case "CT":
    		formato+="\n'<tr>',"
    		for (i=0;i<document.forma1.list21.options.length;i++){
			    campo=document.forma1.list21.options[i].value
			    t=campo.split('|')
				xTag=t[1]
				xTipoE=t[0]
		  		if (xTag!=""){
		  			res=""
					if(Trim(t[5])!=""){
						tag=SubCampos(xTag,t[5],t[6])
					}else{
						tag="v"+xTag+"+|; |"
					}
			    	campo="'<td valign=top><strong>'"+tag+" if a(v"+xTag+") then '&nbsp;' fi,'</strong></td>'/\n"
			    	formato+=campo
			    	head+=t[2]+"\n"
				}
			}
    		break;
    	case "CD":
    		for (i=0;i<document.forma1.list21.options.length;i++){
			    campo=document.forma1.list21.options[i].value
			    t=campo.split('|')
				xTag=t[1]
				xTipoE=t[0]
		  		if (xTag!=""){
					if(Trim(t[5])!=""){
						tag=SubCampos(xTag,t[5],t[6])
					}else{
						tag="v"+xTag
					}
			    	campo=tag+",'|',\n"
			    	formato+=campo
			    	head+=t[2]+"\n"
				}

			}
			formato+="/"
    		break;
    }

	document.forma1.pft.value=formato
	document.forma1.headings.value=head

}

function LeerArchivo(Opcion){
  	if (Opcion!="agregar"){
		ix=document.forma1.fgen.selectedIndex
		if (ix==-1 || ix==0){
    		alert("<?php echo $msgstr["r_self"]?>")
    		return
		}
		fmt=document.forma1.fgen.options[ix].value
		desc=document.forma1.fgen.options[ix].text
		forsel=document.forma1.fgen.options[ix].value
  	}else{
  		forsel="*"  //para indicar que es un formato nuevo
  	}
  	xfmt=fmt+'|'
  	fm=xfmt.split('|')
  	document.forma1.nombre.value=fm[0]
  	document.forma1.descripcion.value=desc
	msgwin=window.open("leertxt.php?base=<?php echo $arrHttp["base"]."&cipar=".$arrHttp["base"]?>.par&pft=s&archivo="+forsel,"editar","menu=no,status=yes, resizable, scrollbars,width=790")
	msgwin.focus()
}

function SubirFormato(){
	document.forma1.pft.value=""
	BorrarFormato("todos")
	theHeight=150
	theWidth=400
	var theTop=(screen.height/2)-(theHeight/2);
	var theLeft=(screen.width/2)-(theWidth/2);
	var features= 'height='+theHeight+',width='+theWidth+',top='+theTop+',left='+theLeft+",scrollbars=yes,resizable";
	msgupload=window.open("","upload",features)
	msgupload.document.writeln("<html><Title><?php echo $msgstr["pft"]?></title>")
	msgupload.document.writeln("<style>")
	msgupload.document.writeln("td{font-family:arial;font-size:10px}</style>")
	msgupload.document.writeln("<form action=upload_pft.php method=POST enctype=multipart/form-data>")
	msgupload.document.writeln("<input type=hidden name=Opcion value=PFT>")
	msgupload.document.writeln("<dd><table bgcolor=#eeeeee>")
	msgupload.document.writeln("<tr>")
	msgupload.document.writeln("<tr><td class=title><?php echo $msgstr["subir"]." ".$msgstr["pft"]?></td>")
	msgupload.document.writeln("<tr><td><input name=userfile[] type=file size=20></td><td></td>")
	msgupload.document.writeln("<tr><td><input type=submit value='<?php echo $msgstr["subir"]?>'></td>")
	msgupload.document.writeln("</table>")
	msgupload.document.writeln("<p>")
	msgupload.document.writeln("</form>")
	msgupload.focus()
	msgupload.document.close()
}
function BorrarFormato(area){
	if (area=="todos"){
		document.forma1.headings.value=""
		document.forma1.pft.value=""
    }else{
    	Ctrl=eval ("document.forma1."+area)
    	Ctrl.value=""
    }

	moveAllOptions(document.forms[0]['list21'],document.forms[0]['list11'],false)
	for (i=0;i<document.forma1.tipof.length;i++){
		document.forma1.tipof[i].checked=false
	}
}

function BorrarExpresion(){
	document.forma1.Expresion.value=''
}

function EnviarForma(vp){
	if (vp=="P") {
		document.forma1.vp.value="S"
		document.forma1.target="VistaPrevia"
		msgwin=window.open("","VistaPrevia","resizable, status, scrollbars")
	}else{
		document.forma1.vp.value=vp
		document.forma1.target=""
	}
	de=Trim(document.forma1.Mfn.value)
  	a=Trim(document.forma1.to.value)
  	if (de!="" || a!="") {
	  	document.forma1.Opcion.value="rango"
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
    	if (de<=0 || a<=0 || de>a ||a>top.maxmfn){
	    	alert("<?php echo $msgstr["numfr"]?>")
	    	return
		}
	}

	if (Trim(document.forma1.pft.value)=="" && document.forma1.fgen.selectedIndex<1 && Trim(document.forma1.pft.value)==""  ){
	  	alert("<?php echo $msgstr["r_selgen"]?>")
	  	return
	}
	if (Trim(document.forma1.Expresion.value)=="" && Trim(document.forma1.Mfn.value)=="" && Trim(document.forma1.seleccionados.value)==""){
		alert("<?php echo $msgstr["r_selreg"]?>")
		return
	}

  	document.forma1.submit()
  	msgwin.focus()
}

function GuardarFormato(){
	document.forma1.fgen.selectedindex=-1
	if (Trim(document.forma1.pft.value)==""){
	  	alert("<?php echo $msgstr["r_selgen"]?>")
	  	return
	}
	if (Trim(document.forma1.nombre.value)==""){
		alert("Debe especificar el nombre del formato a almacenar")
		return
	}
	if (Trim(document.forma1.descripcion.value)==""){
		alert("<?php echo $msgstr["r_fnomb"]?>")
		return
	}
	fn=document.forma1.nombre.value
	bool=  /^[a-z][\w]+$/i.test(fn)
 	if (bool){

   	}else {
      	alert("The name must start with a letter and contains only letters and numbers and the sign _. No extention is required  ");
      	return
   	}
   	tipoformato=""
   	for (i=0;i<document.forma1.tipof.length;i++){
   		if (document.forma1.tipof[i].checked)
   			tipoformato=document.forma1.tipof[i].value
   	}
	document.guardar.pft.value=document.forma1.pft.value
	document.guardar.headings.value=document.forma1.headings.value
	document.guardar.tipof.value=tipoformato
	document.guardar.nombre.value=document.forma1.nombre.value
	document.guardar.descripcion.value=document.forma1.descripcion.value
	document.guardar.base.value=document.forma1.base.value
	document.guardar.submit()

}

function Buscar(){
	base='<?php echo $arrHttp["base"]?>'
	cipar=base+".par"
	Url="../dataentry/buscar.php?Opcion=formab&prologo=prologoact&Target=s&Tabla=imprimir&base="+base+"&cipar="+cipar
  	msgwin=window.open(Url,"Buscar","menu=no, resizable,scrollbars,width=750,height=400")
	msgwin.focus()
}

function EliminarFormato(){
	if (document.forma1.fgen.selectedIndex==0 || document.forma1.fgen.selectedIndex==-1){
		alert("<?php echo $msgstr["selpftdel"]?>")
		return
	}
	ix=document.forma1.fgen.selectedIndex
	if (confirm("delete "+document.forma1.fgen.options[ix].text+"?")){
		file=document.forma1.fgen.options[ix].value +'|'
		f=file.split('|')
    	document.frmdelete.pft.value=f[0]
    	document.frmdelete.submit()
    }
}

function ValidarFormato(){
	if (Trim(document.forma1.pft.value)==""){
		alert("<?php echo $msgstr["genformat"]?>")
		return
	}
	document.forma1.action="crearbd_new_create.php"
	document.forma1.submit()
}

function GuardarBusqueda(){
	document.savesearch.Expresion.value=Trim(document.forma1.Expresion.value)
	if (document.savesearch.Expresion.value==""){
		alert("<?php echo $msgstr["faltaexpr"]?>")
		return
	}
	Descripcion=document.forma1.Descripcion.value
	if (Trim(Descripcion)==""){
		alert("<?php echo $msgstr["errsave"]?>")
		return
	}
	document.savesearch.Descripcion.value=Descripcion
	var winl = (screen.width-300)/2;
	var wint = (screen.height-200)/2;
	msgwin=window.open("","savesearch","menu=no,status=yes,width=300, height=200,left="+winl+",top="+wint)
	msgwin.focus()
	document.savesearch.submit()
}

function Listados(){
	ix=document.forma1.listados.selectedIndex
	if (ix>0){
		exe=document.forma1.listados.options[ix].value
		document.listadosfrm.action=exe
		document.listadosfrm.submit()
	}
}

</script>

<script type="text/javascript">
	addEventListener('load', function() 
		{ 
		var code = document.querySelector('#code'); 
		var worker = new Worker('worker.js'); 
		worker.onmessage = function(event) 
		{ 
			code.innerHTML = event.data; 
		} 
		worker.postMessage(code.textContent); 
	})
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
<?php echo $msgstr["pft"].": ".$arrHttp["base"]?>
	</div>

	<div class="actions">
<?php
if ($arrHttp["Opcion"]=="new"){
	$ayuda="pft_create.html";
	echo "<a href=fst.php?Opcion=new&base=".$arrHttp["base"]."$encabezado class=\"defaultButton backButton\">
	<img src=\"../images/defaultButton_iconBorder.gif\" />
	<span><strong>".$msgstr["back"]."</strong></span></a>";
	echo "<a href=\"menu_creardb.php?$encabezado\"$encabezado class=\"defaultButton cancelButton\">
	<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
<span><strong>".$msgstr["cancel"]."</strong></span></a>
	";
}else{
	$ayuda="pft.html";
	if (isset($arrHttp["encabezado"])){
		if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_MODIFYDB"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_MODIFYDB"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])){
			if (isset($arrHttp["retorno"]))
				$retorno=$arrHttp["retorno"];
			else
				$retorno="menu_modificardb.php";
			echo "<a href=\"$retorno"."?base=".$arrHttp["base"]."$encabezado\" class=\"defaultButton cancelButton\">
			<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
		<span><strong>".$msgstr["cancel"]."</strong></span></a>
			";
		}else{
			echo "<a href=\"../common/inicio.php?reinicio=s&base=".$arrHttp["base"]."$encabezado\" class=\"defaultButton cancelButton\">
			<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
		<span><strong>".$msgstr["cancel"]."</strong></span></a>
			";
		}
	}
}
?>

</div>

<div class="spacer"></div>
</div>
<div class="helper">
<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"];?>/<?php echo $ayuda?> target=_blank><?php echo $msgstr["help"];?></a>
<?php if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/".$ayuda." target=_blank>".$msgstr["edhlp"]."</a>";
echo "<font color=white>&nbsp; &nbsp; Script: dbadmin/pft.php";?></font>
	</div>

<form name=forma1 method=post action=../dataentry/imprimir_g.php onsubmit="Javascript:return false">
<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
<input type=hidden name=cipar value=<?php echo $arrHttp["base"]?>.par>
<input type=hidden name=Dir value=<?php echo $arrHttp["Dir"]?>>
<input type=hidden name=Modulo value=<?php if (isset($arrHttp["Modulo"])) echo $arrHttp["Modulo"]?>>
<input type=hidden name=tagsel>
<input type=hidden name=Opcion>
<input type=hidden name=vp>




<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>\n";
?>
<div class="middle form">
			<div class="formContent">
<?php
if ($arrHttp["Opcion"]!="new"){
 	unset($fp);
    $archivo=$db_path.$base."/pfts/".$_SESSION["lang"]."/listados.dat";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/pfts/".$lang_db."/listados.dat";
	if (file_exists($archivo)) $fp = file($archivo);
	if (isset($fp)){
		echo "<table width=800  class=listTable>";
		echo "<td valign=top>";
		echo "<strong>".$msgstr["listados"]."</strong>: <xselect name=listados onchange=javascript:Listados()><option></option>";
		foreach ($fp as $value){
			if (trim($value)!=""){
				$pp=explode('|',$value);
				if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])
				   or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_".$pp[0]])){
				   if (strpos($pp[1],"?")==false) $pp[1].="?";
				   $url="";
				   $url="&base=".$arrHttp["base"];
				   if (isset($arrHttp["retorno"])) $url.="&retorno=".$arrHttp["retorno"];
				   if (isset($arrHttp["modulo"])) $url.="&modulo=".$arrHttp["modulo"];
					echo "<a href=\"".$pp[1]."$encabezado".$url."\">".$pp[0]."</a><br>\n";
				}
			}
		}
        echo "</xselect>";
        echo "</table><p>";
	}
}
?>




<table border=0 width=600 class=listTable>
	<td  align=center>
		<?php echo "<strong>".$msgstr["r_fgent"]."</strong>";?>
        &nbsp; &nbsp; <a href=http://bvsmodelo.bvsalud.org/download/cisis/CISIS-LinguagemFormato4-<?php echo $_SESSION["lang"]?>.pdf target=_blank><font size=1><?php echo $msgstr["cisis"]?>
        </a>
.   </td>
<?php
echo "</table>\n";
if ($arrHttp["Opcion"]!="new"){
//USE AN EXISTING FORMAT
	echo "<table width=800  class=listTable>
			<tr>
			<td align=left   valign=center>
    		&nbsp; <a href=\"javascript:toggleLayer('useexformat');\"> <u><strong>". $msgstr["useexformat"]."</strong></u></a>
    		<div id=useexformat>
    		<table><td>
    		<br>".$msgstr["r_formatos"].": <select name=fgen onclick=javascript:BorrarFormato(\"todos\")>
    		<option value=''>";
    unset($fp);
    $archivo=$db_path.$base."/pfts/".$_SESSION["lang"]."/formatos.dat";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/pfts/".$lang_db."/formatos.dat";
	if (file_exists($archivo)) $fp = file($archivo);
	if (isset($fp)){
		foreach ($fp as $value){
			if (trim($value)!=""){
				$pp=explode('|',$value);
				if (!isset($pp[2])) $pp[2]="";
				if (!isset($pp[3])) $pp[3]="";
				if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_ALL"])
				   or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_".$pp[0]])){
					echo "<option value=\"".$pp[0]."|".$pp[2]."|".$pp[3]."\">".$pp[1]." (".$pp[0].")</option>\n";
				}
			}
		}

	}
	echo "</select>";
	//echo "<a href=javascript:LeerArchivo(\"\")>".$msgstr["edit"]."</a> | <a href=javascript:EliminarFormato()>".$msgstr["delete"]."</a>";

?>
</table>
</div>
</td>

</table>
<?php }else{
		echo "<div id=useexformat></div>";

}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDPFT"])  or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_EDPFT"]) or isset($_SESSION["permiso"]["CENTRAL_MODIFYDB"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_MODIFYDB"])){


?>


 <div class="page-title">
              <div class="title_left">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> <?php echo $msgstr["listados"].": ".$arrHttp["base"]?></h3>
              </div>

              <div class="title_right">
					<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/stats/stats_tables_generate.html target=_blank><?php echo $msgstr["help"]?></a> Script: tables_generate.php
              </div>
            </div>

            <div class="clearfix"></div>

 <div class="container conteudo">

<!-- CREATE A FORMAT -->
<div class="container">
  <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $msgstr["useexformat"];?> <i class="fa fa-thumb-tack" aria-hidden="true"></i></a>
        </h4>
      </div>


      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body"><?php echo $msgstr["r_formatos"];?> 
        <select name="tables" >
    		<option value="">

<?php        
    unset($fp);
    $archivo=$db_path.$base."/pfts/".$_SESSION["lang"]."/formatos.dat";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/pfts/".$lang_db."/formatos.dat";
	if (file_exists($archivo)) $fp = file($archivo);
	if (isset($fp)){
		foreach ($fp as $value){
			if (trim($value)!=""){
				$pp=explode('|',$value);
				if (!isset($pp[2])) $pp[2]="";
				if (!isset($pp[3])) $pp[3]="";
				if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_ALL"])
				   or isset($_SESSION["permiso"][$arrHttp["base"]."_pft_".$pp[0]])){
					echo "<option value=\"".$pp[0]."|".$pp[2]."|".$pp[3]."\">".$pp[1]." (".$pp[0].")</option>\n";
				}
			}
		}

	}
?>

</i></div>
</select>
      
    <!--editar campo-->

     <a href=javascript:LeerArchivo(\"\") class="btn btn-default campo"><i class="fa fa-pencil-square-o" aria-hidden="true" alt="<?php echo $msgstr["edit"];?>"</a></i>
 
      <!--excluir-->

     <a href=javascript:EliminarFormato() ) class="btn btn-default campo"><i class="fa fa-trash" aria-hidden="true" 
      alt="<?php echo $msgstr["delete"];?>"></i></a>




         </div>
      </div>
    </div>






<div class="panel panel-default">
  <div class="panel-heading">
   <h4 class="panel-title">
     <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $msgstr["r_creaf"]?>  <i class="fa fa-plus" aria-hidden="true"></i></a>
   </h4>
  </div>
<div id="collapse2" class="panel-collapse collapse">
 <div class="panel-body">

   	<label class="col-md-12"><?php echo $msgstr[r_incluirc];?></label>
   <div class="col-md-5">
    <select name="list11" class="form-control" multiple="10" size="10" onDblClick="moveSelectedOptions(this.form['list11'],this.form['list21'],false)">
 	  	<?php
			$t=array();
 			foreach ($Fdt as $linea){
 			$t=explode('|',$linea);
 		?>
   		<option value="<?php echo $linea; ?>" ><?php echo $t[2]."(".$t[1].")"; ?>
  	<?php
  	}
?>
 		</option>
	 </select>
   </div>

<div class="col-md-1">
 	<a href="" ="#" class="btn btn-default campo" onClick="moveSelectedOptions(document.forms[0]['list11'],document.forms[0]['list21'],false);return false;"> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
 
	<a href="" ="#"  class="btn btn-default campo" onClick="moveSelectedOptions(document.forms[0]['list21'],document.forms[0]['list11'],false); return false;"><i class="fa fa-angle-left" aria-hidden="true"></i></a>

 	<a href="" ="#" class="btn btn-default campo" onClick="moveAllOptions(document.forms[0]['list11'],document.forms[0]['list21'],false); return false;"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>

	<a href="" ="#" class="btn btn-default campo" onClick="moveAllOptions(document.forms[0]['list11'],document.forms[0]['list21'],false); return false;"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
</div>

<div class="col-md-5">
	<select class="form-control" multiple="10" size="10" name="list21" onDblClick="moveSelectedOptions(this.form['list21'],this.form['list11'],false)">
	</select>
</div>

<div class="col-md-1">

<a href=# class="btn btn-default campo" onClick="moveOptionUp(document.forms[0]['list21'])" title="<?php echo $msgstr["r_subir"]?>" ><i class="fa fa-angle-up" aria-hidden="true"></i></a>
					
<a href=# onClick=" moveOptionDown(document.forms[0]['list21'])" class="btn btn-default campo" title="<?php echo $msgstr["r_bajar"]?>">
<i class="fa fa-angle-down" aria-hidden="true"></i></a>
	
</div>

<div class="clearfix"></div>
<div class="container">		
<h5>Gerar saida</h5>
<div class="input-group">
  <div class="btn-group radio-group">
   <label class="btn btn-primary not-active">  <input type="radio" name=tipof value=T onclick=GenerarFormato('T') ><?php echo $msgstr["r_tabla"];?></label>
   <label class="btn btn-primary not-active"> <input type="radio" name=tipof value=P onclick=GenerarFormato('P')><?php echo $msgstr["r_parrafo"];?></label>
   <label class="btn btn-primary not-active"> <input type="radio" name=tipof value=PL onclick=GenerarFormato('PL')><?php echo $msgstr["r_parrafo"];?>(with Labels)
   </label>
   <label class="btn btn-primary not-active"> <input type="radio" value=CT onclick=GenerarFormato('CT')><?php echo $msgstr["r_colstab"];?></label>
   <label class="btn btn-primary not-active"> <input type="radio" name=tipof value=CD onclick=GenerarFormato('CD')><?php echo $msgstr["r_colsdelim"];?></label>
  </div>
 </div>
<!--input text-->            
<div class="col-md-9">
	<div class="form-group">
 		<label for="inputlg"></label>
  		<textarea id="code"  class="form-control input-lg html" name="pft"  cols="80" rows="10" style="font-family:courier new;"></textarea>
	</div>

	

<a href=# onClick='javascript:BorrarFormato("pft")' class="btn btn-default campo" title="<?php echo $msgstr["borrar"]?>">
<i class="fa fa-eraser" aria-hidden="true"></i></a>

</div>


	<div class="col-md-3">
		<div class="form-group">
		<label for="inputlg"><?php echo $msgstr["r_heading"]?></label>
 			<textarea class="form-control input-lg" name="headings" cols="30" rows="9" style="font-family:courier new;" onfocus="CheckType()"></textarea>
		</div>
	</div>
</div> <!--div container-->

</div>
</div><!--panel body-->
</div><!--colapse2-->
</div> <!--panel default-->

<div class="panel panel-default">
 <div class="panel-heading">
  <h4 class="panel-title">
   <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><?php echo $msgstr["r_fgent"]?> <i class="fa fa-print" aria-hidden="true"></i></a>
  </h4>
 </div>
<div id="collapse3" class="panel-collapse collapse">
<div class="panel-body">

<div class="container">


 <h5>Seleção de registro</h5>
  <div class="control-group">
   <label class="control-label" for="textinput">De:</label>
    <input id="textinput" name="textinput" type="text"class="input-medium" required="">
   <label class="control-label" for="textinput">Ate:</label>
    <input id="textinput" name="textinput" type="text"class="input-medium" required=""> <button class="btn btn-default campo"><i class="fa fa-trash" aria-hidden="true"></i></button>                          
   </div>
</div>
  <h5>Pesquisa <i class="fa fa-search" aria-hidden="true"></i>    
  <button class="btn btn-default campo"><i class="fa fa-plus" aria-hidden="true"></i></button> </h5>
   <div class="form-group"> <input class="form-control input-lg" id="inputlg" type="text"> </div>
    <button class="btn btn-default campo"><i class="fa fa-trash" aria-hidden="true"></i></button> 
     <button class="btn btn-default campo"><i class="fa fa-check" aria-hidden="true"></i></button> 
       
<h5>Chave de ordenação: 
<input id="textinput" name="textinput" type="text"class="input-medium" required=""></h5>
  <button class="btn btn-default campo"><i class="fa fa-clone" aria-hidden="true"></i></button>
    <i class="fa fa-caret-down" aria-hidden="true"></i>
  <button class="btn btn-default campo"><i class="fa fa-plus" aria-hidden="true"></i></button> 

<h5>Enviar para:</h5>
	<button class="btn btn-default campo"><i class="fa fa-eye" aria-hidden="true"></i></button> 
	<button class="btn btn-default campo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> 
	<button class="btn btn-default campo"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button> 
	<button class="btn btn-default campo"><i class="fa fa-file-text-o" aria-hidden="true"></i></button>  
<br><br>


 
<!--input text-->            
<div class="col-md-6">
	<div class="form-group">
 		<label for="inputlg"></label>
  		<input class="form-control input-lg" id="inputlg" type="text">
	</div>
	<button class="btn btn-default campo"><i class="fa fa-eraser" aria-hidden="true"></i></button>
</div>
<div class="col-md-1"></div>
	<div class="col-md-4">
		<div class="form-group">
		<label for="inputlg">Cabeçalho de colunas (1xlinha)</label>
 			<input class="form-control input-lg" id="inputlg" type="text">
		</div>
	</div>
</div> <!--div container-->
<div class="col-md-1">
</div>
</div>
</div><!--panel d-->
</div><!--acordion-->
</div><!--container-->




<!-- GENERATE OUTPUT -->

<?php
}else{
	echo "<div id=createformat></div>";
}
if ($arrHttp["Opcion"]!="new"){?>
<table width=600 cellpadding=5 class=listTable>
	<tr>
		<td>
			 <a href="javascript:toggleLayer('testformat')"><u><strong><?php echo $msgstr["generateoutput"]?></strong></u></a>
    		<div id=testformat><p>
    		<table>
		<td colspan=2 align=center height=1 ><?php echo $msgstr["r_recsel"]?></td>
	<tr>
		<td  align=center colspan=2><strong><?php echo $msgstr["r_mfnr"]?></strong>: &nbsp; &nbsp; &nbsp;
		<?php echo $msgstr["r_desde"]?>: <input type=text name=Mfn size=10>&nbsp; &nbsp; &nbsp; &nbsp;<?php echo $msgstr["r_hasta"]?>:<input type=text name=to size=10>
		 &nbsp; &nbsp; &nbsp; <a href=javascript:BorrarRango() class=boton><?php echo $msgstr["borrar"]?></a>
		<script> if (top.window.frames.length>0)
			document.writeln(" &nbsp; &nbsp; &nbsp; (<?php echo $msgstr["maxmfn"]?>: "+top.maxmfn+")")</script></td>
	<?php
	if (isset($arrHttp["seleccionados"])){
		echo "<tr>
				  <td  align=center colspan=2><strong>".$msgstr["selected_records"]."</strong>: &nbsp; &nbsp; &nbsp;";
		$sel=str_replace("__",",",trim($arrHttp["seleccionados"]));
		$sel=str_replace("_","",$sel);
		echo "<input type=text name=seleccionados size=100 value=$sel>\n";
		echo "</td></tr>";
	}
	?>
	<tr>
		<td  align=center colspan=2><strong><?php echo $msgstr["r_busqueda"]?></strong>: &nbsp;
<?php
unset($fp);
if (file_exists($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab"))
	$fp = file($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab");
else
	if (file_exists($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab"))
		$fp = file($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab");
if (isset($fp)){
	echo "&nbsp; &nbsp; &nbsp; &nbsp;".$msgstr["copysearch"].":";
	echo "<select name=Expr  onChange=CopiarExpresion()>
    		<option value=''>
    ";
	foreach ($fp as $value){
		$value=trim($value);
		if ($value!=""){
			$pp=explode('|',$value);
			echo "<option value=\"".$pp[1]."\">".$pp[0]."</option>\n";
		}
	}

}
?>
			</select>&nbsp; &nbsp;
			<a href=javascript:Buscar()><?php echo $msgstr["new"]?></a>
			<br>
			<textarea rows=2 cols=100 name=Expresion><?php if ($Expresion!="") echo $Expresion?></textarea>
			<a href=javascript:BorrarExpresion() class=boton><?php echo $msgstr["borrar"]?></a>
<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_SAVEXPR"])  or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_SAVEXPR"])){
	echo "&nbsp; <A HREF=\"javascript:toggleLayer('savesearch')\"> <u><strong>". $msgstr["savesearch"]."</strong></u></a>";
	echo "<div id=savesearch>".$msgstr["r_desc"].": <input type=text name=Descripcion size=40>
     	&nbsp &nbsp <input type=button value=\"". $msgstr["savesearch"]."\" onclick=GuardarBusqueda()>
		</div>\n";
}
?>
		</td>
	<tr>
		<td colspan=2><strong><?php echo $msgstr["sortkey"]?></strong>: &nbsp;
		<input type=text name=sortkey size=70> <?php echo $msgstr["sortkeycopy"]?>
           <select name=sort  onChange=CopySortKey()>
    		<option value=''>
<?php
unset($fp);
if (file_exists($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/sort.tab"))
	$fp = file($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/sort.tab");
else
	if (file_exists($db_path.$arrHttp["base"]."/pfts/".$lang_db."/sort.tab"))
		$fp = file($db_path.$arrHttp["base"]."/pfts/".$lang_db."/sort.tab");
if (isset($fp)){
	foreach ($fp as $value){
		if (trim($value)!=""){
			$pp=explode('|',$value);
			echo "<option value=\"".trim($pp[1])."\">".$pp[0]."</option>\n";
		}
	}

}

echo "			</select>";
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDSORT"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_EDSORT"])){
 echo "&nbsp; &nbsp;<a href=javascript:CreateSortKey()>".$msgstr["sortkeycreate"]."</a>";
 }
?>
		</td>

	<tr>
		<td colspan=2 width=100%>
			<strong><?php echo $msgstr["sendto"]?></strong>:
			<a href=javascript:EnviarForma('WP')><?php echo $msgstr["word"]?></a>
			
			<a href=javascript:EnviarForma('TB')><?php echo $msgstr["wsproc"]?></a>
			
			<a href=javascript:EnviarForma('P')><?php echo $msgstr["vistap"]?></a>
			
			<a href=javascript:EnviarForma('TXT') value=T>TXT</a>
		</td>
</table>
</div>
</td>
</table>

<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDPFT"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])){
$save="Y";
?>

	<tr>
		<td>
		   <a href="javascript:toggleLayer('saveformat')"><u><strong><?php echo $msgstr["r_guardar"];?></strong></u></a>
    		<div id=saveformat><p>
			<table width=600 border=0 cellpadding=0>
				
				<font face=arial size=1><?php echo $msgstr["r_guardar"]." ".$db_path.$arrHttp["base"]."/". $arrHttp["Dir"];?>/ </td>
				<td><input type=text name=nombre size=20 maxlength=30></td>
				<tr><td align=right valign=top><font face=arial size=1>
					<?php echo $msgstr["r_desc"];?></td><td valign=top><input type=text name=descripcion maxlength=50 size=50>

					<a href=javascript:GuardarFormato()><img src=../dataentry/img/toolbarSave.png border=0></a>
				</td>
			</table>
			</div>
	</td>
</table>
<?php }else{
	$save="N";
}
echo "\n<script>save='$save'</script>\n";
if (!isset($arrHttp["Modulo"]))
	if (!isset($arrHttp["encabezado"]))
		echo "&nbsp; &nbsp;<a href=menu_modificardb.php?Opcion=".$arrHttp["Opcion"]."&base=".$arrHttp["base"].">".$msgstr["cancel"]. "</a><p>";
}else{
	echo "<p><a href=javascript:ValidarFormato()>".$msgstr["createdb"] ."</a>";
}
?>
<!--a href=menu_modificardb.php?base=<?php echo $arrHttp["base"]?>><?php echo $msgstr["cancel"]?></a>-->
<input type=hidden name=sel_oper>
</form>
<form name=guardar method=post action=pft_update.php>
<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
<input type=hidden name=pft>
<input type=hidden name=nombre>
<input type=hidden name=descripcion>
<input type=hidden name=tipof>
<input type=hidden name=headings>
<input type=hidden name=pftname>
<input type=hidden name=Modulo value=<?php if (isset($arrHttp["Modulo"])) echo $arrHttp["Modulo"]?>>
<input type=hidden name=sel_oper>
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=S>"; ?>
</form>
<form name=frmdelete action=pft_delete.php method=post>
<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
<input type=hidden name=pft>
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=S>"; ?>
</form>
<form name=savesearch action=../dataentry/busqueda_guardar.php method=post target=savesearch>
	<input type=hidden name=base value=<?php echo $arrHttp["base"];?>>
	<input type=hidden name=Expresion value="">
	<input type=hidden name=Descripcion value="">
</form>	<p>
<form name=sortkey method=post action=sortkey_edit.php target=sortkey>
	<input type=hidden name=base value=<?php echo $arrHttp["base"];?>>
	<input type=hidden name=encabezado value=s>
</form>
<form name=listadosfrm method=post>
<input type=hidden name=base value=<?php echo $arrHttp["base"];?>>
</form>
</center>
</div>
</div>
</center>
<?php
include("../common/footer.php");
?>
</body>
</html>
<?php if (isset($arrHttp["pft"])and $arrHttp["pft"]!="") {
?> <script>
		xpft='<?php echo $arrHttp["pft"]?>'
		xDesc=xpft='<?php echo $arrHttp["desc"]?>'
		document.forma.nombre.value=xpft
		document.forma1.descripcion.value=
		msgwin=window.open("leertxt.php?base=<?php echo $arrHttp["base"]."&cipar=".$arrHttp["cipar"]?>&archivo="+xpft,"editar","menu=no, resizable, scrollbars,width=790")
		msgwin.focus()
	</script>
<?php
  }
?>
<?php
if ($arrHttp["Opcion"]=="new")
	echo "\n<script>toggleLayer('pftedit')\n</script>\n"; ?>