<?php

//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include ("../config.php");
include ("../lang/admin.php");
include ("../lang/dbadmin.php");
include ("../lang/prestamo.php");

if (!isset($_SESSION["login"])){
	echo $msgstr["sessionexpired"];
	die;
}
include("../common/header.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";

// ==================================================================================================
// INICIO DEL PROGRAMA
// ==================================================================================================

//

include("../common/institutional_info.php");
?>
<script src=../dataentry/js/lr_trim.js></script>
<script>
	function LeerFst(base){
		msgwin=window.open("../dbadmin/fst_leer.php?base="+base,"fst","width=400,height=400,resizable,scrollbars=yes")
		msgwin.focus()
	}
	function Guardar(){
		if (Trim(document.forma1.code.value)==""){
			alert("<?php echo $msgstr["falta"]. " ".$msgstr["o_code"]?>")
			return
		}

        if (Trim(document.forma1.pft.value)==""){
			alert("<?php echo $msgstr["falta"]. " ".$msgstr["o_pft_name"]?>")
			return
		}
		code=document.forma1.pft.value
		ix=code.indexOf(".php")
		if (ix==-1){
			if (Trim(document.forma1.heading.value)==""){
				alert("<?php echo $msgstr["falta"]. " ".$msgstr["o_rows"]?>")
				return
			}
			if (Trim(document.forma1.expresion.value)==""){
				alert("<?php echo $msgstr["falta"]. " ".$msgstr["o_search"]?>")
				return
			}
		}
		if (Trim(document.forma1.title.value)==""){
			alert("<?php echo $msgstr["falta"]. " ".$msgstr["o_title"]?>")
			return
		}
		document.forma1.submit()
	}
</script>


	<h2><i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i>   <label><?php echo $msgstr["new_report"];?></label></h2>


	<div class="actions">

		<a class="btn btn-success" href=javascript:Guardar() class="defaultButton saveButton">
			<i class="fa fa-refresh" value="<?php echo $msgstr["update"]?>"></i></a>
	</div>


<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<label> Script: output_circulation/print_add.php</label>";
?>
</font>
	</div>
<form name="forma1" method="post" action="print_add_ex.php">
<div class="middle form">

	
<?php
	$base[]="trans";
	$base[]="suspml";
	$base[]="reserve";
	$code="";
	$pft_name="";
	$rows="";
	$sort="";
	$search="";
	$title="";
	$ask_date="";
	$tag_date_trans="40";
	$tag_date_suspml_1="60";
	$tag_date_suspml_2="110";
	$ask_usertype="";
	$tag_usertype="70";
	$ask_itemtype="";
	$tag_itemtype="80";
	$tag="";
	if (isset($arrHttp["base"]) and isset($arrHttp["codigo"])){
		$bd=$arrHttp["base"];
		if (file_exists($db_path."$bd/pfts/".$_SESSION["lang"]."/outputs.lst")){
			$fp=file($db_path."$bd/pfts/".$_SESSION["lang"]."/outputs.lst");
			$ix=0;
			foreach ($fp as $value){
				$value=trim($value);
				if (substr($value,0,2)!="//") {
					$t=explode("|",$value);

					if ($t[0]==$arrHttp["codigo"]){
						$code=$t[0];
						$pft_name=$t[1];
						$rows=$t[2];
						$sort=$t[3];
						$search=$t[4];
						$title=$t[5];
						if (isset($t[6])){
							switch ($t[6]){
								case "DATE":
									$ask_date=$t[6];

									break;
								case "DATEQUAL":
									$ask_date=$t[6];
									break;
								case "USERTYPE":
									$ask_usertype=$t[6];
									break;
								case "ITEMTYPE":
									$ask_itemtype=$t[6];
									break;
							}
						}else{

						}
						if (isset($t[7])) $tag=$t[7];
						break;
					}
				}
			}
		}
	}
	
	echo "<label>".$msgstr["database"].":</label>";
	
	foreach ($base as $value){
		echo "<br><input type=radio name=base value=$value>";
		if ($value=='$bd') echo " checked<br>";
		echo "<label>$value</label><br>";
		echo "<a href=javascript:LeerFst('$value')> FDT/FST</a>";
	}
	
	echo "<br><br><label>".$msgstr["o_code"].":</label>";

	echo "<input type=text class=\"form-control\" name=code value=\"".$code."\"></td><br>";

	echo "<label>".$msgstr["o_pft_name"].":</label>";
	echo "<input class=\"form-control\" type=text name=pft value=\"".$pft_name."\">
	<a href=../dbadmin/leertxt.php?base=trans&desde=recibos&archivo=$pft_name target=_blank class=\"btn btn-warning\"><i class=\"fa fa-pencil-square-o\" value=".$msgstr["edit"]."></i></a><br>";

	echo "<label>".$msgstr["o_rows"].":</label>";
	$rows=str_replace("#","\n",$rows);
	echo "<td><textarea name=heading class=\"form-control\">$rows</textarea></td>";

	echo "<label>".$msgstr["o_sort"].":</label>
	<td><textarea class=\"form-control\" name=sort>$sort</textarea></td>";
	
	echo "<label>".$msgstr["o_search"].":</label>
	<td><textarea class=\"form-control\" name=expresion>$search</textarea></td>";
	
	echo "<label>".$msgstr["o_title"].":</label>";
	echo "<td><input type=text name=title class=\"form-control\" value=\"".$title."\"></td><br>";

	echo "<table class=\"table\">";

	echo "<label>".$msgstr["o_ask"]."</label>";
	echo "<td><label>".$msgstr["basedatos"].": trans </label></td>";

	echo "<td><label>".$msgstr["basedatos"].": suspml</label></td>";

	echo "<tr><td><label>".$msgstr["date"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"DATE_40\"";
	if ($ask_date=="DATE" and $tag_date_trans==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_trans ".$msgstr["devdate"]."</label>";
	echo "</td>";

	echo "<td><input type=radio name=ask value=\"DATE_60\"";
	if ($ask_date=="DATE" and $tag_date_suspml_1==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_suspml_1 ".$msgstr["o_paymentdate"]."</label>";
	echo "</td>";

    echo "<tr><td><label>".$msgstr["date"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"DATEQUAL_40\"";
	if ($ask_date=="DATEQUAL"  and $tag_date_trans==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_trans ".$msgstr["devdate"]."</label>";
	echo "</td>";

	echo "<td><input type=radio name=ask value=\"DATEQUAL_60\"";
	if ($ask_date=="DATEQUAL"  and $tag_date_suspml_1==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_suspml_1  ".$msgstr["o_paymentdate"]."</label>";
	echo "</td>";

	echo "<tr><td><label>".$msgstr["date"]."</label></td>";
	echo "<td>";
	echo "<input type=radio name=ask value=\"DATELESS_40\"";
	if ($ask_date=="DATELESS"  and $tag_date_trans==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_trans ".$msgstr["devdate"]."</label>";
	echo "</td>";
	echo "</td>";

	echo "<td><input type=radio name=ask value=\"DATE_110\"";
	if ($ask_date=="DATE" and $tag_date_suspml_2==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_date_suspml_2 ".$msgstr["o_canceldate"]."</label>";
	echo "</td>";

    echo "<tr><td><label>".$msgstr["date"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"DATEQUAL_110\"";
	if ($ask_date=="DATEQUAL" and $tag_date_suspml_2==$tag) echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label>  $tag_date_suspml_2 ".$msgstr["o_canceldate"]."</label>";
	echo "</td>";

	echo "<tr><td><label>".$msgstr["typeofusers"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"USERTYPE\"";
	if ($ask_usertype=="USERTYPE") echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_usertype </label>";
	echo "</td><td></td>";
	echo "<tr><td><label>".$msgstr["typeofitems"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"ITEMTYPE\"";
	if ($ask_itemtype=="ITEMTYPE") echo " checked";
	echo ">";
	echo $msgstr["o_compare"]." ".$msgstr["tag"].": <label> $tag_itemtype</label>";
	
	echo "<tr><td><label>".$msgstr["o_noask"]."</label></td>";
	echo "<td><input type=radio name=ask value=\"\"";
	echo ">";

	echo "</table></td>";
    echo "


</table>
<p>";
   if (isset($arrHttp["codigo"])){
   	   echo "<label><i class=\"fa fa-save fa-2x\"></i>     ".$msgstr["saveas"].":</label>";
   	   echo "<input type=text name=saveas class=\"form-control\">";
   }
?>

</form>

</div>
</div>
<?php
include("../common/footer.php");
?>
</body>
</html>
