<?php
session_start();
$_SESSION=array();
include("central/config.php");
include("$app_path/common/get_post.php");
$new_window=time();
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";

if (isset($_SESSION["lang"])){
	$arrHttp["lang"]=$_SESSION["lang"];
}else{
	$arrHttp["lang"]=$lang;
	$_SESSION["lang"]=$lang;
}
include ("$app_path/lang/admin.php");
include ("$app_path/lang/lang.php");

	if (!isset($css_name))
		$css_name="suitesaber";
	else
		$css_name.="/";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br">
	<head>
		<title><?php echo $institution_name; ?></title>
		<meta http-equiv="Expires" content="-1" />
		<meta http-equiv="pragma" content="no-cache" />
		<META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta http-equiv="Content-Language" content="pt-br" />
		<meta name="robots" content="all" />
		<meta http-equiv="keywords" content="" />
		<meta http-equiv="description" content="" />
		<!-- Stylesheets -->
		<link rel="stylesheet" rev="stylesheet" href="<?php echo $app_path?>/css/<?php echo $css_name?>/template.css" type="text/css" media="screen"/>
		<!--[if IE]>
			<link rel="stylesheet" rev="stylesheet" href="<?php echo $app_path?>/css/bugfixes_ie.css" type="text/css" media="screen"/>
		<![endif]-->
		<!--[if IE 6]>
			<link rel="stylesheet" rev="stylesheet" href="<?php echo $app_path?>/css/bugfixes_ie6.css" type="text/css" media="screen"/>
		<![endif]-->
<script src=<?php echo $app_path?>/dataentry/js/lr_trim.js></script>
<script language="javascript">

document.onkeypress =
	function (evt) {
			var c = document.layers ? evt.which
	       		: document.all ? event.keyCode
	       		: evt.keyCode;
			if (c==13) Enviar()
			return true;
	}

function UsuarioNoAutorizado(){
	alert("<?php echo $msgstr["menu_noau"]?>")

}

function Enviar(){
	login=Trim(document.administra.login.value)
	password=Trim(document.administra.password.value)
	if (login=="" || password==""){
		alert("<?php echo $msgstr["datosidentificacion"]?>")
		return
	}else{
		if (document.administra.newindow.checked){
			new_window=new Date()
			document.administra.target=new_window;
			ancho=screen.availWidth-15
			alto=(screen.availHeight||screen.height) -50
			msgwin=window.open("",new_window,"menubar=no, toolbar=no, location=no, scrollbars=yes, status=yes, resizable=yes, top=0, left=0, width="+ancho+", height="+alto)
			msgwin.focus()
		} else{
			document.administra.target=""
		}
		document.administra.submit()
	}
}

</script>
</head>
<body>
	<div class="heading">
		<div class="institutionalInfo">
			<h1><img src=central/images/logoabcd.png >
			<?php echo $institution_name; ?></h1>
		</div>
		<div class="userInfo"></div>
		<div class="spacer">&#160;</div>
	</div>
	<div class="sectionInfo">
		<div class="breadcrumb"></div>
		<div class="actions"></div>
		<div class="spacer">&#160;</div>
	</div>
<form name=administra onsubmit="javascript:return false" method=post action=<?php echo $app_path?>/common/inicio.php?reinicio=s&base=<?php echo $basedefault; ?>>
<input type=hidden name=Opcion value=admin>
<input type=hidden name=cipar value=acces.par>
<input type=hidden name=lang value=<?php echo $arrHttp["lang"]?>>
	<div class="middle login">
		<div class="loginForm">
			<div class="boxTop">
				<div class="btLeft">&#160;</div>
				<div class="btRight">&#160;</div>
			</div>
		<div class="boxContent">
<?php
if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<div class=\"helper alert\">".$msgstr["menu_noau"]."
			</div>
		";
}
?>
		<div class="formRow">
			<label for="user"><?php echo $msgstr["userid"]?></label>
<?php
if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"textEntry superTextEntry inputAlert\" onfocus=\"this.className = 'textEntry superTextEntry inputAlert textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry inputAlert';\" />\n";
}else{
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"textEntry superTextEntry\" onfocus=\"this.className = 'textEntry superTextEntry textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry';\" />\n";
}
?>
		</div>
		<div class="formRow">
			<label for="pwd"><?php echo $msgstr["password"]?></label>
			<input type="password" name="password" id="pwd" value="" class="textEntry superTextEntry" onfocus="this.className = 'textEntry superTextEntry textEntryFocus';" onblur="this.className = 'textEntry superTextEntry';" />
		</div>
		<div id="formRow3" class="formRow formRowFocus">
			<label ><?php echo $msgstr["lang"]?></label> <select name=lang class="textEntry singleTextEntry">
<?php

 	$a=$db_path."lang.tab";
 	if (file_exists($a)){
		$fp=file($a);
		$selected="";
		foreach ($fp as $value){
			$value=trim($value);
			if ($value!=""){
				$l=explode('=',$value);
				if ($l[0]!="lang"){
					if ($l[0]==$_SESSION["lang"]) $selected=" selected";
					echo "<option value=$l[0] $selected>".$msgstr[$l[0]]." </option>";
					$selected="";
				}
			}
		}
	}else{
		echo $msgstr["flang"].$db_path."lang/".$_SESSION["lang"]."/lang.tab";
		die;
	}
?>
			</select>
	/*	</div>
		<div class="formRow"><br>
<?php
if (file_exists("dbpath.dat")){
	$fp=file("dbpath.dat");
	echo $msgstr["database_dir"].": <select name=db_path>\n";
	foreach ($fp as $value){
		if (trim($value)!=""){
			$v=explode('|',$value);
			$v[0]=trim($v[0]);
			echo "<Option value=".trim($v[0]).">".$v[1]."\n";
		}

	}
	echo "</select><p>";
}
?>
			<input type="checkbox" name="newindow" value=
<?php
if (isset($open_new_window) and $open_new_window=="Y")
	echo "Y checked";
else
	echo "N";
?> />
			<label for="setCookie" class="inline"><?php echo $msgstr["openwindow"]?></label>
		</div>
		<div class="submitRow">
			<div class="frLeftColumn"></div>
			<div class="frRightColumn">
				<a href="javascript:Enviar()" class="defaultButton goButton">
				<img src="<?php echo $app_path?>/images/icon/defaultButton_next.png" alt="" title="" />
					<span><strong><?php echo $msgstr["entrar"]?></strong></span>
				</a>
			</div>
		
		</div>
	
	</div>

</div>
</div>
</form>
<?php include ("$app_path/common/footer.php");?>
	</body>
</html>



