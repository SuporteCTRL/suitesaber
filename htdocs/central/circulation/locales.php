<?php
function LeerLocales(){
global $db_path,$locales,$config_date_format;
	if (file_exists($db_path."circulation/def/".$_SESSION["lang"]."/locales.tab")){
		$locales=parse_ini_file($db_path."circulation/def/".$lang_db."/locales.tab",true);
	}
}
session_start();
if (!isset($_SESSION["login"])){
	echo "SESSION EXPIRED";
	die;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include("../config.php");
$lang=$_SESSION["lang"];
include("../lang/dbadmin.php");
include("../lang/prestamo.php");

//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";

$currency="";
$xsel11="";
$xsel12="";
$xsel13="";
$xsel21="";
$xsel22="";
$xsel23="";
$xsel31="";
$xsel32="";
$xsel33="";
$arrHttp["base"]="users";


include("../common/header.php");

?>
<script language="javascript" src="../dataentry/js/lr_trim.js"></script>
<script>
function LimpiarHorario(Ctrl){
	switch (Ctrl.name){
		case "mon":
			document.forma1.mon_from.value=""
			document.forma1.mon_to.value=""
			break
		case "tue":
			document.forma1.tue_from.value=""
			document.forma1.tue_to.value=""
			break
		case "wed":
			document.forma1.wed_from.value=""
			document.forma1.wed_to.value=""
			break
		case "thu":
			document.forma1.thu_from.value=""
			document.forma1.thu_to.value=""
			break
		case "fri":
			document.forma1.fri_from.value=""
			document.forma1.fri_to.value=""
			break
		case "sat":
			document.forma1.sat_from.value=""
			document.forma1.sat_to.value=""
			break
		case "sun":
			document.forma1.sun_from.value=""
			document.forma1.sun_to.value=""
			break
	}
}

function IsValidTime(timeStr,Day) {
// Checks if time is in HH:MM:SS AM/PM format.
// The seconds and AM/PM are optional.

	var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

	var matchArray = timeStr.match(timePat);
	if (matchArray == null) {
		alert(Day+": <?php echo $msgstr["timeformat_inv"]?>");
		return false;
	}
	hour = matchArray[1];
	minute = matchArray[2];
	second = matchArray[4];
	ampm = matchArray[6];

	if (second=="") { second = null; }
	if (ampm=="") { ampm = null }

	if (hour < 0  || hour > 12) {
		alert(Day+": <?php echo $msgstr["hour1_12"]?>");
		return false;
	}
	if (hour <= 12 && ampm == null) {
		alert(Day+": <?php echo $msgstr["am_or_pm"]?>");
		return false;
	}
	if (minute<0 || minute > 59) {
		alert (Day+": <?php echo $msgstr["secs0_59"]?>");
		return false;
	}
	if (second != null && (second < 0 || second > 59)) {
		alert (Day+": <?php echo $msgstr["secs0_59"];?>");
		return false;
	}
	return true;
}
//  End -->


function Guardar(){

//Validaci�n de la abreviatura de la moneda
	if (Trim(document.forma1.currency.value)==""){
		alert("<?php echo $msgstr["ab_mlocal"]?>")
		return
	}

//Validaci�n de los d�as laborables y el horario

	if (document.forma1.mon.checked){
		Day="<?php echo $msgstr["mon"]?>"
		ix=document.forma1.smon_from.selectedIndex
    	strTime=document.forma1.mon_from.value+" "+document.forma1.smon_from.options[ix].value
    	if (!IsValidTime(strTime,Day)) return
    	ix=document.forma1.smon_to.selectedIndex
    	strTime=document.forma1.mon_to.value+" "+document.forma1.smon_to.options[ix].value
    	if (!IsValidTime(strTime,Day)) return
    }

	if (document.forma1.tue.checked){
	    Day="<?php echo $msgstr["tue"]?>"
	    ix=document.forma1.stue_from.selectedIndex
	    strTime=document.forma1.tue_from.value+" "+document.forma1.stue_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.stue_to.selectedIndex
	    strTime=document.forma1.tue_to.value+" "+document.forma1.stue_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	 }

	if (document.forma1.wed.checked){
	    Day="<?php echo $msgstr["wed"]?>"
	    ix=document.forma1.swed_from.selectedIndex
	    strTime=document.forma1.wed_from.value+" "+document.forma1.swed_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.swed_to.selectedIndex
	    strTime=document.forma1.wed_to.value+" "+document.forma1.swed_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	}

    if (document.forma1.thu.checked){
	    Day="<?php echo $msgstr["thu"]?>"
	    ix=document.forma1.sthu_from.selectedIndex
	    strTime=document.forma1.thu_from.value+" "+document.forma1.sthu_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.sthu_to.selectedIndex
	    strTime=document.forma1.thu_to.value+" "+document.forma1.sthu_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	}

    if (document.forma1.fri.checked){
	    Day="<?php echo $msgstr["fri"]?>"
	    ix=document.forma1.sfri_from.selectedIndex
	    strTime=document.forma1.fri_from.value+" "+document.forma1.sfri_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.sfri_to.selectedIndex
	    strTime=document.forma1.fri_to.value+" "+document.forma1.sfri_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
   	}

   	if (document.forma1.sat.checked){
	    Day="<?php echo $msgstr["sat"]?>"
	    ix=document.forma1.ssat_from.selectedIndex
	    strTime=document.forma1.sat_from.value+" "+document.forma1.ssat_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.ssat_to.selectedIndex
	    strTime=document.forma1.sat_to.value+" "+document.forma1.ssat_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	}

	if (document.forma1.sun.checked){
	    Day="<?php echo $msgstr["sun"]?>"
	    ix=document.forma1.ssun_from.selectedIndex
	    strTime=document.forma1.sun_from.value+" "+document.forma1.ssun_from.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	    ix=document.forma1.ssun_to.selectedIndex
	    strTime=document.forma1.sun_to.value+" "+document.forma1.ssun_to.options[ix].value
	    if (!IsValidTime(strTime,Day)) return
	}
    document.forma1.submit()

}

function Test(){
	if (document.forma1.Mfn.value==""){
		alert("<?php echo $msgstr["test_mfn_err"]?>")
		return
	}
    msgwin_t=window.open("","TestPft","")
    msgwin_t.focus()
	document.forma1.action="borrowers_configure_test.php"
	document.forma1.target="TestPft";
	document.forma1.submit()
}
</script>
</script>
<?php
$encabezado="";
include("../common/institutional_info.php");

if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"]))
	
echo "<font color=white> Script: locales.php </font>";
echo "</div>
		<div class=\"middle form\">
			<div class=\"formContent\"> ";
echo "<form name=forma1 action=locales_update.php method=post>\n";
echo "<input type=hidden name=base value=".$arrHttp["base"].">\n";
// Se lee la tabla con los valores locales
$locales["currency"]="";
$locales["fine"]="";
$locales["date1"]="";
$locales["date2"]="";
for ($i=0;$i<7;$i++){
	$locales[$i]["from"]="";
	$locales[$i]["to"]="";
	$locales[$i]["f_ampn"]="";
	$locales[$i]["t_ampn"]="";
}
$archivo=$db_path."circulation/def/".$_SESSION["lang"]."/locales.tab";
if (!file_exists($archivo)) $archivo=$db_path."circulation/def/".$lang_db."/locales.tab";
if (file_exists($archivo)){
	$locales=parse_ini_file($archivo,true,INI_SCANNER_RAW);
}

echo "<br>
<table class=\"table\">
<tr><td><label>1. ".$msgstr["currency"]."</label></td>
	<td><input class=\"form-control\" type=text name=currency value=\"".$locales["currency"]."\"></td>

<tr><td><label>2. ".$msgstr["fine"]."</label></td>
	<td><input class=\"form-control\" type=text name=fine value=\"".$locales["fine"]."\"></td>

<tr><td><label>3. ".$msgstr["dateformat"]."</label></td>
	<td>".$config_date_format."</td>

<tr><td><label>4. ".$msgstr["workingdays"] ."<br> ".$msgstr["workinghours"]."</label></td>




	<table class=\"table\">
	
<!--segunda feira-->

		<input type=checkbox name=mon value=mon";
		if (trim($locales[1]["from"])!="" and trim($locales[1]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)>
		<label>".$msgstr["mon"]."</label>
		<br>
		<label>".$msgstr["from"].":</label>
			<input type=text class=\"form-control\" name=mon_from size=4 value=\"".$locales[1]["from"]."\"><select class=\"form-control\" name=smon_from><option value=am";
		
		if ($locales["1"]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[1]["f_ampm"]=="pm") echo " selected";
		
		echo "<label> pm </label></option></select>
		
		<label>".$msgstr["to"].":</label>
		<input type=text name=mon_to class=\"form-control\" value=\"".$locales[1]["to"]."\"><select class=\"form-control\" name=smon_to>
		<option value=am";
		if ($locales[1]["t_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";

		if ($locales[1]["t_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
	
<br><br>
<!--ter�a-->
	
		<input type=checkbox name=tue value=tue";
		if (trim($locales[2]["from"])!="" and trim($locales[2]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)>
		
		<label>".$msgstr["tue"]."</label>
		<br>
		<label>".$msgstr["from"].":</label>
			<input type=text class=\"form-control\" name=tue_from size=4 value=\"".$locales[2]["from"]."\">
			<select class=\"form-control\" name=stue_from><option value=am";
		if ($locales[2]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[2]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
		<label>".$msgstr["to"].":</label>
			<input type=text class=\"form-control\" name=tue_to size=4 value=\"".$locales[2]["to"]."\">
			<select class=\"form-control\" name=stue_to>
		<option value=am";
		if ($locales[2]["t_ampm"]=="am") echo " selected";
		echo ">am</option><option value=pm";
		if ($locales[2]["t_ampm"]=="pm") echo " selected";
		echo ">pm</option></select>

<br><br>
<!--quarta-->
	
		<input type=checkbox name=wed value=wed";
		if (trim($locales[3]["from"])!="" and trim($locales[3]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)><label>".$msgstr["wed"]."</label>
<br>
		<label> ".$msgstr["from"].":</label>
		<input type=text class=\"form-control\" name=wed_from size=4 value=\"".$locales[3]["from"]."\">
		<select class=\"form-control\" name=swed_from><option value=am";
		if ($locales[3]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[3]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
		<label>".$msgstr["to"].":</label>
		<input type=text class=\"form-control\" name=wed_to size=4 value=\"".$locales[3]["to"]."\">
		<select class=\"form-control\" name=swed_to>
		<option value=am";
		if ($locales[3]["t_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[3]["t_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
	

<br><br>
<!--quinta-->
	
		<input type=checkbox name=thu value=thu";
		if (trim($locales[4]["from"])!="" and trim($locales[4]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)><label>".$msgstr["thu"]."</label>
		<br>
		<label> ".$msgstr["from"].":</label>
		<input type=text class=\"form-control\" name=thu_from  value=\"".$locales[4]["from"]."\">
		<select class=\"form-control\" name=sthu_from><option value=am";
		if ($locales[4]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[4]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
		<label>".$msgstr["to"].":</label>
		<input type=text class=\"form-control\" name=thu_to value=\"".$locales[4]["to"]."\">
		<select class=\"form-control\" name=sthu_to>
		<option value=am";
		if ($locales[4]["t_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[4]["t_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>

<br><br>
<!--sexta-->

		<input type=checkbox name=fri value=fri";
		if (trim($locales[5]["from"])!="" and trim($locales[5]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)><label>".$msgstr["fri"]."</label>
		<br>
		<label> ".$msgstr["from"].":</label>
		<input type=text class=\"form-control\" name=fri_from  value=\"".$locales[5]["from"]."\">
		<select class=\"form-control\" name=sfri_from>
		<option value=am";
		if ($locales[5]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[5]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
		<label>".$msgstr["to"].":</label>
		<input type=text class=\"form-control\" name=fri_to value=\"".$locales[5]["to"]."\">
		<select class=\"form-control\" name=sfri_to>
		<option value=am";
		if ($locales[5]["t_ampm"]=="am") echo " selected";
		echo ">am</option><option value=pm";
		if ($locales[5]["t_ampm"]=="pm") echo " selected";
		echo ">pm</option></select>


<br><br>
<!--sabado-->


		<input type=checkbox name=sat value=sat";
		if (trim($locales[6]["from"])!="" and trim($locales[6]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)><label>".$msgstr["sat"]."</label>
		
		<br>
		<label> ".$msgstr["from"].":</label>
		<input type=text class=\"form-control\" name=sat_from  value=\"".$locales[6]["from"]."\">
		<select class=\"form-control\" name=ssat_from><option value=am";
		if ($locales[6]["f_ampm"]=="am") echo " selected";
		echo " onclick=LimpiarHorario(this)>am</option><option value=pm";
		if ($locales[6]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option><option value=pm>pm</option></select>
		<label>".$msgstr["to"].":</label>
		<input type=text class=\"form-control\" name=sat_to  value=\"".$locales[6]["to"]."\">
		<select class=\"form-control\" name=ssat_to><option value=am";
		if ($locales[6]["t_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[6]["t_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option><option value=pm>pm</option></select>

	<br><br>
<!--domingo-->


		<input type=checkbox name=sun value=sun";
		if (trim($locales[0]["from"])!="" and trim($locales[0]["to"]!="")) echo " checked";
		echo " onclick=LimpiarHorario(this)><label>".$msgstr["sun"]."</label>
		<br>
		<label> ".$msgstr["from"].": </label>
		<input type=text class=\"form-control\" name=sun_from  value=".$locales[0]["from"].">
		<select class=\"form-control\" name=ssun_from><option value=am";
		if ($locales[0]["f_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[0]["f_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>
		<label>".$msgstr["to"].":</label>
		<input type=text class=\"form-control\" name=sun_to  value=".$locales[0]["to"].">
		<select class=\"form-control\" name=ssun_to><option value=am";
		if ($locales[0]["t_ampm"]=="am") echo " selected";
		echo "<label> am </label></option><option value=pm";
		if ($locales[0]["t_ampm"]=="pm") echo " selected";
		echo "<label> pm </label></option></select>

	<font color=darkred>".$msgstr["ejem"].": ".$msgstr["from"]." 8:30 am &nbsp;".$msgstr["to"]." 6:30 pm</td>
	</table>
</td>
</table>
</form></div></div>";
include("../common/footer.php");
echo "</body></html>" ;

?>