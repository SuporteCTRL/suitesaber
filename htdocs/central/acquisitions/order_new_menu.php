<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../config.php");
$lang=$_SESSION["lang"];

include("../lang/acquisitions.php");
include("../lang/admin.php");

include("../common/get_post.php");
$arrHttp["Mfn"]="New";
$arrHttp["base"]="suggestions";
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
foreach ($arrHttp as $var => $value) {
	if (substr($var,0,3)=="tag" ){
		$tag=split("_",$var);
		if (isset($variables[$tag[0]])){
			$variables[$tag[0]].="\n".$value;
			$valortag[substr($tag[0],3)].="\n".$value;
		}else{
			$variables[$tag[0]]=$value;
			$valortag[substr($tag[0],3)]=$value;
		}
   	}

}

include("../common/header.php");
include("javascript.php");
?>

<?php                                                                                                                                      $encabezado="";
echo "<body>\n";
include("../common/institutional_info.php");
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?php echo $msgstr["suggestions"].": ".$msgstr["new"];?></a>
    </div>
	
	
	
	

     <div class="collapse navbar-collapse" >
    <ul  class="nav navbar-nav">

<?php
	$file=$db_path."copies/def/".$_SESSION["lang"]."/acquiredby.tab";
	if (!file_exists($file))
		$file=$db_path."copies/def/".$lang_db."/acquiredby.tab";
	$fp=file($file);
	foreach ($fp as $var){
		$var=trim($var);
		$v=explode('|',$var);
		$var=urlencode($var);
		echo "<li><a target=\"configura\" href=order_new.php?base=purchaseorder&cipar=purchaseorder.par&mov=$var&Opcion=nuevo&wks=".$v[0].">".$v[1]."</a></li>";
	}

?>
	</ul>
	</div>
	</div>


</nav>
<iframe src="" name="configura" width="100%" height="3000px" frameborder="0"></iframe>


<?php include("../common/footer.php");
echo "</body></html>" ;
?>