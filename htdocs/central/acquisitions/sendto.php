<?php
if (isset($index)){
?>

<label><?php echo $msgstr["sendto"];?>:
		<a href='javascript:SendTo("D")'><?php echo $msgstr["doc"];?></a> 
		<a href='javascript:SendTo("W")'><?php echo $msgstr["xls"];?></a></label>

<script>

function SendTo(SendTo){
	index="<?php echo $index?>"
	tit="<?php echo $tit?>"
	Expresion="<?php echo $Expresion?>"
	base="<?php echo $arrHttp["base"]?>"
	sort="<?php echo $arrHttp["sort"]?>"
	msgwin=window.open("sendto_ex.php?base="+base+"&sort="+sort+"&Opcion="+SendTo+"&index="+index+"&tit="+tit+"&Expresion="+Expresion,"sendto")
	msgwin.focus()
}
</script>
<?php
}
?>

