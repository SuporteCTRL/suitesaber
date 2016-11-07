<?php
session_start();
set_time_limit(0);
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");

$IsisScript=$xWxis."leer_mfnrange.xis";
$query =  "&base=".$arrHttp["base"]."&cipar=$db_path"."par/".$arrHttp["base"]. ".par&from=1";
include("../common/wxis_llamar.php");
$ic=-1;
$arr_tags=array();
$repetible=array();
$list_tags=array();
foreach ($contenido as $linea){	$linea=trim($linea);
	if ($linea!=""){
		if (substr($linea,0,4)=="mfn="){
			echo "$linea";
			flush();
    		ob_flush();			foreach ($arr_tags as $key=>$value){				if ($value>1){					if (isset($repetible[$key])){						$repetible[$key]=$repetible[$key]+$value;					}else{						$repetible[$key]=$value;					}				}
				if (isset($list_tags[$key]))
					$list_tags[$key]=$list_tags[$key]+$value;
				else
					$list_tags[$key]=$value;			}
			$arr_tags=array();
			continue;
		}

		$pos=strpos($linea, " ");
		if ($pos>0) {
			$tag=substr($linea,0,$pos);
			if (isset($arr_tags[$tag])){				$arr_tags[$tag]=$arr_tags[$tag]+1;			}else{				$arr_tags[$tag]=1;
			}		}
	}
}
foreach ($arr_tags as $key=>$value){
	if ($value>1){
		if (isset($repetible[$key])){
			$repetible[$key]=$repetible[$key]+$value;
		}else{
			$repetible[$key]=$value;
		}
	}
	if (isset($list_tags[$key]))
		$list_tags[$key]=$list_tags[$key]+$value;
	else
		$list_tags[$key]=$value;
}
ksort($list_tags);
ksort($repetible);
echo "<strong>Campos Presentes</strong>";
echo "<table><td>Tag</td><td>No. de veces que aparece</td>";
foreach($list_tags as $tag=>$value){	echo "<tr><td>$tag</td><td>$value</td></tr>";}
echo "</table>";
echo "<strong>Campos repetibles</strong>";
echo "<table><td>Tag</td><td>No. de veces que aparece</td>";
foreach($repetible as $tag=>$value){
	echo "<tr><td>$tag</td><td>$value</td></tr>";
}
echo "</table>";

?>

