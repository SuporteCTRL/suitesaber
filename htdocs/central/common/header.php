<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Suíte Saber<?php if (isset($subtitle))  echo $subtitle?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<meta http-equiv="Expires" content="-1" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="icon" type="image/png" href="favicon.png" />

		<style>
			#loading {
			   width: 100%;
			   height: 100%;
			   top: 0px;
			   left: 0px;
			   position: fixed;
			   display: none;
			   opacity: 0.7;
			   background-color: #fff;
			   z-index: 99;
			   text-align: center;
			}

			#loading-image {
			  position: absolute;
			  top:50%;
     		  left:50%;
     		  margin:-100px 0 0 -150px;
			  z-index: 100;
			}
		</style>
<?php
	if ((isset($cisis_ver) and $cisis_ver=="unicode/") or (isset($UNICODE) and $UNICODE=="Y")){
	echo $UNICODE;
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	}else{
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />";
	}
	if (!isset($css_name))
		$css_name= "";
	else
		$css_name.="";
?>
		<meta name="robots" CONTENT="NONE" />
		<meta http-equiv="keywords" content="" />
		<meta http-equiv="description" content="" />
	

    <!-- Bootstrap -->
    <link href="../css/<?php echo $css_name?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
   <script src="https://use.fontawesome.com/4c37ce0a9e.js"></script>
    <link href="../css/<?php echo $css_name?>/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../css/<?php echo $css_name?>/css/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../css/<?php echo $css_name?>/css/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../css/<?php echo $css_name?>/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../css/<?php echo $css_name?>/css/jqvmap.min.css" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="../css/<?php echo $css_name?>/css/custom.css" rel="stylesheet">


		<!--Font Awesome-->
    <script src="https://use.fontawesome.com/4c37ce0a9e.js"></script>

		<!-- Stylesheets -->
	<!--	<link rel="stylesheet" rev="stylesheet" href="../css/<?php echo $css_name?>/template.css?<?php echo time(); ?>" type="text/css" media="screen"/> -->
		<!--[if IE]>
			<link rel="stylesheet" rev="stylesheet" href="../css/bugfixes_ie.css" type="text/css" media="screen"/>
		<![endif]-->
		<!--[if IE 6]>
			<link rel="stylesheet" rev="stylesheet" href="../css/bugfixes_ie6.css" type="text/css" media="screen"/>
		<![endif]-->

</head>