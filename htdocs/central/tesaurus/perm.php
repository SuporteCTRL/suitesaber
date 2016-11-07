<?php
session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
if (!isset($_SESSION["lang"]))  $_SESSION["lang"]="en";
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];
include("../lang/admin.php");
include("../lang/dbadmin.php");

//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";if (file_exists($db_path.$tesaurus."/def/".$_SESSION["lang"]."/".$tesaurus.".dat"))
	$fp=file($db_path.$tesaurus."/def/".$_SESSION["lang"]."/".$tesaurus.".dat");
else
	$fp=file($db_path.$tesaurus."/def/".$lang_db."/".$tesaurus.".dat");
foreach($fp as $value) {	$f=explode('=',$value);
	switch($f[0]){		case "alpha_prefix":
			$prefijo=trim($f[1]);
			break;
		case "perm_prefix":
			$perm_prefix=trim($f[1]);
			break;
		case "alpha_pft":
			$Formato=trim($f[1]);
			break;
	}}
if (isset($arrHttp["perm"])) $prefijo=$perm_prefix;
$delimitador="";
$pref=$prefijo;
if(isset($arrHttp["pref"]))

	$prefijo=$prefijo.$arrHttp["pref"];
else
	$pref=$prefijo;

$IsisScript=$xWxis."ifp.xis";

$query ="&base=$tesaurus&cipar=$db_path"."par/$tesaurus".".par&Opcion=autoridades&prefijo=$prefijo&pref=$pref&formato_e=".urlencode($Formato);
include("../common/wxis_llamar.php");
$contenido = array_unique ($contenido);
//foreach ($contenido as $var=>$value) echo "$var=$value<br>";
$subtitle= " Tesaurus";
include("../common/header.php");
echo "<h3>Tesaurus ($tesaurus)</h3>\n";
echo "<script language=Javascript src=../dataentry/js/lr_trim.js></script>\n";
echo "
		<script languaje=Javascript>
		document.onkeypress =
  			function (evt) {
    			var c = document.layers ? evt.which
            		: document.all ? event.keyCode
            		: evt.keyCode;
    			return true;
  		}
		var nav4 = window.Event ? true : false;

		function codes(e) {
  			if (nav4) // Navigator 4.0x
    			var whichCode = e.which

			else // Internet Explorer 4.0x
    			if (e.type == 'keypress') // the user entered a character
     				 var whichCode = e.keyCode
    			else
      				var whichCode = e.button;
  			if (e.type == 'keypress' && whichCode==13)
				IrA()
  			else
				if (whichCode==13) IrA()
		}
		\n";
	echo "Tag=''\n";
	if (isset($arrHttp["Tag"]))
		echo "Tag='".$arrHttp["Tag"]."'\n";
?>
	function ObtenerTermino(Seleccion){        if (document.Lista.ficha.checked){        	document.show.termino.value=Seleccion
        	document.show.submit()
        	return
        }
        if (Seleccion!=""){
			if (Tag==""){
				window.opener.top.Expresion="<?php echo $prefix_search_tesaurus?>"+Seleccion
				window.opener.top.Menu("ejecutarbusqueda")
			}else{
				Var=eval("window.opener.document.forma1."+Tag)
				if (Var.type=="text")
					Separa=";"
				else
					Separa="\n"
				VarOriginal=Var.value
				VarOriginal=Trim(VarOriginal)
				if (VarOriginal=="")
					VarOriginal=Seleccion
				else
					VarOriginal=Var.value+Separa+Seleccion
				Var.value=VarOriginal
				a=Var.value
				if (Var.type=="textarea") {
					b=a.split("\n")
					if(b.length>Var.rows) Var.rows=b.length

				}
			}
		}

	}

function Continuar(){
	AbrirIndice(last)
}

function IrA(ixj){
	a=document.Lista.ira.value
	AbrirIndice(a)
}

<?php
echo "function AbrirIndice(Termino){\n";
	echo "
    	db='".$arrHttp["base"]."'\n
		URL='perm.php?perm=Y&base='+db+'&pref='+Termino
		if (Tag!=\"\") URL+='&Tag='+Tag
		self.location.href=URL
	}

</script>\n";
?>
	<body>
	<div class="helper">
	<a href=../documentacion/ayuda.php?help=<?php echo $_SESSION["lang"]?>/alfa.html target=_blank><?php echo $msgstr["help"]?></a>&nbsp &nbsp;
	<?php if (isset($_SESSION["permiso"]["CENTRAL_EDHLPSYS"])) echo "<a href=../documentacion/edit.php?archivo=".$_SESSION["lang"]."/alfa.html target=_blank>".$msgstr["edhlp"]."</a>";
	echo "<font color=white>&nbsp; &nbsp; <a href='http://abcdwiki.net/wiki/es/index.php?title=Tesauros' target=_blank>abcdwiki.net</a>";
	echo "<font color=white>&nbsp; &nbsp; Script: tesaurus/perm.php" ?>
</font></div>
<form method=post name=Lista onSubmit="javascript:return false">
	<table width=100%>
		<td width=50%><img src=../dataentry/img/toolbarSearch.png> <a href=index.php?base=<?php echo $arrHttp["base"]?>><strong><font color=white><?php echo $msgstr["tes_alphabetic"]?></strong></font></a>  &nbsp; &nbsp; <a href=perm.php?perm=Y&base=<?php echo $arrHttp["base"]?>><strong><font color=white><?php echo $msgstr["tes_permuted"]?></strong></font></a></td>
    	<td width=50% align=right><font color=white><?php echo $msgstr["tes_helpterm"]?><br><img src=../dataentry/img/ficha.png align=bottom> <input type=checkbox name=ficha> </td>
    </table>
 <div class="middle form">
			<div class="formContent">

<?php
	$xwidth="350";
?>
	<table border=0  width=100% height=80%>
	<td  width=5% align=center valign=top><font size=1 face="verdana"><?php for ($i=65;$i<91;$i++ ) echo "<a href=javascript:AbrirIndice('".chr($i)."')>".chr($i)."</a><br>"?></td>
	<td align=center width=95%><table cellpadding=0 cellspacing=0 align=center>
<?php
	foreach ($contenido as $linea){
		$b=explode('|',$linea);
        $termino=$b[1];
        $url="<a href='javascript:ObtenerTermino(\"$termino\")'>";
        $word=trim(substr($b[0],strlen($perm_prefix)));
        $key=" ".$word." ";
        $ix=strpos($termino,$key);
        $w1="";
        $w2="";
        if ($ix!==false){
        	if ($ix==0){        		$w1="";
        	}else{        		$w1=substr($termino,0,$ix)."&nbsp;";        	}
        	$w2="&nbsp;".substr($termino,$ix+1+strlen($word));
        }else{        	$key=" ".$word;
        	$ix=strpos($termino,$key);
        	if ($ix!==false){
        		if ($ix==0){
        			$w1="";
        		}else{
        			$w1=substr($termino,0,$ix)."&nbsp;";
        		}
        		$w2="&nbsp;".substr($termino,$ix+1+strlen($word));
        	}else{
        		$key=$word." ";
        		$ix=strpos($termino,$key);
        		if ($ix!==false){
        			if ($ix==0){
        				$w1="";
        			}else{
        				$w1=substr($termino,0,$ix)."&nbsp;";
        			}
        			$w2=" ".substr($termino,$ix+1+strlen($word));
        		}else{        			$key=$word;
        			$ix=strpos($termino,$key);
        			if ($ix!==false){
        				if ($ix==0){
        					$w1="";
        				}else{
        					$w1=substr($termino,0,$ix);
        				}
        				$w2=substr($termino,$ix+strlen($word));        			}
        		}
        	}        }
		echo "<tr height=18px><td align=right valign=bottom>$url".$w1."</a></td><td class=texto_lista valign=bottom><b>$url$word</a></b>$url".$w2."</a></td>";
		$last=$word;	}

?>
	</td>

	</table></td></table>
	<br><img src=../dataentry/img/toolbarSearch.png><a href=index.php?base=<?php echo $arrHttp["base"]?>><strong><?php echo $msgstr["tes_alphabetic"]?></a>  &nbsp; &nbsp; <a href=perm.php?perm=Y&base=<?php echo $arrHttp["base"]?>><?php echo $msgstr["tes_permuted"]?></a></strong><br>
	<table cellpadding=0 cellspacing=0 border=0 width=100%  height=20% bgcolor=#4E617C>
		<td valign=top width=100%><a href=Javascript:Continuar() class="defaultButton backButton">
		<img src="img/arrowRightTwo.png" alt="" title="" />
					<span><strong><?php echo $msgstr["masterm"]?></strong></span></a>
	    &nbsp;  &nbsp;
		<?php echo $msgstr["avanzara"]?> &nbsp;<input type=text name=ira size=15 value="" onKeyPress="codes(event)" > &nbsp;<a href=Javascript:IrA()><span><strong><?php echo $msgstr["continuar"]?></strong></span></a></td>
	</table>
	</form>
	</div>
	</div>
	</body>
	</html>
	<?php echo "<script>last=\"$word\"</script>";?>
<form name=show method=post action=show.php>
<input type=hidden name=base value=<?php echo $arrHttp["base"]?>>
<input type=hidden name=termino>
<?php if (isset($arrHttp["Tag"])) echo "<input type=hidden name=Tag value=".$arrHttp["Tag"].">\n";?>
</form>