<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
global $valortag;
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}

include("../common/get_post.php");
include("../config.php");



if (isset($arrHttp["cambiolang"]))  $_SESSION["lang"]=$arrHttp["lang"];
include ("../lang/admin.php");
include ("../lang/lang.php");
include("leerregistroisispft.php");

$arrHttp["IsisScript"]="ingreso.xis";
$arrHttp["Mfn"]=$_SESSION["mfn_admin"];

$fp = file($db_path."bases.dat");
if (!$fp){
	echo "falta el archivo bases.dat";
	die;
}
echo "<script>
top.listabases=Array()\n";
foreach ($fp as $linea){
	if (trim($linea)!="") {
		$ix=strpos($linea,"|");
		$llave=trim(substr($linea,0,$ix));
		$lista_bases[$llave]=trim(substr($linea,$ix+1));
		echo "top.listabases['$llave']='".trim(substr($linea,$ix+1))."'\n";
	}

}
echo "</script>\n";
include("../common/header.php");
?>
<script>
lang='<?php echo $_SESSION["lang"]?>'

function AbrirAyuda(){
	msgwin=window.open("ayudas/"+lang+"/menubases.html","Ayuda","status=yes,resizable=yes,toolbar=no,menu=no,scrollbars=yes,width=750,height=500,top=10,left=5")
	msgwin.focus()
}

Entrando="S"

function VerificarEdicion(Modulo){
	 if(top.xeditar=="S"){
		alert("<?php echo $msgstr["aoc"]?>")
		return
	}
}

function CambiarBase(){
	tl=""
   	nr=""
   	top.img_dir=""
  	i=document.OpcionesMenu.baseSel.selectedIndex
  	top.ixbasesel=i
   	if (i==-1) i=0
  	abd=document.OpcionesMenu.baseSel.options[i].value
  	top.base=abd
	if (abd.substr(0,2)=="--"){
		alert("<?php echo $msgstr["seldb"]?>")
		return
	}
	ix=abd.indexOf("^b");
	if (ix>0){
		base=abd.substr(2,ix-2)
	}else{
		base=abd.substr(2)
	}
	top.base=base
	if (document.OpcionesMenu.baseSel.options[i].text==""){
		return
	}
	abd=abd.substr(ix+2)
	ix=abd.indexOf("^c");
	if (ix>0){
		top.db_copies=abd.substr(ix+2)
	}else{
		top.db_copies=""
	}

	cipar=base+".par"
	top.nr=nr
	document.OpcionesMenu.base.value=base
   	document.OpcionesMenu.cipar.value=cipar
	document.OpcionesMenu.tlit.value=tl
	document.OpcionesMenu.nreg.value=nr
	top.base=base
	top.cipar=cipar
	top.mfn=0
	top.maxmfn=99999999
	top.browseby="mfn"
	top.Expresion=""
	top.Mfn_Search=0
	top.Max_Search=0
	top.Search_pos=0
	switch (top.ModuloActivo){
		case "dbadmin":

			top.menu.location.href="../dbadmin/index.php?base="+base

            break;
		case "catalog":
			i=document.OpcionesMenu.baseSel.selectedIndex
			document.OpcionesMenu.baseSel.options[i].text
			//if (top.NombreBase==document.OpcionesMenu.baseSel.options[i].text) return
			top.NombreBase=document.OpcionesMenu.baseSel.options[i].text
			top.menu.location.href="../dataentry/menu_main.php?Opcion=continue&inicio=s&cipar=acces.par&base="+base
			top.menu.document.forma1.ir_a.value=""
			i=document.OpcionesMenu.baseSel.selectedIndex
			break
		case "Capturar":

			break
	}
}

function Modulo(){
	Opcion=document.OpcionesMenu.modulo.options[document.OpcionesMenu.modulo.selectedIndex].value
	switch (Opcion){
		case "loan":
			top.location.href="../common/change_module.php?modulo=loan"
			break
		case "acquisitions":
			top.location.href="../common/change_module.php?modulo=acquisitions"
			break

		case "dbadmin":
				document.OpcionesMenu.modulo.selectedIndex=0
				top.ModuloActivo="dbadmin"
			top.menu.location.href="../dbadmin/index.php?basesel="
			break
		case "catalog":
			top.main.location.href="inicio_base.php?inicio=s&base="+base+"&cipar="+base+".par"
			top.menu.location.href="../dataentry/menu_main.php?Opcion=continue&cipar=acces.par&cambiolang=S&base="+base
			top.ModuloActivo="catalog"
			if (i>0) {
				top.menu.location.href="../dataentry/menu_main.php?Opcion=continue&cipar=acces.par&cambiolang=S&base="+base
			}else{
				top.menu.location.href="../dataentry/blank.html"
			}
			break


	}
}

function CambiarLenguaje(){
	url=top.main.location.href
	lang=document.OpcionesMenu.lenguaje.options[document.OpcionesMenu.lenguaje.selectedIndex].value
	Opcion=top.ModuloActivo
	top.encabezado.location.href="menubases.php?base="+top.base+"&base_activa="+top.base+"&lang="+lang+"&cambiolang=s"
	switch (Opcion){
		case "loan":
			break
		case "dbadmin":
			top.menu.location.href="../dbadmin/index.php?Opcion=continue&lang="+lang+"&base="+top.base
			top.main.location.href=url
			break
		case "catalog":
			break
		case "statistics":
			break

	}
}
</script>
</head>
<body class="menubases">
<form name=OpcionesMenu>
<input type=hidden name=base value="">
<input type=hidden name=cipar value="">
<input type=hidden name=marc value="">
<input type=hidden name=tlit value="">
<input type=hidden name=nreg value="">
<div class="heading"> &nbsp;
	<div class="institutionalInfo">
		<h2><img width="35" src=<?php if (isset($logo))
								echo $logo;
							else
								echo "../images/logoabcd.png";
					  ?>
					  ><?php echo $institution_name?></h2>
	</div>
	<div class="userInfo">
		<span><?php echo $_SESSION["nombre"]?></span>,
		<?php  $dd=explode("/",$db_path);
               if (isset($dd[count($dd)-2])){
			   		$da=$dd[count($dd)-2];
			   		echo " (".$da.") ";
				}
		?> |
		<?php echo $_SESSION["profile"]?> |
<?php

if (isset($_SESSION["newindow"]) or isset($arrHttp["newindow"])){
	echo "<a href=\"javascript:top.close()\" xclass=\"button_logout\"><span>[logout]</span></a>";
}else{
	echo "<a href=\"../dataentry/logout.php\" xclass=\"button_logout\"><span>[logout]</span></a>";
}
?>
<br>
<?php
$central="";
$circulation="";
$acquisitions="";
foreach ($_SESSION["permiso"] as $key=>$value){
	$p=explode("_",$key);
	if (isset($p[1]) and $p[1]=="CENTRAL") $central="Y";
	if (substr($key,0,8)=="CENTRAL_")  $central="Y";
	if (substr($key,0,5)=="CIRC_")  $circulation="Y";
	if (substr($key,0,4)=="ACQ_")  $acquisitions="Y";

}
if ($circulation=="Y" or $acquisitions=="Y"){
		echo $msgstr["modulo"].":";
         ?>
  			<select name=modulo  onclick=VerificarEdicion() onchange=Modulo()>
  				<option value=catalog><?php echo $msgstr["catalogacion"]?>
  				<!--option value=dbadmin><?php echo $msgstr["dbadmin"]?></option -->
  				<option value=loan><?php echo $msgstr["prestamo"]?>
  				<option value=acquisitions><?php echo $msgstr["acquisitions"]?>

  			</select>
          <?php } ?>
  			<?php //echo $msgstr["lang"]?>
  		<!--	<select name=lenguaje style="width:140;font-size:8pt;font-family:arial narrow"  onclick=VerificarEdicion() onchange=CambiarLenguaje()>-->
 <?php
 /*
 	$a=$msg_path."lang/".$_SESSION["lang"]."/lang.tab";
 	if (file_exists($a)){
		$fp=file($a);
		$selected="";
		foreach ($fp as $value){

			$value=trim($value);
			if ($value!=""){
				$l=explode('=',$value);
				if ($l[0]!="lang"){
					if ($l[0]==$_SESSION["lang"]) $selected=" selected";
					echo "<option value=$l[0] $selected>".$msgstr[$l[0]]."</option>";
					$selected="";
				}
			}
		}
	}else{
		echo $msgstr["flang"].$db_path."lang/".$_SESSION["lang"]."/lang.tab";
		die;
	}
*/?>
		<!--</select><br>-->
<?php echo $msgstr["bd"]?>:
		<select name=baseSel onchange=CambiarBase() onclick=VerificarEdicion()>
		<option value=""></option>
<?php
$i=-1;
$hascopies="";
foreach ($lista_bases as $key => $value) {
	$xselected="";
	$t=explode('|',$value);
	if (isset($_SESSION["permiso"]["db_".$key]) or isset($_SESSION["permiso"]["db_ALL"]) or isset($_SESSION["permiso"]["CENTRAL_ALL"]) or  isset($_SESSION["permiso"][$key."_CENTRAL_ALL"])){
		if (isset($arrHttp["base_activa"])){
			if ($key==$arrHttp["base_activa"]) 	{
				$xselected=" selected";
				if (isset($t[1])) $hascopies=$t[1];
			}

		}
		if (!isset($t[1])) $t[1]="";
		echo "<option value=\"^a$key^badm^c".$t[1]."\" $xselected>".$t[0]."\n";
	}
}
echo "</select>" ;
if ($hascopies=="Y" and (isset($_SESSION["permiso"]["CENTRAL_ADDCO"]) or isset($_SESSION["permiso"]["CENTRAL_ALL"]) or  isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"]) or isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ADDCO"]))){
	echo "\n<script>top.db_copies='Y'\n</script>\n";
}
?>

	</div>

</div>
</form>

<script>
<?php
if (isset($arrHttp["inicio"]))
	$inicio="&inicio=s";
else
	$inicio="";
echo "top.menu.location.href=\"menu_main.php?base=\"+top.base+\"$inicio\"\n";?>
</script>
</body>
</html>

