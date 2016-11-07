<?php
	require("auth_check.php");
	require("../php/include.php");
	
	auth_check_login();

	$cgiList[] = "xml=xml/" . $lang . "/adm.xml";
	$cgiList[] = "xsl=xsl/adm/menu.xsl";
	$cgiList[] = "lang=" . $checked['lang'];
	$cgiText = join("&",$cgiList);

	$href = $def['DIRECTORY'] . "php/xmlRoot.php?" . $cgiText;
?>

<html>
<head>
	<title>BVS-Site Admin</title>
</head>
<!-- frames -->
<frameset rows="100%,*">
    <frame name="frameAdm"    src="<?=$href?>" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0">
    <frame name="frameHidden" src="<?=$def['DIRECTORY']?>admin/frameHidden.php" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
</frameset>
</body>
</html>

