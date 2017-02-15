<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
	die;
}
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");

if (file_exists($db_path.$arrHttp["base"]."/data/".$arrHttp["base"].".fst")){
	$arrHttp["Opcion"]="update";
}else{
	$arrHttp["Opcion"]="new";
}
if ($arrHttp["Opcion"]!="new"){
	if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and !isset($_SESSION["permiso"][$arrHttp["base"]."CENTRAL_MODIFYDEF"])  and !isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_MODIFYDEF"]) and !isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_ALL"])){
		 header("Location: ../common/error_page.php") ;
		 die;
	}
}else{
	if (!isset($_SESSION["permiso"]["CENTRAL_ALL"]) and !isset($_SESSION["permiso"]["CENTRAL_CRDB"]) and !isset($_SESSION["permiso"][$arrHttp["base"]."_CENTRAL_CRDB"])){
		header("Location: ../common/error_page.php") ;
		die;
	}
}
if (isset($arrHttp["encabezado"]))
	$encabezado="&encabezado=S";
else
	$encabezado="";
include("../common/header.php");
?>
	<link rel="STYLESHEET" type="text/css" href="../dataentry/js/dhtml_grid/dhtmlXGrid.css">

	<!--script  src="../dataentry/js/dhtml_grid/dhtmlxcommon.js"></script>
	<script  src="../dataentry/js/dhtml_grid/dhtmlxgrid.js"></script>
	<script  src="../dataentry/js/dhtml_grid/dhtmlxgridCell.js"></script-->
	<script  src="../dataentry/js/dhtml_grid/dhtmlx.js"></script>
<script  src="../dataentry/js/lr_trim.js"></script>
<script languaje="javascript">
		pl_type=""
		Opcion="<?php echo $arrHttp["Opcion"]?>"
		valor=""
		prefix=""
		fila=""
		columna=11

		function AgregarFila(ixfila,Option){

			switch (Option){
				case "BEFORE":
					ixf=mygrid.getRowsNum()+1
					ref=ixf
					break
				case "AFTER":
					ixf=mygrid.getRowsNum()+2
					ref=ixf-1
					break
				default:
					ixf=mygrid.getRowsNum()+2
					break
			}

			linkr="<a href=javascript:EditarFila(\""+ixf+"\","+ixf+")><font size=1>"+ref+"</a>";
			pick="<a href=javascript:Picklist(\"\","+ixf+")><font size=1>browse</a>";
			mygrid.addRow((new Date()).valueOf(),['','',''],ixfila)

		}

		function Asignar(){
			mygrid.cells2(fila,columna).setValue(valor)
			mygrid.cells2(fila,12).setValue(prefix)
			closeit()
		}
		function Capturar_Grid(){
			cols=mygrid.getColumnCount()
			rows=mygrid.getRowsNum()
			VC=""
			for (i=0;i<rows;i++){
				if (Trim(mygrid.cells2(i,0).getValue())!=""){
					if (VC!="") VC=VC+"\n"
					for (j=0;j<cols;j++){
						cell=mygrid.cells2(i,j).getValue()
						if (j!=13) VC=VC+cell+' '
					}
				}
			}
			return VC

		}


		function Enviar(){
            <?php if ($arrHttp["Opcion"]=="new") echo "document.forma1.action.value='pft.php'\n"?>
			document.forma1.ValorCapturado.value=Capturar_Grid()
			document.forma1.submit()
		}

		function Test(){
			if (Trim(document.fst.Mfn.value)==""){
				alert("<?php echo $msgstr["mismfn"]?>")
				return
			}
			msgwin=window.open("","FST_Test")
			msgwin.document.close()
			msgwin.focus()
			document.test.Mfn.value=document.fst.Mfn.value
			document.test.ValorCapturado.value=Capturar_Grid()
			document.test.submit()

		}
	</script>
<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
echo "<form name=fst method=post>";
echo "<div class=\"sectionInfo\">
	<div class=\"breadcrumb\"><h2><label>".$msgstr["fst"].": ".$arrHttp["base"]."</label></h2></div>
	<div class=\"actions\">";
if ($arrHttp["Opcion"]=="new"){
	if (isset($arrHttp["encabezado"])){
	
	}else{
	
	}

}else{
	
}

if ($arrHttp["Opcion"]=="new"){
	
}
//echo "<a href=javascript:Enviar() class=\"defaultButton saveButton\">
//	<img src=\"../images/defaultButton_iconBorder.gif\" />
//	<strong>".$msgstr["update"]."</strong></a>";
?>
</div>

</font>
	</div>

			<div class="formContent">

  
   		<table class="table">
	        <tr>
			
				<a href="javascript:void(0)" class="btn btn-primary" onclick="AgregarFila(mygrid.getRowIndex(mygrid.getSelectedId()),'BEFORE')"><?php echo $msgstr["addrowbef"]?></a>
				<a href="javascript:void(0)" class="btn btn-primary" onclick="AgregarFila(mygrid.getRowIndex(mygrid.getSelectedId())+1,'AFTER')"><?php echo $msgstr["addrowaf"]?></a>
				<a href="javascript:void(0)" class="btn btn-primary" onclick="mygrid.deleteSelectedItem()"><?php echo $msgstr["remselrow"]?></a>
				<label><?php echo $msgstr['double_click'];?></label>
			
	
				
					<div id="gridbox" width="1000px" height="600px"></div>
				

		
					<?php if ($arrHttp["Opcion"]!="new"){
						echo "<label>" .$msgstr["testmfn"]. "</label>"  ;
						echo "<input type=text class=\"form-control\" size=5 name=Mfn> 

						
						<a class=\"btn btn-primary\" href=javascript:Test()>".$msgstr["test"]."</a>";
						}

					?>
						 <a class="btn btn-success" href='javascript:Enviar()' $msgstr"update"];?> <i class="fa fa-check" aria hidden="true" ></a></i>
					<?php
					if ($encabezado=""){
						if ($arrHttp["Opcion"]!="new")
							echo "<a href=menu_modificardb.php?base=". $arrHttp["base"].">".$msgstr["cancel"]."</a>";
				  		else
				    		echo "<a href=menu_creardb.php>".$msgstr["cancel"]."</a>";
					}
					?>
	 			</td>
			</tr>
		</table>
	</td>
	
	<iframe id="cframe" src="fdt_leer.php?Opcion=<?php echo $arrHttp["Opcion"]?>&base=<?php echo $arrHttp["base"]?>" width='100%' height="400" scrolling="yes" name="fdt"></iframe>
	</td>
		</table>
	</td>
</table>
<script>
	mygrid = new dhtmlXGridObject('gridbox');
  // mygrid.setSkin("modern");
   // mygrid.enableMultiline(true);

	

	mygrid.setHeader("<?php echo $msgstr["id"]?>,<?php echo $msgstr["intec"]?> ,<?php echo $msgstr["formate"]?>");

	
	
   
	mygrid.init();
    i=-0

<?php  $i=-1;
	unset($fp);
	if ($arrHttp["Opcion"]=="update"){
		$fp=file($db_path.$arrHttp["base"]."/data/".$arrHttp["base"].".fst");
		$t=array();
	}else{
		if (isset($_SESSION["FST"])){
            $_SESSION["FST"].="\n";
			$fp=explode("\n",$_SESSION["FST"]);
		}
	}
	if (isset($fp)){
		foreach ($fp as $value){
			if (trim($value)!=""){
				$ix=strpos($value," ");
				$t[0]=trim(substr($value,0,$ix));
				$value=trim(substr($value,$ix));
				$ix=strpos($value," ");
				$t[1]=trim(substr($value,0,$ix));
				$t2=htmlspecialchars_decode (trim(substr($value,$ix+1)));
				$t[2]=str_replace("'","\'",trim(substr($value,$ix+1)));
				$i=$i+1;
				echo "i=$i\n
				id=(new Date()).valueOf()
				mygrid.addRow(id,['".trim($t[0])."','".trim($t[1])."','".trim($t[2])."'],i)\n
			";
			}
		}
   }
?>
/*
	i++
	for (j=i;j<i+30;j++){
		mygrid.addRow((new Date()).valueOf(),['','',''],j)
	}
*/

	mygrid.clearSelection()
	mygrid.setSizes();
</script>


</form>
<form name="forma1" action="fst_update.php" method="post">
<input type="hidden" name="ValorCapturado">
<input type="hidden" name="desc">
<input type="hidden" name="Opcion" value="<?php echo $arrHttp["Opcion"];?>">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=S>"; ?>
</form>
<form name="test" action="fst_test.php" method="post" target="FST_Test">
<input type="hidden" name="ValorCapturado">
<input type="hidden" name="desc">
<input type="hidden" name="Mfn">
<input type="hidden" name="Opcion" value="<?php echo $arrHttp["Opcion"];?>">
<input type="hidden" name="base" value="<?php echo $arrHttp["base"];?>">
</form>
</div></div>
<?php
include("../common/footer.php");
?>
</body>
</html>
