<?php

function trans($text){
    global $label;

    if(isset($label[$text])) {
        return $label[$text];
    } else if( $text != "") {
        $text = str_replace('_',' ',ucfirst(strtolower($text)));
    }
    return ucfirst(strtolower($text));
}

function loadLabels($xmlpath){
    $label = array();

    $sxe = simplexml_load_file($xmlpath);
    $labelEls = $sxe->xpath("admin/text");

    foreach ($labelEls as $l) {
        $label[ (String) $l['id'] ] = (String) $l;
    }

    return $label;
}

function loadLangs($xmlpath){
    $dirs = array();

    // broken functions: is_dir, scandir, readdir
    
    return array('es','en','pt');
}

function checkSystem(){
    global $messagem, $def;

    $language = array("pt","es","en");
    $diretory = array("xml","html", "ini");

    if ( !is_dir( $def['SITE_PATH'] ) ){
        $cause = $message["invalid_dir"] . $def['SITE_PATH'];
        printError($cause, $message["check_def"]);

        return false;
    }

    foreach ($language as $lang){

        foreach ($diretory as $dir){
            $dir_path = $def['DATABASE_PATH'] . $dir . "/" . $lang . "/";
            if ( !is_writeable( $dir_path ) ) {
                $cause = $message["invalid_perm"] . $dir_path;
                printError($cause, $message["check_perm"]);

                return false;
            }
        }
    }
    return true;
}
?>
