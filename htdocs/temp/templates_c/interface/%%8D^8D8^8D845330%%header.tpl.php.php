<?php /* Smarty version 2.6.18, created on 2011-08-15 18:03:17
         compiled from header.tpl.php */ ?>
<head>
	<title><?php echo $this->_tpl_vars['BVS_LANG']['titleApp']; ?>
<?php if ($this->_tpl_vars['listRequest']): ?> :: <?php if ($_GET['m']): ?> <?php echo $this->_tpl_vars['BVS_LANG']['adminDataOf']; ?>
 <?php endif; ?><?php echo $this->_tpl_vars['BVS_LANG'][$this->_tpl_vars['listRequest']]; ?>
<?php endif; ?></title>

	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['BVS_LANG']['metaCharset']; ?>
" />
	<meta http-equiv="Content-Language" content="<?php echo $this->_tpl_vars['BVS_LANG']['metaLanguage']; ?>
" />

	<meta name="robots" content="all" />
	<meta http-equiv="keywords" content="<?php echo $this->_tpl_vars['BVS_LANG']['metaKeywords']; ?>
" />
	<meta http-equiv="description" content="<?php echo $this->_tpl_vars['BVS_LANG']['metaDescription']; ?>
" />
	<!-- Stylesheets -->
	<!--Styles for yui -->
	<style type="text/css">
	<?php echo '
		/* custom styles for this example */
		#dt-pag-nav { margin-bottom:1em; } /* custom pagination UI */
		#yui-history-iframe {
  			position:absolute;
  			top:0; left:0;
  			width:1px; height:1px; /* avoid scrollbars */
  			visibility:hidden;
		}
	'; ?>

	</style>
	<link rel="shortcut icon" href="favicon.ico"/>		
	<link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/datatable/assets/skins/sam/datatable.css" />
    <link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/container/assets/skins/sam/container.css" />
    <link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/menu/assets/skins/sam/menu.css" />
    <link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/button/assets/skins/sam/button.css" />
    <link rel="stylesheet" type="text/css" href="common/plugins/yui/2.5.1/build/calendar/assets/skins/sam/calendar.css" />


	<!-- Stylesheets -->
        <link rel="stylesheet" rev="stylesheet" href="public/css/template.css" type="text/css" media="screen"/>
        <!--[if IE]>
            <link rel="stylesheet" rev="stylesheet" href="public/css/bugfixes_ie.css" type="text/css" media="screen"/>
        <![endif]-->
        <!--[if IE 6]>
            <link rel="stylesheet" rev="stylesheet" href="public/css/bugfixes_ie6.css" type="text/css" media="screen"/>
   <![endif]-->
	
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/datasource/datasource-beta-min.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/connection/connection-min.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/json/json-min.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/history/history-min.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/element/element-beta-min.js"></script>
	<script type="text/javascript" src="common/plugins/yui/2.5.1/build/datatable/datatable-beta-min.js"></script>
	
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/utilities/utilities.js"></script>
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/container/container_core-min.js"></script>
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/container/container-min.js"></script>
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/menu/menu-min.js"></script>
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/button/button-min.js"></script>
    <script type="text/javascript" src="common/plugins/yui/2.5.1/build/calendar/calendar-min.js"></script>


    <script language="JavaScript" type="text/javascript" src="public/js/validation.js"></script>
	<script language="JavaScript" type="text/javascript" src="public/js/functions.js"></script>
	<script language="JavaScript" type="text/javascript" src="public/js/md5.js"></script>


</head>