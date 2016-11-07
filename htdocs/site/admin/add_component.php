<?
require_once("../php/include.php");
require_once("../php/common.php");
require_once("../php/xmlRoot_functions.php");
require_once("auth_check.php");

session_start();

if ($_SESSION['auth_id'] != "BVS@BIREME") {
    ?>
    <script language="JavaScript">
        top.opener.location.href = "http://<?= SERVERNAME . DIRECTORY?>admin/index.php?lang=<?=$lang?>&timeout=session";
        this.window.close();
    </script>
    <?
    die();
}

$id = $_REQUEST["id"];
$lang = $_REQUEST["lang"];
$type = $_REQUEST["type"];
$fileXml  = $localPath["xml"] . $id . ".xml";

$messageArray = array (
    "es" =>
        array (
            "title" => "Administraci�n: Biblioteca Virtual en Salud",
            "exist" => "Error de sistema. El archivo del componente ya existe: ",
            "fail"  => "Error de sistema. No fue posible grabar en el archivo: ",
            "param" => "Error de sistema. Parametros insuficientes.",
            "decs_connection" => "No fue posible conectarse al servicio del decs para generar automaticamente la lista de categorias. Ud. puede intentar crear nuevamente el componente  o llenar manualmente las categorias del decs.",
            "decs_wait" => "Aguarde ... intentando conectar con el servidor decs",
        ),
    "pt" =>
        array (
            "title" => "Administra��o: Biblioteca Virtual em Sa�de",
            "exist" => "Erro de sistema. O arquivo do componente j� existe: ",
            "fail"  => "Erro de sistema. N�o foi possivel gravar no arquivo:",
            "param" => "Erro de sistema. Parametros insuficientes.",
            "decs_connection" => "N�o foi possivel conectar-se com o servi�o decs para gerar automaticamente a lista de categorias. Voc� pode tentar criar novamente o componente mais tarde ou incluir manualmente as categorias.",
            "decs_wait" => "Aguarde ... tentando conectar com servidor decs",
        ),
    "en" =>
        array (
            "title" => "Administration: Virtual Health Library",
            "exist" => "System error. File already exist: ",
            "fail"  => "System error. Unable to save file: ",
            "param" => "System error. Missing parameters.",
            "decs_connection" => "It was not possible to connect with the service decs in order to generate automatically the list of categories. You can try to create again the component later or include manually the categories.",
            "decs_wait" => "Wait ... trying to connect with decs server",
        ),
    );
$messages = $messageArray[$lang];
$error = "";
$warning = "";

if ($id == "" || $lang == "" || $type == ""){
    $error = $messages["param"] . " id=" . $id . " lang=" . $lang . " type=" . $type;
}else{
    if ( is_file($fileXml) ){
        $error = $messages["exist"] . $fileXml;
    }else{
        $fp = fopen ($fileXml, "a+b");
        chmod($fileXml,0764);

        $content = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
        $content.= "<" . $type . " lang=\"" . $lang . "\" available=\"yes\">";
        if ($type == "decs"){


            $xml = "http://decs.ws.bvsalud.org/main.php";
            $xsl = $def["SITE_PATH"] . "xsl/adm/xml-decs.xsl";

            $decsTree = processTransformation($xml,$xsl);

            if ( $decsTree == '[open failure]' ){
                $warning = $messages["decs_connection"];
            }else{
                $content .= $decsTree;
            }
        }
        $content .= "</" . $type . ">";
           if ( !fwrite($fp, $content) ){
            $error = $messages["fail"] . $fileXml;
        }
    }
}

?>
<html>
    <head>
        <title><?=$messages["title"]?></title>
        <link rel="stylesheet" href="../css/public/components-pt.css" type="text/css" media="screen"/>
        <style>
            .confirm { margin: 10px; padding: 10px; background-color: #ddffdd;}
        </style>
        <script language="JavaScript" src="../js/tree-edit.js"></script>

        <script language="JavaScript">
            var listValues = opener.listValues;

            function removeFromList() {
                listValues[<?=$id?>] = null;
                listValues.length--;
                opener.document.formPage.tree.length--;
                top.close();
            }

            function initialCheck(){
                <? if ( $error != '' || $warning != '' ) {?>
                    if (parseInt(navigator.appVersion)>3){
                          top.resizeTo(360,165);
                        top.moveTo(350,100);
                    }
                <? } else {?>
                    top.close();
                <? } ?>
            }
        </script>

    </head>
    <body onload="javascript:initialCheck();">
        <div class="confirm">
            <? if ($error != '') {?>
                <b><?=$error?></b>
                <div align="center">
                    <input type="button" value="cancelar"  onclick="javascript:removeFromList();" class="submit"/>
                </div>
            <? }else{ ?>
                <b><?=$warning?></b>
                <div align="center">
                    <input type="button" value="OK"  onclick="javascript:top.close();" class="submit"/>
                </div>
            <? } ?>
        </div>
    </body>
</html>