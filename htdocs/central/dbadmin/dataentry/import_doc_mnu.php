
<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      fmt.php
 * @desc:      Search form for z3950 record importing
 * @author:    Guilda Ascencio
 * @since:     20091203
 * @version:   1.0
 *
 * == BEGIN LICENSE ==
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as
 *    published by the Free Software Foundation, either version 3 of the
 *    License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * == END LICENSE ==
*/

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
$lang=$_SESSION["lang"];
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once("../common/get_post.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
require_once("../config.php");
require_once ("../lang/admin.php");
require_once ("../lang/importdoc.php");

if (file_exists($db_path.$arrHttp["base"]."/dr_path.def")){
	$def = parse_ini_file($db_path.$arrHttp["base"]."/dr_path.def");
	if (isset($def["ROOT"])){
		$dr_path=trim($def["ROOT"]);
		$ix=strrpos($dr_path,"/");
        $dr_path_rel=substr($dr_path,0,$ix-1);
        $ix=strrpos($dr_path_rel,"/");
        $dr_path_rel="<i>[dr_path.def]</i>".substr($dr_path,$ix);
	}else{
		$dr_path=getenv("DOCUMENT_ROOT")."/bases/".$arrHttp["base"]."/";
		$dr_path_rel="<i>[DOCUMENT_ROOT]</i>/bases/".$arrHttp["base"]."/";
	}
}

include("../common/header.php");
?>
<body>
<div class="helper">
<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/import_doc.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
<?php
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/import_doc.html target=_blank>".$msgstr["edhlp"]."</a>";
echo "<font color=white>&nbsp; &nbsp; Script: import_doc_mnu.php" ?>
</font>
</div>
<div class="middle form">
	<div class="formContent">
<?php
$OS=strtoupper(PHP_OS);
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/acquisitions.php");
include("../config.php");
include("../common/header.php");

$base=$arrHttp["base"];
$OS=strtoupper(PHP_OS);
$converter_path=$mx_path;
if (strpos($OS,"WIN")=== false) 
{
$converter_path=str_replace('mx.exe','',$converter_path);
$converter_path.="mx";
}
else
$converter_path.="mx.exe";
$mx_path=$converter_path;
echo "<div class=\"middle form\">
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">
<font color='red'>The name of the file can not contain spaces</font>
 <br> <label for=\"archivo\">Choose File:</label>
  <input type=\"file\" name=\"archivo\" id=\"archivo\" />
  ";
 include("../common/get_post.php");
  $base=$arrHttp["base"];
 
  echo " <input type=\"hidden\" value=\"$base\" name=\"base\"/>";
echo "<input type=\"submit\" value=\"Send\"/>
  </form>";
$base=$_POST['base'];
$bd=$db_path.$base;
if( !isset($_FILES['archivo']) )
{
  echo '<div class=\"middle form\"><br>No file chosen yet<br/></div>';
  
}

else
{

  $nombre = $_FILES['archivo']['name'];
  $nombre_tmp = $_FILES['archivo']['tmp_name'];
  $tipo = $_FILES['archivo']['type'];
  $tamano = $_FILES['archivo']['size'];

     $limite = 5000 * 1024;

  
    if( $_FILES['archivo']['error'] > 0 )
	{
      echo 'Error: ' . $_FILES['archivo']['error'] . '<br/>';
    }
	else
	{
	echo "<h3>File upload information</h3>";
      echo 'Name: ' . $nombre . '<br/>';
      echo 'Type: ' . $tipo . '<br/>';
      echo 'Size: ' . ($tamano / 1024) . ' Kb<br/>';
      echo 'saved in: ' . $nombre_tmp;

      if( file_exists( 'subidas/'.$nombre) )
	  {
        echo '<br/>The file: ' . $nombre. " already exists";
      }
	  else
	  {
   move_uploaded_file($nombre_tmp,
          "../../../bases/wrk/" . $nombre);
		  
		  echo "<br/>Saved in: " . "../../../bases/wrk/" . $nombre;
		  $OK="OK";
      }
    }
	
  }

if(isset($OK))
  {
   //abrimos el archivo de texto y obtenemos el identificador
if($tipo!="text/html")
{
if($OS=="WINNT")
{
$converter_path=str_replace('mx.exe','',$converter_path);
$cmdconvert="pdftohtml -i -noframes ".$db_path."wrk/".$nombre." ".$db_path."wrk/".$nombre.".html";
//$cmdconvert="java -jar C:/ABCD/www/cgi-bin/tika16.jar -h ".$db_path."wrk/".$nombre." ".$db_path."wrk/".$nombre.".html";
}
/*else
$cmdconvert="pdftohtml -i -noframes ".$db_path."wrk/".$nombre." ".$db_path."wrk/".$nombre.".html";
 */
exec($cmdconvert,$out,$b);
if($b==1)
{
echo "<br><font color='red'>Error ocurred converting to HTML</font>";
exit;
}
else
{
 

$nombre=$nombre.".html";
echo "<br>Import proccess OK";
}
}
   $fichero_texto = fopen ("../../../bases/wrk/" . $nombre, "r");
   $Nro = fread($fichero_texto, filesize("../../../bases/wrk/" . $nombre));
   $Nro=strip_tags($Nro);
   $IsisScript="$Wxis"." IsisScript=hi.xis";
$bdp=$arrHttp["base"];//"base1";
$tag=$arrHttp["Tag"];
$url=$arrHttp["fURL"];
if($tag=="")
$tag="1";
if($url=="")
$url="2";
$str="<IsisScript name=hi>
<parm name=cipar><pft>'$bdp.*=$db_path"."$bdp/data/$bdp.*',/
'htm.pft=$bdp\data\$bdp.pft'</pft></parm>
<do task=update>
<parm name=db>$bdp</parm>
<parm name=fst><pft>cat('$bdp.fst')</pft></parm>
<parm name=mfn>New</parm>
<field action=define tag=1102>Isis_Status</field>
<update>
<field action=add tag=$tag>$db_path/wrk/$nombre</field>
<field action=add tag=$url>$Nro</field>
<field action=add tag=1001>45</field>
<field action=add tag=1092>0</field>
<field action=add tag=1091>0</field>
<field action=add tag=1002>45</field>
<field action=add tag=3030>all</field>
<field action=add tag=5001>$bdp</field>
<field action=replace tag=100 split=occ><pft>(v100/)</pft></field>
<write>Unlock</write>
<display>
<pft>if val(v1102) = 0 then '<b>Created!</b><hr>' fi </pft>
</display>
</update>
</do>
</IsisScript>";
@ $fp = fopen("hi.xis", "w");

@  flock($fp, 2);

  if (!$fp)
  {
    echo "<p><strong> Error ocurred in ISIS Script."
         ."Please try again.</strong></p></body></html>";
    exit;
  }

  fwrite($fp, $str);
  flock($fp, 3);
  fclose($fp);
exec($IsisScript,$salida,$bandera);
}
?>

</div></div>

<?php
include("../common/footer.php");

?>
</body>
</Html>
