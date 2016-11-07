<?php
error_reporting(E_ALL);
set_time_limit(0);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../config.php");
include("../common/header.php");
echo "<script src=../dataentry/js/lr_trim.js></script>";

function MostrarResultado($output,$t,$path,$opcion){
	$straux="";
	for($i=0;$i<count($output);$i++){
		$straux.=$output[$i]."<br>";
	}
	echo "<font face=courier size=2>";

	echo "<h3>process Output: <br>".$straux;
	switch ($opcion){		case "ln":
			if (file_exists($path.".ln1") and file_exists($path.".ln2"))
				$t=0;
			break;
		case "lk1":
			if (file_exists($path.".lk1"))
				$t=0;
			else
				echo "<br><h2>".$path.".lk1 no fue creado</h2>";
			break;
		case "lk2":
			if (file_exists($path.".lk2"))
				$t=0;
			else
				echo "<br><h2>".$path.".lk2 no fue creado</h2>";
			break;	}
	if ($t==0)
		echo "<br>process Finished OK</h3><br>";
	else
		echo "<h2>Out: <br>process NOT EXECUTED. Error code: $t</h2><br>";
	echo "</font>";
}
?>
<script>
function OpenWindow(){	msgwin=window.open("","test","width=800,height=200");
	msgwin.focus()}
</script>
<?php
echo "<body>\n";
include("../common/institutional_info.php");
echo "
	<div class=\"sectionInfo\">
			<div class=\"breadcrumb\">".
				$msgstr["maintenance"]. ": " . $arrHttp["base"]."
			</div>
			<div class=\"actions\">

	";
echo "<a href=\"../dbadmin/menu_mantenimiento.php?base=".$arrHttp["base"]."&encabezado=S\" class=\"defaultButton backButton\">";
echo "<img src=\"../images/defaultButton_iconBorder.gif\" alt=\"\" title=\"\" />
	<span><strong>". $msgstr["back"]."</strong></span></a>";
echo "</div>
	<div class=\"spacer\">&#160;</div>
	</div>";
?>
<div class="helper">
	<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/menu_mantenimiento.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
 	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/menu_mantenimiento.html target=_blank>".$msgstr["edhlp"]."</a>";
echo "<font color=white>&nbsp; &nbsp; Script: utilities/vmx_fullinv.php";
?>
</font>
</div>
<div class="middle form">
	<div class="formContent">
<form name=maintenance>
<table cellspacing=5 align=center>
	<tr>
		<td>

		<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
             <br>

<?php

session_write_close();
$base=$arrHttp["base"];
$bd=$db_path.$base;

if (file_exists($bd."/data/stw.tab"))
	$stw=" stw=@".$bd."/data/stw.tab";
else
	if (file_exists($db_path."stw.tab"))
		$stw=" stw=@".$db_path."stw.tab";
	else
		$stw="";

if (!file_exists($mx_path)){	echo $mx_path.": ".$msgstr["misfile"];
	die;}
$ind=strrpos($mx_path,"/");
$ex_path=substr($mx_path,0,$ind+1);
$uctab="";
$actab="";
$cipar="";
if (file_exists($db_path."cipar.par")){
	$cipar=$db_path."cipar.par";
	$uctab="isisuc.tab";
	$actab="isisac.tab";
}else{	if (file_exists($db_path.$arrHttp["base"]."/data/isisuc.tab")){		$uctab=$db_path.$arrHttp["base"]."/data/isisuc.tab";	}else{		if (file_exists($db_path."isisuc.tab"))
			$uctab=$db_path."isisuc.tab";	}
	if ($uctab=="")  $uctab="ansi";


	if (file_exists($db_path.$arrHttp["base"]."/data/isisac.tab")){
		$actab=$db_path.$arrHttp["base"]."/data/isisac.tab";
	}else{
		if (file_exists($db_path."isisuc.tab"))
			$actab=$db_path."isisac.tab";
	}
	if ($actab=="")  $actab="ansi";
}

$parameters= "Command line:<br>";
$parameters.= "database: ".$bd."/data/".$base."<br>";
$parameters.= "fst: @".$bd."/data/".$base.".fst<br>";
$parameters.= "mx: $mx_path"." <a href=mx_test.php target=test onclick=OpenWindow()>Test</a><br>";
if ($stw!="") $parameters.= "stw: $stw<br>";
if ($uctab!="") $parameters.= "uctab: $uctab<br>";
if ($uctab!="") $parameters.= "actab: $actab<br>";


switch ($arrHttp["step"]){	case 1:
		$strInv=$mx_path."  ".$bd."/data/".$base. "$cipar fst=@".$bd."/data/".$base.".fst uctab=$uctab actab=$actab $stw ln1=".$bd."/data/".$base.".ln1 ln2=".$bd."/data/".$base.".ln2  ";
		if (isset($arrHttp["from"])) $strInv.="from=".$arrHttp["from"];
		if (isset($arrHttp["to"])) $strInv.=" to=".$arrHttp["to"];
		$strInv.=" +fix -all now ";        if (file_exists($bd."/data/".$base.".ln1")) unlink($bd."/data/".$base.".ln1");
        if (file_exists($bd."/data/".$base.".ln2")) unlink($bd."/data/".$base.".ln2");
        if (file_exists($bd."/data/".$base.".lk1")) unlink($bd."/data/".$base.".lk1");
        if (file_exists($bd."/data/".$base.".lk2")) unlink($bd."/data/".$base.".lk2");
		echo "<font face=courier size=2>".$parameters."<hr><br>";
		echo "Query: $strInv"."</font><br>";
		exec($strInv, $output,$t);
		MostrarResultado($output,$t,$bd."/data/".$base,"ln");
		break;
	case 2:
		if (file_exists($ex_path."mys.exe"))
			$strI=$ex_path."mys.exe";
		else
			$strI=$ex_path."mys";
		if (!file_exists($strI)){
			echo $strI.": ".$msgstr["misfile"];
			die;
		}
		if (!file_exists($bd."/data/".$base.".ln1") or !file_exists($bd."/data/".$base.".ln2")){			echo "<h2>".$msgstr["missing"]. " ".$bd."/data/".$base.".ln1". " or " . $bd."/data/".$base.".ln2";
			die;		}
        if (file_exists($bd."/data/".$base.".lk1")) unlink($bd."/data/".$base.".lk1");
        if (file_exists($bd."/data/".$base.".lk2")) unlink($bd."/data/".$base.".lk2");
		//$strInv=$strI." link1 ".$bd."/data/".$base.".ln1 ".$bd."/data/".$base.".lk1 ";
		$strInv="sort ".$bd."/data/".$base.".ln1 --output=".$bd."/data/".$base.".lk1 ";
		echo "<font face=courier size=2>".$parameters."<hr><br>";
		echo "Query: $strInv"."<br></font>";
		flush();
		exec($strInv, $output,$t);
		MostrarResultado($output,$t,$bd."/data/".$base,"lk1");
		flush();
		//$strInv=$strI." link2 ".$bd."/data/".$base.".ln2 ".$bd."/data/".$base.".lk2 ";
		$strInv="sort ".$bd."/data/".$base.".ln2 --output=".$bd."/data/".$base.".lk2 ";
		echo "<p><font face=courier size=2>Query: $strInv"."</font><br>";
		flush();
		exec($strInv, $output1,$t);
		MostrarResultado($output1,$t,$bd."/data/".$base,"lk2");
		flush();
		break;
	case 3:
		if (file_exists($ex_path."ifload.exe"))
			$strInv=$ex_path."ifload.exe";
		else
			$strInv=$ex_path."ifload";
		if (!file_exists($strInv)){
			echo $strInv.": ".$msgstr["misfile"];
			die;
		}
		$strInv.=" ".$bd."/data/".$base." ".$bd."/data/".$base.".lk1 ".$bd."/data/".$base.".lk2 +fix tell=1000";
		echo "<font face=courier size=2>".$parameters."<hr><br>";
		echo "Query: $strInv"."</font><br>";
		flush();
		exec($strInv, $output,$t);
		MostrarResultado($output,$t,$bd."/data/".$base,"fullinv");
		flush();
		if (file_exists($bd."/data/".$base.".ln1")) unlink($bd."/data/".$base.".ln1");
        if (file_exists($bd."/data/".$base.".ln2")) unlink($bd."/data/".$base.".ln2");
        if (file_exists($bd."/data/".$base.".lk1")) unlink($bd."/data/".$base.".lk1");
        if (file_exists($bd."/data/".$base.".lk2")) unlink($bd."/data/".$base.".lk2");
		break;
}

if($base=="")
{
echo"NO database selected";
}
?></li>


			</ul>

		</td>
</table></form>

<form name=admin method=post action=administrar_ex.php onSubmit="Javascript:return false">
<input type=hidden name=base>
<input type=hidden name=cipar>
<input type=hidden name=Opcion>
<?php if (isset($arrHttp["encabezado"])) echo "<input type=hidden name=encabezado value=s>"?>
</form>
<?php
switch($arrHttp["step"]){
	case "1":
		echo "<h2><a href=fullinv_steps.php?step=2&base=".$arrHttp["base"].">".$msgstr["continuar"]."</a></h2>";
		break;
	case "2":
		echo "<h2><a href=fullinv_steps.php?step=3&base=".$arrHttp["base"].">".$msgstr["continuar"]."</a></h2>";
		break;

}
?>
</div>
</div>
<?php
include("../common/footer.php");
echo "</body></html>";
?>

