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
	if (isset($MD5) and $MD5==1 ){
	$ValorCapturado="d30<30 0>".$new_password."</30>";
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
	$query = "&base=acces&cipar=$db_path"."par/acces.par"."&login=".$arrHttp["login"]."&password=".$pass;
	include("wxis_llamar.php");

	 foreach ($contenido as $linea){

	 	if ($ic==-1){
	    	$pos=strpos($linea, '##LLAVE=');
	    	if (is_integer($pos)) {
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
Global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
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
  		else
  			$fecha="";
  		$today=date("Ymd");
  		if (trim($fecha)!=""){
  				header("Location: ../../index.php?login=N");
  				die;
  		$value=substr($value,2);
  		$ix=strpos($value,'^');
  		$Perfil=substr($value,0,$ix);
    	if (!file_exists($db_path."par/profiles/".$Perfil)){
    		die;
    	$profile=file($db_path."par/profiles/".$Perfil);
    	unset($_SESSION["profile"]);
    	unset($_SESSION["permiso"]);
    	unset($_SESSION["login"]);
    	$_SESSION["profile"]=$Perfil;
    	$_SESSION["login"]=$arrHttp["login"];
    	foreach ($profile as $value){
    		if ($value!=""){
    			$_SESSION["permiso"][$key[0]]=$key[1];
    	}
        if (isset($valortag[70])){
        	$library=$valortag[70];
        	$_SESSION["library"]=$library;
        }else{
 	}else{
 		if ($arrHttp["login"]==$adm_login and $arrHttp["password"]==$adm_password){
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
 			if (isset($_SESSION["HOME"]))
 				echo "self.location.href=\"".$_SESSION["HOME"]."?login=N\"\n";
 			else
 				echo "self.location.href=\"../../index.php?login=N\";\n";

 			echo "</script>\n";
  			die;
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
}else{
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
		if (isset($_SESSION["HOME"]))
			$retorno=$_SESSION["HOME"];
		else
			$retorno="../../index.php";
		header("Location: $retorno?login=P");
		include("homepage.php");
	}
