<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");


include("../lang/admin.php");
include("../lang/soporte.php");

$lang=$_SESSION["lang"];
require_once ('leerregistroisispft.php');

//foreach ($arrHttp as $var => $value) echo "$var=$value<br>";

$prefijo="";
if (isset($arrHttp["prefijo"])) $prefijo=$arrHttp["prefijo"];
		$arrHttp["Opcion"]="STATUS";
		$arrHttp["IsisScript"]="control.xis";
		$llave=LeerRegistro();
		$stat=explode('|',$llave);
		$llave=substr($stat[2],7);
?>
		<HTML>
				<Title>Capturar</title>
				<head>
				<script languaje=javascript>
				var NombreBaseCopiara='<?php echo $arrHttp["base"]?>'
				var base=''
				var cipar=''
				var basecap='<?php echo $arrHttp["base"]?>'
				var ciparcap='<?php echo $arrHttp["base"]?>.par'
				var Formato='<?php echo $xFormato[$arrHttp["base"]]?>'
				var marc=''
				var tl=''
				var nr=''
				var Mfn=0
				var xeliminar=0
				var xeditar=''
				var ModuloActivo="Capturar"
				var cnvtabsel=''
				ValorCapturado=''
				buscar=''
				ep=''
				ConFormato=true
				function ActivarFormato(Ctrl){
					if (xeditar=='S'){
						alert('Debe actualizar o cancelar la edici�n del registro')
						Ctrl.checked=!Ctrl.checked
						return
					}else{
						if (Ctrl.checked){
							ConFormato=true
						}else{
							ConFormato=false
						}
						if (mfn>0){
							mfn=mfn-1
							Menu('proximo')
						}
					}
				}
				function AbrirVentanaAyuda(){
					insWindow = window.open('../html/ayuda.html', 'Ayuda', 'location=no,width=700,height=550,scrollbars=yes,top=10,left=100,resizable');
					insWindow.focus()
				}
				function CapturarRegistro(){
				cnv=""
				if (cnvtabsel!="") cnv="&cnvtabsel="+cnvtabsel
	loc="fmt.php?Opcion=presentar_captura&Mfn="+Mfn+"&ver=N&base="+NombreBaseCopiara+"&cipar="+NombreBaseCopiara+".par&basecap="+top.basecap+"&ciparcap="+top.basecap+".par"+cnv
	window.opener.top.xeditar="S"
	window.opener.top.main.location=loc
	window.opener.focus()
	self.close()

}

</script>
</head>
<frameset cols=410,* border=yes>
	<frame name=indice src=alfa.php?<?php echo "capturar=S&base=".$arrHttp["base"]."&cipar=".$arrHttp["cipar"]."&prefijo=".urlencode($arrHttp["prefijo"])."&formato_e=".urlencode(stripslashes($arrHttp["formato_e"]))."&fc=".$arrHttp["fc"]."&html=ayuda_captura.html"?> scrolling=no frameborder=no  marginheight=0   MARGINWIDTH=0 >
	<frame name=main src="" scrolling=yes frameborder=yes marginheight=2   MARGINWIDTH=0 >

</frameset>
</HTML>
<script>


