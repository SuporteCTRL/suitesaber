<?php
function DibujarFormaBusqueda(){
global $arrHttp,$camposbusqueda,$db_path,$tagisis,$msgstr;

if (!isset($arrHttp["refine"]) ) unset ($_SESSION["Expresion"]);
// Prepare the advanced search form

	

//read search expressions stored
echo "\n<script>
str_expr=\"\"
str_search=new Array()
stored=\"\"
copyTo=\"\"\n";
$str_expr="";
unset($fp);

if (!isset($lang_db)) $lang_db=$_SESSION["lang"];
if (file_exists($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab"))
	$fp = file($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab");
else
	if (file_exists($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab"))
		$fp = file($db_path.$arrHttp["base"]."/pfts/".$lang_db."/search_expr.tab");
if (isset($fp)){
	$str_expr="Y";
	$ix=-1;
	foreach ($fp as $value){
		if (trim($value)!=""){
			$ix=$ix+1;
			$stored[$ix]=trim($value);
			echo "str_search[$ix]=\"".trim($value)."\"\n";
		}
	}
}
echo "</script>\n";

?>

<script languaje=javascript>
document.onkeypress =
	function (evt) {
			var c = document.layers ? evt.which
	       		: document.all ? event.keyCode
	       		: evt.keyCode;
			if (c==13) PrepararExpresion()
			return true;
	}

function CopyExpr(){
	Opc=str_search[stored]
	o=Opc.split('|')
	document.forma1.expre[copyTo].value=o[1]
	document.forma1.camp[copyTo].selectedIndex=0
}

function GetExpression(ic){
// get an previously stored search expression and copy to the search form
	copyTo=ic
	var winl = (screen.width-300)/2;
	var wint = (screen.height-100)/2;

	msgwin=window.open("","sst","width=400,height=100,left="+winl+",top="+wint+",scrollbars,resizable")
    msgwin.document.close()
	msgwin.document.writeln("<html><body><form name=forma1>")
	msgwin.document.writeln("<label><?php echo $msgstr["copysearch"];?></label>")
	msgwin.document.writeln("<select class=\"form-control\" name=ex onchange=window.opener.stored=document.forma1.ex.options[document.forma1.ex.selectedIndex].value;window.opener.CopyExpr();self.close()>\n")
	msgwin.document.writeln("<option value=\"\"></option>")
	<?php
	if (isset($stored)){
		foreach ($stored as $var=>$value){
			$s=explode("|",$value);
			echo "msgwin.document.writeln(\"<option value=$var>".$s[0]."\")\n";
		}
	}
	?>
    msgwin.document.writeln("</select>")
	msgwin.document.writeln("</body></html>")
	msgwin.document.close()
	msgwin.focus()
}

function Ayuda(){
	msgwin=window.open("../html/ayuda_expresion.html")
}
function switchMenu(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != "none" ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = '';
	}
}

/*function LeerExpresion(TipoR,Obj){
	var el = document.getElementById('Expre')
	el.style.display = 'none';
	var el = document.getElementById('myvar')
	el.style.display = '';
	url="busqueda_leer.php?base=<?php echo $arrHttp["base"]?>&tipor="+TipoR+"&obj="+Obj
	msgwin=window.open(url,"busqueda","menu=no,status=yes")
	msgwin.focus()
} */

function Buscar_Expre(){
	document.forma1.Expresion.value=document.forma1.Expre_b.value
<?php	if (!isset($arrHttp["Target"])){
		echo "msgwin=window.open(\"\",\"Resultados\",\"status=yes,resizable=yes,toolbar=no,menu=no,scrollbars=yes,width=630,height=400\")
		msgwin.focus()\n";
	}
?>
	document.forma1.submit()
}

function LimpiarBusqueda() {
  for (i=0; i<document.forma1.camp.length; i++){
      document.forma1.expre[i].value=""
      }
}


function PrepararExpresion(){
	j=document.forma1.expre.length
	Expresion=""
	Operadores=""
	Campos=""
	for (i=0;i<j;i++){
	    if (document.forma1.expre[i].value==""){
			document.forma1.expre[i].value=" "
		}
		if (Expresion==""){
			Expresion=document.forma1.expre[i].value
		}else{
			if (document.forma1.expre[i].value==" "){
				Expresion=Expresion+" ~~~ "
				document.forma1.expre[i].value=""
			}else{
				Expresion=Expresion+" ~~~ "+document.forma1.expre[i].value
			}
		}
		ixSel=document.forma1.camp[i].selectedIndex
		cc=document.forma1.camp[i].options[ixSel].value
		pref=dt[ixSel][2]
		if (Campos==""){
			Campos=cc
			Prefijo=pref
		}else{
			Campos=Campos+" ~~~ "+cc
			Prefijo=Prefijo+" ~~~ "+pref
		}
		if (i<j-1){
			ixSel=document.forma1.oper[i].selectedIndex
			if (ixSel=="undefined") ixSel=0
			cc=document.forma1.oper[i].options[ixSel].value
			if (Operadores==""){
				Operadores=cc
			}else{
				Operadores=Operadores+" ~~~ "+cc
			}
		}
	}
	document.forma1.Expresion.value=Expresion
	document.forma1.Campos.value=Campos
	document.forma1.Operadores.value=Operadores
	document.forma1.Prefijos.value=Prefijo

	for (i=0;i<j;i++){
		if (document.forma1.expre[i].value==" ") document.forma1.expre[i].value=""
	}
	SinExpre="S"
	for (i=0;i<j;i++){
		if (document.forma1.expre[i].value!="") SinExpre="N"
	}

	if (SinExpre=="S"){
		alert("<?php echo $msgstr["faltaexpr"]?>")
	}else{

		document.forma1.submit()
	}
}


function Diccionario(jx){
	j=document.forma1.camp[jx].selectedIndex
	if (document.forma1.camp[jx].options[j].value=="---"){
		alert("<?php echo $msgstr["selcampob"]?>")
		return
	}

<?php
	echo "document.diccio.base.value=\"".$arrHttp['base']."\"\n";
	echo "document.diccio.cipar.value=\"".$arrHttp['cipar']."\"\n";


?>
	document.diccio.Opcion.value="diccionario";
	nombrec=dt[j][0]
    prefijo=dt[j][2]
    decode=dt[j][3]
	if (prefijo=="--"){
		alert("<?php echo $msgstr["sinindice"]?>")
	}else{
		msgwin=window.open("","Diccionario","status=yes,resizable=yes,toolbar=no,menu=no,scrollbars=yes,height=500,width=700")
		msgwin.focus()
		id=dt[j][1]
		document.diccio.campo.value=escape(nombrec)
		document.diccio.prefijo.value=prefijo
		document.diccio.id.value=id
		document.diccio.Diccio.value=jx
		if (decode=="DECODE") document.diccio.Decode.value="S"
		document.diccio.submit()
	}

}


</script>
<?php
	echo "<script>";
	echo "var dt= new Array()\n";
	$ix=0;
	echo "dt[".$ix."]= new Array \n";
	echo "dt[".$ix."][0]=\"---\"\n";
	echo "dt[".$ix."][1]=\"\"\n";
	echo "dt[".$ix."][2]=\"\"\n";
	echo "dt[".$ix."][3]=\"\"\n";

	foreach ($camposbusqueda as $linea) {

		$ix=$ix+1;
		$l=explode('|',$linea);
		if (!isset($l[3])) $l[3]="";
		echo "dt[".$ix."]= new Array \n";
		echo "dt[".$ix."][0]=\"".$l[0]."\"\n";
		echo "dt[".$ix."][1]=\"".$l[1]."\"\n";
		echo "dt[".$ix."][2]=\"".$l[2]."\"\n";
		echo "dt[".$ix."][3]=\"".$l[3]."\"\n";
	}
	$Tope=$ix+1;  //significa que se van a colocar 7 cajas de texto con la expresi�n de b�squeda
	echo "</script>\n";
	echo "<body>";
	if (!isset ($arrHttp["encabezado"])){
		
		if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
		
		echo "<label> Script: dataentry/buscar.php</label>";
		echo "</div>";
	}
	echo "<div class=\"middle form\">
			<div class=\"formContent\">
	";

	echo "<form method=post name=forma1 action=";
	if (isset($arrHttp["Target"]) and $arrHttp["Target"]=="reserve")
		echo "buscar.php";
	else
		echo "../dataentry/buscar.php";
	echo "  onSubmit=\"javascript:return false\" >";

	echo '<input type=hidden name=tag8999 value="">';
	echo "<input type=hidden name=desde value=".$arrHttp['desde'].">\n";
	echo "<input type=hidden name=base value=".$arrHttp['base'].">\n";
	echo "<input type=hidden name=cipar value=".$arrHttp['cipar'].">\n";
	if (isset($arrHttp["copies"])) echo "<input type=hidden name=copies value=".$arrHttp['copies'].">\n";
    echo "<input type=hidden name=from value=1>\n";
	if (isset($arrHttp["Formato"]))echo "<input type=hidden name=Formato value=".$arrHttp['Formato'].">\n";
	if (isset($arrHttp["count"])) echo "<input type=hidden name=count value=".$arrHttp['count'].">\n";
	echo "<input type=hidden name=Opcion value=".$arrHttp['Opcion'].">\n";
	if (isset($arrHttp["prologo"])) echo "<input type=hidden name=prologo value=".$arrHttp['prologo'].">\n";
	if (isset($arrHttp["epilogo"])) echo "<input type=hidden name=epilogo value=".$arrHttp['epilogo'].">\n";
	echo "<input type=hidden name=Campos value=\"\">\n";
	echo "<input type=hidden name=Operadores value=\"\">\n";
	echo "<input type=hidden name=Expresion value=\"\">\n";
	echo "<input type=hidden name=Prefijos value=\"\">\n";
	if (isset($arrHttp["prestamo"])) echo "<input type=hidden name=prestamo value=\"".$arrHttp['prestamo']."\">\n";
	if (isset($arrHttp["Tabla"])) echo "<input type=hidden name=Tabla value=".$arrHttp['Tabla'].">\n";
	if (isset($arrHttp["copies"])) echo "<input type=hidden name=copies value=".$arrHttp['copies'].">\n";
	
	echo "<table class=\"table\">";
				if (file_exists($db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/search_expr.tab")){
					if (isset($_SESSION["permiso"]["db_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_ALL"]) or  isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])  or  isset($_SESSION["permiso"][$arrHttp["base"]."_EDITSTOREDEXPR"])){
						if (isset($arrHttp["desde"]) and $arrHttp["desde"]=="dataentry")
							echo "";
					}
				}
				echo "<label>".$msgstr["mensajeb"]."</label>
			<tr><div class=\"col-md-5\">
				<td><label>".$msgstr["campo"]."</label></td></div>
				<div class=\"col-md-6\">
				<td><label>".$msgstr["expresion"]."</label></td></div>
				
			</tr>";
	for ($jx=0;$jx<$Tope;$jx++){
		echo "<tr><div class=\"col-md-5\"><td>";
		echo "<select class=\"form-control\" name=camp>";

		echo "<OPTION value=\"---\">---";
		for ($i=0;$i<count($camposbusqueda);$i++){
			$linea=$camposbusqueda[$i];
			$l=explode('|',$linea);
			$parte3=$l[2];
			$parte2=$l[1];
			$parte1=$l[0];
			echo "<OPTION value='".$parte2."'";
			if ($jx==0 and isset($_SESSION["Expresion"])){

			}else{
				if ($i==$jx) echo " selected ";
			}
			echo ">".$parte1;
		}
		echo "</select></div>";
		echo "<a href='javascript:Diccionario(".$jx.")' class=\"btn btn-primary\"><i class=\"fa fa-list\" value=".$msgstr["indice"]."></i></a>";
		echo "</td>";
		echo "<td>";
		if (isset($_SESSION["Expresion"]) and $jx==0){
			$E=$_SESSION["Expresion"];
			//if (substr($E,0,1)=="("){
			//	$E=substr($E,1);
			//	if (substr($E,strlen($E)-1)==")")
			//		$E=substr($E,0,strlen($E)-1);
			//}
            $E=str_replace('"','',$E);
			echo "<div class=\"col-md-6\"><input type=text class=\"form-control\" name=expre value=\"".$E."\"></td><td>";
			unset($_SESSION["Expresion"]);
		}else{
			echo "<input type=text class=\"form-control\" name=expre value=\"\"></td><td>";
		}
		
		if ($jx<$Tope-1){
       		echo "<select class=\"form-control\" name=oper size=1>";
       		echo "<option value=and selected>and\n";
       		echo "<option value=or>or\n";
       		echo "<option value=^>and not\n";
       		echo "</select></div>";
 		}else {
       		echo "<input type=hidden name=oper>";
    	}

if ($str_expr=="Y")
			echo "<a href='Javascript:GetExpression(".$jx.")' class=\"btn btn-primary\"><i class=\"fa fa-search\" value=\"".$msgstr["copysearch"]."\"></i></a>";

   		echo "</td>\n";

	}
	echo "<tr>\n";
	echo "<td><br>\n";
	?>
	
		<a href='javascript:PrepararExpresion()' class="btn btn-primary">
		<i class="fa fa-search" value="<?php echo $msgstr["m_buscar"];?>"></i></a>

		<a href='javascript:LimpiarBusqueda(this,1)' class="btn btn-primary">
		<i class="fa fa-refresh" value="<?php echo $msgstr["borrar"];?>"></i></a>


	<?php
	if (isset($arrHttp["desde"]) and $arrHttp["desde"]=="reserve"){
	?>
	
	<?php

	}
	echo "</div>";
	echo "</td>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "<script>document.forma1.expre[0].blur()</script>\n";
	echo "<form name=diccio method=post action=../dataentry/diccionario.php target=Diccionario>\n";

	echo "<input type=hidden name=base value=".$arrHttp['base'].">\n";
	echo "<input type=hidden name=cipar value=".$arrHttp['cipar'].">\n";
	if (isset($arrHttp["Formato"]))echo "<input type=hidden name=Formato value=".$arrHttp['Formato'].">\n";
	echo "<input type=hidden name=Opcion value=buscar>\n";
	echo "<input type=hidden name=prefijo value=\"\">\n";
	echo "<input type=hidden name=campo value=\"\">\n";
	echo "<input type=hidden name=id value=\"\">\n";
	echo "<input type=hidden name=Diccio value=\"\">\n";
	echo "<input type=hidden name=Decode value=\"\">\n";
	echo "<input type=hidden name=desde value=".$arrHttp["desde"].">";
	if (isset($arrHttp["epilogo"])) echo "<input type=hidden name=epilogo value=".$arrHttp['epilogo'].">\n";
	if (isset($arrHttp["prologo"])) echo "<input type=hidden name=prologo value=".$arrHttp["prologo"].">\n";
	if (isset($arrHttp["prestamo"])) {
	    echo "<input type=hidden name=prestamo value=".$arrHttp['prestamo'].">\n";
	}
	if (isset($arrHttp["Target"])) {
	    echo "<input type=hidden name=Target value=".$arrHttp['Target'].">\n";
	}
	if (isset($arrHttp["Tabla"])) {
	    echo "<input type=hidden name=Tabla value=".$arrHttp['Tabla'].">\n";
	}
	echo "</form>\n";
    echo "</div></div></center>\n";
    include("../common/footer.php");
	echo "</body>";
	echo "</html>";

}
?>

