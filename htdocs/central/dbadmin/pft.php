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
// se leen los archivos con la extensi�n .pft
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
    		formato="'<table>'\n"
    		for (i=0;i<document.forma1.list21.options.length;i++){
			    campo=document.forma1.list21.options[i].value
			    t=campo.split('|')
				xTag=t[1]
				xTipoE=t[0]
				if (xTag=="" || xTipoE=="L"){
					campo="'<tr><td><label>"+t[2]+"</label></td>'/\n"
		    	}else {
		    		if(Trim(t[5])!=""){
						tag=SubCampos(xTag,t[5],t[6])
					}else{
						tag="v"+xTag+"+|<br>|"
					}
			    	campo="if p(v"+xTag+ ") then '<tr><td><label>"+t[2]+"</label></td><td><label>'"+tag+",'</label></td>' fi/"
				}
				formato+=campo
			}
			formato+="'</table>'";
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
		    			label_f=  "<label>"+t[2]+"</label>: "
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
			    	campo="'<td valign=top><font face=arial size=2>'"+tag+" if a(v"+xTag+") then '&nbsp;' fi,'</td>'/\n"
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
<body>

<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}

?>


<div class="page-title">
  <div class="title_left">
      <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> <?php echo $msgstr["listados"].": ".$arrHttp["base"]?></h3>
      <small><?php echo $msgstr["pft"].": ".$arrHttp["base"]?></small>
  </div>            
</div>

<div class="clearfix"></div>







<form name="forma1" method="post" action="../dataentry/imprimir_g.php" onsubmit="Javascript:return false">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"]?>">
<input type="hidden" name="cipar" value="<?php echo $arrHttp["base"]?>.par">
<input type="hidden" name="Dir" value="<?php echo $arrHttp["Dir"]?>">
<input type="hidden" name="Modulo" value="<?php if (isset($arrHttp["Modulo"])) echo $arrHttp["Modulo"]?>">
<input type="hidden" name="tagsel">
<input type="hidden" name="Opcion">
<input type="hidden" name="vp">



<?php 
if (isset($arrHttp["encabezado"])) 

echo "<input type=hidden name=encabezado value=s>\n";
if ($arrHttp["Opcion"]!="new"){
 	unset($fp);
    $archivo=$db_path.$base."/pfts/".$_SESSION["lang"]."/listados.dat";
	if (!file_exists($archivo)) $archivo=$db_path.$base."/pfts/".$lang_db."/listados.dat";
	if (file_exists($archivo)) $fp = file($archivo);
	if (isset($fp)){

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
	}
}
?>



<?php echo "<label>".$msgstr["r_fgent"]."</label>";?>
<a href=http://bvsmodelo.bvsalud.org/download/cisis/CISIS-LinguagemFormato4-<?php echo $_SESSION["lang"]?>.pdf target="_blank"><font size=1><?php echo $msgstr["cisis"]?></a>
<label>pft.php</label>




<!--USAR EXISTENTE-->

<?php

if ($arrHttp["Opcion"]!="new"){
//USE AN EXISTING FORMAT
?>	

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

	<label><?php echo $msgstr["r_formatos"]; ?></label>
	<select name="fgen" class="form-control" onclick="javascript:BorrarFormato("todos")">
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


	</select>
	<br>
	<a class="btn btn-primary" href="javascript:LeerArchivo()"><?php echo $msgstr["edit"];?></a> 
	<a class="btn btn-danger" href="javascript:EliminarFormato()"><?php echo $msgstr["delete"];?></a>

         </div>
      </div>
    </div>

<!--FIM USAR EXISTENTE-->








<?php }else{
		echo "<div id=useexformat></div>";

}
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDPFT"])  or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_EDPFT"]) or isset($_SESSION["permiso"]["CENTRAL_MODIFYDB"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_MODIFYDB"])){
?>
<!-- CREATE A FORMAT -->


<div class="panel panel-default">
  <div class="panel-heading">
   <h4 class="panel-title">
     <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $msgstr["r_creaf"]?>  <i class="fa fa-plus" aria-hidden="true"></i></a>
   </h4>
  </div>
<div id="collapse2" class="panel-collapse collapse">
 <div class="panel-body">

   	<label class="col-md-12"><?php echo $msgstr['r_incluirc'];?></label>
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
 	<a href="" ="#" class="btn btn-primary campo" onClick="moveSelectedOptions(document.forms[0]['list11'],document.forms[0]['list21'],false);return false;"> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
 
	<a href="" ="#"  class="btn btn-primary campo" onClick="moveSelectedOptions(document.forms[0]['list21'],document.forms[0]['list11'],false); return false;"><i class="fa fa-angle-left" aria-hidden="true"></i></a>

 	<a href="" ="#" class="btn btn-primary campo" onClick="moveAllOptions(document.forms[0]['list11'],document.forms[0]['list21'],false); return false;"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>

	<a href="" ="#" class="btn btn-primary campo" onClick="moveAllOptions(document.forms[0]['list21'],document.forms[0]['list11'],false); return false;"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
</div>

<div class="col-md-5">
	<select class="form-control" multiple="10" size="10" name="list21" onDblClick="moveSelectedOptions(this.form['list21'],this.form['list11'],false)">
	</select>
</div>

<div class="col-md-1">

<a href=# class="btn btn-success campo" onClick="moveOptionUp(document.forms[0]['list21'])" title="<?php echo $msgstr["r_subir"]?>" ><i class="fa fa-angle-up" aria-hidden="true"></i></a>
					
<a href=# onClick=" moveOptionDown(document.forms[0]['list21'])" class="btn btn-success campo" title="<?php echo $msgstr["r_bajar"]?>">
<i class="fa fa-angle-down" aria-hidden="true"></i></a>
	
<a href=# onClick='javascript:BorrarFormato("pft")' class="btn btn-warning campo" title="<?php echo $msgstr["borrar"]?>">
<i class="fa fa-eraser" aria-hidden="true"></i></a>




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

	





	<div class="col-md-3">
		<div class="form-group">
		<label for="inputlg"><?php echo $msgstr["r_heading"]?></label>
 			<textarea class="form-control input-lg" name="headings" cols="30" rows="9" style="font-family:courier new;" onfocus="CheckType()"></textarea>
		</div>
	</div>
</div>
</div>
</div>
</div>
</div> <!--panel default-->


<!--FECHA CRIAR FORMATO-->



<!-- GENERATE OUTPUT -->
<?php
}else{
	echo "<div id=createformat></div>";
}
if ($arrHttp["Opcion"]!="new"){?>


<div class="panel panel-default">
 <div class="panel-heading">
  <h4 class="panel-title">
   <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><?php echo $msgstr["generateoutput"];?> <i class="fa fa-print" aria-hidden="true"></i></a>
  </h4>
 </div>
<div id="collapse3" class="panel-collapse collapse">
<div class="panel-body">

<div class="container">


 <h5><?php echo $msgstr["r_mfnr"]?></h5>
  <div class="control-group">
   <label class="control-label" for="textinput"><?php echo $msgstr["r_desde"]?></label>
    <input class="form-control" id="textinput" name="Mfn" type="text"class="input-medium" required="">
   <label class="control-label" for="textinput"><?php echo $msgstr["r_hasta"]?></label>
    <input class="form-control" id="textinput" name="to" type="text"class="input-medium" required="">
<br>
     <a href='javascript:BorrarRango()' class="btn btn-warning campo" title="<?php echo $msgstr["borrar"]?>"><i class="fa fa-trash" aria-hidden="true"></i></a> 
     <script>
      if (top.window.frames.length>0)
			document.writeln(<?php echo $msgstr["maxmfn"]?>: "+top.maxmfn+")
</script>
	<?php
	if (isset($arrHttp["seleccionados"])){
		echo "<tr>
				  <td><label>".$msgstr["selected_records"]."</label>:";
		$sel=str_replace("__",",",trim($arrHttp["seleccionados"]));
		$sel=str_replace("_","",$sel);
		echo "<input type=text name=seleccionados class=\"form-control\" value=$sel>\n";
		echo "</td></tr>";
	}
	?>




   </div>
</div>
<br>
  <label><?php echo $msgstr["r_busqueda"];?></label>
    <a href=javascript:Buscar() title="<?php echo $msgstr["new"];?>" class="btn btn-primary campo"><i class="fa fa-search" aria-hidden="true"></i></a>

  <?php
unset($fp);
if (file_exists($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab"))
	$fp = file($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab");
else
	if (file_exists($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab"))
		$fp = file($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab");
if (isset($fp)){
	echo "&nbsp; &nbsp; &nbsp; &nbsp;".$msgstr["copysearch"].":";
	echo "<select class=\"form-control\" name=Expr  onChange=CopiarExpresion()>
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

<br>
<br>
<br>


<div class="form-group">
   <textarea class="form-control col-md-11" rows="3" cols="80" name="Expresion"><?php if ($Expresion!="") echo $Expresion?></textarea>

<br><br>
    <a href=javascript:BorrarExpresion() class= "btn btn-danger campo col-md-1" title="<?php echo $msgstr["borrar"]?>"><i class="fa fa-trash" aria-hidden="true"></i></a> 

<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_SAVEXPR"])  or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_SAVEXPR"])){
?>

	<a href="javascript:toggleLayer('savesearch')" class="btn btn-success campo col-md-1" title="<?php echo $msgstr["savesearch"];?>"><i class="fa fa-check" aria-hidden="true"></i></strong></a>
</div>


	<div id="savesearch">
		<label><?php echo $msgstr["r_desc"];?>:</label>
		 <input  type="text" class="form-control input-medium"  name="Descripcion">
     	 <input type="button"  class="btn btn-default campo" value="<?php echo $msgstr["savesearch"];?>" onclick="GuardarBusqueda()">
	</div>

<?php
}
?>


       
<h5><label>Chave de ordena��o: </label></h5>
<input  id="textinput" name="textinput" type="text" class="form-control" required=""></h5>
 <select class="form-control" name=sort  onChange=CopySortKey()>
    		<option value=""></option>
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
?>


<a href=javascript:CreateSortKey() title="<?php echo $msgstr["sortkeycreate"];?>" class="btn btn-primary campo"><i class="fa fa-plus" aria-hidden="true"></i></a>

<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDSORT"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_EDSORT"])){
 }
?>
  
  
 <br><br>

<label><?php echo $msgstr["sendto"];?></label>
<br><br>
	<a href="javascript:EnviarForma('WP')"  class="btn btn-primary campo" title="<?php echo $msgstr["word"];?>">
		<i class="fa fa-file-word-o" aria-hidden="true"></i>
	</a> 
	
	<a href="javascript:EnviarForma('TB')" title="<?php echo $msgstr["wsproc"];?>" class="btn btn-primary campo">
		<i 	class="fa fa-file-excel-o" aria-hidden="true"></i>
	</a> 

	<a href="javascript:EnviarForma('TXT')" value="T" class="btn btn-primary campo">
		<i class="fa fa-file-text-o" aria-hidden="true"></i>
	</a> 
	
	<a href="javascript:EnviarForma('P')" title="<?php echo $msgstr["vistap"];?>" class="btn btn-primary campo">
		<i class="fa fa-eye" aria-hidden="true"></i>
	</a> 





<!--FECHA GERAR SA�DA-->






<?php
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_EDPFT"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])){
$save="Y";
?>

			 <a href="javascript:toggleLayer('saveformat')"><label><?php echo $msgstr["r_guardar"]?></label></a>
    		<div id=saveformat>
				<label><?php echo $msgstr["r_guardar"]." ".$db_path.$arrHttp["base"]."/". $arrHttp["Dir"];?> </label></td>
				<input type="text" name="nombre" class="form-control">
					<label><?php echo $msgstr["r_desc"];?></label>
					<input class="form-control" type="text" name="descripcion" >

					<a class="btn btn-primary" href=javascript:GuardarFormato()><i class="fa fa-save"></i></a>


<!--FECHA container conteudo-->



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

<form name="guardar" method="post" action="pft_update.php">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<input type="hidden" name="pft">
<input type="hidden" name="nombre">
<input type="hidden" name="descripcion">
<input type="hidden" name="tipof">
<input type="hidden" name="headings">
<input type="hidden" name="pftname">
<input type="hidden" name="Modulo" value="<?php if (isset($arrHttp["Modulo"])) echo $arrHttp["Modulo"];?>">
<input type="hidden" name="sel_oper">
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=S>"; ?>
</form>

<form name="frmdelete" action="pft_delete.php" method="post">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<input type="hidden" name="pft">
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=S>"; ?>
</form>

<form name="savesearch" action="../dataentry/busqueda_guardar.php" method="post" target="savesearch">
	<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
	<input type="hidden" name="Expresion" value="">
	<input type="hidden" name="Descripcion" value="">
</form>	<p>

<form name="sortkey" method="post" action="sortkey_edit.php" target="sortkey">
	<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
	<input type="hidden" name="encabezado" value="s">
</form>

<form name="listadosfrm" method="post">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
</form>




<?php
include("../common/footer.php");
?>
</body>
</html>
	<?php 
if (isset($arrHttp["pft"])and $arrHttp["pft"]!="") {
?> 

<script>
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
	echo "\n<script>toggleLayer('pftedit')\n</script>\n"; 
?>