<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      users administration
 * @desc:
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
global $arrHttp;
session_start();
unset($_SESSION["Browse_Expresion"]);
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
include("../common/header.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/profile.php");
//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";
echo "<body>\n";
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>

<div class="sectionInfo">
	<div class="breadcrumb">
<?php echo $msgstr["usuarios"]?>
	</div>

	
<div class="middle form">
	<div class="formContent">
	<ul>
<?php
echo "<a class=\"btn btn-default\" href=../dataentry/browse.php?showdeleted=Y&encabezado=s&base=acces&cipar=acces.par&return=../dbadmin/users_adm.php|>".$msgstr["usuarios"] ."</a><p>";
echo "<a class=\"btn btn-default\" href=profile_edit.php?encabezado=s>".$msgstr["profiles"]."</a>";

?>
	</ul>
	</div>
</div>
</center>
<?php include("../common/footer.php");
echo "</body></html>\n";

?>