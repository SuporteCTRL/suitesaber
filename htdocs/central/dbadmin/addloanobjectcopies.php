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
echo "<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">".$msgstr["addLOwithoCP_mx"].": " . $base_ant."
			</div>
			<div class=\"actions\">";
?>
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
document.getElementById("ml").innerHTML='<table width="750" border="0"><tr><td width="202" align="right"><label>Main Library Field&nbsp;<input name="mlf" type="text" id="mlf" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="mlsf" type="text" id="mlsf" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table>';
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
document.getElementById("bl").innerHTML='<table width="750" border="0"><tr><td width="202" align="right"><label>Branch Library Field&nbsp;<input name="blf" type="text" id="blf" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="blsf" type="text" id="blsf" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table>';
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
document.getElementById("tome").innerHTML='<table width="750" border="0"><tr><td width="202" align="right"><label>Tome Field&nbsp;<input name="tomef" type="text" id="tomef" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="tomesf" type="text" id="tomesf" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="right">&nbsp;</td></tr></table>';
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
document.getElementById("volume").innerHTML='<table width="750" border="0"><tr><td width="202" align="right"><label>Volume/Part Field&nbsp;<input name="volumef" type="text" id="volumef" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="volumesf" type="text" id="volumesf" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="right">&nbsp;</td></tr></table>';
document.getElementById("volume").style.display='block';
}
}
else
{
document.getElementById("volume").innerHTML='';
document.getElementById("volume").style.display='none';
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

<div class="form-group row">
<form action="" method="post" name="form1" target="_self" id="form1" onsubmit="OpenWindows();">

<?php
echo "<p>".$msgstr["addloanobjectcopies"]."</p>";   
  echo " <input type=\"hidden\" value=\"$base_ant\" name=\"base\"/>";
  echo $msgstr["database"]." ".$base_ant."<p>";
  ?>  
  

  <label class="col-sm-1 col-form-label">From:</label>
    <div class="col-sm-10">
      <div class="col-md-3">
        <input class="form-control" type="text" name="from" id="from" onchange="ComprobarNum('from')"/>
      </div>

 <label class="col-sm-1 col-form-label">To:</label>
    
      <div class="col-md-3">
  <input class="form-control" type="text" name="to" id="to" onchange="ComprobarNum('to')">
  <label>Last MFN:</label>
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
      </div>
    </div>
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



  <label class="col-sm-1 col-form-label">Control Number Field</label>
    <div class="col-sm-10 col-form-label">
      <div class="col-md-3">
      <input name="cnf" class="form-control" type="text" id="cnf" value="<?php 
        if (isset($_POST['cnf']))
         echo $_POST['cnf'];
        else echo "v1";
       ?>">
    </div>
      



<label class="col-sm-1 col-form-label">SubField:</label>
     <div class="col-md-3">
      <input name="cnsf" class="form-control" type="text" id="cnsf" value="<?php 
      if (isset($_POST['cnsf']))
      echo $_POST['cnsf'];
      ?>">
 
   </div>


<div class="col-md-3">
  <select name=agregar id=atunique onChange=AlterEntry(1) class="form-control">
   <option value=''>add</option>
    <option value="ml">Main Library</option>
    <option value="bl">Branch Library</option>
    <option value="tome">Tome</option>
   <option value="volume">Volume/Part</option>
  </select>
  <a href=javascript:AlterEntry(0)></a>

	</select>

<a class="btn btn-danger" href=javascript:AlterEntry(0)><i class="fa fa-times" aria hidden="true"></i></a>  

</div>
</div>
</form>



<div class="form-group row">
  <label class="col-sm-2 col-form-label">Inventory Number Field:</label>
    <div class="col-sm-10">
      <input name="inf" type="text" id="inf" value="<?php if (isset($_POST['inf'])) echo $_POST['inf'];?>"/>
   
<label>SubField:</label>
  <input name="insf" type="text" id="insf" value="<?php if (isset($_POST['insf'])) echo $_POST['insf'];?>"/>

    </div>
</div>




<?php 
if (isset($_POST['mlf']))
echo '<div id="ml" style="display:block"><table width="750" border="0"><tr><td width="202" align="right"><label>Main Library Field&nbsp;<input name="mlf" type="text" id="mlf" value="'.$_POST["mlf"].'" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="mlsf" type="text" id="mlsf" value="'.$_POST["mlsf"].'" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table></div>'; 
else 
echo '<div id="ml" style="display:none"></div>';

if (isset($_POST['blf']))
echo '<div id="bl" style="display:block"><table width="750" border="0"><tr><td width="202" align="right"><label>Branch Library Field&nbsp;<input name="blf" type="text" id="blf" value="'.$_POST["blf"].'" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="blsf" type="text" id="blsf" value="'.$_POST["blsf"].'" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table></div>'; 
else 
echo '<div id="bl" style="display:none"></div>';

if (isset($_POST['tomef']))
echo '<div id="tome" style="display:block"><table width="750" border="0"><tr><td width="202" align="right"><label>Tome Field&nbsp;<input name="tomef" type="text" id="tomef" value="'.$_POST["tomef"].'" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="tomesf" type="text" id="tomesf" value="'.$_POST["tomesf"].'" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table></div>'; 
else 
echo '<div id="tome" style="display:none"></div>';

if (isset($_POST['volumef']))
echo '<div id="volume" style="display:block"><table width="750" border="0"><tr><td width="202" align="right"><label>Volume Field&nbsp;<input name="volumef" type="text" id="volumef" value="'.$_POST["volumef"].'" size="5"/></label></td><td width="22" align="center">-</td><td width="187"align="left"><label>SubField&nbsp;<input name="volumesf" type="text" id="volumesf" value="'.$_POST["volumesf"].'" size="5"/></label></td><td width="121" align="left"></td><td width="200" align="left">&nbsp;</td></tr></tr><tr><td align="right">&nbsp;</td><td>&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td></tr></table></div>'; 
else 
echo '<div id="volume" style="display:none"></div>';
?>






<div class="form-group row">
 <label class="col-sm-1 col-form-label">Use the system types:</label>
<div class="col-md-3">
 <input name="radiobutton" type="radio" value="fieldsel"  onchange="ChangeOption(2)"
   <?php if (isset($_POST['radiobutton'])) echo 'checked="checked"';?> >

</div>     



<div id="systype" style="display:<?php if ($_POST["radiobutton"]=="systype") echo "block"; else if (!$_POST["submit"]) echo "block"; else echo "none"; ?>">
<label class="col-sm-1 col-form-label">Type of object:</label>
  <div class="col-md-3">
    <select class="form-control" name="type" id="type">
   <?php
  @ $fp = fopen($db_path."circulation/def/$lang/items.tab", "r");
 flock($fp, 1);
 if (!$fp)
   {
     echo "Unable to open file circulation/def/$lang/items.tab.</strong></p></body></html>";         
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

 </div>
 </div>

</div>


<div class="form-group row">
<label class="col-sm-3 col-form-label"> Use a field-subfield combination:</label>
<div class="col-sm-03">
      <input name="radiobutton" type="radio" value="fieldsel" <?php if (isset($_POST['radiobutton'])) echo "checked";?> onchange="ChangeOption(2)">
<div id="systype" style="display:<?php if ($_POST["radiobutton"]=="systype") echo "block"; else if (!$_POST["submit"]) echo "block"; else echo "none"; ?>">
  <label class="col-sm-1 col-form-label">Type Field:</label>
    <input name="typef" type="text" id="typef" value="<?php if (isset($_POST['typef'])) echo $_POST['typef'];?>">

 <label class="col-sm-1 col-form-label">SubField:</label>
     <input name="typesf" type="text" id="typesf" value="<?php if (isset($_POST['typesf'])) echo $_POST['typesf'];?>">
</div>
</div>
</div>
</div>







<?php 
  echo "<input class=\"btn btn-primary\"  type=\"submit\" name=\"submit\" value=".$msgstr["update"].">"; 
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
if (isset($_POST["submit"]))
{
$from=$_POST['from'];
$to=$_POST['to'];
if (!is_numeric($from) or !is_numeric($to))
{
echo '<br /><span style="color:red"><b>The fields "from" or "to" had non numeric values</b></span>';
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
//Getting the subfields information
$cnsf=RemovePico(strtolower($_POST['cnsf']));
$insf=RemovePico(strtolower($_POST['insf']));
$mlsf=RemovePico(strtolower($_POST['mlsf']));
$blsf=RemovePico(strtolower($_POST['blsf']));
$tomesf=RemovePico(strtolower($_POST['tomesf']));
$volumesf=RemovePico(strtolower($_POST['volumesf']));
//Concatenating the fields and subfields
$cnfent="";
$infent="";
$mlfent="";
$blfent="";
$tomefent="";
$volumefent="";
if ($cnsf=="") $cnfent=$cnf; else $cnfent=$cnf."^".$cnsf;
if ($insf=="") $infent=$inf; else $infent=$inf."^".$insf;
if ($mlsf=="") $mlfent=$mlf; else $mlfent=$mlf."^".$mlsf;
if ($blsf=="") $blfent=$blf; else $blfent=$blf."^".$blsf;
if ($tomesf=="") $tomefent=$tomef; else $tomefent=$tomef."^".$tomesf;
if ($volumesf=="") $volumefent=$volumef; else $volumefent=$volumef."^".$volumesf;
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
//Loanobjects Work----------------------------------------------------------------------
@ $fp = fopen($db_path."wrk/lo_create.prc", "w");
if (!$fp)
 {
   echo "Unable to write the file ".$db_path."wrk/lo_create.prc";         
   exit;
 }
$savestring="'d*',
if p(".$infent.") then \n";
if ($cnf!=$inf) $savestring.="'<1>',".$cnfent."[1],'</1>'\n";
else $savestring.="'<1>',".$cnfent.",'</1>'\n";
$savestring.="'<10>".$base_ant."</10>',
('<959>',
'^i'".$infent.",\n";
if ($mlf!="") if ($mlf!=$inf) $savestring.="'^l'".$mlfent."[1],\n";
 else $savestring.="'^l'".$mlfent.",\n";
if ($blf!="") if ($blf!=$inf) $savestring.="'^b'".$blfent."[1],\n";
 else $savestring.="'^b'".$blfent.",\n";
if ($_POST["radiobutton"]=="systype") $savestring.="'^o".$_POST['type']."',\n"; 
else $savestring.="'^o'".Vfield(strtolower($_POST['typef']))."^".RemovePico(strtolower($_POST['typesf'])).",\n";
if ($volumef!="") if ($volumef!=$inf) $savestring.="'^v'".$volumefent."[1],\n";
 else $savestring.="'^v'".$volumefent.",\n";
if ($tomef!="") if ($tomef!=$inf) $savestring.="'^t'".$tomefent."[1],\n";
 else $savestring.="'^t'".$tomefent.",\n";
$savestring.="'</959>',),
fi,
";
fwrite($fp,$savestring);
fclose($fp);

$mx=$converter_path." $db_path".$base_ant."/data/".$base_ant." \"proc=@".$db_path."wrk/lo_create.prc\" append=".$db_path."loanobjects/data/loanobjects from=$from to=$to now -all";
exec($mx,$outmx,$banderamx);
$mxinv=$converter_path." ".$db_path."loanobjects/data/loanobjects fst=@".$db_path."loanobjects/data/loanobjects.fst fullinv=".$db_path."loanobjects/data/loanobjects now -all";
exec($mxinv, $outputmxinv,$banderamxinv);
//End of Loanobjects Work----------------------------------------------------------------------
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
echo '<br /><span style="color: blue"><b>&nbsp;&nbsp;'.$cantadd.' loanobjects records were created</b></span>';
@ unlink($db_path."wrk/lo_create.prc");
//Unlink the base to the copies database
	$fp=file($db_path."bases.dat");
	$new=fopen($db_path."bases.dat","w");
	foreach ($fp as $value){
		$value=trim($value);
		$val=explode('|',$value);
		if (trim($val[0])==trim($arrHttp["base"])){
			$value=$val[0].'|'.$val[1];							
		}
		fwrite($new,$value."\n");
	}
	fclose($new);
    echo '<br /><span style="color: blue"><b>&nbsp;&nbsp;The '.$base_ant.' database was unlinked to the copies database.</b></span>';
}
}//if ($_POST["submit"])
?>
</div>
<?
include("../common/footer.php");
?>