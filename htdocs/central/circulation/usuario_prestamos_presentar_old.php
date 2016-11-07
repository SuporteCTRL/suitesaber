<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      usuario_prestamos_presentar.php
 * @desc:      Analyzes the user and item for establishing the loan policy
 * @author:    Guilda Ascencio
 * @since:     20091203
 * @version:   1.0
 *
 * == BEGIN LICENSE ==
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as
 *    published by the Free Software Foundation, either version 3 of the
 *    License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * == END LICENSE ==
*/
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}

//date_default_timezone_set('UTC');
$debug="";
if (!isset($_SESSION["login"])) die;
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=>$value<br>";  //die;
include("../config.php");
//include("../config_loans.php");              // BORRADO EL 07/03/2013
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/prestamo.php");
include("fecha_de_devolucion.php");
include ('../dataentry/leerregistroisispft.php');
include("leer_pft.php");
//Calendario de días feriados
include("calendario_read.php");
//Horario de la biblioteca, unidades de multa, moneda
include("locales_read.php");
// se leen las politicas de préstamo y la tabla de tipos de usuario
include("loanobjects_read.php");
// se lee la configuración de la base de datos de usuarios
include("borrowers_configure_read.php");
# Se lee el prefijo y el formato para extraer el código de usuario
$us_tab=LeerPft("loans_uskey.tab","users");
$t=explode("\n",$us_tab);
$uskey=$t[0];

include("../reserve/reserves_read.php");

$valortag = Array();

$ec_output="" ;
$recibo_arr=array();

function LocalizarReservas($control_number,$catalog_db,$usuario,$items_prestados,$prefix_cn,$copies,$pft_ni) {global $xWxis,$Wxis,$db_path,$msgstr;
	echo "$control_number,$catalog_db,$usuario,$items_prestados,$prefix_cn,$copies,$pft_ni";
	$IsisScript=$xWxis."cipres_usuario.xis";
	$Pft=$db_path."reserve/pfts/".$_SESSION["lang"]."/tbreserve.pft";
	// 10:codigo de usuario
	// 30:Fecha reserva
	// 40:Fecha límite de retiro
	// 60:Fecha en que fue devuelto el préstamo
	// 130:Fecha de cancelación de la reserva
	// 200:Fecha en que se ejecutó la reserva y se prestó el item al usuario
	$Pft="f(mfn,1,0)'|'v10'|'v30'|'v40'|'v60'|'v130'|'v200/";
	$query="&base=reserve&cipar=$db_path"."par/reserve.par&Expresion=CN_".$catalog_db."_".$control_number."&Pft=$Pft";
	include("../common/wxis_llamar.php");
	$reservas=array();
	foreach ($contenido as $value){		if (trim($value)!=""){			$r=explode('|',$value);
			//SE ELIMINAN LAS RESERVAS CANCELADAS
			if (isset($r[5]) and trim($r[5])!="" or isset($r[6]) and trim($r[6])!="") continue;
			$reservas[]=$value;
		}
	}
	//SI HAY RESERVAS PENDIENTES SE ANALIZA SI QUEDAN EJEMPLARES DISPONIBLES LUEGO DE SACAR LOS DE RESERVA
	// A. LEER EL TOTAL DE ITEMS DEL TITULO
	$IsisScript=$xWxis."loans/prestamo_disponibilidad.xis";
	if ($copies=="Y"){		$catalog_db="loanobjects";
		$Expresion="CN_".$catalog_db."-".$control_number;
		$pft_ni="(v959/)";
	}else{
		//SE LEE EL PREFIJO A UTILIZAR PARA LOCALIZAR EL OBJETO A TRAVÉS DE SU NÚMERO DE INVENTARIO
		$Expresion=$prefix_cn.$control_number;
		$catalog_db=strtolower($catalog_db);
		$pft_ni="(".$pft_ni."/)";
	}

	$query = "&Opcion=disponibilidad&base=$catalog_db&cipar=$db_path"."par/$catalog_db.par&Expresion=".$Expresion."&Pft=".urlencode($pft_ni);
	include("../common/wxis_llamar.php");
	$obj=array();
	foreach ($contenido as $value){
		$value=trim($value);
		echo $value."<br>";
		if (trim($value)!="" and substr($value,0,8)!='$$TOTAL:')
		    $obj[]=$value;
	}
	$disponibilidad=count($obj)-$items_prestados-count($reservas);
	//SI LA DISPONIBILIDAD ES MAYOR QUE EL NUMERO DE RESERVAS PENDIENTES SE OTORGA EL PRESTAMO
	//Y SI EL USUARIO ESTÁ EN LA COLA DE PENDIENTES SE LE GRABAN LOS DATOS PARA INDICAR QUE YA SE
	//EJECUTO LA RESERVA
	//LA FUNCION DEVUELVE EL MFN DEL REGISTRO DE RESERVAS PARA ACTUALIZAR LOS DATOS DEL PRÉSTAMO CONCEDIDO
	//O CERO SI EL PRESTAMO NO SATISFACE NINGUNA RESERVA
	if ($disponibilidad>count($reservas)){		foreach ($reservas as $value){			$r=explode('|',$value);
			if ($r[1]==$usuario){				return array("continuar",$r[0]);			}		}
		return array("continuar",0);	}
	//SI LA DISPONIBILIDAD ES MENOR O IGUAL AL NUMERO DE RESERVAS, SE DA EL PRESTAMO SI EL USUARIO LO TIENE RESERVADO
	if ($disponibilidad<=count($reservas)){
		foreach ($reservas as $value){
			$r=explode('|',$value);
			if ($r[1]==$usuario){
				return array("continuar",$r[0]);
			}
		}
		return array("no_continuar",0);
	}
    return array("no_continuar",0);
}

function ProcesarPrestamo($usuario,$inventario,$signatura,$item,$usrtype,$copies,$ppres,$prefix_in,$prefix_cn){
global $db_path,$Wxis,$wxisUrl,$xWxis,$pr_loan,$pft_storobj,$recibo_arr;
	$item_data=explode('||',$item);
	$nc=$item_data[0];                  // Control number of the object
	$bib_db=$item_data[1];
	$arrHttp["db"]=$bib_db;
	$item="$pft_storobj";
	// Read the bibliographic database that contains the object using the control mumber extracted from the copy
	$IsisScript=$xWxis."loans/prestamo_disponibilidad.xis";
	if ($copies=="Y"){
		$Expresion="CN_".$nc;
	}else{
		//SE LEE EL PREFIJO A UTILIZAR PARA LOCALIZAR EL OBJETO A TRAVÉS DE SU NÚMERO DE INVENTARIO
		$Expresion=$prefix_cn.$nc;
	}
    $bib_db=strtolower($bib_db);
	$query = "&Opcion=disponibilidad&base=$bib_db&cipar=$db_path"."par/$bib_db.par&Expresion=".$Expresion."&Pft=".urlencode($item);
	include("../common/wxis_llamar.php");
	$obj="";
	foreach ($contenido as $value){
		$value=trim($value);
		if (trim($value)!="")
			$obj.=$value;
	}
	$objeto=explode('$$',$obj);
	$obj=explode('|',$ppres);
	$fp=date("Ymd h:i A");	// DEVOLUTION DATE
	$fd=FechaDevolucion($obj[3],$obj[5],"");
//	echo "<br>Fecha de devolución: ".$fd;
//	die;

	$ix=strpos($fp," ");
	$diap=trim(substr($fp,0,$ix));
	$horap=trim(substr($fp,$ix));
	$ix=strpos($fd," ");
	$diad=trim(substr($fd,0,$ix));
	$horad=trim(substr($fd,$ix));

	$ValorCapturado="0001P\n";
	$ValorCapturado.="0010".trim($inventario)."\n";	// INVENTORY NUMBER
	if (isset($item_data[6])) $ValorCapturado.="0012".$item_data[6]."\n";         	// VOLUME
	if (isset($item_data[7])) $ValorCapturado.="0015".$item_data[7]."\n";             // TOME
	$ValorCapturado.="0020".$usuario."\n";
	$ValorCapturado.="0030".$diap."\n";
	#if ($obj[5]=="H")
	$ValorCapturado.="0035".$horap."\n";
	$ValorCapturado.="0040".$diad."\n";
	#if ($obj[5]=="H")
	$ValorCapturado.="0045".$horad."\n";
	$ValorCapturado.="0070".$usrtype."\n";
	$ValorCapturado.="0080".$item_data[5]."\n";
	$ValorCapturado.="0095".$item_data[0]."\n";                   // Control number of the object
	$ValorCapturado.="0098".$item_data[1]."\n";             			// Database name
	if ( $signatura!="") $ValorCapturado.="0090".$signatura."\n";
	$ValorCapturado.="0100".$objeto[0]."\n";
	$ValorCapturado.="0400".$ppres."\n";
	$ValorCapturado.="0120^a".$_SESSION["login"]."^b".date("Ymd H:i:s");
	$ValorCapturado=urlencode($ValorCapturado);
	$IsisScript=$xWxis."crear_registro.xis";
	$Formato="";
	$recibo="";
	if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/r_loan.pft")){
		$Formato=$db_path."trans/pfts/".$_SESSION["lang"]."/r_loan";
	}else{
		if (file_exists($db_path."trans/pfts/".$lang_db."/r_loan.pft")){
			$Formato=$db_path."trans/pfts/".$lang_db."/r_loan";
		}
	}
	if ($Formato!="") {		$Formato="&Formato=$Formato";
		$Pft="mfn/";	}

	$query = "&base=trans&cipar=$db_path"."par/trans.par&login=".$_SESSION["login"]."$Formato&ValorCapturado=".$ValorCapturado;
	include("../common/wxis_llamar.php");
    $recibo="";
	if ($Formato!="") {		foreach ($contenido as $r){			$recibo.=trim($r);		}		$recibo_arr[]=$recibo;
		//ImprimirRecibo($recibo);	}
	$fechas=array($diad,$horad);
	return $fechas;}

// Se localiza el número de control en la base de datos bibliográfica
function ReadCatalographicRecord($control_number,$db,$inventory){
global $db_path,$Wxis,$xWxis,$arrHttp,$pft_totalitems,$pft_ni,$pft_nc,$pft_typeofr,$titulo,$prefix_in,$prefix_cn,$multa,$pft_storobj,$lang_db;
	//Read the FDT of the database for extracting the prefix used for indexing the control number
//	echo $control_number;
	if (isset($arrHttp["db_inven"])){
		$Expresion=trim($prefix_cn).trim($control_number);
	}else{
	    $Expresion="CN_".$control_number;
	}
	if ($control_number=="")
		$Expresion=$prefix_in.$inventory;
//    echo $Expresion;
	// Se extraen las variables necesarias para extraer la información del título al cual pertenece el ejemplar
	// se toman de databases_configure_read.php
	// pft_totalitems= pft para extraer el número total de ejemplares del título
	// pft_in= pft para extraer el número de inventario
	// pft_nc= pft para extraer el número de clasificación
	// pft_typeofr= pft para extraer el tipo de registro

	$formato_ex="'||'".$pft_nc."'||'".$pft_typeofr."'###'";
	//se ubica el título en la base de datos de objetos de préstamo
	$IsisScript=$xWxis."loans/prestamo_disponibilidad.xis";
	$Expresion=urlencode($Expresion);
	$formato_obj=$db_path."$db/loans/".$_SESSION["lang"]."/loans_display.pft";
	if (!file_exists($formato_obj)) $formato_obj=$db_path."$db/loans/".$lang_db."/loans_display.pft";
	$formato_obj.="\n /".urlencode($formato_ex).urlencode($pft_storobj);
	$query = "&Opcion=disponibilidad&base=". strtolower($db)."&cipar=$db_path"."par/$db.par&Expresion=".$Expresion."&Pft=@$formato_obj";
	//echo $query;
	include("../common/wxis_llamar.php");
	$total=0;
	$titulo="";
	foreach ($contenido as $linea){		$linea=trim($linea);
		if (trim($linea)!=""){
			if (substr($linea,0,8)=='$$TOTAL:')
				$total=trim(substr($linea,8));
			else
				$titulo.=$linea."\n";
		}
	}
	return $total;
}

// Se localiza el número de inventario en la base de datos de objetos  de préstamo
function LocalizarInventario($inventory){
global $db_path,$Wxis,$xWxis,$arrHttp,$pft_totalitems,$pft_ni,$pft_nc,$pft_typeofr,$copies_title,$prefix_in,$multa;
    $Expresion=$prefix_in.$inventory;
	// Se extraen las variables necesarias para extraer la información del título al cual pertenece el ejemplar
	// se toman de databases_configure_read.php
	// pft_totalitems= pft para extraer el número total de ejemplares del título
	// pft_in= pft para extraer el número de inventario
	// pft_nc= pft para extraer el número de clasificación
	// pft_typeofr= pft para extraer el tipo de registro

	//READ LOANOBJECT DATABASE TO GET THE RECORD WITH THE ITEMS OF THE TITLE
	$IsisScript=$xWxis."loans/prestamo_disponibilidad.xis";
	$Expresion=urlencode($Expresion);
	if (isset($arrHttp["db_inven"])){
	//IF NO LOANOBJECTS READ THE PFT FOR EXTRACTING THEN INVENTORY NUMBER AND THE TYPE OF RECORD		$d=explode('|',$arrHttp["db_inven"]);
		$arrHttp["base"]=$d[0];
		$arrHttp["db_inven"]=$d[0];
		$pft_typeofrec=LeerPft("loans_typeofobject.pft",$d[0]);
		$pft_ni=LeerPft("loans_inventorynumber.pft",$d[0]);
		$pft_nc=LeerPft("loans_cn.pft",$d[0]);
		$formato_ex="($pft_nc'||".$d[0]."||',$pft_ni,'||||||',".$pft_typeofrec."'||||||'/)";
	}else{		$arrHttp["base"]="loanobjects";
		$formato_ex="(v1[1]'||'v10[1],'||',v959^i,'||',v959^l,'||',v959^b,'||',v959^o,'||',v959^v,'||',v959^t,'||'/)";
    // control number||database||inventory||main||branch||type||volume||tome	}
	$formato_obj=urlencode($formato_ex);
	$query = "&Opcion=disponibilidad&base=".$arrHttp["base"]."&cipar=$db_path"."par/".$arrHttp["base"].".par&Expresion=".$Expresion."&Pft=$formato_obj";
	include("../common/wxis_llamar.php");
	$total=0;
	$copies_title=array();
	$item="";
	foreach ($contenido as $linea){
		$linea=trim($linea);
		if ($linea!=""){
			if (substr($linea,0,8)=='$$TOTAL:'){
				$total=trim(substr($linea,8));
			}else{
				$t=explode('||',$linea);
				if ($inventory==trim($t[2])) $item=$linea;
				$copies_title[]=$linea;
			}
		}
	}
	return array($total,$item);
}

//se busca el numero de control en el archivo de transacciones para ver si el usuario tiene otro ejemplar prestado
function LocalizarTransacciones($control_number,$prefijo){
global $db_path,$Wxis,$xWxis,$arrHttp,$msgstr,$tr_prestamos;
	$formato_obj=$db_path."trans/pfts/".$_SESSION["lang"]."/loans_display.pft";
	if (!file_exists($formato_obj)) $formato_obj=$db_path."trans/pfts/".$lang_db."/loans_display.pft";
	$query = "&Expresion=".$prefijo."_P_".$control_number."&base=trans&cipar=$db_path"."par/trans.par&Formato=".$formato_obj;
	$IsisScript=$xWxis."cipres_usuario.xis";
	include("../common/wxis_llamar.php");
	$prestamos=array();
	foreach ($contenido as $linea){		if (trim($linea)!="")			$tr_prestamos[]=$linea;
	}
}



///////////////////////////////////////////////////////////////////////////////////////////


//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";

// ARE THE COPIES IN THE COPIES DATABASE OR IN THE BIBLIOGRAPHIC DATABASE?

if (isset($arrHttp["db_inven"])){
	$from_copies="N";
	$x=explode('|',$arrHttp["db_inven"]);
    $var=LeerPft("loans_conf.tab",$x[0]);
   // echo $arrHttp["db_inven"]."<br>".$var;
   // die;

	$prefix_in=trim($x[2]);      //OJO ESTO SE DEBE LEER DE UN ARCHIVO DE CONFIGURACION
}else{
	$prefix_in="IN_";
	$from_copies="Y";
}
if (isset($arrHttp["Opcion"])){
	if ( $arrHttp["Opcion"]=="reservar")
		$msg_1=$msgstr["reserve"];
	else
		if ($arrHttp["Opcion"]=="prestar") $msg_1=$msgstr["loan"];
}

// ------------------------------------------------------
//--------------------------------------------------------------

$link_u="";
if (isset($arrHttp["usuario"])) $link_u="&usuario=".$arrHttp["usuario"];
if (isset($arrHttp["inventory"])) $presentar_reservas="N";
$nmulta=0;
$cont="";
$np=0;
include("ec_include.php");  //se incluye el procedimiento para leer el usuario y los préstamos pendientes
if ($sanctions_output!="") {	$cont="N";
	unset($arrHttp["inventory"]);}
if (count($prestamos)>0) $ec_output.= "<strong><a href=javascript:DevolverRenovar('D')>".$msgstr["return"]."</a> | <a href=javascript:DevolverRenovar('R')>".$msgstr["renew"]."</a></strong><p>";

//Se obtiene el código, tipo y vigencia del usuario
$formato=$pft_uskey.'\'$$\''.$pft_ustype.'\'$$\''.$pft_usvig;
$formato=urlencode($formato);
$query = "&Expresion=".trim($uskey).$arrHttp["usuario"]."&base=users&cipar=$db_path"."par/users.par&Pft=$formato";
$contenido="";
$IsisScript=$xWxis."cipres_usuario.xis";
include("../common/wxis_llamar.php");
$user="";
$nmulta=0;
$msgsusp="";
$vig="";

foreach ($contenido as $linea){
	$linea=trim($linea);
	if ($linea!="")  $user.=$linea;
}

if (trim($user)==""){	ProduceOutput("<h4>".$msgstr["userne"]."</h4>","");
	die;
}else{
	$reserves_user=ReservesRead("CU_".$arrHttp["usuario"]);
	if ($nsusp>0) {		 $msgsusp= "pending_sanctions";
		 $vig="N";	}else{	//Se analiza la vigencia del usuario
		$userdata=explode('$$',$user);
	    if (trim($userdata[2])!=""){	    	if ($userdata[2]<date("Ymd")){	    		$msgsusp= "limituserdata";
				$vig="N";	    	}    	}
    }}
$ec_output.= "\n
<script>
  Vigencia='$vig'
  np=$np
</script>\n";
if ($msgsusp!=""){
	if ($reserves_user!="")
		$ec_output.="<p><strong>".$msgstr["reserves"]."</strong><br>".$reserves_user."<p>";	$ec_output.="<font color=red><h3>".$msgstr[$msgsusp]."</h3></font>";
	ProduceOutput($ec_output,"");
	die;}
//OJO AGREGARLE AL TIPO DE USUARIO SI SE LE PUEDEN PRESTAR CUANDO ESTÁ VENCIDO
if ($nv>0 and isset($arrHttp["inventory"])){
	$ec_output.= "<font color=red><h3>".$msgstr["useroverdued"]."</h3></font>";
	ProduceOutput($ec_output,"");
	die;
}
//////////////////////////////////////////////////////////////////
// Si viene desde la opción de prestar, se localiza el número de inventario solicitado

$xnum_p=$np;
$prestamos_este=0;
if (isset($arrHttp["inventory"]) and $vig=="" and !isset($arrHttp["prestado"]) and !isset($arrHttp["renovado"]) and !isset($arrHttp["devuelto"])){

	$ec_output.="<table width=100% bgcolor=#cccccc><td></td>
		<td width=50 align=center><strong>".$msgstr["inventory"]."</strong></td><td width=50 align=center><strong>".$msgstr["control_n"]."<strong></td><td align=center><strong>".$msgstr["reference"]."<strong></td><td align=center><strong>".$msgstr["typeofitems"]."</strong></td><td align=center><strong>".$msgstr["devdate"]."</td>\n";

    $invent=explode("\n",trim(urldecode($arrHttp["inventory"])));

    foreach ($invent as $arrHttp["inventory"]){
    	$cont="Y";
    	if (isset($inventory_numeric) and $inventory_numeric =="Y"){
    		$i=0;
    		while (substr($arrHttp["inventory"],$i,1)=="0"){
    			$i++;
    			$arrHttp["inventory"]=substr($arrHttp["inventory"],$i,1);
    		}
    	}
    	$arrHttp["inventory"]=trim($arrHttp["inventory"]);
    	$ec_output.="<tr>";
    	$este_prestamo="";

    	$este_prestamo.= "<td bgcolor=white valign=top align=center><font color=red>".$arrHttp["inventory"]."</font></td>";
	//Se ubica el ejemplar en la base de datos de objeto
		$inv_item=LocalizarInventario($arrHttp["inventory"]);

		$total=$inv_item[0];
		$item=$inv_item[1];
		if ($total==0){
			$este_prestamo.= "<td bgcolor=white valign=top></td><td bgcolor=white></td><td  bgcolor=white valign=top></td><td bgcolor=white valign=top></td><td  bgcolor=white valign=top><font color=red>".$msgstr["copynoexists"]."</font></td>";
			$cont="N";
			$ec_output.="<td bgcolor=white></td>".$este_prestamo;
 			//ProduceOutput($ec_output,"");
		}else{
		//se extrae la información del número de control del título y la base de datos catalográfica a la cual pertenece
			$tt=explode('||',$item);
			$control_number=$tt[0];
			$este_prestamo.="<td bgcolor=white valign=top align=center>$control_number</td>";
			$catalog_db=$tt[1];
    		$tipo_obj=trim($tt[5]);      //Tipo de objeto

// se lee la configuración de la base de datos de objetos de préstamos
			$arrHttp["db"]="$catalog_db";
            require_once("databases_configure_read.php");
			$ppres="";
    		$tipo_obj=trim(strtoupper($tipo_obj));
    		$userdata[1]=trim(strtoupper($userdata[1]));

			if (isset($politica[$tipo_obj][$userdata[1]])){	    		$ppres=$politica[$tipo_obj][$userdata[1]];
	    		$using_pol=$tipo_obj." - " .$userdata[1];
			}
			if (trim($ppres)==""){				if (isset($politica[0][$userdata[1]])) {					$ppres=$politica[0][$userdata[1]];
					$using_pol="0 - " .$userdata[1];				}
			}
			if (trim($ppres)==""){
				if (isset($politica[$tipo_obj][0])){
	    			$ppres=$politica[$tipo_obj][0];
	    			$using_pol=$tipo_obj." - 0" ;
	  			}
			}
			if (trim($ppres)==""){
				if (isset($politica["0"]["0"])){
					$ppres=$politica["0"]["0"];
					$using_pol="0 - 0";
				}
			}
			$obj=explode('|',$ppres);
			$fechal_usuario="";
			$fechal_objeto="";
			if (isset($obj[15])){
				$fechal_usuario=$obj[15];
				$fecha_d=date("Ymd");
				if (trim($fechal_usuario)!=""){
					if ($fecha_d>$fechal_usuario){						$este_prestamo.= "fecha límite del usuario ";
						$norenovar="S";
						$cont="N";
						//die;					}
				}
			}
			if (isset($obj[15])){				$fechal_objeto=$obj[16];
				if (trim($fechal_objeto)!=""){
					if ($fecha_d>$fechal_objeto){
						$este_prestamo.= "fecha límite del objeto ";
						$cont="N";
						$este_prestamo.="<hr>";
					}
				}
			}
			//SE VERIFICA SI EL USUARIO TIENE PRÉSTAMOS VENCIDOS
            if ($nv>0 and isset($arrHttp["inventory"]) and $obj[12]!="Y"){
				$este_prestamo.= "<font color=red><h3>".$msgstr["useroverdued"]."</h3></font>";
				$cont="N";
			}
			//Se verifica si el usuario puede recibir más préstamos en total
			//SE ASIGNA EL TOTAL DE PRESTAMOS QUE PUEDE RECIBIR UN USUARIO  SEGUN EL TIPO DE OBJETO  (calculado en loanobjects_read.php)
			$tprestamos_politica=$total_individual_politica[$tipo_obj];
			if (isset($tipo_u[$userdata[1]]["Tp"]))
				$tprestamos_p=$tipo_u[$userdata[1]]["Tp"];
			else
				$tprestamos_p=$total_prestamos_politica;
			if ($cont=="Y"){
		// Se localiza el registro catalográfico utilizando los datos anteriores
				$ref_cat=ReadCatalographicRecord($control_number,$catalog_db,$arrHttp["inventory"]);
	 			if ($ref_cat==0){      //The catalographic record is not found
	 				$este_prestamo.= "<td  bgcolor=white valign=top></td><td  bgcolor=white valign=top></td><td  bgcolor=white valign=top><font color=red>".$msgstr["catalognotfound"]."</font></td>";
					$cont="N";
	 				//ProduceOutput($ec_output,"");
					//die;
	 			}
	 			if ($ref_cat>1){      //More than one catalographic record
	 				$este_prestamo.= "<td  bgcolor=white valign=top></td><td  bgcolor=white valign=top></td><td  bgcolor=white valign=top><font color=red>".$msgstr["dupcopies"]."</font></td>";
					$cont="N";
	 				//ProduceOutput($ec_output,"");
					//die;
	 			}
	 			if ($cont=="Y"){
		 			$tt=explode('###',trim($titulo));
		    		$obj_store=$tt[1];
					$tt=explode('||',$tt[0]);
					$titulo=$tt[0];
					$signatura=$tt[1];     //signatura topográfica
		    		$este_prestamo.= "<td bgcolor=white valign=top>$titulo</td>";
		    		$este_prestamo.= "<td bgcolor=white valign=top>".$tipo_obj."<br>";
		    		if (trim($ppres)==""){
						//$debug="Y";
						$este_prestamo.=$msgstr["nopolicy"]." ".$tipo_obj."-".$userdata[1]."<td bgcolor=white></td>";
                        $grabar="N";
					}else{
						$este_prestamo.= $msgstr["policy"].": ". $using_pol;
						$grabar="Y";
					}
					$este_prestamo.="</td>";

	// se verifica si el ejemplar está prestado

					$tr_prestamos=array();
					LocalizarTransacciones($arrHttp["inventory"],"TR");
					$Opcion="";
					$msg="";
					$msg_1="";
					if (count($tr_prestamos)>0){   // Si ya existe una transacción de préstamo para ese número de inventario, el ejemplar está prestado
						$cont="N";
						$msg= $msgstr["itemloaned"];
						//$ec_output.= "<td bgcolor=white valign=top></td><td bgcolor=white valign=top><font color=red> $msg";
					}
				// VERIFY IF THE USER HAS ANOTHER ITEM OF THE SAME OBJECT
					$tr_prestamos=array();
					//SE VERIFICA SI EL USUARIO YA TIENE UN MISMO EJEMPLAR, VOLUMEN Y TOMO DE ESE TÍTULO Y SI SE LE PERMITE O NO
					LocalizarTransacciones($control_number,"ON");
					$items_prestados=count($tr_prestamos);
					if (count($tr_prestamos)>0){
						foreach($tr_prestamos as $value){							if (trim($value)!=""){								$nc_us=explode('^',$value);
		                		$pi=$nc_us[0];                                   //GET INVENTORY NUMBER OF THE LOANED OBJECT
		                		$pv=$nc_us[14];                                  //GET THE VOLUME OF THE LOANED OBJECT
		                		$pt=$nc_us[15];                                  //GET THE TOME OF THE LOANED OBJECT
								$comp=$pi." ".$pv." ".$pt;
								foreach ($copies_title as $cop){									$c=explode('||',$cop);
									$comp_01=$c[2];
									if (isset($c[6]))
										$comp_01.=" ".$c[6];
									if (isset($c[7]))
										$comp_01.=" ".$c[7];
									if ($nc_us[10]==$arrHttp["usuario"]){    //SE VERFICA SI LA COPIA ESTÁ EN PODER DEL USUARIO
										if ($comp_01==$comp and $obj[14]!="Y"){											if ($msg=="")												$msg= $msgstr["duploan"];
											else
												$msg.="<br>".$msgstr["duploan"];
										}
									}								}							}
	        			}
	        			if ($msg!=""){	        				$cont="N";
	        				$este_prestamo.="<td valign=top bgcolor=white><font color=red>".$msg."</font></td><td bgcolor=white></td>";	        			}
	        		}
	        		if ($cont=="Y"){
	        			$msg="";
	        			$ec_output.="<td bgcolor=white valign=top>";	        			if ($grabar=="Y"){	        				//SE LOCALIZA SI EL TITULO ESTÁ RESERVADO
	        				$reservado=LocalizarReservas($control_number,$catalog_db,$arrHttp["usuario"],$items_prestados,$prefix_cn,$from_copies,$pft_ni);
	        				if ($reservado[0]=="continue"){
	        					$prestamos_este++;
	        					if (isset($tpp[$tipo_obj]))
	        						$tpp[$tipo_obj]=$tpp[$tipo_obj]+1;
	        					else
	        						$tpp[$tipo_obj]=1;
	        					if ($np<$tprestamos_p and $tpp[$tipo_obj]<=$tprestamos_politica){	        						$np=$np+1;
	        						$xnum_p=$xnum_p+1;
									$ec_output.="$xnum_p. <input type=checkbox name=chkPr_".$xnum_p." value=0  id='".$arrHttp["inventory"]."'>";
	  								$ec_output.= "<input type=hidden name=politica value=\"".$ppres."\"> \n";
	  							}else{	  								$grabar="N";	  								$msg="<font color=red>".$msgstr["nomoreloans"]."</font>";	  							}
	  						}else{	  							$grabar="N";
	  							$msg="<font color=red>".$msgstr["reserved_other_user"]."</font>";	  						}
  						}
						$ec_output.="</td>";
						$ec_output.=$este_prestamo;
						$Opcion="prestar";
				//	$action="usuario_prestamos_prestar.php";
						$msg_1=$msgstr["loan"];
						if ($grabar=="Y"){							$devolucion=ProcesarPrestamo($arrHttp["usuario"],$arrHttp["inventory"],$signatura,$item,$userdata[1],$from_copies,$ppres,$prefix_in,$prefix_cn,$reserva[1]);
						}else{							$devolucion=array();
						}
						$ec_output.="<td bgcolor=white valign=top >$msg";
						if (count($devolucion)>0) {
							if (substr($config_date_format,0,2)=="DD"){								$ec_output.=substr($devolucion[0],6,2)."/".substr($devolucion[0],4,2)."/".substr($devolucion[0],0,4);							}else{								$ec_output.=substr($devolucion[0],4,2)."/".substr($devolucion[0],6,2)."/".substr($devolucion[0],0,4);							}
							$ec_output.=" ".$devolucion[1];
						}
						$ec_output.="</td><td bgcolor=white valign=top ></td> ";
						//header("Location:usuario_prestamos_prestar.php?usuario=".$arrHttp["usuario"]."&inventario=".$arrHttp["inventory"]."&signatura=".$signatura."&item=".urlencode($item)."&usrtype=".$userdata[1]."&copies=".$from_copies."&policy=".urlencode($ppres)."&prefix_in=$prefix_in");
					}else{
						$arrHttp["vienede"]="prestamos";
						require_once("opac_reservas.php");
						ProduceOutput($ec_output,$reserva_output);					}
           		}else{           			$ec_output.="<td bgcolor=white></td>".$este_prestamo;           		}
			}else{				$ec_output.="<td bgcolor=white></td>".$este_prestamo;			}
		}else{			$ec_output.="<td bgcolor=white></td>".$este_prestamo;		}
	}
	$ec_output.="</table>";
}
if ($prestamos_este>0) $ec_output.= "<strong><a href=javascript:DevolverRenovar('D')>".$msgstr["return"]."</a></strong>\n";
if ($reserves_user!="")
	$ec_output.="<p><strong>".$msgstr["reserves"]."</strong><br>".$reserves_user."<p>";
ProduceOutput($ec_output,"");

function ProduceOutput($ec_output,$reservas){global $msgstr,$arrHttp,$signatura,$msg_1,$cont,$institution_name,$lang_db,$copies_title,$link_u,$recibo_arr,$db_path,$Wxis,$xWxis;global $prestamos_este,$xnum_p;
	include("../common/header.php");    echo "<body>";
 	include("../common/institutional_info.php");
// 	if ($recibo!=""){// 		$recibo="&recibo=$recibo";
// 		$link_u.=$recibo;// 	}
?>
<script  src="../dataentry/js/lr_trim.js"></script>
<script>
document.onkeypress =
  function (evt) {
    var c = document.layers ? evt.which
            : document.all ? event.keyCode
            : evt.keyCode;
	if (c==13)
		self.location.href='prestar.php?encabezado=s<?php echo $link_u;?>'
    return true;
  };


function EvaluarRenovacion(p,atraso,fecha_d,nMultas,item){	if (p[6]==0){     // the object does not accept renovations
		alert(item+". <?php echo $msgstr["noitrenew"] ?>")
		return false
	}
	if (atraso!=0){
		if (p[13]!="Y"){
			alert(item+". <?php echo $msgstr["loanoverdued"]?>")
			return false
		}
	}
	if (Trim(p[15])!=""){
		if (fecha_d>p[15]){
			alert(item+". <?php echo $msgstr["limituserdata"]?>"+": "+p[15])
			return false
		}
	}
	if (Trim(p[16])!=""){
		if (fecha_d>p[16]){
			alert(item+". <?php echo $msgstr["limitobjectdata"]?>"+": "+p[16])
			return false
		}
	}
	if (nMultas!=0){
		alert(item+". <?php echo $msgstr["norenew"]?>")
		return false
	}
	return true}

function DevolverRenovar(Proceso) {	if (Proceso=="D"){
		document.devolver.action="devolver_ex.php"
	}else{
		if (Vigencia=="N"){
			alert("<?php echo $msgstr["norenew"]?>");
			return
		}
		document.devolver.action="renovar_ex.php"
	}
	marca="N"
	search=""
	atraso=""
	politica=""
	switch (np){     // número de préstamos del usuario
		case 1:
			if (document.ecta.chkPr_1.checked){
				search=document.ecta.chkPr_1.id
				atraso=document.ecta.chkPr_1.value
				politica=document.ecta.politica.value
				fecha_d="<?php echo date("Ymd")?>"
				p=politica.split('|')
				if (Proceso=="R"){
					res=EvaluarRenovacion(p,atraso,fecha_d,nMultas,1)
					if (res)
						marca="S"
					else
						marca="N"
				}else{					marca="S"				}
			}
			break
		default:
			for (i=1;i<=np;i++){				Ctrl=eval("document.ecta.chkPr_"+i)
				if (Ctrl.checked){
					marca="S"
					search+=Ctrl.id+"$$"
					atraso=Ctrl.value
					fecha_d="<?php echo date("Ymd")?>"
					politica=document.ecta.politica[i-1].value
					p=politica.split('|')
					if (Proceso=="R"){    // si es una renovación
						res=EvaluarRenovacion(p,atraso,fecha_d,nMultas,i)
						if (res)
							marca="S"
						else
							marca="N"
					}else{
						marca="S"
					}
				}  // FIN DE OPCION SELECIONADA
			} // FIN DE REVISAR TODAS LAS OPCIONES

		}// FIN DEL CASE
		if (marca=="S"){
			document.devolver.searchExpr.value=search
			document.devolver.submit()
		}else{
			alert("<?php echo $msgstr["markloan"]?>")
		}
}

function PagarMultas(){
	Mfn=""
	switch (nMultas){
		case 1:
			if (document.ecta.pay.checked){
            	Mfn=document.ecta.pay.value
			}
			break
		default:
			for (i=0;i<nMultas;i++){
				if (document.ecta.pay[i].checked){
					Mfn+=document.ecta.pay[i].value+"|"
				}
			}
			break
	}
	if (Mfn==""){
		alert("<?php echo $msgstr["selfine"]?>")
		return
	}
	document.multas.Mfn.value=Mfn
	document.multas.submit()
}

function DeleteSuspentions(){
	Mfn=""
	switch (nSusp){
		case 1:
			if (document.ecta.susp.checked){
            	Mfn=document.ecta.susp.value
			}
			break
		default:
			for (i=0;i<nSusp;i++){
				if (document.ecta.susp[i].checked){
					Mfn+=document.ecta.susp[i].value+"|"
				}
			}
			break
	}
	if (Mfn==""){
		alert("<?php echo $msgstr["selsusp"]?>")
		return
	}
	document.multas.Mfn.value=Mfn
	document.multas.submit()
}
</script>
<body>
<div class="sectionInfo">
	<div class="breadcrumb">
		<?php echo $msgstr["statment"]?>
	</div>
	<div class="actions">
		<?php include("submenu_prestamo.php");?>
	</div>
	<div class="spacer">&#160;</div>
</div>
<div class="helper">
<?php
echo "<a href=../documentacion/ayuda.php?help=". $_SESSION["lang"]."/circulation/loan.html target=_blank>". $msgstr["help"]."</a>&nbsp &nbsp;";
if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	echo "<a href=../documentacion/edit.php?archivo=". $_SESSION["lang"]."/circulation/loan.html target=_blank>".$msgstr["edhlp"]."</a>";
echo "<font color=white>&nbsp; &nbsp; Script: usuarios_prestamos_presentar.php </font>
	</div>";
// prestar, reservar o renovar
?>
<div class="middle form">
	<div class="formContent">
<form name=ecta>
<?php
if ($xnum_p=="") $xnum_p=0;

$ec_output.= "</form>\n";
$ec_output.="<script\n>
		np=$xnum_p
		</script>\n";
$ec_output.= "<form name=devolver action=devolver_ex.php>
<input type=hidden name=searchExpr>
<input type=hidden name=usuario value=".$arrHttp["usuario"]."\n>
<input type=hidden name=vienede value=ecta>
</form>
<form name=multas action=multas_eliminar_ex.php method=post>
<input type=hidden name=usuario value=".$arrHttp["usuario"]."\n>
<input type=hidden name=Mfn value=\"\">
</form\n>
<br>
";

echo $ec_output;
if ($reservas !="" ){	echo "<P><font color=red><strong>".$msgstr["total_copies"].": ".count($copies_title).". ".$msgstr["item_reserved"]."</strong></font><br>";
	echo $reservas ;}
if (isset($arrHttp["prestado"]) and $arrHttp["prestado"]=="S"){
	if (isset($arrHttp["resultado"])){
		$inven=explode(';',$arrHttp["resultado"]);
		foreach ($inven as $inventario){			echo "<p><font color=red>". $inventario." ".$msgstr["item"].": ".$msgstr["loaned"]." </font>";
			if (isset($arrHttp["policy"])){				$p=explode('|',$arrHttp["policy"]);
				echo $msgstr["policy"].": " . $p[0] ." - ". $p[1];			}
		}
	}
}
if (isset($arrHttp["devuelto"]) and $arrHttp["devuelto"]=="S"){	if (isset($arrHttp["resultado"])){
		$inven=explode(';',$arrHttp["rec_dev"]);
		foreach ($inven as $inventario){			if (trim($inventario)!=""){
				$Mfn=trim($inventario);
				echo "<p><font color=red>".$msgstr["item"].": ".$msgstr["returned"]." </font>";
				$Formato="v10,' ',mdl,v100,mpl,' ('v98'-'v95')'";
				$Formato="&Pft=$Formato";
				$IsisScript=$xWxis."leer_mfnrange.xis";
				$query = "&base=trans&cipar=$db_path"."par/trans.par&from=$Mfn&to=$Mfn$Formato";
				include("../common/wxis_llamar.php");
				foreach ($contenido as $value){
					echo $value;
				}
			}
		}
	}
	if (isset($arrHttp["lista_control"])){		$cntrl=explode(";",$arrHttp["lista_control"]);
		foreach ($cntrl as $value){			if (trim($value)!=""){				$salida=ReservesRead($value);
				if (trim($salida)!=""){					 echo "<br><strong><font color=darkred>".$msgstr["reserves"].": </font></strong><br>";
					 echo "<table>$salida</table>";				}
			}		}	}
}
if (isset($arrHttp["renovado"]) and $arrHttp["renovado"]=="S"){	if (isset($arrHttp["resultado"])){		$inven=explode(';',$arrHttp["resultado"]);
		foreach ($inven as $inventario){
			if (trim($inventario)!="")
				echo "<p><font color=red>".$msgstr["item"]." ". $inventario." </font>";
		}	}
}else{}

//SE IMPRIMEN LOS RECIBOS DE PRÉSTAMOS
if (count($recibo_arr)>0) {
	ImprimirRecibo($recibo_arr);
}

//SE IMPRIMEN LOS RECIBOS DE DEVOLUCION
if (isset($arrHttp["rec_dev"])){	$Mfn_rec=$arrHttp["rec_dev"];
	$fs="r_return";	$r=explode(";",$Mfn_rec);
	$rec_salida=array();

	foreach ($r as $Mfn){
		if ($Mfn!=""){
			$Formato="";
			if (file_exists($db_path."trans/pfts/".$_SESSION["lang"]."/$fs.pft")){
				$Formato=$db_path."trans/pfts/".$_SESSION["lang"]."/$fs";
			}else{
				if (file_exists($db_path."trans/pfts/".$lang_db."/$fs.pft")){
					$Formato=$db_path."trans/pfts/".$lang_db."/$fs";
				}
			}
			if ($Formato!="") {
                $Formato="&Formato=$Formato";
				$IsisScript=$xWxis."leer_mfnrange.xis";
				$query = "&base=trans&cipar=$db_path"."par/trans.par&from=$Mfn&to=$Mfn$Formato";
				include("../common/wxis_llamar.php");
				$recibo="";
				foreach ($contenido as $value){
					$recibo.=trim($value);
				}
				$rec_salida[]=$recibo;
			}
		}
	}
	if (count($rec_salida)>0) {
		ImprimirRecibo($rec_salida);
	}
}
?>
</div></div>
<?php include("../common/footer.php");?>
</body>
</html>

<?php
if (isset($arrHttp["error"])){
	echo "<script>
	alert('".$arrHttp["error"]."')
	</script>
	";
}
}  //END FUNCTION PRODUCEOUTPUT



function ImprimirRecibo($recibo_arr){	$salida="";
	foreach ($recibo_arr as $Recibo){		$salida=$salida.$Recibo;
	}
	$salida=str_replace('/','\/',$salida);
?>
<script>
	msgwin=window.open("","recibo","width=400, height=300, scrollbars, resizable")
	msgwin.document.write("<?php echo $salida?>")
	msgwin.focus()
	msgwin.print()
	msgwin.close()
</script>
<?php
}

?>

<form name=reservas method=post action=../reserve/delete_reserve.php>
<input type=hidden name=Mfn_reserve>
<input type=hidden name=Accion>
<?php
foreach ($arrHttp as $var=>$value){
	echo "<input type=hidden name=$var value=\"$value\">\n";
}
?>
<input type=hidden name=retorno value="../reserve/buscar.php">
</form>
<script>
function  DeleteReserve(Mfn){
	document.reservas.Accion.value="delete"
	document.reservas.Mfn_reserve.value=Mfn
	document.reservas.submit()
}
function  CancelReserve(Mfn){
	document.reservas.Accion.value="cancel"
	document.reservas.Mfn_reserve.value=Mfn
	alert(document.reservas.Mfn_reserve.value)
	document.reservas.submit()
}
</script>