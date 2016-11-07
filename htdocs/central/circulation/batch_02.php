<?php
// se determina si el préstamo está vencido
function compareDate ($FechaP){
global $locales,$config_date_format;
//Se convierte la fecha a formato ISO (yyyymmaa) utilizando el formato de fecha local
	$f_date=explode('/',$config_date_format);
	switch ($f_date[0]){
		case "DD":
			$dia=substr($FechaP,0,2);
			break;
		case "MM":
			$mes=substr($FechaP,0,2);
			break;
	}
	switch ($f_date[1]){
		case "DD":
			$dia=substr($FechaP,3,2);
			break;
		case "MM":
			$mes=substr($FechaP,3,2);
			break;
	}
	$year=substr($FechaP,6,4);
	$exp_date=$year."-".$mes."-".$dia;

	$ixTime=strpos($FechaP," ");
	if ($ixTime>0) {
		$exp_date.=substr($FechaP,$ixTime);
		$todays_date = date("Y-m-d h:i A");
	}else{
		$todays_date = date("Y-m-d");
	}
//	echo $exp_date,$todays_date."\n";
	$today = strtotime($todays_date);
	$expiration_date = strtotime($exp_date);
	$diff=$expiration_date-$today;
	return $diff;

}//end Compare Date

//======================================================================

include("../config.php");
//session_start();
$_SESSION["lang"]="es";
// se leen las politicas de préstamo
include("loanobjects_read.php");
// se presenta la  información del usuario
$formato_us=$db_path."reserve/pfts/".$_SESSION["lang"]."/record";
if (!file_exists($formato_us.".pft")){	$formato_us=$db_path."trans/pfts/".$lang_db."/record";}
$query = "&from=1&base=reserve&cipar=$db_path/par/reserve.par&Formato=".$formato_us;
$contenido="";
$IsisScript=$xWxis."leer_mfnrange.xis";
include("../common/wxis_llamar.php");
echo "Usuario  \t N.Control \t Fecha  \t Hasta \t Situación\n";
foreach ($contenido as $linea){	$p=explode('|',$linea);
	if ($p[4]!=1){
		$dif= compareDate ($p[3]);
		$mora="0";
		if ($dif<0) {
			$nv=$nv+1;
			$mora=floor(abs($dif)/(60*60*24));    //cuenta de préstamos vencidos
		}
		echo $p[0]." \t ".$p[1] ." \t\t ".$p[2]."\t".$p[3]." \t ".$mora."\n";
	}
}


?>
