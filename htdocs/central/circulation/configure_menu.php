<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      configure_menu.php
 * @desc:      Configuration menu
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
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
$lang=$_SESSION["lang"];
include("../common/get_post.php");
include("../config.php");
include("../lang/admin.php");
include("../lang/prestamo.php");


//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
include("../common/header.php");
$encabezado="";
echo "<body>\n";
$encabezado="&encabezado=s";
include("../common/institutional_info.php");
echo "
	<div class=\"actions\">";
	include("submenu_prestamo.php");
echo "</div>
	
</div>";
?>


	<nav class="navbar navbar-default">
  <div class="container-fluid">

    <div class="navbar-header">
      <a class="navbar-brand"> Configuração</a>
    </div>


  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">

          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">	<label><?php echo $msgstr["policy"]?></label><span class="caret"></span></a>

          <ul class="dropdown-menu">
			

<li><a href="databases.php" target="configura" class="menuButton multiLine origendatabaseButton"><?php echo $msgstr["sourcedb"];?></a></li>
<li><a href="borrowers_configure.php" target="configura" class="menuButton multiLine usersconfigureButton"><?php echo $msgstr["bconf"];?></a></li>
<li><a href="typeofusers.php" target="configura" class="menuButton multiLine userstypeButton"><?php echo $msgstr["typeofusers"];?></li></a>
<li><a href="typeofitems.php" target="configura" class="menuButton multiLine itemstypeButton"><?php echo $msgstr["typeofitems"];?></li></a>
<li><a href="loanobjects.php" target="configura" class="menuButton multiLine loanpolicyButton"><?php echo $msgstr["objectpolicy"];?></a></li>
<li><a href="locales.php" target="configura" class="menuButton multiLine currency_daysButton"><?php echo $msgstr["local"];?></li></a>
<li><a href="calendario.php" target="configura" class="menuButton multiLine calendarButton"><?php echo $msgstr["calendar"];?></a></li>
<li><a href="sala_configure.php" target="configura" class="menuButton multiLine loanpolicyButton"><?php echo $msgstr["sala"];?> </li></a>
  
  </ul>
     </li>
      </ul>
		
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><label><?php echo $msgstr["outputs"];?></label> <span class="caret"></span></a>
          <ul class="dropdown-menu">

             <li><a target="configura" href="../circulation/receipts.php?base=trans&encabezado=s&retorno=../circulation/configure_menu.php" class="menuButton multiLine receiptsButton"><?php echo $msgstr["receipts"];?> </a></li>

				<li><a target="configura" href="../dbadmin/pft.php?base=trans&encabezado=s&retorno=../circulation/configure_menu.php" class="menuButton multiLine reportsButton"><?php echo $msgstr["reports_trans"];?></a></li>

				<li><a target="configura" href="../dbadmin/pft.php?base=suspml&encabezado=s&retorno=../circulation/configure_menu.php" class="menuButton multiLine reportsButton"> <?php echo $msgstr["reports_suspml"];?></a></li>

				<li><a target="configura" href="../dbadmin/pft.php?base=users&encabezado=s&retorno=../circulation/configure_menu.php" class="menuButton multiLine reportsButton"><?php echo $msgstr["reports_borrowers"];?></a></li>

		



    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<iframe src name="configura" width="100%" height="3000px" frameborder="0"></iframe>





<form name="admin" method="post" action="administrar_ex.php" onSubmit="Javascript:return false">
<input type="hidden" name="base">
<input type="hidden" name="cipar">
<input type="hidden" name="Opcion">
</form>
</div>
</div>
<?php include("../common/footer.php");?>

</body>
</html>
