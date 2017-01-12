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


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php echo $institution_name; ?></title>

  <!-- Bootstrap -->
    <link href="<?php echo $app_path;?>/css/<?php echo $css_name?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
   <script src="https://use.fontawesome.com/4c37ce0a9e.js"></script>
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/jqvmap.min.css" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="<?php echo $app_path?>/css/<?php echo $css_name?>/css/custom.css" rel="stylesheet">


		<!--Font Awesome-->
    <script src="https://use.fontawesome.com/4c37ce0a9e.js"></script>
	
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
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form target="top" name="administra" onsubmit="javascript:return false" method="post" action="<?php echo $app_path?>/common/inicio.php?reinicio=s&base=<?php echo $basedefault; ?>">

				<input type="hidden" name="Opcion" value="admin">
				<input type="hidden" name="cipar" value="acces.par">
				<input type="hidden" name="lang" value="<?php echo $arrHttp['lang']?>" >
	
              <h1>Entrar</h1>

              <div>
                <input type="text" name="login" id="user" class="form-control" placeholder="<?php echo $msgstr['userid'];?>" required=""/>
               
              </div>

              <div>
                <input type="password" id="pwd"  name="password" class="form-control" placeholder="<?php echo $msgstr["password"]?>" required="" />
              </div>

              <div>


	
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
	echo "</select>	";
}
?>

<div class="form-group">
			<input type="checkbox" name="newindow" value=
<?php
if (isset($open_new_window) and $open_new_window=="Y")
	echo "Y checked";
else
	echo "N";
?> />
			<label for="setCookie" class="inline"><?php echo $msgstr["openwindow"]?></label>
</div>
                <a href="javascript:Enviar()" class="btn btn-default"><?php echo $msgstr["entrar"]?></a>
               
              </div>

 
                <div class="clearfix"></div>
              



                <div>
                  <h1><img src=central/images/logoabcd.png> <?php echo $institution_name; ?></h1>
                  <?php include ("$app_path/common/footer.php");?>
                </div>
              </div>


            </form>
          </section>
        </div>


      </div>
    </div>
  </body>
</html>


<!--



<body>





      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">	

<form name="administra" onsubmit="javascript:return false" method="post" action="<?php echo $app_path?>/common/inicio.php?reinicio=s&base=<?php echo $basedefault; ?>">
<input type=hidden name=Opcion value=admin>
<input type=hidden name=cipar value=acces.par>
<value=<?php echo $arrHttp["lang"]?>>


<?php
if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<div class=\"helper alert\">".$msgstr["menu_noau"]."
			</div>
		";
}
?>
		
			<label for="user"><?php echo $msgstr["userid"]?></label>

<?php

if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"form-control\" onfocus=\"this.className = 'textEntry superTextEntry inputAlert textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry inputAlert';\" />\n";
}else{
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"form-control  \" onfocus=\"this.className = 'textEntry superTextEntry textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry';\" />\n";
}
?>
		
			<label for="pwd"><?php echo $msgstr["password"]?></label>
			<input  type="password" name="password" id="pwd" value="" class="textEntry superTextEntry" onfocus="this.className = 'textEntry superTextEntry textEntryFocus';" onblur="this.className = 'textEntry superTextEntry';" />
		


	
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
	echo "</select>	";
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
			
			<a href="javascript:Enviar()" class="btn btn-default"><?php echo $msgstr["entrar"]?></a>
	
<!--

		
			<label><?php echo $msgstr["lang"]?></label> <select name=lang class="textEntry singleTextEntry">
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



</form>
          </section>
        </div>
       </div> 

   <div class="clearfix"></div>       
 <div class="footer">
</div>
	</body>
</html>



