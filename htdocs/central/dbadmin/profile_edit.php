<?php
/**
 * @program:   ABCD - ABCD-Central - http://reddes.bvsaude.org/projects/abcd
 * @copyright:  Copyright (C) 2009 BIREME/PAHO/WHO - VLIR/UOS
 * @file:      profile_edit.php
 * @desc:
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
global $arrHttp;
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}

include("../common/get_post.php");
include("../config.php");
include("../common/header.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/profile.php");
//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";
echo "<body>\n";
if (isset($arrHttp["encabezado"])){
	include("../common/institutional_info.php");
	$encabezado="&encabezado=s";
}else{
	$encabezado="";
}
?>
<script src=../dataentry/js/lr_trim.js></script>
<script>
//VERIFICA SI ESTA MARCADO ALL PARA TODAS LAS BASES DE DATOS PARA TAMBIEN MARCAR LA CASILLA DE NIVEL SUPERIOR
function CheckAll(){
	ixnum_db=0;
	ixchk_db=0;
	ixALL=0;
	for (db in datab){
	   ixnum_db=ixnum_db+1
		ctrl=eval("document.profile.db_"+db)
		if (ctrl.checked)
			ixchk_db=ixchk_db+1
		ctrl=eval("document.profile."+db+"_CENTRAL_ALL")
		if (ctrl.checked)
			ixALL=ixALL+1

	}
	if (ixALL==ixnum_db && ixchk_db==ixnum_db){
		document.profile.db_ALL.checked=true
	}
}

function returnObjById( id ){
    if (document.getElementById)
        var returnVar = document.getElementById(id);
    else if (document.all)
        var returnVar = document.all[id];
    else if (document.layers)
        var returnVar = document.layers[id];
    return returnVar;
}

function getElement(psID) {
	if(!document.all) {
		return document.getElementById(psID);

	} else {
		return document.all[psID];
	}
}

function DeleteProfile(Profile){
	if (confirm("<?PHP echo $msgstr["DELETE"]?> "+Profile))
		self.location.href="profile_edit.php?profile="+Profile+"&Opcion=delete&encabezado=<?php echo $encabezado?>"

}

function AllDatabases(){
	ixdb=document.profile.elements.length
	for (id=0;id<ixdb;id++){
		ctrl=document.profile.elements[id].name
		if (ctrl.substr(0,3)=="db_") {
			if (document.profile.db_ALL.checked){
				document.profile.elements[id].checked=true
			}else{
				document.profile.elements[id].checked=false
			}
		}
		c=ctrl.split("_")
		if (c[1]=="pft" || c[1]=="fmt" || c[2]=="ALL"){
			if (c[2]=="ALL"){
				if (document.profile.db_ALL.checked){
					document.profile.elements[id].checked=true
				}else{
					document.profile.elements[id].checked=false
				}
			}
		}
	}
}

function AllPermissions(){
	ixdb=document.profile.elements.length
	for (id=0;id<ixdb;id++){
		ctrl=document.profile.elements[id].name

		if (ctrl.substr(0,7)=="CENTRAL") {
			if (document.profile.CENTRAL_ALL.checked)
				document.profile.elements[id].checked=true
			else
				document.profile.elements[id].checked=false
		}
	}
}

function AllPermissionsCirculation(){
	ixdb=document.profile.elements.length
	for (id=0;id<ixdb;id++){
		ctrl=document.profile.elements[id].name

		if (ctrl.substr(0,4)=="CIRC") {
			if (document.profile.CIRC_CIRCALL.checked)
				document.profile.elements[id].checked=true
			else
				document.profile.elements[id].checked=false
		}
	}
}

function AllPermissionsAcquisitions(){
	ixdb=document.profile.elements.length
	for (id=0;id<ixdb;id++){
		ctrl=document.profile.elements[id].name

		if (ctrl.substr(0,3)=="ACQ") {
			if (document.profile.ACQ_ACQALL.checked)
				document.profile.elements[id].checked=true
			else
				document.profile.elements[id].checked=false
		}
	}
}
function ValidateName(Name){
	bool=  /^[a-z][\w]+$/i.test(Name)
 	if (bool){
        return true
   	}else {
      	return false
   	}
}

function SendForm(){
	Name=Trim(document.profile.profilename.value)
	re=/  /gi
	Name=Name.replace(re,' ')
	re=/ /gi
	Name=Name.replace(re,'_')
	document.profile.profilename.value=Name
	if (Name==""){
		alert("<?php echo $msgstr["MISSPROFNAME"]?>")
		return
	}
	if (!ValidateName(Name)){
		alert("<?php echo $msgstr["INVPROFNAME"]?>")
		return
	}
	if (Trim(document.profile.profiledesc.value)==""){
		alert("<?php echo $msgstr["MISSPROFDESC"]?>")
		return
	}
    document.profile.submit()
}
</script>

<div class="sectionInfo">
	<div class="breadcrumb">
<h2>  <i class="fa fa-users fa-2x" aria-hidden="true"></i>   <label><?php echo $msgstr["PROFILES"]?></label></h2>
	</div>
	
<div class="middle form">
	<div class="formContent">
<form name="profile" action="profile_save.php" onsubmit="javascript:return false" method="post">
<?php
if (isset($arrHttp["encabezado"]))
	echo "<input type=hidden name=encabezado value=S>\n";
if (!isset($arrHttp["Opcion"])){
	DisplayProfiles();
}else{

	switch ($arrHttp["Opcion"]){
		case "edit":
			EditProfile();
			break;
		case "new":
			NewProfile("");
			break;
		case "delete":
			DeleteProfile();
			break;
	}
}

?>
<iframe src="" name="configura" width="100%" height="3000px" frameborder="0"></iframe>
<?php
echo "</form></div>
</div>
</center>";
include("../common/footer.php");
echo "</body></html>\n";



function DisplayProfiles(){
global $db_path,$msgstr,$encabezado;
	echo "<table class=\"table table-bordered\">";
	$fp=file($db_path."par/profiles/profiles.lst");
	foreach ($fp as $val){
		$val=trim($val);
		if ($val!=""){
			$p=explode('|',$val);
            if ($p[0]!="adm"){
				echo "<tr><td>".$p[1]." (".$p[0].")</td><td><center>
					<a class=\"btn btn-warning\" href=profile_edit.php?profile=".$p[0]."$encabezado&Opcion=edit>".$msgstr["EDIT"]."</a> ";
				echo "<a class=\"btn btn-danger\" href=javascript:DeleteProfile(\"".$p[0]."\")>".$msgstr["delete"]."</a></td>";
			}
		}
	}

	echo "
	<a class=\"btn btn-primary\" href=profile_edit.php?Opcion=new&encabezado=s>".$msgstr["new"]."</a>";
	echo "</table>\n";
	
}

function DeleteProfile(){
global $db_path,$msgstr,$lang_db,$arrHttp,$xWxis,$wxisUrl,$Wxis;
// READ ACCES DATABASE AND FIND IF THE PROFILE IS IN USE
	$IsisScript=$xWxis."leer_mfnrange.xis";
	$query = "&base=acces&cipar=$db_path"."par/acces.par"."&Pft=v40^a/";
	include("../common/wxis_llamar.php");
	 foreach ($contenido as $linea){
	 	if (trim($linea)==$arrHttp["profile"]){
	 		echo "<h2>".$msgstr["INUSE"]."<h2>";
	 		return;
	 	}
	}
    $fp=file($db_path."par/profiles/profiles.lst");
    $new=fopen($db_path."par/profiles/profiles.lst","w");
    foreach ($fp as $prof){
    	$p=explode('|',$prof);
    	if ($p[0]!=trim($arrHttp["profile"]))
    		$res=fwrite($new,$prof);
    }
    fclose($new);
    $res=unlink($db_path."par/profiles/".$arrHttp["profile"]);
	if ($res==0){
		echo $arrHttp["profile"].": The file could not be deleted";
	}else{
		echo "<h2>".$arrHttp["profile"]." ".$msgstr["deleted"]."</h2>";
	}
}

function EditProfile(){
global $db_path,$msgstr,$lang_db,$arrHttp;

    $fp=file($db_path."par/profiles/".$arrHttp["profile"]);
    NewProfile($arrHttp["profile"]);
}

function NewProfile($profile){
global $db_path,$msgstr,$lang_db,$profiles_path;
	$fprofile=file("profiles.tab");
	$module="CENTRAL";
	foreach ($fprofile as $p){
		$p=trim($p);
		if ($p=="[CIRCULATION]"){
			$module="CIRC";
		}else{
			if ($p=="[ACQUISITIONS]"){
				$module="ACQ";
			}else{
				if ($p=="[ADMINISTRATION]"){
					$module="ADM";
				}else{
					$p=trim($p);
					if ($p!=""){
						$p_el=explode("=",$p);
						$profile_usr[$module."_".$p_el[0]]="";
						$profile_general[$module][$p_el[0]]=$p;
					}
				}
			}
		}
	}
//	echo "<pre>".print_r($profile_usr)."</pre>";die;
	if ($profile!=""){
		$fprofile=file($db_path."par/profiles/".$profile);
		foreach ($fprofile as $p){
			$p=trim($p);
			if ($p!=""){
				$p_el=explode("=",$p);
				$profile_usr[$p_el[0]]=$p_el[1];
			}
		}
	}
//	echo "<xmp>";
//	print_r($profile_usr);
//	echo "</xmp>";//die;
	echo "<label>".$msgstr["PROFILENAME"].":</label>
		<input type=text name=profilename class=\"form-control\" value=\"";
	if (isset($profile_usr["profilename"])) echo $profile_usr["profilename"];
	echo "\">";

	echo "<label>".$msgstr["PROFILEDESC"].":</label>
	<input type=text name=profiledesc class=\"form-control\" value=\"";
	if (isset($profile_usr["profiledesc"])) echo $profile_usr["profiledesc"];
	echo "\"></td>";
	echo "</table>";

	$fp=file($db_path."bases.dat");
//	echo "<div style=\"position:relative;overflow:auto;height:300px;border-style:double;\">";
 	$inicio="S";
 	$bases_dat=array();
 	$select_db= "<select name=select_db class=\"form-control\"
 	onchange=\"javascript:window.location.hash=this.options[this.selectedIndex].value\">\n<option></option>\n";
 	foreach($fp as $dbs){
 		$dbs=trim($dbs);
		if ($dbs!=""){
			$dd=explode('|',$dbs);
			$dbn=$dd[0];
			$select_db.= "<option value=".$dbn.">".$dd[1]." ($dbn)</option>\n";
		}
 	}
 	$select_db.= "</select>\n";
	foreach ($fp as $dbs){
		$dbs=trim($dbs);
		if ($dbs!=""){
			$dd=explode('|',$dbs);
			$dbn=$dd[0];
			if ($dd[0]!="acces" ){
				echo "<a name=$dbn><table class=\"table\">";
				echo "<th><label>".$msgstr["DATABASES"]." ".$select_db."</label></th><th>".$msgstr["DISPLAYFORMAT"]."</th><th>".$msgstr["WORKSHEET"]."</th>";
				if ($inicio=="S"){
					$inicio="N";

					echo "<tr><td><input type=checkbox name=db_ALL value=ALL onclick=AllDatabases()><label>".$msgstr["ALL"]."</label>";
				}
                $bases_dat[$dbn]=$dbn;
				echo "<tr><td><input type=checkbox name=db_".$dbn." value=".$dbn;
				if (isset($profile_usr["db_".$dbn])) echo " checked";
				echo "<label><br>".$dd[1]." (".$dbn.")</label></td>\n";
				echo "<td>";
				$file=$db_path.$dbn."/pfts/".$_SESSION["lang"]."/formatos.dat";
				if (!file_exists($file)){
					$file=$db_path.$dbn."/pfts/".$lang_db."/formatos.dat";
				}
				$checked="";
				if (isset($profile_usr[$dbn."_pft_ALL"])) $checked=" checked";
				echo "<input type=checkbox name=".$dbn."_pft_ALL $checked>
				<label>".$msgstr["ALL"]."</label><br>";
				if (file_exists($file)){
					$pft=file($file);
					foreach($pft as $val){
						$val=trim($val);
						if ($val!=""){
							$p=explode('|',$val);
							$checked="";
							if (isset($profile_usr[$dbn."_pft_".$p[0]])) $checked=" checked";



							echo "<input type=checkbox name=".$dbn."_pft_".$p[0]." value=".$p[0]." $checked";

							echo " onclick=document.profile.".$dbn."_pft_ALL.checked=false";
							echo "\n>".$p[1]." (".$p[0].")<br>\n";
						}
					}
				}
				echo "</td>";
				echo "<td>";
				$file=$db_path.$dd[0]."/def/".$_SESSION["lang"]."/formatos.wks";
				if (!file_exists($file)){
					$file=$db_path.$dd[0]."/def/".$lang_db."/formatos.wks";
				}
				$checked="";
				if (isset($profile_usr[$dbn."_fmt_ALL"])) $checked=" checked";
				echo "<input type=checkbox name=".$dbn."_fmt_ALL $checked";
				echo "<label>".$msgstr["ALL"]."</label><br>";
				if (file_exists($file)){
					$pft=file($file);
					foreach($pft as $val){
						$val=trim($val);
						if ($val!=""){
							$p=explode('|',$val);
							$checked="";
							if (isset($profile_usr[$dbn."_fmt_".$p[0]])) $checked=" checked";
							echo "<input type=checkbox name=".$dbn."_fmt_".$p[0]." value=".$p[0]." $checked";
							echo " onclick=document.profile.".$dbn."_fmt_ALL.checked=false";
							echo ">".$p[1]." (".$p[0].")<br>\n";
						}
					}
				}else{
			
				}
				echo "</td>";
				echo "</table>";
				echo "<table class=\"table\">\n";
				echo "<label>".$msgstr["PERMISSIONS"].": ".$msgstr["DATAENTRY"]." ($dbn)</label>\n";
		        $i=3;
		        $j=0;
				foreach ($profile_usr as $key=>$value){
					$value=trim($value);
					if (substr($key,0,7)=="CENTRAL"){
						$k=explode("_",$key);
						//SE FILTRAN LOS PERMISOS QUE ANTES ESTABAN LIGADOS A LA BASE DE DATOS Y QUE AHORA PERTENECEN A ADMINISTRACION
						if ($k[1]=="CRDB" or $k[1]=="TRANSLATE"  or $k[1]=="USRADM" or $k[1]=="EDHLPSYS" ){
							continue;
						}
						if ($i>2){
							echo "<tr>";
							$i=0;
						}

						$perm=$k[1];
						$i++;
						echo "<td>";
						echo "<input type=checkbox name=$dbn"."_".$key." value=Y";
						if (isset($profile_usr[$dbn."_".$key])) echo " checked";
						if ($j!=0){
							echo " onclick=document.profile.$dbn"."_CENTRAL_ALL.checked=false";
						}else{

						}
						$j=1;
						echo ">".$msgstr[$k[1]]."</td>\n";
					}
				}
				echo "</table>";
				echo "<br><br>";
			}
		}
	}

//	echo "</div>";
	$general=array("ADMINISTRATION","ADM","CIRCULATION","ACQUISITIONS");
	foreach ($general as $key){

		$bgcolor="";
		if (isset($msgstr[$key])) {
			
			echo "<br><br>";
			echo "<table class=\"table\"><tr><th>\n";
			echo "<label>".$msgstr["PERMISSIONS"].": ".$msgstr[$key]."</label>";
		}else{
			echo "<table class=\"table\">";
		}
		$modulo="ADM";
		switch($key){
			case "ADMINISTRATION":
				$modulo="CENTRAL";
				break;
			case "CIRCULATION":
				$modulo="CIRC";
				break;
			case "ACQUISITIONS":
				$modulo="ACQ";
				break;
		}
		$i=0;
		$j=0;
		$onclick="";
		$field="";
		$adm="";
		if (isset($profile_general[$modulo])){
			foreach ($profile_general[$modulo] as $usr_p=>$val){
	            $mod=$modulo;
				if ($mod=="ADM" and $usr_p=="ALL") {
					$adm="Y";
					$field="ALL";
					$j=1;
					continue;
				}else{
					$adm="";
				}
				if ($modulo=="ADM" )$mod="CENTRAL";
				$i=$i+1;
				if ($i==1){
					echo "<tr>";
				}
				if ($j==0) $field=$usr_p;
				if ($j==1 ) {
					$onclick= "onclick=document.profile.$mod"."_".$field.".checked=false";
				}
				$j=1;
				$checked="";
				if (isset($profile_usr[$mod."_".$usr_p]) and $profile_usr[$mod."_".$usr_p]=="Y") $checked=" checked";
				echo "<td><input type=checkbox name=$mod"."_".$usr_p." $checked $onclick value=Y>".$msgstr[$usr_p]."</td>\n";
				$onclick="";
				if ($i>2){
					$i=0;
					echo "</tr>";
				}
			}
		}
		echo "</table>";
	}
	echo "</td></table>";
	echo "\n<script>\n";
	echo "datab= new Array()\n";
	foreach ($bases_dat as $value)
		echo "datab['$value']='$value'\n";
	echo "CheckAll()\n";
	echo "</script>\n";
}
?>

