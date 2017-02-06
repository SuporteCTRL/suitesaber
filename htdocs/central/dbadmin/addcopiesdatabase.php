<?php

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../common/header.php");
$converter_path=$mx_path;
$base_ant=$arrHttp["base"];
echo "<script src=../dataentry/js/lr_trim.js></script>";
echo "<body onunload=win.close()>\n";
if (isset($arrHttp["encabezado"])) {
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}


?>
<div class="helper">

<script language="javascript">
function AlterEntry(opcion)
{
value=document.form1.agregar.value;

//Main Library
if (value=="ml")
if (opcion==1)
{
if (document.getElementById("ml").style.display=='none')
{
document.getElementById("ml").innerHTML='<label>Main Library Field:</label><input name="mlf" type="text" id="mlf" /><label>SubField:</label><input name="mlsf" type="text" id="mlsf"/>';
document.getElementById("ml").style.display='block';
}
}
else
{
document.getElementById("ml").innerHTML='';
document.getElementById("ml").style.display='none';
}
//Branch Library
if (value=="bl")
if (opcion==1)
{
if (document.getElementById("bl").style.display=='none')
{
document.getElementById("bl").innerHTML='<label>Branch Library Field:</label><input name="blf" type="text" id="blf"/><label>SubField:</label><input name="blsf" type="text" id="blsf" />';
document.getElementById("bl").style.display='block';
}
}
else
{
document.getElementById("bl").innerHTML='';
document.getElementById("bl").style.display='none';
}
//Tome
if (value=="tome")
if (opcion==1)
{
if (document.getElementById("tome").style.display=='none')
{
document.getElementById("tome").innerHTML='<label>Tome Field:</label><input name="tomef" type="text" id="tomef" /><label>SubField:</label><input name="tomesf" type="text" id="tomesf"/>';
document.getElementById("tome").style.display='block';
}
}
else
{
document.getElementById("tome").innerHTML='';
document.getElementById("tome").style.display='none';
}
//Volume
if (value=="volume")
if (opcion==1)
{
if (document.getElementById("volume").style.display=='none')
{
document.getElementById("volume").innerHTML='<label>Volume/Part Field:</label><input name="volumef" type="text" id="volumef"/><label>SubField:</label><input name="volumesf" type="text" id="volumesf" />';
document.getElementById("volume").style.display='block';
}
}
else
{
document.getElementById("volume").innerHTML='';
document.getElementById("volume").style.display='none';
}
//Copy Number
if (value=="cpnum")
if (opcion==1)
{
if (document.getElementById("cpnum").style.display=='none')
{
document.getElementById("cpnum").innerHTML='<label>Copy Number Field:</label><input name="cpnumf" type="text" id="cpnumf"/><label>SubField:</label><input name="cpnumsf" type="text" id="cpnumsf" />';

document.getElementById("cpnum").style.display='block';
}
}
else
{
document.getElementById("cpnum").innerHTML='';
document.getElementById("cpnum").style.display='none';
}
//Acquisition
if (value=="ad")
if (opcion==1)
{
if (document.getElementById("ad").style.display=='none')
{
document.getElementById("ad").innerHTML='<label>Acquisition Field:</label><input name="adf" type="text" id="adf"/><label>SubField:</label><input name="adsf" type="text" id="adsf"/>';
document.getElementById("ad").style.display='block';
}
}
else
{
document.getElementById("ad").innerHTML='';
document.getElementById("ad").style.display='none';
}
//Provider
if (value=="provider")
if (opcion==1)
{
if (document.getElementById("provider").style.display=='none')
{
document.getElementById("provider").innerHTML='<label>Provider Field:</label><input name="providerf" type="text" id="providerf" /><label>SubField:</label><input name="providersf" type="text" id="providersf" />';
document.getElementById("provider").style.display='block';
}
}
else
{
document.getElementById("provider").innerHTML='';
document.getElementById("provider").style.display='none';
}
//Date of arraival
if (value=="date")
if (opcion==1)
{
if (document.getElementById("date").style.display=='none')
{
document.getElementById("date").innerHTML='<label>Date of arraival Field:</label><input name="datef" type="text" id="datef" /><label>SubField:</label><input name="datesf" type="text" id="datesf" />';
document.getElementById("date").style.display='block';
}
}
else
{
document.getElementById("date").innerHTML='';
document.getElementById("date").style.display='none';
}
//Price
if (value=="price")
if (opcion==1)
{
if (document.getElementById("price").style.display=='none')
{
document.getElementById("price").innerHTML='<label>Price Field:</label><input name="pricef" type="text" id="pricef" /><label>SubField:</label><input name="pricesf" type="text" id="pricesf" />';
document.getElementById("price").style.display='block';
}
}
else
{
document.getElementById("price").innerHTML='';
document.getElementById("price").style.display='none';
}
//Purchase order
if (value=="po")
if (opcion==1)
{
if (document.getElementById("po").style.display=='none')
{
document.getElementById("po").innerHTML='<label>Purchase order Field:</label><input name="pof" type="text" id="pof" /><label>SubField:</label><input name="posf" type="text" id="posf"/>';
document.getElementById("po").style.display='block';
}
}
else
{
document.getElementById("po").innerHTML='';
document.getElementById("po").style.display='none';
}
//Suggestion number
if (value=="sn")
if (opcion==1)
{
if (document.getElementById("sn").style.display=='none')
{
document.getElementById("sn").innerHTML='<label>Suggestion number Field:</label><input name="snf" type="text" id="snf" /><label>SubField:</label><input name="snsf" type="text" id="snsf" />';
document.getElementById("sn").style.display='block';
}
}
else
{
document.getElementById("sn").innerHTML='';
document.getElementById("sn").style.display='none';
}
//Conditions
if (value=="cond")
if (opcion==1)
{
if (document.getElementById("cond").style.display=='none')
{
document.getElementById("cond").innerHTML='<label>Conditions Field:</label><input name="condf" type="text" id="condf" /><label>SubField:</label><input name="condsf" type="text" id="condsf" />';
document.getElementById("cond").style.display='block';
}
}
else
{
document.getElementById("cond").innerHTML='';
document.getElementById("cond").style.display='none';
}
//In exchange of
if (value=="exchange")
if (opcion==1)
{
if (document.getElementById("exchange").style.display=='none')
{
document.getElementById("exchange").innerHTML='<label>In exchange of Field:</label><input name="exchangef" type="text" id="exchangef" /><label>SubField:</label><input name="exchangesf" type="text" id="exchangesf" />';
document.getElementById("exchange").style.display='block';
}
}
else
{
document.getElementById("exchange").innerHTML='';
document.getElementById("exchange").style.display='none';
}
}
function ComprobarNum(origin)
{
numero=document.getElementById(origin).value;
if (isNaN(numero)==true)
  {
    alert("Please check the number in the field: "+origin);
    if (origin=='from') document.form1.from.focus();
    else document.form1.to.focus();
    return;
  }
}
function ChangeOption(option)
{
if (option==1)
{
document.getElementById("systype").style.display="block";
document.getElementById("fieldsel").style.display="none";
}
else
{
document.getElementById("systype").style.display="none";
document.getElementById("fieldsel").style.display="block";
document.form1.typef.focus();
}
}
</script>
</div>
<div class="middle form">
	<div class="formContent">
<form action="" method="post" name="form1" target="_self" id="form1" onsubmit="OpenWindows();">

 <h2><?php echo $msgstr['addcopiesdatabase'];?> </h2>
   <input type="hidden" value="$base_ant" name="base">
 <?php echo "".$msgstr["database"]." ".$base_ant."<p>";?>
  



<div class="form-group">
  <label  class="col-sm-1 col-form-label">From: </label>
   <div class="col-sm-2">
   <input class="form-control" type="text" name="from" id="from" onchange="ComprobarNum('from')"/>
  
</div>   
    <label  class="col-sm-1 col-form-label">To :</label>
     <div class="col-sm-2">
  <input class="form-control" type="text" name="to" id="to" onchange="ComprobarNum('to')"/>
  </div>
 


      
	<script language="javascript">//estableciendo el foco en el 2do textbox
   document.form1.from.value="1";
  document.form1.to.focus();
     function OpenWindows() {
NewWindow("../dataentry/img/preloader.gif","progress",100,100,"NO","center")
win.focus()
    }
function NewWindow(mypage,myname,w,h,scroll,pos){
if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
win=window.open(mypage,myname,settings);}

  </script>


  <label  class="col-sm-1 col-form-label"> Last MFN:</label>
<?php
$IsisScript=$xWxis."administrar.xis";
$query = "&base=".$base_ant."&cipar=$db_path"."par/".$base_ant.".par&Opcion=status";
include("../common/wxis_llamar.php"); 
$ix=-1;
foreach($contenido as $linea) {
	//echo "$linea<br>";
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		if (isset($a[1])) $tag[$a[0]]=$a[1];
	  	}
	}
}

$total=(int) $tag["MAXMFN"];
echo '<script language="javascript">
   document.form1.to.value="'.$total.'";
   </script>';
echo $total;
  ?>


<div class="form-group row">
  <label  class="col-sm-2 col-form-label">Control Number Field:</label>
    <div class="col-sm-2">
      <input name="cnf" type="text" id="cnf" value="
  <?php 
  if (isset($_POST["cnf"])) 
    echo $_POST["cnf"]; 
  else 
    echo "v1";?>" 
>
 </div>
 </div>

 <div class="form-group row">
  <label  class="col-sm-2 col-form-label">SubField:</label>
  <div class="col-sm-2">
  <input name="cnsf" type="text" id="cnsf" value="
  <?php 
  if (isset($_POST["cnsf"])) 
    echo $_POST["cnsf"];
  ?>">
  
</div>
</div>

  <div class="form-group row">
<div class="col-md-5">
  <select class="form-control" name="agregar" id="atunique" onChange="AlterEntry(1)" >
	<option value="">adicionar</option>
	<option value="ml">Main Library</option>
	<option value="bl">Branch Library</option>
	<option value="tome">Tome</option>
	<option value="volume">Volume/Part</option>
	<option value="cpnum">Copy Number</option>
	<option value="ad">Acquisition type</option>
	<option value="provider">Provider/Donnor/Institution</option>
	<option value="date">Date of arraival</option>
	<option value="price">Price</option>
	<option value="po">Purchase order</option>
	<option value="sn">Suggestion number</option>
	<option value="cond">Conditions</option>
	<option value="exchange">In exchange of</option>
	</select>
  <a href=javascript:AlterEntry(0) class="btn btn-danger"><i class="fa fa-times" aria hidden="true"></i></a>
	</div>

</div>
  <div class="form-group row">
  <label>Inventory Number Field:</label>
  <input name="inf" class="form-control" type="text" id="inf" value="">
  <?php 
  if (isset($_POST["inf"])) 
    echo $_POST['inf'];
  ?>



    <label>SubField:</label>
  <input name="insf" class="form-control" type="text" id="insf" value="
  <?php 
  if (isset($_POST["insf"])) 
    echo $_POST["insf"];
  ?> ">
  


<?php
if (isset($_POST["mlf"]))
echo '<div id="ml" style="display:block"><label>Main Library Field:</label><input name="mlf" type="text" id="mlf" value="'.$_POST["mlf"].'"/><label>SubField:</label><input name="mlsf" type="text" id="mlsf" value="'.$_POST["mlsf"].'" /></div>';
else
echo '<div id="ml" style="display:none"></div>';

if (isset($_POST["blf"]))
echo '<div id="bl" style="display:block"><label>Branch Library Field:</label><input name="blf" type="text" id="blf" value="'.$_POST["blf"].'" /><label>SubField:</label><input name="blsf" type="text" id="blsf" value="'.$_POST["blsf"].'" /></div>';
else
echo '<div id="bl" style="display:none"></div>';

if (isset($_POST["tomef"]))
echo '<div id="tome" style="display:block"><label>Tome Field:</label><input name="tomef" type="text" id="tomef" value="'.$_POST["tomef"].'" /><label>SubField:</label><input name="tomesf" type="text" id="tomesf" value="'.$_POST["tomesf"].'"></div>';
else
echo '<div id="tome" style="display:none"></div>';

if (isset($_POST["volumef"]))
echo '<div id="volume" style="display:block"><label>Volume Field:</label><input name="volumef" type="text" id="volumef" value="'.$_POST["volumef"].'" /><label>SubField:</label><input name="volumesf" type="text" id="volumesf" value="'.$_POST["volumesf"].'" /></div>';
else
echo '<div id="volume" style="display:none"></div>';

if (isset($_POST["cpnumf"]))
echo '<div id="cpnum" style="display:block"><label>Copy Number Field:</label><input name="cpnumf" type="text" id="cpnumf" value="'.$_POST["cpnumf"].'" /><label>SubField:</label><input name="cpnumsf" type="text" id="cpnumsf" value="'.$_POST["cpnumsf"].'"/></div>';
else
echo '<div id="cpnum" style="display:none"></div>';

if (isset($_POST["adf"]))
echo '<div id="ad" style="display:block"><label>Acquisition Field:</label><input name="adf" type="text" id="adf" value="'.$_POST["adf"].'" /><label>SubField:</label><input name="adsf" type="text" id="adsf" value="'.$_POST["adsf"].'"/></div>';
else
echo '<div id="ad" style="display:none"></div>';

if (isset($_POST["providerf"]))
echo '<div id="provider" style="display:block"><label>Provider Field:</label><input name="providerf" type="text" id="providerf" value="'.$_POST["providerf"].'" /><label>SubField:</label><input name="providersf" type="text" id="providersf" value="'.$_POST["providersf"].'" /></div>';
else
echo '<div id="provider" style="display:none"></div>';

if (isset($_POST["datef"]))
echo '<div id="date" style="display:block"><label>Date Field:</label><input name="datef" type="text" id="datef" value="'.$_POST["datef"].'" /><label>SubField:</label><input name="datesf" type="text" id="datesf" value="'.$_POST["datesf"].'" /></div>';
else
echo '<div id="date" style="display:none"></div>';

if (isset($_POST["pricef"]))
echo '<div id="price" style="display:block"><label>Price Field:</label><input name="pricef" type="text" id="pricef" value="'.$_POST["pricef"].'" /><label>SubField:</label><input name="pricesf" type="text" id="pricesf" value="'.$_POST["pricesf"].'" /></div>';
else
echo '<div id="price" style="display:none"></div>';

if (isset($_POST["pof"]))
echo '<div id="po" style="display:block"><label>Purchase order Field:</label><input name="pof" type="text" id="pof" value="'.$_POST["pof"].'" /><label>SubField:</label><input name="posf" type="text" id="posf" value="'.$_POST["posf"].'" /></div>';
else
echo '<div id="po" style="display:none"></div>';

if (isset($_POST["snf"]))
echo '<div id="sn" style="display:block"><label>Suggestion number Field:</label><input name="snf" type="text" id="snf" value="'.$_POST["snf"].'" /><label>SubField:</label><input name="snsf" type="text" id="snsf" value="'.$_POST["snsf"].'" /></div>';
else
echo '<div id="sn" style="display:none"></div>';

if (isset($_POST["condf"]))
echo '<div id="cond" style="display:block"><label>Conditions Field:</label><input name="condf" type="text" id="condf" value="'.$_POST["condf"].'" /><label>SubField:</label><input name="condsf" type="text" id="condsf" value="'.$_POST["condsf"].'" /></div>';
else
echo '<div id="cond" style="display:none"></div>';

if (isset($_POST["exchangef"]))
echo '<div id="exchange" style="display:block"><label>In exchange of Field:</label><input name="exchangef" type="text" id="exchangef" value="'.$_POST["exchangef"].'" /><label>SubField:</label><input name="exchangesf" type="text" id="exchangesf" value="'.$_POST["exchangesf"].'" "/></div>';
else
echo '<div id="exchange" style="display:none"></div>';
?>
<label>Use the system types:</label>
<input name="radiobutton" type="radio" value="systype" 
<?php 
if (isset($_POST["radiobutton"])) 
  echo "checked"; 
else 
  if (isset($_POST["submit"])) 
    echo '"checked"'; 
  ?> 
  onchange="ChangeOption(1)"/>
     


<div id="systype" style="display:
<?php 
if (isset($_POST["radiobutton"])) 
  echo "block"; 
else 
  if (isset($_POST["submit"]))
    echo "block"; 
  else 
    echo "none"; ?>">
	    
      <label>Type of object:</label>
<select name="type" id="type">
   <?php
	@ $fp = fopen($db_path."circulation/def/$lang/items.tab", "r");
 flock($fp, 1);
 if (!$fp)
   {
     echo "Unable to open file circulation/def/$lang/items.tab.</body></html>";
     exit;
   }
while(!feof($fp))
{
 $order= fgets($fp, 100);
 $splitorder=explode("|",$order);
 if ($splitorder[0]!="" and $splitorder[1]!="") if ($_POST["type"]==$splitorder[0]) echo "<option value=\"$splitorder[0]\" selected=\"selected\" > $splitorder[1]</option>"; else echo "<option value=\"$splitorder[0]\" > $splitorder[1]</option>";
}
 flock($fp, 3);
  fclose($fp);
   ?>
    </select>
    

    <label>Use a field-subfield combination:</label>
      <input  name="radiobutton" type="radio" value="fieldsel" 
      <?php 
      if (isset($_POST["radiobutton"])) 
        echo '"checked"'; 
      ?> 
      onchange="ChangeOption(0)"/>
 
   <div id="fieldsel" style="display:
   <?php 
   if (isset($_POST["radiobutton"])) 
    echo "block"; 
  else 
    echo "none"; ?>">
      

      <label>Type Field:</label>
  <input class="form-control" name="typef" type="text" id="typef" value="
  <?php 
  if (isset($_POST["typef"])) 
    echo $_POST["typef"];
  ?>" 
  size="5"/>


 <label>SubField:</label>
  <input class="form-control" name="typesf" type="text" id="typesf" value="
  <?php 
  if (isset($_POST["typesf"])) 
    echo $_POST["typesf"];
  ?>" 
  size="5"/>
 


<?php
  echo "<input class=\"btn btn-default\" type=submit name=submit value=".$msgstr["update"].">";
  if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>";
 ?>

</form>
</div>
<?php
function Vfield($field)
{
$field=trim($field);
if (($field!="") && ($field[0]!=='v')) return 'v'.$field;
return $field;
}
function RemovePico($field)
{
$field=trim($field);
if ($field[0]=='^') return str_replace( '^','',$field);
return $field;
}
if (isset($_POST['submit']))
{
$from=$_POST['from'];
$to=$_POST['to'];
if (!is_numeric($from) or !is_numeric($to))
{
echo '<br /><span style="color:red">The fields "from" or "to" had non numeric values</span>';
}
else
{
//Getting the fields information
$cnf=Vfield(strtolower($_POST['cnf']));
$inf=Vfield(strtolower($_POST['inf']));
$mlf=Vfield(strtolower($_POST['mlf']));
$blf=Vfield(strtolower($_POST['blf']));
$tomef=Vfield(strtolower($_POST['tomef']));
$volumef=Vfield(strtolower($_POST['volumef']));
$cpnumf=Vfield(strtolower($_POST['cpnumf']));
$adf=Vfield(strtolower($_POST['adf']));
$providerf=Vfield(strtolower($_POST['providerf']));
$datef=Vfield(strtolower($_POST['datef']));
$pricef=Vfield(strtolower($_POST['pricef']));
$pof=Vfield(strtolower($_POST['pof']));
$snf=Vfield(strtolower($_POST['snf']));
$condf=Vfield(strtolower($_POST['condf']));
$exchangef=Vfield(strtolower($_POST['exchangef']));
//Getting the subfields information
$cnsf=RemovePico(strtolower($_POST['cnsf']));
$insf=RemovePico(strtolower($_POST['insf']));
$mlsf=RemovePico(strtolower($_POST['mlsf']));
$blsf=RemovePico(strtolower($_POST['blsf']));
$tomesf=RemovePico(strtolower($_POST['tomesf']));
$volumesf=RemovePico(strtolower($_POST['volumesf']));
$cpnumsf=RemovePico(strtolower($_POST['cpnumsf']));
$adsf=RemovePico(strtolower($_POST['adsf']));
$providersf=RemovePico(strtolower($_POST['providersf']));
$datesf=RemovePico(strtolower($_POST['datesf']));
$pricesf=RemovePico(strtolower($_POST['pricesf']));
$posf=RemovePico(strtolower($_POST['posf']));
$snsf=RemovePico(strtolower($_POST['snsf']));
$condsf=RemovePico(strtolower($_POST['condsf']));
$exchangesf=RemovePico(strtolower($_POST['exchangesf']));
//Concatenating the fields and subfields
$cnfent="";
$infent="";
$mlfent="";
$blfent="";
$tomefent="";
$volumefent="";
$cpnumfent="";
$adfent="";
$providerfent="";
$datefent="";
$pricefent="";
$pofent="";
$snfent="";
$condfent="";
$exchangefent="";
if ($cnsf=="") $cnfent=$cnf; else $cnfent=$cnf."^".$cnsf;
if ($insf=="") $infent=$inf; else $infent=$inf."^".$insf;
if ($mlsf=="") $mlfent=$mlf; else $mlfent=$mlf."^".$mlsf;
if ($blsf=="") $blfent=$blf; else $blfent=$blf."^".$blsf;
if ($tomesf=="") $tomefent=$tomef; else $tomefent=$tomef."^".$tomesf;
if ($volumesf=="") $volumefent=$volumef; else $volumefent=$volumef."^".$volumesf;
if ($cpnumsf=="") $cpnumfent=$cpnumf; else $cpnumfent=$cpnumf."^".$cpnumsf;
if ($adsf=="") $adfent=$adf; else $adfent=$adf."^".$adsf;
if ($providersf=="") $providerfent=$providerf; else $providerfent=$providerf."^".$providersf;
if ($datesf=="") $datefent=$datef; else $datefent=$datef."^".$datesf;
if ($pricesf=="") $pricefent=$pricef; else $pricefent=$pricef."^".$pricesf;
if ($posf=="") $pofent=$pof; else $pofent=$pof."^".$posf;
if ($snsf=="") $snfent=$snf; else $snfent=$snf."^".$snsf;
if ($condsf=="") $condfent=$condf; else $condfent=$condf."^".$condsf;
if ($exchangesf=="") $exchangefent=$exchangef; else $exchangefent=$exchangef."^".$exchangesf;
$IsisScript=$xWxis."administrar.xis";
$query = "&base=copies&cipar=$db_path"."par/copies.par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	//echo "$linea<br>";
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		if (isset($a[1])) $tag[$a[0]]=$a[1];
	  	}
	}
}
$cantcopiersbefore=(int) $tag["MAXMFN"];
$IsisScript=$xWxis."administrar.xis";
$query = "&base=loanobjects&cipar=$db_path"."par/loanobjects.par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	//echo "$linea<br>";
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		if (isset($a[1])) $tag[$a[0]]=$a[1];
	  	}
	}
}
$cantloabobjectsbefore=(int) $tag["MAXMFN"];
//Copies Work----------------------------------------------------------------------
// SE LEE LA TABLA DE STATUS DE LAS COPIAS
$mysentstatus="^a1^bIn process in technical office";
$mystatcopy="^a2^bSent to loanobjects";
$tab_st=$db_path."copies/def/".$_SESSION["lang"]."/status_copy.tab";
if (!file_exists($tab_st))
	$tab_st=$db_path."copies/def/".$lang_db."/status_copy.tab";
if (file_exists($tab_st)){
$fp=file($tab_st);
foreach ($fp as $value){
	$value=trim($value);
	if ($value!=""){
		$v=explode("|",$value);
		$status["^a".$v[0]."^b".$v[1]]="(".$v[0].") ".$v[1];
		if ($v[0]==1) $mysentstatus="^a".$v[0]."^b".$v[1];
		if ($v[0]==2) $mystatcopy="^a".$v[0]."^b".$v[1];
	}

}
}
@ $fp = fopen($db_path."wrk/cp_create.pft", "w");
if (!$fp)
 {
   echo "Unable to write the file ".$db_path."/wrk/cp_create.pft";
   exit;
 }
$savestring="mpl,
(if p(".$infent.") then \n";
if ($cnf!=$inf) $savestring.=$cnfent."[1],'|',\n";
else $savestring.=$cnfent.",'|',\n";
$savestring.="'".$base_ant."|',
".$infent.",'|',
'".$mystatcopy."|',\n";

if ($mlfent!="")
{
 if ($mlf!=$inf) $savestring.=$mlfent."[1],'|',\n";
 else $savestring.=$mlfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($blfent!="")
{
 if ($blf!=$inf) $savestring.=$blfent."[1],'|',\n";
 else $savestring.=$blfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($tomefent!="")
{
 if ($tomef!=$inf) $savestring.=$tomefent."[1],'|',\n";
 else $savestring.=$tomefent.",'|',\n";
}
else $savestring.="'|',\n";

if ($volumefent!="")
{
 if ($volumef!=$inf) $savestring.=$volumefent."[1],'|',\n";
 else $savestring.=$volumefent.",'|',\n";
}
else $savestring.="'|',\n";

if ($cpnumfent!="")
{
 if ($cpnumf!=$inf) $savestring.=$cpnumfent."[1],'|',\n";
 else $savestring.=$cpnumfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($adfent!="")
{
 if ($adf!=$inf) $savestring.=$adfent."[1],'|',\n";
 else $savestring.=$adfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($providerfent!="")
{
 if ($providerf!=$inf) $savestring.=$providerfent."[1],'|',\n";
 else $savestring.=$providerfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($datefent!="")
{
 if ($datef!=$inf) $savestring.=$datefent."[1],'|',\n";
 else $savestring.=$datefent.",'|',\n";
}
else $savestring.="'|',\n";

if ($pricefent!="")
{
 if ($pricef!=$inf) $savestring.=$pricefent."[1],'|',\n";
 else $savestring.=$pricefent.",'|',\n";
}
else $savestring.="'|',\n";

if ($pofent!="")
{
 if ($pof!=$inf) $savestring.=$pofent."[1],'|',\n";
 else $savestring.=$pofent.",'|',\n";
}
else $savestring.="'|',\n";

if ($snfent!="")
{
 if ($snf!=$inf) $savestring.=$snfent."[1],'|',\n";
 else $savestring.=$snfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($condfent!="")
{
 if ($condf!=$inf) $savestring.=$condfent."[1],'|',\n";
 else $savestring.=$condfent.",'|',\n";
}
else $savestring.="'|',\n";

if ($exchangefent!="")
{
 if ($exchangef!=$inf) $savestring.=$exchangefent."[1],'|',\n";
 else $savestring.=$exchangefent.",'|',\n";
}
else $savestring.="'|',\n";


$savestring.="/ fi)\n";

fwrite($fp,$savestring);
fclose($fp);
@ $fp = fopen($db_path."wrk/cp_create.prc", "w");
if (!$fp)
 {
   echo "Unable to write the file ".$db_path."/wrk/cp_create.prc";
   exit;
 }
$savestring="'d*',
'<1>'v1'</1>',
'<10>'v2'</10>',
'<30>'v3'</30>',
'<200>'v4'</200>',
'<35>'v5'</35>',
'<40>'v6'</40>',
'<50>'v7'</50>',
'<60>'v8'</60>',
'<63>'v9'</63>',
'<68>'v10'</68>',
'<70>'v11'</70>',
'<80>'v12'</80>',
'<90>'v13'</90>',
'<100>'v14'</100>',
'<110>'v15'</110>',
'<300>'v16'</300>',
'<400>'v17'</400>',
";
fwrite($fp,$savestring);
fclose($fp);

$mx=$converter_path." $db_path".$base_ant."/data/".$base_ant." pft=@".$db_path."wrk/cp_create.pft from=$from to=$to lw=600 now -all >".$db_path."wrk/copies.lst";
exec($mx,$outmx,$banderamx);
$mx1=$converter_path." seq=".$db_path."wrk/copies.lst proc=@".$db_path."wrk/cp_create.prc append=".$db_path."copies/data/copies now -all";
exec($mx1,$outmx1,$banderamx1);
$mxinv=$converter_path." ".$db_path."copies/data/copies fst=@".$db_path."copies/data/copies.fst fullinv=".$db_path."copies/data/copies now -all";
exec($mxinv, $outputmxinv,$banderamxinv);
//End of Copies Work----------------------------------------------------------------------
//Loanobjects Work----------------------------------------------------------------------
@ $fp = fopen($db_path."wrk/lo_create.prc", "w");
if (!$fp)
 {
   echo "Unable to write the file ".$db_path."wrk/lo_create.prc";
   exit;
 }
$savestring="'d*',
if p(v3330) then
'<1>',v1,'</1>',
'<10>',v3310[1],'</10>',
('<959>',
'^i'v3330,
'^l'v3335,
'^b'v3340,\n";
if ($_POST["radiobutton"]=="systype") $savestring.="'^o".$_POST['type']."',\n";
else $savestring.="'^o'".Vfield(strtolower($_POST['typef']))."^".RemovePico(strtolower($_POST['typesf'])).",\n";
$savestring.="'</959>',),
fi,
";
fwrite($fp,$savestring);
fclose($fp);
$mx=$converter_path." $db_path".$base_ant."/data/".$base_ant." \"join=".$db_path."copies/data/copies,3330:30,3335:35,3320:200,3340:40,3310:10=mpu,'CN_".$base_ant."_'$cnfent\" \"proc=@".$db_path."wrk/lo_create.prc\" append=".$db_path."loanobjects/data/loanobjects ";
if ($inf!="") $mx.="from=$from to=$to ";
$mx.="now -all";
exec($mx,$outmx,$banderamx);
$mxinv=$converter_path." ".$db_path."loanobjects/data/loanobjects fst=@".$db_path."loanobjects/data/loanobjects.fst fullinv=".$db_path."loanobjects/data/loanobjects now -all";
exec($mxinv, $outputmxinv,$banderamxinv);
//Update the copy status if necessary
if ($inf=="")
{
@ $fp = fopen($db_path."wrk/cp_change.prc", "w");
if (!$fp)
 {
   echo "Unable to write the file ".$db_path."wrk/cp_change.prc";
   exit;
 }
$savestring="if v10='".$base_ant."' then if v200^a='1' then 'd200', '<200>".$mystatcopy."</200>',fi,fi,";
fwrite($fp,$savestring);
fclose($fp);
$mxchcopies=$converter_path." ".$db_path."copies/data/copies \"proc=@".$db_path."wrk/cp_change.prc\" copy=".$db_path."copies/data/copies now -all";
exec($mxchcopies, $outputchcopie,$banderamxchcopie);
}

//End of Loanobjects Work----------------------------------------------------------------------
$IsisScript=$xWxis."administrar.xis";
$query = "&base=copies&cipar=$db_path"."par/copies.par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	//echo "$linea<br>";
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		if (isset($a[1])) $tag[$a[0]]=$a[1];
	  	}
	}
}
$cantcopiersafter=(int) $tag["MAXMFN"];
$cantadd=$cantcopiersafter-$cantcopiersbefore;
echo ''.$cantadd.' copies added from the database records';
$IsisScript=$xWxis."administrar.xis";
$query = "&base=loanobjects&cipar=$db_path"."par/loanobjects.par&Opcion=status";
include("../common/wxis_llamar.php");
$ix=-1;
foreach($contenido as $linea) {
	//echo "$linea<br>";
	$ix=$ix+1;
	if ($ix>0) {
		if (trim($linea)!=""){
	   		$a=explode(":",$linea);
	   		if (isset($a[1])) $tag[$a[0]]=$a[1];
	  	}
	}
}
$cantloabobjectsafter=(int) $tag["MAXMFN"];
$cantadd=$cantloabobjectsafter-$cantloabobjectsbefore;
echo ''.$cantadd.' loanobjects records were created';
@ unlink($db_path."/wrk/copies.lst");
@ unlink($db_path."/wrk/cp_create.pft");
@ unlink($db_path."/wrk/cp_create.prc");
@ unlink($db_path."wrk/lo_create.prc");
@ unlink($db_path."wrk/cp_change.prc");
//Link the base to the copies database
	$fp=file($db_path."bases.dat");
	$new=fopen($db_path."bases.dat","w");
	foreach ($fp as $value){
		$value=trim($value);
		$val=explode('|',$value);
		if (trim($val[0])==trim($arrHttp["base"])){
			$value=$val[0].'|'.$val[1]."|".'Y';
		}
		fwrite($new,$value."\n");
	}
	fclose($new);
    echo 'The '.$base_ant.' database was linked to the copies database.';
}
}//if ($_POST["submit"])
?>
</div>
<?
include("../common/footer.php");
?>