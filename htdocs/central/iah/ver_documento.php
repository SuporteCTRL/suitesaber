<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <title>Solicitud de documento</title>
</head>

<?php include ("header.php")?>
<form name=solicitud action=ver_documento_ex.php method=post>
<?php
include ("configure.php");
$db_path=$config["DB_PATH"];
$dbn=$arrHttp["base"];
$IsisScript=$xWxis."leer_mfnrange.xis";
$Pft=urlencode($config["DOCUMENT_DISPLAY"]);
$query="&base=$dbn&cipar=".$db_path."par/$dbn.par&from=".$arrHttp["mfn"]."&to=".$arrHttp["mfn"]."&Pft=$Pft";
include("../common/wxis_llamar.php");
?>
<font face="arial" size="2">
<font color="maroon">
<?php
foreach ($contenido as $value) echo "$value<br>";
?>
<p>
<font face="arial" size="1">
<b>	Para consultar el texto completo del documento	ingrese su c�dula de identidad <BR>
             incluya el d�gito de control, no use puntos ni guiones. <br>
	ejemplo: 1.123.456-7   debe ingresarse   11234567
</b>
	<br><br>

<?php
echo "<br><br>";
echo "C�digo: ";
echo "<input type=text name=usuario size=10>\n";
echo "<input type=submit value=enviar target=_blank>\n";
foreach ($arrHttp as $var=>$value){	echo "<input type=hidden name=$var value=\"$value\">\n";}




?>
</form>
<br><br>
<a href="javascript:onClick=self.close()"><img src="/iah/es/image/salir.gif" border="0"></a>
</body>

</html>