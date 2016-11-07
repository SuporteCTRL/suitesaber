<?php
if (isset($def["DIRTREE"]))
 	$dirtree=$def["DIRTREE"];
 if ($dirtree==1) $dirtree="Y";
//SETTING FOR HEICHT OF THE FRAMES OF THE CATALOGUING WINDOWS
if (isset($def["FRAME_1H"])) $FRAME_1H=$def["FRAME_1H"];
if (isset($def["FRAME_2H"])) $FRAME_2H=$def["FRAME_2H"];
//direccion donde se localiza el archivo de perfiles gen�ricos
if (isset($def["PROFILES_PATH"]))
	$profiles_path=$def["PROFILES_PATH"];
//archivos de estilo
if (isset($def["CSS_NAME"])){	$css_name=$def["CSS_NAME"];
}

//set the charset of all the databases
if (isset($def["UNICODE"])){
	$UNICODE=$def["UNICODE"];
}
//Pedir lapso del pr�stamo
if (isset($def["ASK_LPN"])){
	$ASK_LPN=$def["ASK_LPN"];
}else{
	$ASK_LPN="";
}
//Pol�tica de pr�stamos
if (isset($def["LOAN_POLICY"])){
	$LOAN_POLICY=$def["LOAN_POLICY"];
}else{	$LOAN_POLICY="";}
//se incluyen los feriados en el c�lculo de suspensiones y multas: Y=/N
if (isset($def["CALENDAR"])) {	$CALENDAR_S=$def["CALENDAR"];}else{	$CALENDAR_S="N";}

//Validacion de password segura
if (isset($def["SECURE_PASSWORD_LEVEL"])){
	$SECURE_PASSWORD_LEVEL=$def["SECURE_PASSWORD_LEVEL"];
}else{
	$SECURE_PASSWORD_LEVEL="";
}
if (isset($def["SECURE_PASSWORD_LENGTH"])){
	$SECURE_PASSWORD_LENGTH=$def["SECURE_PASSWORD_LENGTH"];
}else{
	$SECURE_PASSWORD_LENGTH="";
}
$AC_SUSP="Y";
if (isset($def["AC_SUSP"])){	$AC_SUSP=strtoupper($def["AC_SUSP"]);}
//Logo
if (isset($def["LOGO"])) $logo=$def["LOGO"];
if (isset($def["LOGO_OPAC"]))
	$logo_opac=$def["LOGO_OPAC"];
else
	if (isset($logo)) $logo_opac=$logo;
$reserve_active="Y";
if (isset($def["RESERVATION"])) $reserve_active=strtoupper($def["RESERVATION"]);
//se lee el archivo dr_path.def para ver las configuraciones locales de la base de datos
if (isset($arrHttp["base"])){
	if (file_exists($db_path.$arrHttp["base"]."/dr_path.def")){		$def_db = parse_ini_file($db_path.$arrHttp["base"]."/dr_path.def");
		if (isset($def_db["UNICODE"]))  		    	$UNICODE=$def_db["UNICODE"];
		if (isset($def_db["inventory_numeric"]))      	$inventory_numeric=$def_db["inventory_numeric"];
		if (isset($def_db["max_inventory_length"]))   	$max_inventory_length=$def_db["max_inventory_length"];
		if (isset($def_db["max_cn_length"]))     		$max_cn_length=$def_db["max_cn_length"];
		if (isset($def_db["mx_path"]))                	$mx_path=$def_db["mx_path"];
		if (isset($def_db["dirtree"]))                  $dirtree=1;
		unset($_SESSION["BARCODE"]);
		unset($_SESSION["BARCODE_SIMPLE"]);
		if (isset($def_db["barcode"]))                  $_SESSION["BARCODE"]="Y";
		if (isset($def_db["barcode_simple"]))			$_SESSION["BARCODE_SIMPLE"]="Y";
		if (isset($def_db["db_path"]))                  $db_path=$def_db["db_path"];
		if (isset($def_db["barcode1reg"]))              $barcode1reg=$def_db["barcode1reg"];
        if (isset($def_db["tesaurus"]))                 $tesaurus=$def_db["tesaurus"];
        if (isset($def_db["prefix_search_tesaurus"]))   $prefix_search_tesaurus=$def_db["prefix_search_tesaurus"];
		//SE REDEFINEN LOS SIGUIENTES PAR�METROS DEL CONFIG.PHP
		if (isset($def_db["cisis_ver"]))			 $cisis_ver=$def_db["cisis_ver"]."/";
		if (isset($def_bd["wxis_get"]))
			//Path to the wxis.exe when using get;
			$Wxis=$def_bd["wxis_get"];
   		if (isset($def_bd["wxis_post"]))
   			//Url for the execution of WXis, when using POST
   			$wxisUrl=$def_bd["wxis_post"];
		if (isset($def_db["mx_path"]))
			$mx_path=$def_db["mx_path"];
 		if (isset($def_db["leader"]))  $LEADER_TAG=$def_db["leader"];


	}
}
$show_acces="N";
$fix_file_name = array(    '�'=>'S', '�'=>'s', '�'=>'Z', '�'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E',
                            '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I', '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U',
                            '�'=>'U', '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'c',
                            '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i', '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o',
                            '�'=>'o', '�'=>'o', '�'=>'u', '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'b', '�'=>'y',' '=>'_' );
//NO EXTRA BLANK LINE MUST APPEAR AFTER THE CLOSING TAG
?>