<?php
set_time_limit(0);
session_start();
?>
<html>
    <title>Env�o de Correos</title>
	<style>
		body{			font-family:arial;
			font-size:12px;		}
	</style>
    <head>

    </head>
<body>
<?php

//error_reporting(0);

include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];
include("../lang/reports.php");
include("../lang/prestamo.php");
echo "<h3>".$msgstr["mail_sent_date"]."  ".date("d-m-y h:m")."</h3><p>";
include("class.phpmailer.php");
$mail= new PHPMailer();
//foreach ($_REQUEST as $key=>$value)  echo "$key=$value<br>";die;


function LlamarWxis($query,$IsisScript){global $xWxis,$Wxis,$db_path,$wxisUrl;
	include("../common/wxis_llamar.php");
	return($contenido);}

function SendPhpMail($to,$name,$invitacion,$ini,$cuenta,$desde){global $enviados,$msgstr;
	$subject = $ini["SUBJECT"];
// NOT SUGGESTED TO CHANGE THESE VALUES
	$headers = 'From: ' . $ini[ "FROM" ] . PHP_EOL ;
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$invitacion=$name."<p>".$invitacion;
	$resultado=mail ( $to, $subject,$invitacion, $headers ) ;
	echo "<p><font face=arial size=1>($cuenta) ".$msgstr["from"].": ".$ini["FROM"].". ".$ini["FROMNAME"];
	echo "<br>".$msgstr["to"].": ".$to. " ".$name;
	if ($resultado)
		$enviados=$enviados+1;}

function SendMail($mail,$to,$name,$invitacion,$ini,$cuenta,$desde){
global $enviados,$msgstr;
	//CUERPO DEL MENSAJE
	$invitacion=$name."<p>".$invitacion;
    $mail= new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;                  	// enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 	// sets the prefix to the servier
	$mail->Host       = $ini["HOST"];      		// sets the SMTP server
	$mail->Port       = $ini["PORT"];           // set the SMTP port

	if (isset($ini["USERNAME"])) $mail->Username   = $ini["USERNAME"];  // GMAIL username
	if (isset($ini["PASSWORD"]))  $mail->Password   = $ini["PASSWORD"];       // GMAIL password

	$mail->From       = $ini["FROM"];
	$mail->FromName   = $ini["FROMNAME"];
	$mail->Subject    = $ini["SUBJECT"];
	$mail->ContentType= "text/html";
	$mail->AltBody    = "Debe habilitar el modo HTML para visualizar el resultado de la consulta"; //Text Body
	$mail->WordWrap   = 200; // set word wrap
	$mail->MsgHTML($invitacion);


	$mail->AddAddress($to,$name);
    $mail->SMTPDebug  = 1;
	$mail->IsHTML(true); // send as HTML
    //echo "<META HTTP-EQUIV=Content-Type; CONTENT=text/html; charset=ISO-8859-1>";
  	echo "<p><font face=arial size=1>".$msgstr["from"].": ".$ini["FROM"].". ".$ini["FROMNAME"];
	echo "<br>".$msgstr["to"].": ".$to. " ".$name;
	$secuencia=$cuenta+$desde;
	if(!$mail->Send()) {
  		echo "<BR>($secuencia) ".$msgstr["mail_error"].": " . $mail->ErrorInfo;
	} else {

		echo "<br><STRONG>($secuencia) ".$msgstr["mail_sent"]."</strong>";
		$enviados=$enviados+1;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////

$fp=file($db_path."correo.ini");
foreach ($fp as $key=>$value){	$value=trim($value);
	if ($value!=""){		$x=explode('=',$value);
		$ini[$x[0]]=$x[1];	}}
$ex=explode('|',$_REQUEST["contactos"]);
$IsisScript=$xWxis."leer_mfnrange.xis";
$Expresion="";
$icuenta=0;
$desde=0;
$enviados=0;
if (!isset($_REQUEST["desde"]))  $_REQUEST["desde"]=1;
if (!isset($_REQUEST["count"]))  $_REQUEST["count"]=count($ex)-1;
if (trim($_REQUEST["desde"])=="") $_REQUEST["desde"]=1;
if (trim($_REQUEST["count"])=="")  $_REQUEST["count"]=count($ex)-1;
foreach ($ex as $Mfn){

	$Mfn=trim($Mfn);
	if ($Mfn!=""){		if (isset($_REQUEST["desde"])){			$desde=$desde+1;			if ($desde<$_REQUEST["desde"]) continue;
		}		$icuenta=$icuenta+1;
		if ($icuenta>$_REQUEST["count"]) break;
        $query = "&base=trans&cipar=$db_path"."par/trans.par&from=$Mfn$&to=$Mfn&Formato=$db_path"."trans/pfts/".$_SESSION["lang"]."/mail_dev";		$correo=LlamarWxis($query,$IsisScript);
		$correo=implode("\n",$correo);
		if (trim($correo)!=""){			$datos=explode('$$$',$correo);
			$c=explode('|',$datos[0]);

			if (isset($c[0])){				if (isset($ini["TEST"])){
					$to=$ini["TEST"];
				}else{					$to=$c[0];				}

				$body=$datos[1];
				$name=$c[1];
				//echo "<hr>";
				//echo "$to<br>$name<br>$body";
				if (isset($ini["PHPMAILER"]) and $ini["PHPMAILER"]=="phpmailer"){
					SendMail($mail,$to,$name,$body,$ini,$icuenta,$_REQUEST["desde"]-1);
				}else{					SendPhpMail($to,$name,$body,$ini,$icuenta,$_REQUEST["desde"]-1);				}
				flush();
    			ob_flush();
			}
		}
	}}
echo "<P>";
$aenviar=count($ex)-1;
$correos_enviados=$enviados;
echo $msgstr["mail_to_send"].": ".$aenviar."<br>";
echo $msgstr["mail_sent"].": ".$enviados."<p>";
$enviados=$_REQUEST["desde"]+$enviados;

/*
if ($enviados<$aenviar){	echo "<form name=continuar method=post action=correos.php>\n";
	echo "Continuar desde: <input type=text name=desde value=$enviados size=4>\n";
	echo " y enviar <input type=text name=count value=".$_REQUEST["count"]." size=5> correos m�s\n";
	echo "<input type=hidden name=contactos value=".$_REQUEST["contactos"].">\n";
	echo "&nbsp;<input type=submit value='continuar'>";
	echo "</form>";
}
*/
?>

</body>
</html>
<script>
alert("<?php echo $correos_enviados." ".$msgstr["mail_sent"]?>")
</script>
