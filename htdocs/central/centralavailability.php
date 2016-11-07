<?php
include("common/header.php");
require_once ("config.php");
$def = parse_ini_file($db_path."abcd.def");
include("common/get_post.php");

if (isset($arrHttp["lang"])){
	$_SESSION["lang"]=$arrHttp["lang"];
	$lang=$arrHttp["lang"];
}else{
	if (!isset($_SESSION["lang"]))
    $_SESSION["lang"]=$lang;
}
require_once ("lang/mysite.php");
require_once("lang/lang.php");
?>

<html>
<head>
	<!-- Stylesheets -->
		<link rel="stylesheet" rev="stylesheet" href="css/template.css" type="text/css" media="screen"/>

</head>
<body class="mysite">

<div class="headingmysite">
    <div class="institutionalInfo">
				<h1><?php echo $def["LEGEND2"]; ?></h1>
				<h2>ABCD</h2>
			</div>

			<div class="userInfo">

			</div>
			<div class="spacer">&#160;</div>

</div>
<?php
    //Search for the data in the loanobjects database 
	$Expresion="CONTROL_".$_GET["copyId"];
	$IsisScript= $xWxis."buscar_ingreso.xis";
	$Pft="v1'|',v10'~',(v959^i'!'v959^v'!'v959^l'!'v959^o'|')/";
	$query = "&base=loanobjects&cipar=$db_path"."par/loanobjects.par&Expresion=$Expresion&Pft=$Pft";
	include("common/wxis_llamar.php");
	$tcopias="";	
	foreach ($contenido as $linea){		
		if (trim($linea)!=""){	
			$splitbycopy=explode("~",$linea);
			$tcopias.=$splitbycopy[1];						
		}
	}
	$copias=explode("|",$tcopias);
	if (count($copias)>7) echo '<div style="background:#FFFFFF; width:100; height:100">';
	else echo '<div style="background:#FFFFFF; width:100; height:160px">';
	if (count($copias)==1) echo '</br><table><tr><td></td><td>'.$msgstr["centralnocopies"]."</td></tr></table>";
	else
	{	
?>

<table>
     <tr>
        <td width="85px"><b><?php echo $msgstr["inventory"]; ?></b></td>
        <td width="80px"><b><?php echo $msgstr["volume"]; ?></b></td>
        <td width="80px"><b><?php echo $msgstr["library"]; ?></b></td>
        <td width="80px"><b><?php echo $msgstr["objtype"]; ?></b></td>
        <td width="80px"><b><?php echo $msgstr["status"]; ?></b></td>
        <td width="110px"><b><?php echo $msgstr["loaneduntil"]; ?></b></td>
     </tr>
     
    <?php	       
         for ($i=0;$i<=(count($copias)-2);$i++)
         {
		 $onecopy=explode("!",$copias[$i]);
		 //Search for the record in the trans database 
	     $Expresion="TR_P_".$onecopy[0];
		 $IsisScript= $xWxis."buscar_ingreso.xis";
		 $Pft="v1'|',v10'~',v40'|',v45'|'/";
		 $query = "&base=trans&cipar=$db_path"."par/trans.par&Expresion=$Expresion&Pft=$Pft";
		 include("common/wxis_llamar.php");
		 $Copyloant="";
		 foreach ($contenido as $lineaT){	
			 if (trim($lineaT)!="") {
			 $splitbycopy=explode("~",$lineaT);
			 $Copyloant.=$splitbycopy[1];
			 }
		 }
		 $loans=explode("|",$Copyloant);
			 
     ?>

     <tr>
        <td><?php echo $onecopy[0];?></td>
        <td><?php echo $onecopy[1];?></td>
        <td><?php echo $onecopy[2];?></td>
        <td><?php echo $onecopy[3];?></td>
        <td><?php if (count($loans)==1) echo $msgstr["available"]; else echo $msgstr["notavailable"];?></td>
        <td><?php if (count($loans)>1) {echo $loans[0]; if ($loans[1]!="") echo " at ".$loans[1];}?></td>
     </tr>

    <?php
        }       	
	 ?>
</table>
<hr />
<?php
	 }//else if (count($copias)==0)
	 ?>
</div>
		<div class="footermysite">
			<div class="systemInfo">
				<strong style="color:#FFFFFF">ABCD <?php echo $def["VERSION"] ?></strong>
				<span style="color:#FFFFFF"><?php echo $def["LEGEND1"]; ?></span>
				<a href="<?php echo $def["URL1"]; ?>" target=_blank style="color:#FFFFFF"><?php echo $def["URL1"]; ?></a>
			</div>
			<div class="distributorLogo">
				<a href="<?php echo $def["URL2"]; ?>" target=_blank><span><?php echo $def["LEGEND2"]; ?></span></a>
			</div>
			<div class="spacer">&#160;</div>
		</div>	

</body>
</html>