<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/acquisitions.php");

include("../common/get_post.php");
include("../common/header.php");
$encabezado="";
?>
<script>
function Mostrar(Expresion){
	msgwin=window.open("../dataentry/show.php?base=suggestions&Expresion=NC_"+Expresion,"show")
	msgwin.focus()
}
function Editar(Mfn){
	document.EnviarFrm.Mfn.value=Mfn
	document.EnviarFrm.Opcion.value="editar"
	document.EnviarFrm.submit()

}

function AbrirIndiceAlfabetico(){
	db="purchaseorder"
	cipar="purchaseorder.par"
	postings=1
	tag="searchExpr"
	Prefijo="PO_"
	Ctrl_activo=document.forma1.searchExpr
	lang="<?php echo $_SESSION["lang"]?>"
	Separa=""
	Repetible=""
	Formato="v1"
	Prefijo=Separa+"&tagfst=&prefijo="+Prefijo
	ancho=200
	url_indice="../dataentry/capturaclaves.php?opcion=autoridades&base="+db+"&cipar="+cipar+"&Tag="+tag+Prefijo+"&postings="+postings+"&lang="+lang+"&repetible="+Repetible+"&Formato="+Formato
	msgwin=window.open(url_indice,"Indice","width=480, height=425,scrollbars")
	msgwin.focus()
}
</script>
<?php
echo "<body>\n";
include("../common/institutional_info.php");
$arrHttp["base"]="purchaseorder";
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
?>
<!-- calendar stylesheet -->
  <link rel="stylesheet" type="text/css" media="all" href="../dataentry/calendar/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <!-- main calendar program -->
  <script type="text/javascript" src="../dataentry/calendar/calendar.js"></script>
  <!-- language for the calendar -->
  <script type="text/javascript" src="../dataentry/calendar/lang/calendar-en.js"></script>
  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="../dataentry/calendar/calendar-setup.js"></script>

<script src=../dataentry/js/lr_trim.js></script>

<script>
document.onkeypress =
  function (evt) {
    var c = document.layers ? evt.which
            : document.all ? event.keyCode
            : evt.keyCode;
	if (c==13) EnviarForma("")
    return true;
  }

function DateToIso(From,To){
	d=From.split('/')
	<?php echo "dateformat=\"$config_date_format\"\n" ?>
	if (dateformat="DD/MM/YY"){
		iso=d[2]+d[1]+d[0]
	}else{
		iso=d[2]+d[0]+d[1]
	}
	To.value=iso
}
function EnviarForma(){

	if (Trim(document.forma1.date.value)==""){
		alert("<?php echo $msgstr["miss_date"]?>")
		return
	}
	if (Trim(document.forma1.isodate.value)==""){
		alert("<?php echo $msgstr["miss_iso"]?>")
		return
	}
	if (Trim(document.forma1.searchExpr.value)==""){
		alert("<?php echo $msgstr["missorder"]?>")
		return
	}
    document.forma1.submit();
}

</script>
<?php

?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><i class="fa fa-check-square fa-2x" aria-hidden="true"></i> <label><?php echo $msgstr["purchase"].": ".$msgstr["receiving"];?></label></h2>
	</div>
	<div class="actions">
	<?php include("order_menu.php");?>
	</div>
	
</div>

<div class="middle list">

	<div class="searchBox">
	<form name="forma1" action="receive_order_ex.php" method="post" onsubmit="javascript:return false">
	
					<label><?php echo $msgstr["date_receival"];?></label>
<!-- calendar attaches to existing form element -->

		
		<br>
		<label><?php echo $msgstr["isodate_receival"];?>:</label>
		
		<input type="text"  name="isodate" id="isodate" value="<?php echo date("Ymd");?>" class="form-control"/>
        <br>
		<input type="text" class="form-control" name="date" id="date"  onChange='Javascript:DateToIso(this.value,document.forma1.isodate)' value="<?php echo date("d/m/Y")?>"/>

 		<i class="fa fa-calendar" id="f_date" title="Date selector"/></i>
		<script type="text/javascript">
	  	Calendar.setup({
	      inputField     :    "date",     // id of the input field
		  ifFormat       :   "<?php if ($config_date_format=="DD/MM/YY")    // format of the input field
							        	echo "%d/%m/%Y";
							        else
							        	echo "%m/%d/%Y";
							        ?>",
		  button         :    "f_date",  // trigger for the calendar (button ID)
		  align          :    "Tl",           // alignment (defaults to \"Bl\")
		  singleClick    :    true
		});
		
		</script>









		<br>
		<label for="searchExpr"><?php echo $msgstr["order_no"];?>:</label>

		
		<input type="text" name="searchExpr" id="searchExpr" value="" class="form-control" />
        <input type="hidden" name="base" value="">
        <br>

		<button class="btn btn-primary" type="submit" name="list" value="<?php echo $msgstr["search"];?>" onclick="javascript:EnviarForma();return false"/><i class="fa fa-search"></i></button>

		<button class="btn btn-warning" type="submit" name="list" value="<?php echo $msgstr["listorders"];?>"  onclick="javascript:AbrirIndiceAlfabetico();return false"/><i class="fa fa-list"></i></button>
			<?php //echo $msgstr["clic_en"]." <i>[".$msgstr["return"]."]</i> ".$msgstr["para_c"]?>
	</form>
	</div>
</div>
<?php include("../common/footer.php");
echo "</body></html>" ;
?>