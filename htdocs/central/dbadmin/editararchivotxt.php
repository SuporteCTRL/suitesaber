<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];

include("../lang/dbadmin.php");;
$archivo=str_replace("\\","/",$arrHttp["archivo"]);
include("../common/header.php");
?>

<body>
<?php
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
if (isset($arrHttp["retorno"]))
	$retorno=$arrHttp["retorno"];
else
	$retorno="menu_modificardb.php?base=".$arrHttp["base"]."&encabezado=s";
?>
<div class="sectionInfo">
	<div class="breadcrumb">
		<?php echo "<h5>"." " .$msgstr["database"].": ".$arrHttp["base"]."</h5>"?>
	</div>
	<div class="actions">
<?php echo "<a href=\"$retorno\" class=\"defaultButton backButton\">";
?>
		<img src="../images/defaultButton_iconBorder.gif" alt="" title="" />
		<span><strong><?php echo $msgstr["back"]?></strong></span>
		</a>
	</div>
	<div class="spacer">&#160;</div>
</div>
<?php }?>
<div class="middle form">
	<div class="formContent">
<br><br>
<?php
$ar=explode('/',$arrHttp["archivo"]);
$xi=count($ar)-1;
$file=$ar[$xi];
if (file_exists($db_path.$archivo))
	$fp=file($db_path.$archivo);
else
	$fp=array();
echo "
<form method=post action=actualizararchivotxt.php>
<input type=hidden name=archivo value='".$arrHttp["archivo"]."'>";
if (!isset($arrHttp["retorno"]))
	echo "<label><h4>".$file."</label> 
<a class=\"btn btn-danger\" href=javascript:self.close()><i class=\"fa fa-times\" value=".$msgstr["close"]."></i></a>";
echo "<textarea name=txt rows=20 cols=100 class=\"form-control\" style=\"font-family:courier\";>";
foreach ($fp as $value) echo $value;
echo "</textarea>
<br><button class=\"btn btn-primary\" type=submit ><i class=\"fa fa-refresh\" value=".$msgstr["update"]."></i></button>    ";
if (isset($arrHttp["retorno"]))
	echo "<input type=hidden name=retorno value=\"".$arrHttp["retorno"]."\">\n";
if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=\"".$arrHttp["encabezado"]."\">\n";
if (isset($arrHttp["base"]))
	echo "<input type=hidden name=base value=\"".$arrHttp["base"]."\">\n";
echo "</form>
</div></div>";
include("../common/footer.php");
echo "</body></html>";
?>