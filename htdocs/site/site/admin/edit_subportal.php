<?php
require_once("../php/include.php");
require_once("auth_check.php");

auth_check_login();

$xml = simplexml_load_file( DEFAULT_DATA_PATH . 'xml/subportals.xml');
$items = count($xml->item);

$messageArray = array (
"es" =>
    array (
        "title" => "Administraci�n: Biblioteca Virtual en Salud",
        "add" => "A�adir",
        "exit" => "Salir",
        "remove" => "Borrar",
        "rename" => "Renomear",
        "selected" => "Selecionado",
        "subportal" => "Subportal",
        "subportals" => "Subportais",
        "subportals list" => "Subportals list",
    ),
"pt" =>
    array (
        "title" => "Administra��o: Biblioteca Virtual em Sa�de",
        "add" => "Adicionar",
        "exit" => "Sair",
        "remove" => "Remover",
        "rename" => "Renomear",
        "selected" => "Selecionado",
        "subportal" => "Subportal",
        "subportals" => "Subportais",
        "subportals list" => "Lista de subportais",
    ),
"en" =>
    array (
        "title" => "Administration: Virtual Health Library",
        "add" => "Add",
        "exit" => "Exit",
        "remove" => "Remove",
        "rename" => "Rename",
        "selected" => "Selected",
        "subportal" => "Subportal",
        "subportals" => "Subportals",
        "subportals list" => "Subportals list",
    ),
);
$message = $messageArray[$lang];

?>
<html>
    <head>
        <meta http-equiv="Expires" content="-1"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
        <title><?=$message['title']?></title>
        <link rel="stylesheet" href="../css/admin/adm.css" type="text/css"/>

        <style type="text/css">
            button {
                padding: 0 5px;
            }
        </style>
    </head>
    <body>
        <span class="identification">
            <center><?=$message['title']?></center>
        </span>
        <hr size="1" noshade=""/>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" class="bar">
            <tr valign="top">
                <td align="left" valign="middle"><?=$message['subportals']?></td>
                <td align="right" valign="middle">
                    <a href="../php/xmlRoot.php?xml=xml/<?=$lang?>/adm.xml&xsl=xsl/adm/menu.xsl&lang=<?=$lang?>" target="_top"><?=$message["exit"]?></a>
                </td>
            </tr>
        </table>
        <hr size="1" noshade=""/>
        <br/>

        <form name="newSubportal" action="manage_subportal.php" method="POST">
            <input type="hidden" name="lang" value="<?=$checked['lang']?>"/>
            <table width="100%" class="tree-edit">
                <tr valign="top">
                    <td>
                        <br/>
                        <ul>
                            <li><?=$message['add'].' '.$message['subportal']?><br/>
                                <input type="text" name="addname" style="width:250px" id="newsubportal"/>
                                <button type="submit" name="action" value="add">+</button>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr valign="middle">
                    <td>
                        <br/>
                        <ul>
                            <li><?=$message['subportals list']?><br/>
                                <select name="subportal" size="15" style="width:325px">
                                    <?for ($i = 0; $i < $items; $i++){?>
                                    <option value="<?=(String) $xml->item[$i]['id']?>">
                                        <?= utf8_decode( (String) $xml->item[$i] )?>
                                    </option>
                                    <?}?>
                                </select>
                            </li>
                            <li>
                                <span style="width:11em; display:block; float:left"><?=$message['rename']?> <?=$message['selected']?></span>
                                <input type="text" name="rename" value="" id="rensubportal"/>
                                <button type="submit" name="action" value="ren">ok</button>
                            </li>
                            <li>
                                <span style="width: 11em; display:block;float:left"><?=$message['remove']?> <?=$message['selected']?></span>
                                <button type="submit" name="action" value="del">-</button>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
