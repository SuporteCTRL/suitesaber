<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="pt";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");
include("../common/header.php");
?>
<script  src="../dataentry/js/lr_trim.js"></script>

<script languaje=javascript>

function VerFdt(Fdt){
	Ctrl=eval("document.tor."+Fdt)
	Fdt=Ctrl.value
	if (Trim(Fdt)==""){
		return
    }
	msgwin=window.open("","FDT","resizable,scrollbars,status")
	document.FdtEdit.target="FDT"
	document.FdtEdit.type.value=Fdt
	msgwin.focus()
	document.FdtEdit.submit()


}

function EnviarTipoR(){
	if (Trim(document.tipordef.tipom.value)==""){
		alert("<?php echo $msgstr["typeofrecords_new"]?>")
		return
	}
	document.tipordef.submit()
}

function Enviar(rows){
	err=false
	for (i=1;i<rows;i++){
		c1=""
		c2=""
		c3=""
		c4=""
		c5=""
		tr=0
		for (j=1;j<5;j++){
			Ctrl=eval("document.tor.cell"+i+"_"+j)
			switch (j){
				case 1:
					if (Ctrl.selectedIndex!=-1){
						c1=Ctrl.options[Ctrl.selectedIndex].value
					}
					break
				case 2:
					if (Ctrl.selectedIndex!=-1){
						c2=Ctrl.options[Ctrl.selectedIndex].value
					}
					break
				case 3:
					c3=Ctrl.value
					break
				case 4:
					c4=Ctrl.value
					break
				case 5:
					c5=Ctrl.value
					break
			}
		}

		if (Trim(c1)+Trim(c2)+Trim(c3)+Trim(c4)+Trim(c5)==""){

		}else{
			if (Trim(c1)=="" || (Trim(c2)=="" && Trim(c3)=="") || Trim(c4)==""){
				alert ("<?php echo $msgstr["typeofrecerror"]?>")
				err=true
			}else{
				if (c2==""){
					Ctrl=eval("document.tor.cell"+i+"_"+2)
					Ctrl.value="_"
				}
				if (c3==""){
					Ctrl=eval("document.tor.cell"+i+"_"+3)
					Ctrl3=Ctrl
					Ctrl.value="_"
				}
				tr++
			}
		}
	}
    if (err==false) document.tor.submit();
}

function SelectFixedFormat(Ctrl,i){
	ix=Ctrl.selectedIndex
	tr=Ctrl.options[ix].value
	field=ldr[tr]
	t=field.split('|')
	cell=eval("document.tor.cell"+i+"_4")
	cell.value=t[1]


}

</script>
<body>
<?php if (isset($arrHttp["encabezado"])){
    	include("../common/institutional_info.php");
}
echo "<div class=\"sectionInfo\"><div class=\"breadcrumb\">".$msgstr["typeofrecords"].": ". $arrHttp["base"]."</div><div class=\"actions\">\n";
if (isset($arrHttp["encabezado"]))
	$encabezado="&encabezado=s";
else
	$encabezado="";

?>

<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<font color=white> Script: typeofrecs_marc_edit.php</font>";
?>
</font>
	</div>
<div class="middle form">
			<div class="formContent"> 


<?php
unset($fp);
if (!file_exists($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/formatos.wks")){
	if (!file_exists($db_path.$arrHttp["base"]."/def/".$lang_db."/formatos.wks")){
		echo "<p><span class=title>".$msgstr["typeofrecnowks"];
		if (!isset($arrHttp["encabezado"]))
			echo "<a href=menu_modificardb.php?base=".$arrHttp["base"].">".$msgstr["back"]."</a>";
		die;
	}else{
		$fp=file($db_path.$arrHttp["base"]."/def/".$lang_db."/formatos.wks");
	}
}else{
	$fp = file($db_path.$arrHttp["base"]."/def/".$_SESSION[	"lang"]."/formatos.wks");
}
if (isset($fp)){
	foreach ($fp as $linea){
		$fmt[]=$linea;
	}
}
//READ THE TYPE OF RECORDS USING THE PICKLIST ASSOCIATED TO THE FIELD 3006 OF THE LEADER
if (file_exists($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/ldr_06.tab"))
	$fixed_list=file($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/ldr_06.tab");
else
    if (file_exists($db_path.$arrHttp["base"]."/def/".$lang_db."/ldr_06.tab"))
		$fixed_list=file($db_path.$arrHttp["base"]."/def/".$lang_db."/ldr_06.tab");
if (!isset($fixed_list)){
	foreach ($fixed_list as $val) echo "$val<br>";
}
unset($ldr_06);
if (file_exists($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/ldr_06.tab"))
	$ldr_06=file($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/ldr_06.tab");
else
    if (file_exists($db_path.$arrHttp["base"]."/def/".$lang_db."/ldr_06.tab"))
		$ldr_06=file($db_path.$arrHttp["base"]."/def/".$lang_db."/ldr_06.tab");
unset($fpType);
if (file_exists($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/typeofrecord.tab"))
	$fpType=file($db_path.$arrHttp["base"]."/def/".$_SESSION["lang"]."/typeofrecord.tab");
else
    if (file_exists($db_path.$arrHttp["base"]."/def/".$lang_db."/typeofrecord.tab"))
		$fpType=file($db_path.$arrHttp["base"]."/def/".$lang_db."/typeofrecord.tab");
echo "<script>
ldr=new Array()\n";
foreach ($ldr_06 as $leader){
	$leader=trim($leader);
	$l=explode('|',$leader);
	echo "ldr['".$l[0]."']='".$leader."'\n";
}
echo "</script>";
if (!isset($fpType)){
?>

	<form name="tipordef"  method="post" action="typeofrecs_update.php" onsubmit="javascript:return false">
	<input type="hidden" name="Opcion" value="tipom">
	<input type="hidden" name="base" value="<?php echo $arrHttp["base"]; ?>">

<?php

	if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>";
    echo "

	
		<label>" . $msgstr["typeofrecords_new"]."</label>

		<label>".$msgstr["tag"]." 1</label><td><input class=\"form-control\" type=text name=tipom value=''></td>
	
		<label>".$msgstr["tag"]." 2</label><td><input class=\"form-control\" type=text name=nivelr value=''></td>



	<input  type=submit value='  ".$msgstr["send"]."  ' onClick=javascript:EnviarTipoR()>
</form>";


echo "</body></html>";
	die;
}
echo "<form name=tor method=post action=typeofrecs_update.php onsubmit='return false'>

<input type=hidden name=base value=".$arrHttp["base"].">";
if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>";
$ix=0;

$j=0;
if (isset($fpType)) {
	foreach($fpType as $linea){
		if (trim($linea)!="") {
			$linea=trim($linea);
			if ($ix==0){
				$ixpos=strpos($linea," ");
				if ($ixpos===false){
					$tipom=trim($linea);
					$nivelr="";
				}else{
					$tipom=trim(substr($linea,0,$ixpos));
					$nivelr=trim(substr($linea,$ixpos+1));
				}
				echo "

	<h2><label>" .$msgstr["typeofrecords_new"]." ". $msgstr["typeofrecords_tags"]."</label></h2>
	
		    <label>".$msgstr["tag"]." 1</label>
		    <td><input class=\"form-control\" type=text name=tipom value='$tipom'><td>
	
		    <label>".$msgstr["tag"]." 2</label>
		    <td><input class=\"form-control\" type=text name=nivelr value='$nivelr'></td>
	

";
				$ix=1;
				
				echo "<br><div class=\"col-md-6\"><label>".$msgstr["fmt"]."</label></div>
				<div class=\"col-md-3\"><label>".$msgstr["tag"]." 1<br>".$msgstr["value"]."</label></div>
				<div class=\"col-md-2\"><label>".$msgstr["tag"]." 2<br>".$msgstr["value"]."</label></div>
				<div class=\"col-md-1\"><label>".$msgstr["typeofrecords"]." ".$msgstr["description"]."</label></div>";
			}else{
				$j=$j+1;
				$i=0;
				$l=explode('|',$linea);
				foreach ($l as $value) {
					$value=trim($value);
					$i=$i+1;
					$link="";
					$xsize="";
					switch ($i){

						case 1:
							$link="<div class=\"col-md-6\">
							<a class=\"btn btn-warning\" href='javascript:VerFdt(\"cell$j"."_".$i."\")'><i class=\"fa fa-pencil-square-o\"></i></a>";
							echo " <select class=\"form-control\" name=cell$j"."_".$i.">
								<option value=\"\"></option> ";
							foreach ($fmt as $f){
								$xxtm=explode('|',$f);
								$xselected="";
								if ($xxtm[0].".fmt"==$l[0]) $xselected=" selected";
								echo "<option value=\"".$xxtm[0].".fmt\"$xselected>".trim($xxtm[1])." (".$xxtm[0].")\n";
							}
							echo "</select>$link";
							$xsize= "";
							break;
						case 2:
							echo "</div>
							<div class=\"col-md-3\">
									<select name=cell$j"."_".$i." onchange=SelectFixedFormat(this,$j)>
								<option value=\"\"></option> ";
							foreach ($ldr_06 as $f){
								$lead=explode('|',$f);
								$xselected="";
								if ($lead[0]==$l[1]) $xselected=" selected";
								echo "<option value=\"".$lead[0]."\"$xselected>".$lead[0]."\n";
							}
							echo "</select></div>";
							$xsize= "";
                            break;
						case 3:
							$xsize="size=10";
							break;
						case 4:
							$xsize="size=40";
							break;
						case 5:
							$link="<a href='javascript:VerFdt(\"cell$j"."_".$i."\")'>edit</a>";
							$xsize="size=20";
					}
					if ($i!=1 and $i!=2)echo "
						<input type=text name=cell$j"."_".$i." value=\"$value\" $xsize>";
				}
			}
		}
	}

}

for ($k=$j+1;$k<$j+8;$k++){
	$i=0;
	$linea="|||";
	$l=explode('|',$linea);
	
	foreach ($l as $value) {
		$i=$i+1;
		$link="";
		switch ($i){
			case 1:
			
				echo "$link<select class=\"form-control\" name=cell$k"."_".$i.">
					<option value=\"\"></option> ";
				foreach ($fmt as $f){
					$xxtm=explode('|',$f);
					echo "<option value=\"".$xxtm[0].".fmt\">".trim($xxtm[1])." (".$xxtm[0].")\n";
				}
				echo "</select>";
				$xsize= "";
				break;
			case 2:
				echo "$link<select name=cell$k"."_".$i." onchange=SelectFixedFormat(this,$k)>
					<option value=\"\"></option> ";
				foreach ($ldr_06 as $f){
					$lead=explode('|',$f);
					$xselected="";
					if ($lead[0]==$l[1]) $xselected=" selected";
					echo "<option value=\"".$lead[0]."\"$xselected>".$lead[0]."\n";
				}
				echo "</select>";
				$xsize= "";
                break;
			case 3:
				$xsize="size=10";
				break;
			case 4:
				$xsize=" size=50";
				break;
	//		case 5:
	//			$link="<a href='javascript:VerFdt(\"cell$k"."_".$i."\")'>edit</a>";
	//			$xsize="size=20";
		}
		$value=trim($value);
		if ($i!=1 and $i!=2)echo "
			<input type=text name=cell$k"."_".$i." value=\"$value\" $xsize>";
	}

}
echo "
<a class=\"btn btn-primary\" href=javascript:Enviar($k)>".$msgstr["update"]."</a>";
if (!isset($arrHttp["encabezado"]))
 echo "<a href=menu_modificardb.php?base=".$arrHttp["base"].">".$msgstr["cancel"]."</a>";
?>
</form>
<form name="FdtEdit" method="post" action="fdt.php" target="_blank">
<input type="hidden" name="Opcion" value="update">
<input type="hidden" name="type" value="">
<input type="hidden" name="base" value="<?php echo $arrHttp['base'];?>">
<input type="hidden" name="ventana" value="s">
</form>

</div></div>
<?php include("../common/footer.php");?>
</body>
</html>
