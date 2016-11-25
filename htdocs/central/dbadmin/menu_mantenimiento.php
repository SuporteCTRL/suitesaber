<?php
session_start();
unset($_SESSION["Browse_Expresion"]);
$Permiso=$_SESSION["permiso"];
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
if (!isset($arrHttp["base"])) $arrHttp["base"]="";

if (strpos($arrHttp["base"],"|")===false){

}   else{
		$ix=strpos($arrHttp["base"],'^b');
		$arrHttp["base"]=substr($arrHttp["base"],2,$ix-2);
}
$db=$arrHttp["base"];
include ("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/soporte.php");
include("../lang/dbadmin.php");

if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and
    !isset($_SESSION["permiso"]["CENTRAL_DBUTILS"]) and
    !isset($_SESSION["permiso"][$db."_CENTRAL_ALL"]) and
    !isset($_SESSION["permiso"][$db."_CENTRAL_DBUTILS"])
    ){
	echo "<script>
	      alert('".$msgstr["invalidright"]."')
          history.back();
          </script>";
    die;
}


//SEE IF THE DATABASE IS LINKED TO COPIES
$copies="N";
$fp=file($db_path."bases.dat");
foreach ($fp as $value){
	$value=trim($value);
	$x=explode("|",$value);
	if ($x[0]==$arrHttp["base"]){
		if (isset($x[2]) and $x[2]=="Y"){
			$copies="Y";
		}
		break;
	}
}
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");
?>

<script src=../dataentry/js/lr_trim.js></script>


<script language="javascript" type="text/javascript">

function EnviarForma(Opcion,Mensaje){

	base="<?php echo $arrHttp["base"]?>"
	if (Opcion=="eliminarbd" || Opcion=="inicializar"){
		if (base==""){
			alert("<?php echo $msgstr["seldb"]?>")
			return
		}

	}
	switch (Opcion){
		case "dbcp":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/copy_db.php"
			document.admin.target=""
			break;
		case "mxdbread":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/mx_dbread.php"
			document.admin.target=""
			break;
		case "readiso":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/mx_dbread.php"
			document.admin.iso="Y"
			document.admin.target=""
			break;
		case "dbrestore":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/dbrestore.php"
			document.admin.target=""
			break;
		case "lock":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="lock_bd.php"
			document.admin.target=""
			break;
		case "eliminarbd":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="eliminarbd.php"
			document.admin.target=""
			break;
		case "inicializar":
			document.admin.base.value=base
			document.admin.target=""
			break;
        case "cn":  //assign control number
          	document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="assign_control_number.php"
			document.admin.target=""
			break
		case "resetcn":    //RESET LAST CONTROL NUMBER IN THE BIBLIOGRAPHIC DATABASE
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="reset_control_number.php"
			document.admin.target=""
			break;
		case "linkcopies":    //LINK BIBLIOGRAPHIC DATABASE WITH COPIES DATABASE
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="copies_linkdb.php"
			document.admin.target=""
			break;
		case "addcopiesdatabase":    //Marcos Script
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="addcopiesdatabase.php"
			document.admin.target=""
			break;
		case "copiesocurrenciesreport":    //Marcos Script
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="copiesdupreport.php"
			document.admin.target=""
			break;
		case "addloanobjectcopies":    //Marcos Script
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="addloanobjectcopies.php"
			document.admin.target=""
			break;
		case "addloanobj":    //Marino Vretag
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="addloanobject.php"
			document.admin.target=""
			break;
		case "fullinv":     //INVERTED FILE GENERATION WITH MX
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/vmx_fullinv.php"
			document.admin.target=""
			break;
		case "importiso":    //Marino ISO load
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/vmxISO_load.php"
			document.admin.target=""
			break;
		case "exportiso":
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/iso_export.php"
			document.admin.target=""
			break;
		case "unlock":    //Marino Vretag
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="unlock_db_retag_check.php"
			document.admin.target=""
			break;
		case "addloanobj":    //Marino addloanobj
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="addloanobject.php"
			document.admin.target=""
			break;
		case "barcode":    //Marino barcode search
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/barcode.php"
			document.admin.target=""
			break;
		case "dirtree": //EXPLORE DATABASE DIRECTORY
			switch (Mensaje){
				case "par":
				case "www":
				case "wrk":
					document.admin.base.value=Mensaje
					break;
				default:
					document.admin.base.value=base
					break;
			}
			document.admin.action="dirtree.php";
			document.admin.target=""
			break;
		case "more_utils":    //More utils
			document.admin.base.value=base
			document.admin.cipar.value=base+".par"
			document.admin.action="../utilities/more_utils.php"
			document.admin.target=""
			break;
		default:
			alert("")
			return;
	}
	document.admin.Opcion.value=Opcion
	document.admin.cipar.value=base+".par"
	document.admin.submit()
}

</script>
<body onunload=win.close()>
<?php

	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
echo "
	<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">".
				$msgstr["maintenance"]. ": " . $arrHttp["base"]."
			</div>
			<div class=\"actions\">

	";

	
?>




</font>
</div>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Ultilitários</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">Base de dados <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <li><a href='javascript:EnviarForma("mxdbread","<?php echo $msgstr["mx_dbread"]?>")'><?php echo $msgstr["mx_dbread"]?></a></li>
			<li><a href='javascript:EnviarForma("dbrestore","<?php echo $msgstr["db_restore"]?>")'><?php echo $msgstr["db_restore"]?></a></li>
			<li><a href='javascript:EnviarForma("inicializar","<?php echo $msgstr["mnt_ibd"]?>")'><?php echo $msgstr["mnt_ibd"]?></a></li>
			<li><a href='javascript:EnviarForma("eliminarbd","<?php echo $msgstr["mnt_ebd"]?>")'><?php echo $msgstr["mnt_ebd"]?></a></li>
			<li><a href='javascript:EnviarForma("lock","<?php echo $msgstr["protect_db"]?>")'><?php echo $msgstr["protect_db"]?></a></li>
			<li><a href='javascript:EnviarForma("unlock","<?php echo $msgstr["mnt_unlock"]?>")'><?php echo $msgstr["mnt_unlock"]?></a></li>
			<li><a href='javascript:EnviarForma("cn","<?php echo $msgstr["assigncn"]?>")'><?php echo $msgstr["assigncn"]?></a></li>
			<li><a href='javascript:EnviarForma("linkcopies","<?php echo $msgstr["linkcopies"]?>")'><?php echo $msgstr["linkcopies"]?></a></li>
          </ul>
        </li>
      </ul>
      

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Exportar/ Importar <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <li><a href='Javascript:EnviarForma("exportiso","<?php echo "ExportISO MX"?>")'><?php echo $msgstr["exportiso_mx"]?></a></li>
			<li><a href='Javascript:EnviarForma("importiso","<?php echo "ImportISO MX"?>")'><?php echo $msgstr["importiso_mx"]?></a></li>
            <li><a href='Javascript:EnviarForma("readiso","<?php echo "ReadISO  MX"?>")'><?php echo $msgstr["readiso_mx"]?></a></li>
       </ul>
       </li>
       </ul>
      
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-3">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Outros <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <li><a href='Javascript:EnviarForma("addloanobj","<?php echo $msgstr["addLOfromDB_mx"]?>")'><?php echo $msgstr["addLOfromDB_mx"]?></a></li>
			<li><a href='Javascript:EnviarForma("addloanobjectcopies","<?php echo $msgstr["addLOwithoCP_mx"]?>")'><?php echo $msgstr["addLOwithoCP_mx"]?></a></li>
			<li><a href='Javascript:EnviarForma("addcopiesdatabase","<?php echo $msgstr["addCPfromDB_mx"]?>")'><?php echo $msgstr["addCPfromDB_mx"]?></a></li>
       </ul>
       </li>
       </ul>

 <ul class="nav navbar-nav navbar-right">
        <li><a href='Javascript:EnviarForma("barcode","<?php echo "Barcode search"?>")'><?php echo "Barcode search"?></a></li>
        <li><a href='Javascript:EnviarForma("copiesocurrenciesreport","<?php echo $msgstr["CPdupreport_mx"]?>")'><?php echo $msgstr["CPdupreport_mx"]?></a></li>
       </div>
        </nav>




    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

			
		

			<?php if (($arrHttp["base"]!="copies") and ($arrHttp["base"]!="providers") and ($arrHttp["base"]!="suggestions") and ($arrHttp["base"]!="purchaseorder") and ($arrHttp["base"]!="users") and ($arrHttp["base"]!="loanobjects") and ($arrHttp["base"]!="trans") and ($arrHttp["base"]!="suspml") ) {
				if ($copies=="Y"){
			?>

			<?php }}
			if ($arrHttp["base"]!="providers" and $arrHttp["base"]!="suggestions" and $arrHttp["base"]!="purchaseorder" and $arrHttp["base"]!="users" and $arrHttp["base"]!="loanobjects" and $arrHttp["base"]!="trans" and $arrHttp["base"]!="suspml") {
				if ($copies=="Y" or $arrHttp["base"]=="copies" or $arrHttp["base"]=="loanobjects"){
            ?>
			
            <?php }?>
             <li><a href='Javascript:EnviarForma("barcode","<?php echo "Barcode search"?>")'><?php echo "Barcode search"?></a></li>
			<?php
			}
			if ($arrHttp["base"]=="copies") {
			?>
			<li><a href='Javascript:EnviarForma("copiesocurrenciesreport","<?php echo $msgstr["CPdupreport_mx"]?>")'><?php echo $msgstr["CPdupreport_mx"]?></a></li>
			<?php }?>

	<?php
	if ($_SESSION["profile"]=="adm"
        and isset($dirtree) and $dirtree=="Y"
    ){
    ?>
    <li><a href='Javascript:EnviarForma("dirtree","<?php echo $msgstr["expbases"]?>")'><?php echo $msgstr["expbases"]?></a></li>
    <?php
    }
	if (($_SESSION["profile"]=="adm" or isset($_SESSION["permiso"]["CENTRAL_ALL"]) or
		isset($_SESSION["permiso"]["CENTRAL_EXDBDIR"]) or
        isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_EXDBDIR"]))
        and isset($dirtree) and $dirtree=="Y"
    ){

    ?>

	        <li><?php echo $msgstr["explore_sys_folders"]?></li>
	        <ul>
			<li><a href='Javascript:EnviarForma("dirtree","par")'><?php echo "par"?></a></li>
			<li><a href='Javascript:EnviarForma("dirtree","www")'><?php echo "www"?></a></li>
			<li><a href='Javascript:EnviarForma("dirtree","wrk")'><?php echo "wrk"?></a></li>
	        </ul>
	<?php }?>
	<li><a href='javascript:EnviarForma("more_utils","<?php echo $msgstr["more_utils"]?>")'><?php echo $msgstr["more_utils"]?></a></li>
			</ul>

		</td>
</table></form>
<form name=admin method=post action=administrar_ex.php onSubmit="Javascript:return false">
<input type=hidden name=base>
<input type=hidden name=cipar>
<input type=hidden name=Opcion>
<input type=hidden name=encabezado value=s>
<input type=hidden name=iso>
<input type=hidden name=activa value=<?php echo $_REQUEST["base"]?>>
</form>
</div>
</div>
<?php include("../common/footer.php");?>
</body>
</html>
