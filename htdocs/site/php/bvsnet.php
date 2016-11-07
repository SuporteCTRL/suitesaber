<?php
    $DirNameLocal=dirname(__FILE__).'/';
    include_once($DirNameLocal . "./include.php");
    include_once($DirNameLocal . "./common.php");

    $bvsNetUrl = "http://" . $def['SERVICES_SERVER'] ."/bvsnet/";

    $params = array();
    $params['type']    = '/^[0-9]+$/';
    $params['list']    = '/^(countries|subjects)$/';
    $params['country'] = '/^[A-Za-z]+$/';
    $params['network'] = '/^[A-Za-z]+$/';
    $params['status']   = '/^[0-9]+$/';

    $action = "list";
    if(preg_match($params['list'], $_GET['list'],$listType)){
        $action = $listType[1].'List';
    }
    $bvsNetUrl .= $action."?lang=" . $checked["lang"];

    foreach ($params as $param => $format){
        if(isset($_GET[$param]) )
            if(preg_match($format, $_GET[$param]))
                $bvsNetUrl .= '&'.$param.'='.$_GET[$param];
    }

    if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != ""){
        $bvsNetUrl .= "&bvs=".$_SERVER['SERVER_NAME'];
    } else if(isset($def['SERVERNAME']) && $def['SERVERNAME'] != ""){
        $bvsNetUrl .= "&bvs=".$def['SERVERNAME'];
    }
    
    $messages["pt"]["network"] = "Redes";
    $messages["pt"]["connection.fail"] = "N�o foi possivel conectar com a aplica��o. Por favor tente mais tarde!";
    $messages["es"]["network"] = "Redes";
    $messages["es"]["connection.fail"] = "No fue posible conectarse con la aplicaci�n. Por favor intente mas tarde!";
    $messages["en"]["network"] = "Networks";
    $messages["en"]["connection.fail"] = "It was not possible to connect with the application. Please try later!";

    $texts = $messages[$lang];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>
            <?=$title[$item]?>
        </title>
        <? include($DirNameLocal . "./head.php"); ?>
    </head>
    <body>
    <div class="container">
        <div class="level2">
            <? include($localPath['html'] . "/bvs.html"); ?>
            <div class="middle">
                <div id="portal">
                    <h3><span><?=$texts["network"]?></span></h3>
                    <div id="breadCrumb">
                        <a href="../index.php?lang=<?=$lang?>">home</a>&gt;
                        <a href="../php/bvsnet.php?lang=<?=$lang?>"><?=$texts["network"]?></a>
                        <? if ($network != "")  echo "&gt; " .$network;?>
                    </div>
                    <div class="content">
                        <h4><span><? if ($network != "")  echo $network; else echo $texts["network"];?></span></h4>
                        <?
                            $bvsNetList= getDoc($bvsNetUrl);
                            if ($bvsNetList == "[open failure]"){
                                echo "<img src='/image/common/alert.gif'>" . $texts["connection.fail"];
                            }else{
                                echo $bvsNetList;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <? include($DirNameLocal. "./foot.php");  ?>
     </div>   
    </body>
</html>
