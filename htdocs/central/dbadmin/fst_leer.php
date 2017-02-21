<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");


?>
<xlink rel="STYLESHEET" type="text/css" href="../css/styles.css">

<script>
function AbrirVentana(){
	msgwin=window.open("","Fdt","width=600,height=600,resizable,scrollbars=yes")
	msgwin.focus()
}
</script>
<body>
<?php echo "<label>Script: fst_leer.php</label>";
?>
 
 <label>
 <?php echo $msgstr["fst"];?></label>
 <a href=fdt_leer.php?base=<?php echo $arrHttp["base"];?> target="Fdt" onclick="AbrirVentana()">
 <?php echo $msgstr["displayfdt"];?></a>
 
 <a href=adform_leer.php?base=<?php echo $arrHttp["base"];?> target="Fdt" onclick="AbrirVentana()"><?php echo $msgstr["advsearch"];?></a>

 <br>
<table class="table table-striped">
			<td><label>ID</label></td>
			<td><label><?php echo $msgstr["itech"];?></label></td>
			<td><label><?php echo $msgstr["extrpft"];?></label></td>
<?php
$fst=$db_path.$arrHttp["base"]."/data/".$arrHttp["base"].".fst";
if (file_exists($fst)){
	$fp=file($fst);
	foreach ($fp as $value){
		if (trim($value)!=""){
			$ix=strpos($value," ");
			$id=trim(substr($value,0,$ix));
			$value=trim(substr($value,$ix));
			$ix=strpos($value," ");
			$ti=trim(substr($value,0,$ix));
			$format=stripslashes(trim(substr($value,$ix+1)));
			echo "<tr><td><label> $id </label></td>
			<td><label> $ti </label></td>
			<td><label> $format </label></td>";
		}
	}
}else{


}
?>
</table>

</body>
</html>