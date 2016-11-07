<?php
    $DirNameLocal=dirname(__FILE__).'/';
    include_once($DirNameLocal."./include.php");

    $site = parse_ini_file($localPath['ini'] . "bvs.ini", true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>
            <?= $site['title']?>
        </title>
        <?php include($DirNameLocal."./head.php"); ?>
    </head>
    <body class="flat-blue">
        <div class="app-container">
            <?php
            include($localPath['html'] . "/bvs.html");
            flush();
            ?>
   <div class="row content-container">
	<!-- Main Content -->
		<div class="container-fluid">           
            
	<div class="card primary">
		<div class="card-body">
    
			<div class="col-md-2"></div>
    

			<div class="col-md-8">    
				<?php include($localPath['html'] . "/metasearch.html"); ?>
			</div>

		<div class="col-md-2"></div>			
	
		</div>
	</div>         

	<div class="row"> <!--form de pesquisa-->
		<div class="col-xs-12">
		</div><!--form de pesquisa-->
	</div>  


<div class="row"><!--conteúdo-->
	<div class="col-md-3 col-sm-12">
		<div class="thumbnail no-margin-bottom">
			<div class="caption">
				<?php
				foreach ($site["col1"] as $id=>$file){
				$html = $localPath['html'] . $file . ".html";
				include($html);
				}
			flush();
				?>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="thumbnail no-margin-bottom">
			<div class="caption">
				<?php
				foreach ($site["col2"] as $id=>$file){
				$html = $localPath['html'] . $file . ".html";
				include($html);
				}
				flush();
				?>
			</div>
		</div>
	</div>



	<div class="col-md-3 col-sm-12">
		<div class="thumbnail no-margin-bottom">
			<div class="caption">
				 <?php
					foreach ($site["col3"] as $id=>$file){
					$html = $localPath['html'] . $file . ".html";
					include($html);
					}
					flush();
				?>
			</div>
		</div>
	</div>

</div>


            <div class="bottom">
                <? include($localPath['html'] . "/responsable.html"); ?>
            </div>
        </div>

 <footer class="app-footer">
	 <div class="wrapper">
            
            BVS Site <?= VERSION ?> &copy; <a href="http://www.bireme.br/" target="_blank">BIREME/OPS/OMS</a>
            <a href="http://validator.w3.org/check?uri=http://<?=$def["SERVERNAME"].$def["DIRECTORY"].$_SERVER["PHP_SELF"]?>" target="w3c"><img src="../image/common/valid-xhtml10.png" alt="Valid XHTML 1.0 Transitional" border="0"/></a>
            <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://<?=$def["SERVERNAME"].$def["DIRECTORY"].$_SERVER["PHP_SELF"]?>" target="w3c"><img src="../image/common/valid-css.png" alt="Valid CSS" border="0"/></a>
	</div>
</footer>
        <? include($DirNameLocal. "./foot.php");  ?>
    </body>
</html>