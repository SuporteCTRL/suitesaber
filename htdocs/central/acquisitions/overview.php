<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../config.php");
$lang=$_SESSION["lang"];

include("../lang/acquisitions.php");

include("../common/get_post.php");
$arrHttp["base"]="suggestions";
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");

// Se determina el total de registros según cada status del proceso
$query = "&base=".$arrHttp["base"]."&cipar=$db_path"."par/".$arrHttp["base"].".par"."&prefijo=STA_&Opcion=diccionario";
$IsisScript=$xWxis."ifp.xis";
include("../common/wxis_llamar.php");
$Total=array(0,0,0,0,0,0,0,0);

foreach ($contenido as $value)  {
	$L=explode('|',$value);
	$ix=substr($L[0],4);
	$Total[$ix]=$L[1];
}
//

$encabezado="";
echo "<body>\n";
include("../common/institutional_info.php");
?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<h2><i class="fa fa-pencil-square-o fa-2x"></i> <label><?php echo $msgstr["suggestions"];?></label>   </h2> 
	</div>
	<div class=actions>
		<?php include("suggestions_menu.php");?>
	</div>
	
</div>

<div>

	<div class="formContent">

	
	<label><?php echo $msgstr["overview"].": ".$msgstr["suggestions"]?></label>
	
	<ul class="list-group">
            
           
			<a href=../dataentry/show.php?base=suggestions&Expresion=STA_0 target=_blank> 
				<li class="list-group-item">
            		<span class="badge"> <?php if (isset( $Total[0])) echo $Total[0];?></span>
					<?php echo $msgstr["status_0"];?> 
				</li>
			</a> 
			
			<a href=../dataentry/show.php?base=suggestions&Expresion=STA_1 target=_blank>
				<li class="list-group-item">
					<span class="badge"><?php if (isset($Total[1])) echo $Total[1];?></span> 
					<?php echo $msgstr["approved"];?>
		    	</li>
		    </a>

			<a href=../dataentry/show.php?base=suggestions&Expresion=STA_2 target=_blank>
				<li class="list-group-item">
					<span class="badge"><?php if (isset($Total[2])) echo $Total[2];?></span>
					<?php echo $msgstr["rejected"];?>
 				</li>
 			</a>
		

		    <a href=../dataentry/show.php?base=suggestions&Expresion=STA_3 target=_blank>
		    	<li class="list-group-item">
		    		<span class="badge"><?php if (isset($Total[3])) echo $Total[3];?></span>
					<?php echo $msgstr["inbidding"];?> 
				 </li>
			</a>

			<a href=../dataentry/show.php?base=suggestions&Expresion=STA_4 target=_blank>
				<li class="list-group-item">
					<span class="badge"><?php if (isset($Total[4])) echo $Total[4];?></span>
		   			<?php echo $msgstr["prov_sel"];?> 
		   		</li>
		   	</a>
		 
            <a href=../dataentry/show.php?base=suggestions&Expresion=STA_5 target=_blank>
 				<li class="list-group-item">
 					<span class="badge"><?php if (isset($Total[5])) echo $Total[5];?></span>
				 	<?php echo $msgstr["purchase"];?>  
				</li>
		    </a>
		
		    <a href=../dataentry/show.php?base=suggestions&Expresion=STA_6 target=_blank>
 				<li class="list-group-item">
 					<span class="badge"><?php if (isset($Total[6])) echo $Total[6];?></span>
					<?php echo $msgstr["itemsrec"];?>  
				</li>
			</a>
		 
         <a href=../dataentry/show.php?base=suggestions&Expresion=STA_7 target=_blank>
		 	<li class="list-group-item">
		 		<span class="badge"><?php if (isset($Total[7])) echo $Total[7];?></span>
                <?php echo $msgstr["completed"];?>
		 </li>
		 	</a>
	</ul>

	</div>
</div>
<?php include("../common/footer.php");
echo "</body></html>" ;


function Sugerencias(){

}
?>
