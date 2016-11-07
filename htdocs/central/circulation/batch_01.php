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
	echo $exp_date." ".$todays_date."\n";
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
$formato_us=$db_path."trans/pfts/".$_SESSION["lang"]."/loans_display";
if (!file_exists($formato_us.".pft")) $formato_us=$db_path."trans/pfts/".$lang_db."/loans_display";
$query = "&from=1&base=trans&cipar=$db_path/par/trans.par&Formato=".$formato_us;
$contenido="";
$IsisScript=$xWxis."leer_mfnrange.xis";
include("../common/wxis_llamar.php");
echo "Usuario  \t Fecha Devolucion          \t mora \n \t lapso";
foreach ($contenido as $linea){	$p=explode('^',$linea);
	if (isset($p[17]) and trim($p[17])!=""){
		$politica_este=explode('|',$p[17]);
	}else{
		$politica_este=explode('|',$politica[$p[3]][$p[6]]);
	}
 	$lapso_p=$politica_este[5];
	if ($p[16]=="P"){
		echo $p[10]." ";
		$dif= compareDate ($p[5]);
		$mora="0";
		if ($dif<0) {
			$nv=$nv+1;
			if ($lapso_p=="D"){
				$mora=floor(abs($dif)/(60*60*24));    //cuenta de préstamos vencidos
			}else{
				$fulldays=floor(abs($dif)/(60*60*24));
				$fullhours=floor((abs($dif)-($fulldays*60*60*24))/(60*60));
				$fullminutes=floor((abs($dif)-($fulldays*60*60*24)-($fullhours*60*60))/60);
				$mora=$fulldays*24+$fullhours;
				//echo "<br>** $fulldays, $fullhours , $fullminutes";
			}
		    if ($mora>0){
		    	$fecha_d=$p[5];
		    	while (strlen($fecha_d)<25 ) $fecha_d.=" ";		    	echo " \t",$fecha_d,"\t $mora $lapso_p\n";		    }
		}
		echo "\n";
	}
}


?>
