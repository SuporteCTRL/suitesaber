<?php
session_start();
$_SESSION=array();

include("get_post.php");
include("../config.php");

if (isset($_SESSION["lang"])){	$arrHttp["lang"]=$_SESSION["lang"];}else{	$arrHttp["lang"]=$lang;
	$_SESSION["lang"]=$lang;}

include ("../lang/lang.php");

include ("../lang/admin.php");
include("header.php");
include("institutional_info.php");





?>

<script src="../dataentry/js/lr_trim.js"></script>
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
			new_window=new Date()			document.administra.target=new_window;
			ancho=screen.availWidth-15
			alto=(screen.availHeight||screen.height) -50
			msgwin=window.open("",new_window,"menubar=no, toolbar=no, location=no, scrollbars=yes, status=yes, resizable=yes, top=0, left=0, width="+ancho+", height="+alto)
			msgwin.focus()		} else{			document.administra.target=""		}
		document.administra.submit()
	}
}

</script>
<body>
<div class="middle form">
	<div class="formContent">

<?php
echo "<br><br><h1>".$msgstr['sessionexpired']."</h1>";
?>


<form name=administra onsubmit="javascript:return false" method=post action=inicio.php>
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
					echo "<option value=$l[0] $selected>".$msgstr[$l[0]]."</option>";
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
		</div>
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
?> 
			<label for="setCookie" class="inline"><?php echo $msgstr["openwindow"]?></label>
		</div>
		<div class="submitRow">
			<div class="frLeftColumn"></div>
			<div class="frRightColumn">
				<a href="javascript:Enviar()" class="defaultButton goButton">
				<img src="../images/icon/defaultButton_next.png" alt="" title="" />
					<span><strong><?php echo $msgstr["entrar"]?></strong></span>
				</a>
			</div>
			<div class="spacer">&#160;</div>
		</div>
		<div class="spacer">&#160;</div>
	</div>
	<div class="boxBottom">
		<div class="bbLeft">&#160;</div>
			<div class="bbRight">&#160;</div>
	</div>
</div>
</div>
</form>


</div>
</div>
<?php include("footer.php")?>
</body>
</html>

