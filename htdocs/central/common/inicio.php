<?php
global $Permiso, $arrHttp,$valortag,$nombre;
$arrHttp=Array();
session_start();
unset( $_SESSION["TOOLBAR_RECORD"]);
include("get_post.php");
if (isset($arrHttp["db_path"]))
	$_SESSION["DATABASE_DIR"]=$arrHttp["db_path"];
require_once ("../config.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";die;
$valortag = Array();

function CambiarPassword($Mfn,$new_password){
global $xWxis,$Wxis,$db_path,$wxisUrl,$MD5;
	if (isset($MD5) and $MD5==1 ){
		$new_password=md5($new_password);
	}
	$ValorCapturado="d30<30 0>".$new_password."</30>";
	$ValorCapturado=urlencode($ValorCapturado);
	$IsisScript=$xWxis."actualizar.xis";
  	$query = "&base=acces&cipar=$db_path"."par/acces.par&login=".$_SESSION["login"]."&Mfn=" . $Mfn."&Opcion=actualizar&ValorCapturado=".$ValorCapturado;
    echo $query;
    include("wxis_llamar.php");
}
function LeerRegistro() {
// la variable $llave permite retornar alguna marca que est� en el formato de salida
// identificada entre $$LLAVE= .....$$

$llave_pft="";
global $llamada, $valortag,$maxmfn,$arrHttp,$OS,$Bases,$xWxis,$Wxis,$Mfn,$db_path,$wxisUrl,$MD5;
    $ic=-1;
	$tag= "";
	$IsisScript=$xWxis."login.xis";
	$pass=$arrHttp["password"];
	if (isset($MD5) and $MD5==1){
		$pass=md5($pass);
	}
	$query = "&base=acces&cipar=$db_path"."par/acces.par"."&login=".$arrHttp["login"]."&password=".$pass;
	include("wxis_llamar.php");

	 foreach ($contenido as $linea){

	 	if ($ic==-1){
	    	$ic=1;
	    	$pos=strpos($linea, '##LLAVE=');
	    	if (is_integer($pos)) {
	     		$llave_pft=substr($linea,$pos+8);
	     		$pos=strpos($llave_pft, '##');
	     		$llave_pft=substr($llave_pft,0,$pos);
			}
		}else{
			$linea=trim($linea);
			$pos=strpos($linea, " ");
			if (is_integer($pos)) {
				$tag=trim(substr($linea,0,$pos));
	//
	//El formato ALL env�a un <br> al final de cada l�nea y hay que quitarselo
	//
				$linea=rtrim(substr($linea, $pos+2,strlen($linea)-($pos+2)-5));
				if (!isset($valortag[$tag])) $valortag[$tag]=$linea;
			}
		}

	}
	return $llave_pft;

}

function VerificarUsuario(){
global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
 	$llave=LeerRegistro();
 	if ($llave!=""){
  		$res=explode('|',$llave);
  		$userid=$res[0];
  		$_SESSION["mfn_admin"]=$res[1];
  		$mfn=$res[1];
  		$nombre=$res[2];
		$arrHttp["Mfn"]=$mfn;
  		$Permiso="|";
  		$Per="";
  		$value=$valortag[40];
  		if (isset($valortag[60]))
  			$fecha=$valortag[60];
  		else{
  			$fecha="";
  		$today=date("Ymd");
  		if (trim($fecha)!=""){
  			if ($today>$fecha){
  				header("Location: ../../login.php?login=N");
  				die;
  			}
  		}
  	}
  		$value=substr($value,2);
  		$ix=strpos($value,'^');
  		$Perfil=substr($value,0,$ix);
    	if (!file_exists($db_path."par/profiles/".$Perfil)){
    		echo "missing ". $db_path."par/profiles/".$Perfil;
    		die;
    	}
    	$profile=file($db_path."par/profiles/".$Perfil);
    	unset($_SESSION["profile"]);
    	unset($_SESSION["permiso"]);
    	unset($_SESSION["login"]);
    	$_SESSION["profile"]=$Perfil;
    	$_SESSION["login"]=$arrHttp["login"];
    	foreach ($profile as $value){
    		$value=trim($value);
    		if ($value!=""){
    			$key=explode("=",$value);
    			$_SESSION["permiso"][$key[0]]=$key[1];
    		}
    	}
        if (isset($valortag[70])){
        	$library=$valortag[70];
        	$_SESSION["library"]=$library;
        }else{
        	unset ($_SESSION["library"]);
        }
 	}else{
 		if ($arrHttp["login"]==$adm_login and $arrHttp["password"]==$adm_password){
 			$Perfil="adm";
 			unset($_SESSION["profile"]);
    		unset($_SESSION["permiso"]);
    		unset($_SESSION["login"]);
 			$profile=file($db_path."par/profiles/".$Perfil);
    		$_SESSION["profile"]=$Perfil;
    		$_SESSION["login"]=$arrHttp["login"];
    		foreach ($profile as $value){
    			$value=trim($value);

    			if ($value!=""){
    				$key=explode("=",$value);
    				$_SESSION["permiso"][$key[0]]=$key[1];
    			}
    		}
    	}else{
 			echo "<script>\n";
 			if (isset($_SESSION["HOME"]))
 				echo "self.location.href=\"".$_SESSION["HOME"]."?login=N\"\n";
 			else{
 				echo "self.location.href=\"../../login.php?login=N\";\n";

 			echo "</script>\n";
  			die;
  		}
  		}
 	}
}

/////
/////   INICIO DEL PROGRAMA
/////


$query="";



//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";

if (isset($arrHttp["newindow"]))
	$_SESSION["newindow"]="Y";

else 

	if (!isset($arrHttp["reinicio"])) unset($_SESSION["newindow"]);

if (isset($arrHttp["lang"])){
	$_SESSION["lang"]=$arrHttp["lang"];
	$lang=$arrHttp["lang"];
	}

else {
	if (!isset($_SESSION["lang"])) $_SESSION["lang"]=$lang;
}
include("../lang/dbadmin.php");
include("../lang/admin.php");
include("../lang/prestamo.php");
include("../lang/lang.php");
include("../lang/acquisitions.php");

	if (!isset($_SESSION["Expresion"])) $_SESSION["Expresion"]="";

	if (isset($arrHttp["login"])){
		VerificarUsuario();
		$_SESSION["lang"]=$arrHttp["lang"];
		$_SESSION["login"]=$arrHttp["login"];
		$_SESSION["password"]=$arrHttp["password"];
		$_SESSION["nombre"]=$nombre;

	}
	if (!isset($_SESSION["permiso"])){
		$msg=$msgstr["invalidright"]." ".$msgstr[$arrHttp["startas"]];
		echo "
		<html>
		<body>
		<form name=err_msg action=error_page.php method=post>
		<input type=hidden name=error value=\"$msg\">
		";
		if (isset($arrHttp["newindow"]))
			echo "<input type=hidden name=newindow value=Y>
		";
		echo "
		</form>
		<script>
			document.err_msg.submit()
		</script>
		</body>
		</html>
		";
    	session_destroy();
    	die;
    }
	$Permiso=$_SESSION["permiso"];
	if (isset($arrHttp["Opcion"]) and $arrHttp["Opcion"]=="chgpsw"){
		CambiarPassword($arrHttp["Mfn"],$arrHttp["new_password"]);
		if (isset($_SESSION["HOME"]))
			$retorno=$_SESSION["HOME"];
		else
			$retorno="../../login.php";
		header("Location: $retorno?login=P");
	}else{

    include("header.php");
?>

    <body class="nav-md todo">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-gitlab"></i> <span><?php echo $institution_name?></span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="/bases/acces/user.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Ol�,</span>
                <h2><?php echo $_SESSION["nombre"]?>, </h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

       <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Administra��o</h3>
                  <ul class="nav side-menu">


<?php
		include("homepage.php");
		include("menucirculation.php");
		include("menuacquisitions.php");

	}

?>


    
             </div>
            </div>
            <!-- /sidebar menu -->

          </div>
        </div>


        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                
              </div>
                 
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/user.png" alt=""><?php echo $_SESSION["nombre"];?>, <?php echo $_SESSION["profile"];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>

                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="../dataentry/logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        
  <!-- page content -->
        <div class="right_col" role="main" id="content">
          <div class="">
			
			<iframe name="content" frameborder="no" scrolling="auto" width="100%" height="3000px" src="/site"></iframe>

          </div>
        </div>
        <!-- /page content -->
        

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Suite saber 
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

  <?php
    include("../common/footer.php");
  ?>
  </body>