<?php

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
$lang=$_SESSION["lang"];
include("../common/get_post.php");
include("../config.php");
include("../lang/dbadmin.php");
include("../lang/prestamo.php");


//foreach ($arrHttp as $var=>$value) echo "$var=".urldecode($value)."<br>";

include("../common/header.php");
$encabezado="";
include("../common/institutional_info.php");
echo "
		<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">".
				$msgstr["policy"]."
			</div>
			<div class=\"actions\">\n";

				echo "
			</div>
			
		</div>
		<div class=\"middle form\">
			<div class=\"formContent\">\n";

$archivo=$db_path."circulation/def/".$_SESSION["lang"]."/typeofitems.tab";
if (!file_exists($archivo)) $archivo=$db_path."circulation/def/".$lang_db."/typeofitems.tab";
$fp=fopen($archivo,"w");
@  $ValorCapturado=urldecode($arrHttp["ValorCapturado"]);
fwrite($fp,$ValorCapturado);
fclose($fp);
echo "<div class=\"alert alert-success\">
<strong>". $msgstr["saved"]." </strong> circulation/def/".$_SESSION["lang"]."/typeofitems.tab</div>";
echo "</div></div>";
include("../common/footer.php");
echo "
</body>
</html>";
?>