tables_generate.php<?php
// ==================================================================================================
// GENERA LOS CUADROS ESTADÍSTICOS
// ==================================================================================================
//

session_start();
include("../common/get_post.php");
include ("../config.php");
include ("../lang/admin.php");
include ("../lang/statistics.php");

//foreach ($arrHttp as $key => $value) echo "$key = $value <br>";

//SE EXTRAE EL NOMBRE DE LA BASE DE DATOS
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

// SE LEE EL MÁXIMO MFN DE LA BASE DE DATOS
$IsisScript=$xWxis."administrar.xis";
$query = "&base=".$arrHttp["base"] . "&cipar=$db_path"."par/".$arrHttp["base"].".par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	$ix++;
	if ($ix>1) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		$tag[$a[0]]=$a[1];
	  	}
	}
}


//HEADER DEL LA PÁGINA HTML Y ARCHIVOS DE ESTIVO
include("../common/header.php");
?>
<script language="javascript1.2" src="../dataentry/js/lr_trim.js"></script>
<style type=text/css>

td{
	font-size:12px;
	font-family:Arial;
}

div#useextable{

	display: none;
	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#createtable{
<?php if ($arrHttp["Opcion"]!="new") echo "display: none;\n"?>

	margin: 0px 20px 0px 20px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}

div#generate{
	display: none;
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

div#configure{
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
		case "createtable":
<?php
		echo '
			EsconderVentana("useextable")
			break
			';

?>
		case "useextable":
			EsconderVentana("createtable")
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

function BorrarExpresion(){
	document.forma1.Expresion.value=''
}

function EnviarForma(){
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
    			alert("<?php echo $msgstr["inv_mfn"]?>")
	    		return
    		}
    	}
    	for (i = 0; i < a.length; i++){
    		strChar = a.charAt(i);
    		if (strValidChars.indexOf(strChar) == -1){
    			alert("<?php echo $msgstr["inv_mfn"]?>")
	    		return
    		}
    	}
    	de=Number(de)
    	a=Number(a)
    	if (de<=0 || a<=0 || de>a ||a><?php echo $tag["MAXMFN"]?>){
	    	alert("<?php echo $msgstr["inv_mfn"]?>")
	    	return
		}
	}
    if (Trim(document.forma1.Expresion.value)=="" && (Trim(document.forma1.Mfn.value)=="" )){
		alert("<?php echo $msgstr["selreg"]?>")
		return
	}
	if (Trim(document.forma1.Expresion.value)!="" && (Trim(document.forma1.Mfn.value)!="" )){
		alert("<?php echo $msgstr["selreg"]?>")
		return
	}
	if (document.forma1.tables.selectedIndex>0 ){
		if (document.forma1.rows.selectedIndex>0 || document.forma1.cols.selectedIndex>0){
			alert("<?php echo $msgstr["seltab"]?>")
			return
		}
	}
	if (document.forma1.tables.selectedIndex || document.forma1.rows.selectedIndex>0 || document.forma1.cols.selectedIndex>0){
	  	document.forma1.submit()
	  	return
	}
	document.forma1.submit();
}

function Buscar(){
	base=document.forma1.base.value
	cipar=document.forma1.cipar.value
  	Url="../dataentry/buscar.php?Opcion=formab&Target=s&Tabla=Expresion&base="+base+"&cipar="+cipar
  	msgwin=window.open(Url,"Buscar","menu=no, resizable,scrollbars,width=750,height=400")
	msgwin.focus()
}

function Configure(Option){
	if (document.configure.base.value==""){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
	switch (Option){
		case "stats_var":
			document.configure.action="config_vars.php"
			break
		case "stats_tab":
			document.configure.action="tables_cfg.php"
			break
	}
	document.configure.submit()
}
</script>
<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}
?>

            <div class="page-title">
              <div class="title_left">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> <?php echo $msgstr["stats"].": ".$arrHttp["base"];?></h3>
              </div>

              <div class="title_right">
					<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"];?>/stats/stats_tables_generate.html target=_blank><?php echo $msgstr["help"];?></a> Script: tables_generate.php
              </div>
            </div>

            <div class="clearfix"></div>

 <div class="container conteudo">

<form name="forma1" method="post" action="tables_generate_ex.php" onsubmit="Javascript:return false">
 <input type="hidden" name="base" value="<?php echo $arrHttp['base']; ?>">
 <input type="hidden" name="cipar" value="<?php echo $arrHttp['base']; ?>.par">
 <input type="hidden" name="Opcion">

<?php if (isset($arrHttp["encabezado"])) ;?>
 <input type="hidden" name="encabezado" value="s">


  <div class="panel-group" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $msgstr["exist_tb"];?>
           <i class="fa fa-thumb-tack" aria-hidden="true"></i></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div class="panel-body">  
          	 
		<select class="form control" name="tables" ><?php echo $msgstr["tab_list"]; ?>
    		<option value=''>
  <?php 
   unset($fp);
	$file=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/tabs.cfg";
	if (!file_exists($file)) $file=$db_path.$arrHttp["base"]."/def/".$lang_db."/tabs.cfg";
	if (!file_exists($file)){
		$error="S";
	}else{
		$fp=file($file);
		$fields="";
		foreach ($fp as $value) {
			$value=trim($value);
			if ($value!=""){
				$t=explode('|',$value);
				echo "<option value=".urlencode($value).">".trim($t[0])."</option>";
			}
		}
	}
?>
</option>
			</select>
         </div>
      </div>
    </div>

<div class="panel panel-default">
  <div class="panel-heading">
   <h4 class="panel-title">
     <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><?php echo $msgstr["create_tb"]?> <i class="fa fa-plus" aria-hidden="true"></i></a>
   </h4>
  </div>
<div id="collapse2" class="panel-collapse collapse">
 <div class="panel-body">

   <div class="col-md-6">
    <select class="form-control" name="rows">
    <option value=""> <?php echo $msgstr["rows"]; ?></option>
<?php
 	unset($fp);
	$file=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/stat.cfg";
	if (!file_exists($file)) $file=$db_path.$arrHttp["base"]."/def/".$lang_db."/stat.cfg";
	if (!file_exists($file)){
		$error="S";
	}else{
		$fp=file($file);
		foreach ($fp as $value) {
			$value=trim($value);
			if ($value!=""){
				$t=explode('|',$value);

				echo "<option value=\"".urlencode($value)."\">".trim($t[0])."</option>";
			}
		}
	}
?>

   </select>
   </div>

<div class="col-md-6">
 <select class="form-control" name="cols">
  <option value=""><?php echo $msgstr["cols"];?></option>
 
 <?php
		foreach ($fp as $value) {
			$value=trim($value);
			if ($value!=""){
				$t=explode('|',$value);
				echo "<option value=\"".$value."\">".trim($t[0])."</option>";
			}
		}
?>
 </select>
</div>

</div><!--panel body-->
</div><!--colapse2-->
</div> <!--panel default-->
<!--GERAR SAIDA-->
<div class="panel panel-default">
 <div class="panel-heading">
  <h4 class="panel-title">
   <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><?php echo $msgstr["gen_output"]?> <i class="fa fa-print" aria-hidden="true"></i></a>
  </h4>
 </div>
<div id="collapse3" class="panel-collapse collapse">
<div class="panel-body">

<div class="container conteudo">


 <label><?php echo $msgstr["bymfn"]; ?></label>
  <div class="control-group">
   <label class="control-label" for="textinput"><?php echo $msgstr["from"]; ?></label>
    <input id="textinput" name="Mfn" type="text" class="input-medium" value="1" required="">

   <label class="control-label" for="textinput"><?php echo $msgstr["to"]; ?></label>
    <input name="to" placeholder="<?php echo $tag["MAXMFN"]; ?>" value="<?php echo $tag["MAXMFN"];?>" type="text" class="input-medium" required="">
    
    <a href="javascript:BorrarRango()" class="btn btn-danger campo" ><i class="fa fa-trash" aria-hidden="true" value="<?php echo $msgstr["clear"];?>"></i>
      
    </a> 

   </div>
</div>
  <label><?php echo $msgstr["bysearch"];?></label>
  <a href="javascript:Buscar()" class="btn btn-warning campo">
  	<i class="fa fa-search" aria-hidden="true" alt="<?php echo $msgstr["bysearch"];?>"></i>
  </a>    


    <div class="form-group"> 
		<textarea class="form-control input-lg" rows="2" name="Expresion"><?php if (isset($Expresion )) echo $Expresion ;?></textarea>
 	
    </div>
     <a href=javascript:BorrarExpresion() class="btn btn-primary"><i class="fa fa-trash" aria-hidden="true" value="<?php echo $msgstr["clear"]?>"></i></a> 

   <a class="btn btn-primary" onclick='EnviarForma()'><i class="fa fa-check" value="<?php echo $msgstr["send"]?>"></i></a>
     

</div> <!--div container-->
</div><!--panel d-->
</div><!--acordion-->

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><?php echo $msgstr["stats_conf"]?> <i class="fa fa-thumb-tack" aria-hidden="true"></i></a>
        </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse">
        <div class="panel-body">

    		<a href="javascript:Configure('stats_var')" class="btn btn-primary"><?php echo $msgstr["var_list"]?></a>
            <a href="javascript:Configure('stats_tab')" class="btn btn-primary"><?php echo $msgstr["tab_list"]?></a>

        </div>
      </div>
    </div>


</div>
</div>
</div>

</form>
<form name="configure" onSubmit="return false">
	<input type="hidden" name="Opcion" value="update">
	<input type="hidden" name="from" value="statistics">
	<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
	<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>";?>
</form>

<?php
include("../common/footer.php");
?>

</body>
</html>