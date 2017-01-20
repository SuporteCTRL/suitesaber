<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");
include("../lang/lang.php");

include("../common/header.php");
include("fdt_include.php");

?>
<script  src="../dataentry/js/lr_trim.js"></script>
<script languaje=javascript>
function EditarAyuda(){
	ix=document.edithlp.FDT.selectedIndex
	if (ix==0) return
	ix1=document.edithlp.FDT.options[ix].value
	a=fdt[ix1]
	campo=a.split('|')
	tag=Trim(campo[1])
	lenguaje="<?php echo $_SESSION["lang"]?>"
	subcampo=""
	if (Trim(document.edithlp.subc.value)!="")
		subcampo="_"+document.edithlp.subc.value
	msgwin=window.open("../documentacion/edit_help_db.php?base=<?php echo $arrHttp["base"]?>&help="+tag+subcampo)
	msgwin.focus()

}

function VerAyuda(){
	ix=document.edithlp.FDT.selectedIndex
	if (ix==0) return
	ix1=document.edithlp.FDT.options[ix].value
	a=fdt[ix1]
	campo=a.split('|')
	subcampo=""
	if (Trim(document.edithlp.subc.value)!="")
		subcampo="_"+document.edithlp.subc.value
	tag="tag_"+campo[1]+subcampo+".html"
	lenguaje="<?php echo $_SESSION["lang"]?>"
	msgwin=window.open("../documentacion/ayuda_db.php?base=<?php echo $arrHttp["base"]?>&campo="+tag)
	msgwin.focus()

}
function ProducirSalida(a,tipo){
	tab=a.split('|')
	out=""
	if (tipo!="sc"){
		if (tab[1]!="") out="Tag: "+tab[1]+"\r"
		out+="Title: "+tab[2]+"\r"
		if (tab[3]==1)
			out+="Main Entry: yes\r"
		if (tab[4]==1)
			out+="Repetible: yes\r"
	}
    if (tab[5]!="") out+="Sub-fields: "+tab[5]+"\r"
    if (tipo=="sc") out+="Title: "+tab[2]+"\r"
    if (tab[6]!="") out+="Sub-fields pre literals: "+tab[6]+"\r"
    if (tab[7]!="") out+="Type of entry: "+input_type[tab[7]]+"\r"
    if (tab[8]!="") out+="Rows: "+tab[8]+"\r"
    if (tab[9]!="") out+="Cols: "+tab[9]+"\r"
    if (tab[10]!="") out+="Picklist type: "+pick_type[tab[10]]+"\r"
    if (tab[11]!="") out+="Picklist name: "+tab[11]+"\r"
    if (tab[12]!="") out+="Picklist prefix: "+tab[12]+"\r"
    if (tab[13]!="") out+="Picklist display format: "+tab[13]+"\r"
    if (tab[14]!="") out+="Picklist extract format: "+tab[14]+"\r"
    if (tab[15]==1) out+="Sub-fields assist: yes\r"
    if (tab[16]==1) out+="Help file: yes\r"
    return out
}

function Edit(){
	input_type=Array()
	pick_type=Array()
<?php
	foreach ($input_type as $key=>$type){
		echo  "input_type[\"$key\"]=\"$type\"\n";
	}
	foreach ($pick_type as $key=>$type){
		echo  "pick_type[\"$key\"]=\"$type\"\n";
	}
?>
	ix=document.edithlp.FDT.selectedIndex
	if (ix==0) return
	ix1=document.edithlp.FDT.options[ix].value
	a=fdt[ix1]
	variable=ProducirSalida(a,"campo")
    ix1++
    for (i=ix1;i<999;i++){
    	a=fdt[i]
		tab=a.split('|')
		if (tab[0]!="S") {
			i=1000
		}else{
			linea=ProducirSalida(a,"sc")
			variable+="\r"+linea
		}

    }
	document.edithlp.campo.value=variable
}
</script>
<?php
echo "<body>\n";
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>
<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["helpdatabasefields"].": ".$arrHttp["base"]?>
	</div>
	
<div class="middle form">
	<div class="formContent">
<form name="edithlp" method="post">

<?php
$archivo=$db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/".$arrHttp["base"].".fdt";
if (!file_exists($archivo)) $archivo=$db_path.$arrHttp["base"]."/def/".$lang_db."/".$arrHttp["base"].".fdt";
if (file_exists($archivo))
	$fp=file($archivo);
else
	echo "missing file ".$archivo;
$ixFdt=0;
echo "<script>\n";
echo "fdt=new Array()\n";
echo "fdt[0]=\"\"\n";
foreach ($fp as $value){
	$value=trim($value);
	$t=explode('|',$value);
	if ($t[1]!=""){
		$fdt[$t[1]]=$value;
		$ixFdt=$ixFdt+1;
		$value=str_replace('"','\"',$value);
		echo "fdt[$ixFdt]=\"$value\"\n";
	}
}
echo "</script><label>\n";
echo $msgstr["selfields"].": ";
echo "<select class=\"form-control\" name=FDT onchange=Edit()>
	</label><option></option>\n";
$ixFdt=0;
foreach ($fdt as $key=>$value){
	$t=explode('|',$value);
	$ixFdt=$ixFdt+1;
	echo "<option value=$ixFdt>".$t[1]." ".$t[2]."</option>\n";

}
echo "</select><label>";
echo " ".$msgstr["subfields"].": </label>
<input type=text name=subc class=\"form-control\"><br>";
$encabezado="";
if (isset($arrHttp["encabezado"])) $encabezado="&encabezado=s"

?>

<textarea name="campo" class="form-control"></textarea>
<br>
<a class="btn btn-primary" href='javascript:VerAyuda()'><i class="fa fa-eye" value="<?php echo $msgstr['preview'];?>"></i></a> 
<a href=javascript:EditarAyuda() class="btn btn-warning" ><i class="fa fa-pencil-square-o" value="<?php echo $msgstr['edhlp'];?>"></a></i>
</td>
</table>
</form>
<?php
echo "
</div>
</div>
";
include("../common/footer.php");
echo "</body></html>\n";
?>