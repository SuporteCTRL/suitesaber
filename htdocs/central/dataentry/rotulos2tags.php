<?
//Convierte un archivo TXT con r�tulos a TagIsis, en base a un archivo de conversi�n que debe tener el siguiente formato:
//Primera l�nea: Separador de registros
//l�neas subsiguientes: esquema de conversi�n suministrado de la siguiente forma:
//R�tulo|Tag Isis|Separador ocurrencias|Subcampos|Delimitadores

function Rotulos2Tags($Rotulos,$Texto){
Global $noLocalizados;
	$Texto=trim($Texto);
	$salida=array();
	if ($Texto=="") return $salida;
	foreach($Rotulos as $key => $value){
		$inicio=strpos($Texto,$value[0]);
		while ($inicio!==false){			$in=$inicio;
			$principio=$in+strlen($value[0]);
			$fin=strpos($Texto,'$$',$principio);
			if ($fin ===false) $fin=strlen($Texto);
			$var=substr($Texto,$principio,$fin-$principio);
			$tag=$value[1];
			//$tag=substr($value[0],2);
			//$ix_pos=strlen($tag)-1;
			//$tag=substr($tag,0,$ix_pos);
			$salida[$tag][]=$var;
			$Texto=substr($Texto,0,$in).substr($Texto,$fin);
			$inicio=strpos($Texto,$value[0]);
		}
	}
	$noLocalizados=$Texto;
	return $salida;

}
?>