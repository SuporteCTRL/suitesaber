<?php
/*
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation in any version
 of the License.
 This program is non professional and distributed in the hope that it will be
 useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 =============================================================================
 Application  : WEB SERVER PHP DIRECTORY TREEVIEW FILE MANAGER/EXPLORER V.1.0
 name Program : dirtree.php, auxiliary sample calling program inidirtree.php
 Author       : Aitor Solozabal Merino (Spain)
 Email        : aitor-3@euskalnet.net
 Date         : 25-02-2005
 type         : php program utility ( all in one )
 Description  : A file manager and directory treeview from any server path
              : (hidden) outside or inside the Web Server Root, this path
              : become the root for the treeview with features like
              : File Filter Criteria, Login Users, Download file, Upload file
              : Make dir, Remove dir, Rename dir, Erase file, Rename File.
              : The treeview include the visualization of the total number of
              : subdirs, files and bytes under every node displayed checking
              : the current File Filter Extensions Criteria
              : Developed & Tested in WAMP enviroment
              : (Windows XP SP2, Apache 1, Mysql 4, Php 4)
 Installation : Put this programs in a subfolder of the www of your web server
              : document root directory
              : Can be executed alone or with a calling external program
              : (see "how" with Sample inidirtree.php)
 Login User   : with specific privileges
              : loginuser="username" and password="userpassword"
              : privileges = restricted
              : loginuser="administrator" and password="adminpassword"
              : privileges = all
 =============================================================================
                               W A R N I N G S
 Due to storing the full information of the tree directory in SUPERGLOBAL
 $_SESSION arrays, the perfomance slow down when the number of nodes in the
 directory treeview grows up - is acceptable up to 3.000 nodes (files)
 The purpose is to manage a SUBDIR of a user, a project, a sharing zone, etc,
                  !!!!!! NOT THE FULL HARD DISK  ¡¡¡¡¡¡¡¡
 The LOGIN function is not professional, you must change it to accomodate to
 your data base of users
 =============================================================================
 The server path can be indicated inside php code or captured with html form
 and passed thru a SUPERGLOBAL variable $_POST or $_SESSION with a calling
 external program (see "how" with Sample inidirtree.php)
 Samples      : $_POST['Server_Path']= "c:\x-files\top secret\rockwell"
              : $_SESSION['Server_Path']= "c:\appserv\www\ftpzone"
 This path will be the ROOT for the treeview
 =============================================================================

                           Features of the 1.0 version:

 Treeview directory - showing number of subfolders, files etc under every node
 File filter extensions criteria - choose files to display and find them quickly
 Download file - open or save at the client side (with or without compresssion)
 Upload file - it does not rewrite an existing file in the server as a safe mode
 Erase file - with user confirmation
 Rename file - with user confirmation
 Make directory - with user confirmation
 Remove directory - with user confirmation. removes any subdir and file under
 Rename directory - with user confirmation
 Refresh process - to see changes made by other users
 5 modes of file size visualization (bytes, kilobytes, megabytes, lines and % )
 Turn a selected subdir into the treeview root
 Compress a selected file
 List contents of a selected compressed file
 Email a selected file - (with previous compression or not)
 Previously to use this function the SMTP parameter and  the sendmail_From
 parameter in the PHP.INI file must be set acordingly to your ISP smtp server.
 (your_ip_server could be anything). See a sample extracted from PHP.INI:
 [mail function]
 ; For Win32 only.
 SMTP = smtp.your_ip_server.com
 smtp_port = 25
 ; For Win32 only.
 sendmail_from = address@your_ip_server.com

 ------------------------------------------------------------------------------
                                T O  D O   L I S T
 ------------------------------------------------------------------------------
 1.- Full error checking to preserve and protect hidden real directory names
 2.- Rearrange font sizes conforming the display screen resolution of the client
     Variables $_SESSION['widht'] and $_SESSION['Height'] have the data pixels
 3.- Aesthetic and professional improvements in tables, forms & backgrounds(CSS)
 4.- Manage a virtual directory from a MySql database table with a SQL query
 5.- Copy or move files between subdirs in the server
 6.- Convert the full program in smaller units for a less consumption of memory
 ==============================================================================
                     FLOW DIAGRAM OF THE PROGRAM STRUCTURE
                             +-------------+
                             |    OTHER    |
      SAMPLE: INIDIRTREE.PHP |   CALLING   |
                             |   PROGRAM   |
                             +------+------+
                                    |
                                    V
                             +------+------+
+--------------------------->| DIRTREEVIEW |<----------------------------------+
| +------------------------->|   P  H  P   |                                   |
| |         +--------------->|   UTILITY   |<-------------------------------+  |
| |         |                +------+------+                                |  |
| |         |                       V                                       |  |
| |         |                  +----+----+                                  |  |
| |         |                  |  START  |                                  |  |
| |         |                  | SESSION |                                  |  |
| |         |                  +----+----+                                  |  |
| |         |                       V           +-----------------+ METHOD  |  |
| |         |                +------+------+ NO | "administrator" | "POST"  |  |
| |         |                | USERname ?  +--->+                 +-------->+  |
| |         |                +------+------+    | "adminpassword" |            |
| |         |                   YES V           +--------+--------+            |
| |         |             +---------+-------+     NO     |                     |
| |         |             |IS A VALID USER? +----------->+                     |
| |         |             +---------+-------+            |                     |
| |         |                   YES V                    |                     |
| |         |        +--------------+-------------+  NO  |                     |
| |         |        |IS A SESSION "AUTENTIFIED"? +----->+                     |
| |         |        +-----------------------+----+                            |
| |METHOD   | METHOD                         V YES                             |
| |"POST"   | "POST"                   +-----+------------------+              |
| |         |                      YES |   NO ACTIONS DEFINED   |              |
| |         |                     +----+          OR            |              |
| |         |                     |    |     "POST" ACTIONS     |              |
| |         |                     V    +-------------------+----+              |
| |    +----+--------+ YES +------+----------------+       |                   |
| |    + SELECT PATH +<----+IS EMPTY SERVER PATH ? |       |                   |
| |    +-------------+     +---------+-------------+       |                   |
| |          form                    V  NO                 |                   |
| |                        +---------+----------+          |                   |
| |                        |BUILD TREE STRUCTURE|          |NO                 |
| |          formS         +---------+----------+          |                   |
| |    +----------------+            V                     |                   |
| |  +-+ MAKE DIRECTORY +<-+         +-------------------->+                   |
| |  | +----------------+  |                               |                   |
| |  +-+REMOVE DIRECTORY+<-+                               |                   |
| |  | +----------------+  +  +--------------+             |                   |
| +<-+-+REname DIRECTORY+<-+<-+ DIR FUNCTIONS+<-+          |                   |
| |  | +----------------+  |  +--------------+  |          |                   |
| |  +-+BECAME TREE ROOT+<-+                    |          |                   |
| |  | +----------------+  |                    |          |            METHOD |
| |  +-+  UPLOAD FILE   +<-+                    |          V             "GET" |
| |    +----------------+                       |   +------+--------+          |
| |                                             +<--+ "GET" ACTIONS |          |
| |    +----------------+                       |   +------+--------+          |
| |  +-+  DOWNLOAD FILE +<-+                    |          |                   |
| |  | +----------------+  |                    |          |                   |
| |  +-+  REname FILE   +<-+                    |          |NO                 |
| |  | +----------------+  |  +--------------+  |          |                   |
| +<-+-+  ERASE FILE    +<-+<-+FILE FUNCTIONS+<-+          |                   |
| |  | +----------------+  |  +--------------+  |          |                   |
| |  +-+  EMAIL FILE    +<-+                    |          |                   |
| |  | +----------------+  |                    |          |                   |
| |  +-+  COMPRESS FILE +<-+                    |          |                   |
| |    +----------------+                       |          V                   |
| |                                             |  +-------+-------+           |
| |    +----------------+                       |  |    ACTIONS    |           |
| |  +-+  FILE FILTER   +<-+                    |  |EXPAND/COLLAPSE|           |
| |  | +----------------+  +  +--------------+  |  |  FULL EXPAND  |           |
| +<-+-+REFRESH TREEVIEW+<-+<-+ OTHER ACTIONS+<-+  +---+-----------+           |
|    | +----------------+  |  +--------------+         |                       |
|    +-+FILESIZE DISPLAY+<-+                           V                       |
|      +----------------+                  +-----------+-----+                 |
|                                          |DISPLAY TREEVIEW |                 |
|METHOD                                    +--------+--------+                 |
|"POST"                                             |                          |
|                                                   V                          |
|           form                                /---+---\                      |
|    +------------------+                      /  FINAL  \      +----------+   |
|    |   L O G O U T    |                     /  RESULT   \     |USER CLICK|   |
+<---+                  +<-------------------+   W  E  B   +--->+ON ACTIONS+-->+
     | SESSION DESTROY  |                     \  H T M L  /     |METHOD GET|
     +------------------+                      \ P A G E /      +----------+
                                                \-------/
*/
session_start();

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^E_STRICT);
$arrHttp=Array();
include("../common/get_post.php");
//foreach ($arrHttp as $var=>$value)  echo "$var=$value<br>";  //die;

if (strpos($arrHttp["activa"],"|")===false){
    $db=$arrHttp["activa"]."**";
}   else{
		$ix=strpos($arrHttp["activa"],'^b');
		$db=substr($arrHttp["activa"],2,$ix-2);
}

if (isset($arrHttp["base"])) $_SESSION["dir_base"]=trim($arrHttp["base"]);
if ((isset($_SESSION["permiso"]["CENTRAL_ALL"]) or
    isset($_SESSION["permiso"]["CENTRAL_EXDBDIR"]) or
    isset($_SESSION["permiso"][$db."_CENTRAL_EXDBDIR"])) or
    (isset($_SESSION["permiso"][$_SESSION["dir_base"]."_CENTRAL_ALL"]) or
    isset($_SESSION["permiso"][$_SESSION["dir_base"]."CENTRAL_EXDBDIR"]))
    )
{
}else{
	header("Location: ../common/error_page.php") ;
}
if (isset($arrHttp["base"]))
	$_SESSION["dir_base"]=trim($arrHttp["base"]);
else
	unset($_SESSION["dir_base"]);
include("../config.php");
$_POST["Server_Path"]=$db_path;
$_POST["Server_Path"].=trim($_SESSION["dir_base"]);
$_SESSION["Server_Path"]=$_POST["Server_Path"];
$_SESSION['Upload_Extension']="*.png,*.gif,*.jpg,*.pdf,*.xrf,*.mst,*.n01,*.n02,*.l01,*.l02,*.cnt,*.ifp,*.fmt,*.fdt,*.pft,*.fst,*.tab,*.txt,*.par,*.html,*.zip,*.iso";
$_POST['FILE_EXTENSION']="*.dat,*.def,*.iso,*.png,*.gif,*.jpg,*.pdf,*.xrf,*.mst,*.n01,*.n02,*.l01,*.l02,*.cnt,*.ifp,*.fmt,*.fdt,*.pft,*.fst,*.tab,*.txt,*.par,*.html,*.zip,";
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>"; die;
$_POST['File_Extension']=$_POST['FILE_EXTENSION'];
include("../lang/dbadmin.php");
include("../lang/soporte.php");
$lang=$_SESSION["lang"];
$_POST['username']=$_SESSION["login"];
$_POST['password']=$_SESSION["password"];
$_SESSION['autentified']=true;
global $encabezado;
$encabezado="";
if ($_REQUEST["ACTION"]!="downloadfile1"){
	include("../common/header.php");
	if (isset($arrHttp["encabezado"])){
		$encabezado="&encabezado=s";
	}
	EncabezadoPagina();
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/".$css_name."template.css\">";
}

function EncabezadoPagina(){
global $arrHttp,$msgstr,$institution_name,$logo;
	echo "<Body>";
    //if (isset($arrHttp["encabezado"]))
    	include("../common/institutional_info.php");
     echo "
		<div class=\"sectionInfo\">
		<div class=\"breadcrumb\">".
				$msgstr["expbases"]."
		</div>
		<div class=\"actions\">\n";
	//if (isset($arrHttp["encabezado"]))
			if (isset($_REQUEST["retorno"]))
				$retorno="../common/inicio.php?reinicio=s";
			else
				$retorno="menu_mantenimiento.php?base=".$_REQUEST["activa"];
			

	 echo "
	 	<div class=\"helper\">
	<a href=../documentacion/ayuda.php?help=".$_SESSION["lang"]."/dirtree.html target=_blank>".$msgstr["help"]."</a>
    ";
    echo "<font color=white> Script: dirtree.php</font></div>";

     echo "<div class=\"middle form\">
			<div class=\"formContent\">";
}

Function BUILD_DIRECTORY_TREE($SOURCE, $PREVIOUS) {
/*
This code build the structure of the tree directory processing the output of the
dir PHP command in a recursive way, using the current file filter criteria and
storing the values in a set of arrays that must be shared among the rest of the
functions in the programs.
The first time call variable $SOURCE is the path choosen to be shown in a tree
the variable $_SESSION['Numfile'] the value of -1 and the variable $PREVIOUS=0.
*/
    $_SESSION['Numfile']++; //the first time from -1 to numfile=0
    $_SESSION['Folder_name'] [$_SESSION['Numfile']] = BASEname($SOURCE);
    $_SESSION['Father'] [$_SESSION['Numfile']] = $PREVIOUS;
    $_SESSION['Numbytes'][$_SESSION['Numfile']] = 0;
    $_SESSION['File_Date'][$_SESSION['Numfile']] = "";
    $_SESSION['Children_Files'] [$_SESSION['Numfile']] = 0;
    $_SESSION['Children_Subdirs'][$_SESSION['Numfile']] = 0;
    $_SESSION['Level_Tree'][$_SESSION['Numfile']] = SUBSTR_COUNT($SOURCE, DIRECTORY_SEPARATOR) - $_SESSION['Levels_Fixed_Path'];
    $_SESSION['Folder_type'] [$_SESSION['Numfile']] = "Folder";
    $_SESSION['Opened_Folder'][$_SESSION['Numfile']] = 0;
    if (IS_FILE($SOURCE)) {
        $_SESSION['Folder_type'] [$_SESSION['Numfile']] = "File";
        $_SESSION['Numbytes'][$_SESSION['Numfile']] = FILESIZE($SOURCE);
        $_SESSION['File_Date'][$_SESSION['Numfile']] = FILEMTIME($SOURCE);
        return; //finish this branch and go up one level the recursion process
    }
    // ok is a directory
    $PREVIOUS = $_SESSION['Numfile'];
    // reading source path
    $DIR = DIR($SOURCE);
    While (false !== $ENTRY = $DIR->READ()) {
        if (($ENTRY != '.') && ($ENTRY != '..')) {
            // directory has children
            $NEW_DIR = $SOURCE . DIRECTORY_SEPARATOR . $ENTRY;
            if (IS_DIR($NEW_DIR) || (IS_FILE($NEW_DIR) && IS_FILE_TO_DISPLAY($ENTRY))) {
                // go down one level the recursion process
                BUILD_DIRECTORY_TREE($NEW_DIR, $PREVIOUS);
            }
        }
    }
    $DIR->CLOSE();
    return; //finish this branch
} //end funcion

Function IS_FILE_TO_DISPLAY($name) {
/*
This function check the file with the current file filter criteria to decide if
the file is displayable or not.
*/
    if ($_SESSION['File_Extension'] == "") return true;
    $EXT = "*" . STRTOUPPER(STRRCHR ($name, ".")) . ",";
    return STRPOS(" " . $_SESSION['File_Extension'], $EXT);
} //end function

Function DIR_SIZES() {
/*
When the structure of the tree directory has been built this function calculate
the amount of files and bytes in every folder of the structure.
*/
    $_SESSION['Maxfilesize']=0;
    $_SESSION['Maxfoldersize']=0;
    For ($I = 1;$I <= $_SESSION['Numfile'];$I++) {
        if ($_SESSION['Folder_type'] [$I] == "File") {
            if ($_SESSION['Father'] [$I] != 0) {
                $BACK = $I;
                While ($_SESSION['Father'] [$BACK] != 0) {
                    $_SESSION['Numbytes'][$_SESSION['Father'] [$BACK]] = $_SESSION['Numbytes'][$_SESSION['Father'] [$BACK]] + $_SESSION['Numbytes'][$I];
                    if ($_SESSION['Numbytes'][$_SESSION['Father'] [$BACK]] > $_SESSION['Maxfoldersize']) {
                        $_SESSION['Maxfoldersize'] = $_SESSION['Numbytes'][$_SESSION['Father'] [$BACK]];
                    }
                    $_SESSION['Children_Files'] [$_SESSION['Father'] [$BACK]] = $_SESSION['Children_Files'] [$_SESSION['Father'] [$BACK]] + 1;
                    $BACK = $_SESSION['Father'] [$BACK];
                }
            }
            $_SESSION['Children_Files'] [0]++;
            $_SESSION['Numbytes'][0] = $_SESSION['Numbytes'][0] + $_SESSION['Numbytes'][$I];
            if ($_SESSION['Numbytes'][$I] > $_SESSION['Maxfilesize']) {
                $_SESSION['Maxfilesize'] = $_SESSION['Numbytes'][$I];
            }
        } else { // is a directory

            if ($_SESSION['Father'] [$I] != 0) {
                $BACK = $I;
                While ($_SESSION['Father'] [$BACK] != 0) {
                    $_SESSION['Children_Subdirs'][$_SESSION['Father'] [$BACK]] = $_SESSION['Children_Subdirs'][$_SESSION['Father'] [$BACK]] + 1;
                    $BACK = $_SESSION['Father'] [$BACK];
                }
            }
            $_SESSION['Children_Subdirs'][0]++;
        }
        if (Empty($_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$I]])) {
            $_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$I]] = $I;
        } else {
            if ($_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$I]] < $I) {
                $_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$I]] = $I;
            }
        }
    }
} //end function

Function UPLOAD ($NODE) {
global $encabezado;
/*
This is the first phase of the upload file process in this phase the file to
upload is chosen in the client computer with a form and sent to be processed in
the second phase.
*/
    Global $ACTION;
    $ACTION = "upload2";
    $UPLOAD_DIR = BUILD_PATH($NODE);
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "UPLOAD FILE", "", "");
    ?>
    <table widht="100%" border="0" cellpading="0" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
    <?php
    // check if the directory exists or not with the server path added
    $REAL_UPLOAD_DIR = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $UPLOAD_DIR;
    if (!IS_DIR($REAL_UPLOAD_DIR)) {
        Echo "<h3>The directory where to upload doesn't exist, please verify.. </h3></font></center></td></tr>";
        Echo "<tr><td><a href=" . $_SERVER['PHP_SELF'] . "?action=expand&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>   Cancel   </a></td></tr>";
        Echo "</table>";
    } else {
        // check if the directory is writable.
        if (!IS_WRITEABLE($REAL_UPLOAD_DIR)) {
            Echo "<h3>The directory where to upload is NOT writable, please put the write attribute permissions on </h3></font></center></td></tr>";
            Echo "<TR><TD><a href=" . $_SERVER['PHP_SELF'] . "?action=expand&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>   Cancel   </a></td></tr>";
            Echo "</table>";
        } else {
            Echo"<h3>Directorio actual:" . $UPLOAD_DIR . "</h3></font></center></td></tr>";
    ?>
        <tr>
            <td>
                UPLOAD File Extensions allowed:
    <?php

            if ($_SESSION['Upload_Extension'] == "") {
                Echo "*.*";
            } else {
                Echo $_SESSION['Upload_Extension'];
            }
    ?>

            </td>
        </tr>
            <tr><td><h3>Maximo tamaño de fichero=<?= $_SESSION['Size_Bytes'];?></h3></td></tr>
        <tr>
            <td>
            
            <font face="tahoma">
            <h3> Choose a the file to upload</h3>
            <form name="formUploadFile" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <table align="center" border="0" class="td">
                    <input type="hidden" name="MAX_FILE_Svalue=<?php echo $_SESSION['Size_Bytes'];?>">
                    <tr>
                        <td></td><td valign="baseline"><input type="file" name="filetoupload" size="50"></td>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                    <input type="hidden" name="ACTION" value="upload2">
                    <tr></tr>
                    <tr>
                        <td><input type="button" value="cancel" onclick='javascript:history.back()'></td>
                        <td></td>
                        <td><input type="submit" name="submit" value=""></td>
                    </tr>
                    <tr>
                        <td><script> Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                    </tr>
                </table>
            </form>
            </font>
            </center>
            </td>
        </tr>
   </table>
   <?php
        }
    }
    PAGE_FOOTER("", "");
    exit;
} //end function

Function UPLOAD2($NODE) {
/*
This is the second phase of the upload file process in this phase all the
process is checked against errors.
if the things go well the process is terminated with the move of the uploaded
file to the directory chosen.
if the things go bad the process is aborted and the uploaded file is erased.
*/
    Global $ACTION;
    $UPLOAD_DIR = BUILD_PATH($NODE);
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "UPLOAD FILE - CKECK PHASE", "", "");
    ?>
   <table widht="100%"" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                <font face="tahoma">
   <?php
    $ERROR_FUNCTION = false;
    // File Size in bytes (change this value to fit your need)
    echo "<h3>Biggest File Size=" . $_SESSION['Size_Bytes'] . "</h3></font></td></tr>";
    echo "<h3>Upload Dir=" . $UPLOAD_DIR . "</h3></font></td></tr>";
    if ($_SESSION['File_Extension'] != "") {
        echo "<tr><td>Extensions allowed= " . $_SESSION['File_Extension'] . "</td></tr>";
    }
    // check if the directory exists or not with the server path added
    $REAL_UPLOAD_DIR = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $UPLOAD_DIR;
    if (!IS_DIR($REAL_UPLOAD_DIR)) {
        echo "<tr><td><h3>The directory where to upload doesn't exist, please verify.. </h3></td></tr>";
        $ERROR_FUNCTION = true;
    } else {
        // check if the directory is writable.
        if (!IS_WRITEABLE($REAL_UPLOAD_DIR)) {
            echo "<tr><td><h3>The directory where to upload is NOT writable, please put the write attribute permissions on </h3></td></tr>";
            $ERROR_FUNCTION = true;
        } else {
            // check if no file selected.
            if (!IS_UPLOADED_FILE($_FILES['filetoupload']['tmp_name'])) {
                echo "<tr><td><h3>Error: Please select a file to upload!. </h3></td></tr>";
                $ERROR_FUNCTION = true;
            } else {
                // Get the Size of the File
                $SIZE = $_FILES['filetoupload']['size'];
                // Make sure that file size is correct
                if ($SIZE > $_SESSION['Size_Bytes']) {
                    $KB = $_SESSION['Size_Bytes'] / 1024;
                    echo "<tr><td><h3>File Too Large. File must BE LESS THAN <b>$KB</b> KB. </h3></td></tr>";
                    $ERROR_FUNCTION = true;
                } else {
                    // check file extension
                    if (($_SESSION['File_Extension'] != "") && (!IS_FILE_TO_DISPLAY($_FILES['filetoupload']['name']) != false)) {
                        echo "<tr><td><h3>***Wrong file extension. </h3></td></tr>";
                        $ERROR_FUNCTION = true;
                    } else {
                        // $Filename will hold the value of the file name submitted from the form.
                        $FILEname = $_FILES['filetoupload']['name'];
                        // Check if file is Already EXISTS.
                        if (File_Exists($REAL_UPLOAD_DIR . DIRECTORY_SEPARATOR . $FILEname)) {
                            echo "<tr><td><h3>Sorry but the file named <b>" . $FILEname . "</b> already exists in the server, please change the filename before UPLOAD</h3></td></tr>";
                            $ERROR_FUNCTION = true;
                        } else {
                            // Move the File to the Directory choosen + the server path determined
                            // move_uploaded_file('filename','destination') Moves afile to a new location.
                            if (!MOVE_UPLOADED_FILE($_FILES['filetoupload']['tmp_name'], $REAL_UPLOAD_DIR . DIRECTORY_SEPARATOR . $FILEname)) {
                                // Print error msg.
                                echo "<tr><td><h3>There was a problem moving your file. </h3></td></tr>";
                                $ERROR_FUNCTION = true;
                            } else {
                                // tell the user that the file has been uploaded.
                                echo "<tr><td><h3>File SUCCESFULLY uploaded! </h3></td></tr>";
                            }
                        }
                    }
                }
            }
        }
    }
    ?>
       <tr>
            <td>
            
            <font face="tahoma">
           <form name="formUploadFile2" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <table align="center" border="0" class="td">
                    <tr>
                        <input type="hidden" name="FILE_EXTENSION" value="<?= $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="upload3">
	<?php
    if ($ERROR_FUNCTION) {
        ?> <td><input type="submit" name="submit" value="Cancel"></td>
        <?php
    } else {
        ?> <td><input type="submit" name="submit" value="Accep"></td>
        <?php
    }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
    </table>
   <?php
    PAGE_FOOTER("", "");
    exit();
} //END FUNCTION

Function DOWNLOAD($NODE) {
/*
This function is the first phase of the Download File process, the client must
confirm the operation.
*/
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "DOWNLOAD FILE", "ORANGE", "BLACK");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Filename :
    <?php
    $CURRENT_FILE = BUILD_PATH($NODE);
    Echo $CURRENT_FILE;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
            
                <font face="tahoma">
                <h3> File to DOWNLOAD: <?php echo $CURRENT_FILE;?></h3>
                <form name="downloadfile" method="post" ENCtype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="downloadfile1">
                        <tr>
                            <td><input type="button" value="back" onclick="javascript:history.back()"></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td><input type="submit" name="downloadfileform" value="download"></td>
                        </tr>
                        <tr>
                            <td>When this process has been finished, click the BACK Button</noscript></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("ORANGE", "BLACK");
    exit;
} //end function

Function DOWNLOAD1($NODE) {
/*
This is the second phase, the function to download a file in a NODE of the tree
structure from the server to the client computer , can be opened or saved.
*/
    $FILE_name = $_SESSION['Folder_name'] [$NODE];
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE;
    if ((!File_Exists($REAL_FILE)) || (!IS_FILE($REAL_FILE))) {
        $ERROR_TEXT = "<td><CENTER><h3>Error: Filename not exist</h3></td>";
        $ERROR_FUNCTION = true;
    } else {
        // these lines must be the first to be executed before any other output or echo
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$FILE_name");
        header("Content-Length: " . filesize($REAL_FILE));
        header("Accept-Ranges: bytes");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-transfer-encoding: binary");
        if ($fh = fopen("$REAL_FILE", "rb")) {
            if (fpassthru($fh)) {
                fclose($fh);
                $ERROR_FUNCTION = false;
            } else {
                fclose($fh);
                $ERROR_TEXT = "<td><CENTER><h3>Error: Filename can`t be downloaded</h3></td>";
                $ERROR_FUNCTION = true;
            }
        } else {
            $ERROR_TEXT = "<td><CENTER><h3>Error: Filename can`t be downloaded</h3></td>";
            $ERROR_FUNCTION = true;
        }
    }
    if ($ERROR_FUNCTION == true) {
        PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "DOWNLOAD FILE", "ORANGE", "BLACK");
    ?>
        <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
            <tr>
                <td valign="top" align="left">
                    
                    <font face="tahoma">
                    <h3>Filename:
        <?php
        echo $CURRENT_FILE . "</h3>";
        ?>
                </td>
            </tr>
            <tr>
                <td>
                <form name="downloadfile1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <tr>
                            <input type="hidden" name="FILE_EXTENSION" value="<?= $_SESSION['File_Extension'];?>">
                            <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                            <input type="hidden" name="ACTION" value="">
        <?php if ($ERROR_FUNCTION) {
            ECHO $ERROR_TEXT;
        ?>
                        </tr>
                        <tr>
                            <td><center><input type="Submit" name="downloadfileform3b" value="Accept"></td>
        <?php
         }
        ?>
                        </tr>
                    </table>
                </form>
                </td>
            </tr>
        </table>
        <?php
        PAGE_FOOTER("ORANGE", "BLACK");
    }
    exit;
} //end function

Function EXPAND($NODE) {
/*
This is the function to open a closed folder, it calculates the complete branch
from the NODE to the root in a backward loop way reading backward the tree to
open nodes.
*/
    $_SESSION['Opened_Folder'][$NODE] = 1;
    While ($_SESSION['Father'] [$NODE] != 0) {
        $NODE = $_SESSION['Father'][$NODE];
        $_SESSION['Opened_Folder'][$NODE] = 1;
    }
} //end funcion

Function COLLAPSE($NODE) {
/*
This is the function to close a opened folder.
*/
    $_SESSION['Opened_Folder'][$NODE] = 0;
} //end funcion

Function DISPLAY_NODE($NODE) {
/*
This function is called every time from the display_Tree function to show a row
of the tree structure, checking the status of the NODE (opened or closed).
*/
// begin row
    // number of subdirs
    COLUMN_FOLDER($NODE, "", "");
    // number of files
    COLUMN_FILES($NODE, "", "");
    // filename
    COLUMN_FILEnameS($NODE, "", "");
    // filesize
    COLUMN_SIZE($NODE, "", "");
    // filedate
    COLUMN_DATE($NODE, "", "");
// end row
} //end funcion

Function DISPLAY_TREE () {
/*
This function read the tree structure and display every node if it is opened.
*/
    For ($I = 1;$I <= $_SESSION['Numfile'];$I++) {
        // check if the NODE can be seen
        if ($_SESSION['Father'] [$I] != 0) {
            $J = $I;
            $PARENTS_OPENED = 0;
            While (($_SESSION['Father'] [$J] != 0) && ($PARENTS_OPENED == 0)) {
                $J = $_SESSION['Father'] [$J];
                if ($PARENTS_OPENED == 0) {
                    if ($_SESSION['Opened_Folder'][$J] == 0) {
                        $PARENTS_OPENED = 1;
                    }
                }
            }
            if ($PARENTS_OPENED == 0) {
                DISPLAY_NODE($I);
            }
        } else {
            DISPLAY_NODE($I);
        }
    }
} //end funcion

Function FILTERFILE() {
/*
This funtion set the file filter criteria with a form, you can limit the
extensions of the files to be displayed/downloaded/uploaded with a file filter
criteria obtained with a form in the FilterFile function.
You can use as an sample: image group       : "*.gif,*.jpg,*.jpeg,*.png,*.tiff,"
                          text group        : "*.txt,*.nfo,*.doc,*.rtf,"
                          compressed  group : "*.zip,*.rar,*.gz,"
You can indicate the criteria in a grouped or individual way, every extension
must start with an asterisc ( * ) and dot ( . ) and finish with a comma ( , ).
Commodin characters (*) (?) are not evaluated.
Set nothing is like no filter as "*.*".
*/
    PAGE_HEADER("FILE FILTER - DIRTREEVIEW", "SET FILE FILTER CRITERIA - File Extension ( not case sensitive )", "BLACK", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
               
                <font face="tahoma">
                <h2> ! IMPORTANT ¡</h2>
                <h3> Every Extension must be separated by an asterisc(*) and dot(.) at the begining and a comma(,) at the end. </h3>
                <h3> The special characters (*) and (?) are not evaluated.</h3>
                <h3> Set Sample for image filter: *.gif,*.jpg,*.jpeg,*.tiff,</h3>
                </Font>
                <form name="formFILEFILTER" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <tr>
                            <td valign="baseline"> <h2>Extensions :</h2></td>
                            <td valign="top"> <h2><input type="text" name="FILE_EXTENSION" size="50" value="<?php echo $_SESSION['File_Extension'];?>"></h2></td>
                        </tr>
                        <input type="hidden" name="NODE" value="<?php echo $_SESSION['Node'];?>">
                        <input type="hidden" name="ACTION" value="filter2">
                        <tr></tr>
                        <tr></tr>
                        <tr>
                            <td><input type="button" value="Cancel" onclick="javascript:history.back()"></td>
                            <td></Td>
                            <td><input type="Submit" name="Filefilterform" value="Accept"></td>
                        </tr>
                        <tr>
                            <td> <script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("BLACK", "WHITE");
    exit;
} //end function

Function BUILD_PATH($NODE) {
/*
This function rebuild the path of the node using a loop thru the full branch of
the tree.
*/
    if ($NODE != 0) {
        $I = $NODE;
        $DIR_PATH = $_SESSION['Folder_name'] [$I];
        While ($_SESSION['Father'] [$I] != 0) {
            $DIR_PATH = $_SESSION['Folder_name'] [$_SESSION['Father'] [$I]] . DIRECTORY_SEPARATOR . $DIR_PATH;
            $I = $_SESSION['Father'] [$I];
        }
        $DIR_PATH = $_SESSION['Folder_name'] [0] . DIRECTORY_SEPARATOR . $DIR_PATH;
    } else {
        $DIR_PATH = $_SESSION['Folder_name'] [0];
    }
    return $DIR_PATH;
} // end function

Function FILE_FUNCTIONS($NODE) {
/*
This function show the File functions options, the client must choose between a
different button options.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "FILE FUNCTIONS", "BLACK", "ORANGE");
 ?>
    <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h2>  Choose the desired option</h2>
                <h3>Current File :
    <?php
    $CURRENT_FILE = BUILD_PATH($NODE);
    Echo $CURRENT_FILE . "</h3>";
    ?>
                <form name="FILEFUNCTIONS" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="downloadfile" name="ACTION"> Download</td>
                        </tr>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="emailfile" name="ACTION"> E-mail</td>
                        </tr>
    <?php
    if (STRTOUPPER(STRRCHR($CURRENT_FILE, ".")) == ".ZIP") {
        ?>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="listzipfile" name="ACTION"> List CONTENTS</td>
                        </tr>
    <?php
    }
    if ($_SESSION['privileges'] == 'all') {
        ?>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="renamefile" name="ACTION"> Rename</td>
                        </tr>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="erasefile" name="ACTION"> Erase</td>
                        </tr>
                        <!--Tr>
                            <Td></Td><Td ALIGN=left VALIGN=top><P><input type="radio" VALUE="compressfile" name="ACTION"> Compress (ZIP)</P></Td>
                        </Tr-->
    <?php
    }
    ?>
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <tr>
                            <td><input type="button" value="Cancel" ONCLICK="javascript:history.back()"></td>
                            <td></td>
                            <td><input type="Submit" name="FileFunctionsform" value="Accept"> </td>
                        </tr>
                        <tr>
                            <td> <script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
    <?php
    PAGE_FOOTER("BLACK", "ORANGE");
    exit;
} //end function

Function DIR_FUNCTIONS($NODE) {
/*
This function show the Directory functions options, the client must choose
between a different button options.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "DIRECTORY FUNCTIONS", "BLACK", "blue");
 ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma" color="white">
                <h2>  Choose the desired option</h2>
                <h3>Current Folder :
    <?php
    $CURRENT_DIR = BUILD_PATH($NODE);
    Echo $CURRENT_DIR . "</h3>";
    ?>
                <form name="MAKEDIR" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="menusec3">
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="upload" name="ACTION"> Upload a File</td>
                        </tr>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="makedir" name="ACTION"> Make a New Folder</Td>
                        </tr>
                        <tr>
                            <td></td><td align="left" valign="top"><input type="radio" value="makeroot" name="ACTION"> Turn into the root of the Treeview</Td>
                        </tr>
    <?php
    if ($_SESSION['privileges'] == 'all') {
        if ($_SESSION['File_Extension'] == "") {
    ?>
                                <tr>
                                <td></td><td align="left" valign="top"><input type="radio" value="removedir" name="ACTION"> Remove Folder & Subfolders</Td>
                                </tr>
    <?php
        }
    ?>
                            <tr>
                                <td></td><td align="left" valign="top"><input type="radio" value="renamedir" name="ACTION"> Rename Folder</td>
                            </tr>
    <?php
    }
    ?>
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <tr>
                            <td><input type="button" value="Cancel" ONCLICK="javascript:history.back()"></td>
                            <td></Td>
                            <td><input type="Submit" name="Makedirform" value="Accept"> </td>
                        </tr>
                        <tr>
                            <td> <script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("BLACK", "RED");
    exit;
} //end function

Function CHANGE_ROOTDIR($NODE) {
/*
This function is to Change Root Dir process the client must confirm the
operation.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "TURN THE DIRECTORY IN THE ROOT TREEVIEW", "GREEN", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Directory :
    <?php
    $CURRENT_DIR = BUILD_PATH($NODE);
    Echo $CURRENT_DIR;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
                
                <font face="tahoma">
                <h3> Directory to TURN in the ROOT of the Treeview: <?= $CURRENT_DIR;?></h3>
                <h3> Are you SURE ?</h3>
                <form name="makerootdir" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <input type="hidde" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="makeroot1">
                        <tr>
                            <td><input type="button" value="NO" onclick="javascript:history.back()"></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td><input type="Submit" name="makerootdirform" value="YES"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("GREEN", "WHITE");
    exit;
} //end function

Function MAKEDIR($NODE) {
/*
This function is the first phase of the Make Directory process the client write
the name of the new directory with a form.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "MAKE DIRECTORY", "RED", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8 " cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Folder Directory :
    <?php
    $CURRENT_DIR = BUILD_PATH($NODE);
    Echo $CURRENT_DIR;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
               
                <Font face="tahoma">
                <h3> Choose a name for the new Sub-Directory Folder</h3>
                <form name="makedir" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border=0 class=td>
                        <tr>
                            <td valign="baseline"><h3>New Sub-Directory :<h3></Td>
                            <td valign="top"><input type="text" name="MAKE_DIR" SIZE=50 VALUE=""></td>
                        </tr>
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="makedir1">
                        <tr>
                            <td><input type="button" value="    Cancel   " ONCLICK="javascript:history.back()"></td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td><input type="Submit" name="MakeDirform2" VALUE="   Accept  "></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("RED", "WHITE");
    exit;
} //end function

Function MAKEDIR1($MAKE_DIR) {
/*
This function is the second phase of the Make Dir process this is the checking
phase. The client must confirm the operation.
*/
    $CURRENT_DIR = BUILD_PATH($_SESSION['Node']) . DIRECTORY_SEPARATOR . $MAKE_DIR;
    $REAL_DIR = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_DIR;
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "MAKE DIRECTORY", "RED", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
               
                <font face="tahoma">

                <h3>New Sub-Directory Folder:
    <?php
    Echo $CURRENT_DIR . "</h3>";
    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="makedir1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    if ($MAKE_DIR != "") {
        CLEARSTATCACHE();
        if (!IS_DIR($REAL_DIR)) {
            if (MKDIR($REAL_DIR, 0750)) {
                Echo "<h3>It has been created successfully</h3>";
                $ERROR_FUNCTION = false;
            } else {
                Echo "<h3>Error: It can`t be created</h3>";
                $ERROR_FUNCTION = true;
            }
        } else {
            Echo "<h3>Error: Directory exist</h3>";
            $ERROR_FUNCTION = true;
        }
    } else {
        Echo "<h3>Error: You have not introduced any name for the new directory</h3>";
        $ERROR_FUNCTION = true;
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION"  value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE"  value="<?php echo $_SESSION['Node'];?>">
    <?php if ($ERROR_FUNCTION) {
        ?>
                    <input type="hidden" name="ACTION"  value="">
                    <tr>
                        <td><input type="Submit" name="Makedirform3"  value="Cancel"></td>
    <?php } else {
        ?>
                    <input type="hidden" name="ACTION"  value="makedir2">
                    <tr>
                        <td><input type="Submit" name="Makedirform3" value="Accept">
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("RED", "WHITE");
    exit;
} //end function

Function REMOVEDIR($NODE) {
/*
This function is the first phase of the Remove Dir process the client must
confirm the operation.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "REMOVE DIRECTORY", "GREEN", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td  valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Directory :
    <?php
    $CURRENT_DIR = BUILD_PATH($NODE);
    Echo $CURRENT_DIR;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
                
                <font face="tahoma">
                <h3> Directory to REMOVE: <?php echo $CURRENT_DIR;?></h3>
                <h3> Are you SURE ?</h3>
                <form name="removedir" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="removedir1">
                        <tr>
                            <td><input type="button" value="NO" onclick="javascript:history.back()"></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td><input type="Submit" name="Removedirform" value="YES"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("GREEN", "WHITE");
    exit;
} //end function

function DELETE_RECURSIVE_FOLDERS($dirname) {
/*
Recursive function to delete all subfolders and files under the node selected.
*/
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . DIRECTORY_SEPARATOR . $file)) {
                    if (!unlink ($dirname . DIRECTORY_SEPARATOR . $file)) {
                        return false;
                    }
                } else {
                    if (!DELETE_RECURSIVE_FOLDERS($dirname . DIRECTORY_SEPARATOR . $file)) {
                        return false;
                    }
                }
            }
        }
        closedir($dir_handle);
        if (rmdir($dirname)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
} //end function

Function REMOVEDIR1() {
/*
This is the last phase of the Remove Directory process, must be a confirmation
to refresh the tree when the process is finished.
*/
    PAGE_HEADER("REMOVE DIRECTORY - DIRTREEVIEW", "DIR FUNCTION EXECUTED", "GREEN", "WHITE");
    $TEMP_PATH = BUILD_PATH($_SESSION['Node']);
    $REAL_PATH = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $TEMP_PATH;
    if (!DELETE_RECURSIVE_FOLDERS($REAL_PATH)) {
        Echo "ERROR: borrando directorio";
    }
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h2> ! IMPORTANT ¡</h2>
                <h3> Dir function REMOVE DIRECTORY has been executed </h3>
                <h3> Click on the CONTINUE button to REFRESH the DIRECTORY TREEVIEW.</h3>
                </font>
                <form name="REMOVEDIR" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                         <tr>
                        <input type="hidden" name="FILE_EXTENSION" VALUE="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" VALUE="0">
                        <input type="hidden" name="ACTION" VALUE="">
                        </tr>
                        <tr>
                             <td><input type="Submit" name="REMOVEDIR" value="CONTINUE"></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("GREEN", "WHITE");
    exit;
} //end function

Function REnameDIR($NODE) {
/*
This function is the first phase of the Rename Dir process, the client write
the new name for the selected directory with a form.
*/
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "REname DIRECTORY", "PURPLE", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Directory :
    <?php
    $CURRENT_DIR = BUILD_PATH($NODE);
    Echo $CURRENT_DIR;

    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
                
                <font face="tahoma">
                <h3> Choose a new name for this directory</h3>
                <form name="renamedir" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <tr>
                            <td valign="baseline"> <h3> New name for this Directory :</h3></td>
                            <td valign="top"> <input type="text" name="REname_DIR" SIZE="50" VALUE="<?php echo $_SESSION['Folder_name'] [$NODE];?>"> </td>
                        </tr>
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" VALUE="renamedir1">
                        <tr>
                            <td><input type="button" value="Cancel" onclick="javascript:history.back()"></td>
                            <td></td>
                            <td><input type="Submit" name="RenameDirform2" value="Accept"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("PURPLE", "WHITE");
    exit;
} //end function

Function REnameDIR1($REname_DIR) {
/*
This function is the second phase of the Rename Dir process, this is the
checking phase.
*/
    $CURRENT_DIR = BUILD_PATH($_SESSION['Node']);
    $REAL_DIR = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_DIR; // the real full filename of the directory
    PAGE_HEADER("DIRECTORY MANAGER - DIRTREEVIEW", "REname DIRECTORY", "PURPLE", "WHITE");

    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
              
                <font face="tahoma">
                <h3>Old name Directory:
    <?php
    Echo $_SESSION['Folder_name'] [$_SESSION['Node']] . "</h3>";

    ?>
                <h3>New name Directory:
    <?php
    Echo $REname_DIR . "</h3>";

    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="renamedir1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    if ($REname_DIR != "") {
        CLEARSTATCACHE();
        if (IS_DIR($REAL_DIR)) {
            if (!IS_DIR(str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($REAL_DIR)) . DIRECTORY_SEPARATOR . $REname_DIR)) {
                if (REname($REAL_DIR, str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($REAL_DIR)) . DIRECTORY_SEPARATOR . $REname_DIR)) {
                    Echo "<h3>The Directory has been renamed successfully</h3></td>";
                    $_SESSION['Folder_name'] [$_SESSION['Node']] = $REname_DIR;
                    $ERROR_FUNCTION = false;
                } else {
                    Echo "<h3>Error: The Old name Directory can't be renamed</h3>";
                    $ERROR_FUNCTION = true;
                }
            }
        } else {
            Echo "<h3>Error: Old name Directory not exist</h3>";
            $ERROR_FUNCTION = true;
        }
    } else {
        Echo "<h3>Error: You have not introduced any new name for the this directory</h3>";
        $ERROR_FUNCTION = true;
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" VALUE="<?php echo $_SESSION['Node'];?>">
    <?php if ($ERROR_FUNCTION) {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="Renamedirform3a" value="Cancel"></td>
    <?php } else {
        ?>
                    <input type="hidden" name="ACTION" value="renamedir2">
                    <tr>
                        <td><input type="Submit" name="Renamedirform3b" value="Accept"></td>
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("PURPLE", "WHITE");
    exit;
} //end function

Function ERASEFILE($NODE) {
/*
This function is the first phase of the Erase File process, the client must
confirm the operation.
*/
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "ERASE FILE", "ORANGE", "BLACK");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Current Filename :
    <?php
    $CURRENT_FILE = BUILD_PATH($NODE);
    Echo $CURRENT_FILE;

    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
                
                <font face="tahoma">
                <h3> File to ERASE: <?php echo $CURRENT_FILE;?></h3>
                <h3> Are you SURE ?</h3>
                <form name="erasefile" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <input type="hidden" name='FILE_EXTENSION' value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name='NODE' value="<?php echo $NODE;?>">
                        <input type="hidden" name='ACTION' value="erasefile1">
                        <tr>
                            <td><input type="button" value="NO" onclick="javascript:history.back()"></td>
                            <td></td><td></td><td></td><td></td><td></td>
                            <td><input type="Submit" name="erasefileform" value="YES"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("ORANGE", "BLACK");
    exit;
} //end function

Function ERASEFILE1($NODE) {
/*
This function is the second phase of the Erase File process, this is the
checking phase.
*/
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE; // the real full filename of the directory
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "ERASE FILE", "ORANGE", "BLACK");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Filename:</h3>
    <?php
    echo "$CURRENT_FILE." ;
    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="erasefile1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    $ERROR_FUNCTION = false;
    CLEARSTATCACHE();
    if (IS_FILE($REAL_FILE)) {
        if (UNLINK($REAL_FILE)) {
            Echo "<h3>The File has been erased successfully</h3>";
            $ERROR_FUNCTION = false;
        } else {
            Echo "<h3>Error: Filename can`t be erased</h3>";
            $ERROR_FUNCTION = true;
        }
    } else {
        Echo "<h3>Error: Filename not exist</h3>";
        $ERROR_FUNCTION = true;
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
    <?php if ($ERROR_FUNCTION) {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="erasefileform3a" value="Cancel"></td>
    <?php } else {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><Center><input type="Submit" name="erasefileform3b" value="Accept"></td>
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("ORANGE", "BLACK");
    exit;
} //end function

Function COMPRESSFILE($NODE) {
/*
This function is the first phase of the Compress (ZIP) File process, the client
must confirm the operation.
*/
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "COMPRESS (ZIP) FILE", "YELLOW", "BLACK");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                <font face="tahoma">
                <h3>Current Filename :
    <?php
    $CURRENT_FILE = BUILD_PATH($NODE);
    Echo $CURRENT_FILE;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
              
                <font face="tahoma">
                <h3> File to COMPRESS ( ZIP ): <?php echo $CURRENT_FILE;?></h3>
                <h3> Are you SURE ?</h3>
                <form name="compressfile" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
                        <input type="hidden" name="ACTION" value="compressfile1">
                        <tr>
                            <td><input type="button" value="NO" onclick="javascript:history.back()"></td>
                            
                            <td><input type="Submit" name="compressfileform" value="YES"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("YELLOW", "BLACK");
    exit;
} //end function

Function COMPRESSFILE1($NODE) {
/*
This function is the second phase of the Compress (ZIP) File process, this is
the checking phase.
*/
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE; // the real full filename of the directory
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "COMPRESS (ZIP) FILE", "YELLOW", "BLACK");
    ?>
   <Table widht=100% border=0 CELLPADDING=8 CELLSPACING=0 class=td>
        <Tr>
            <td VALIGN=top ALIGN=left>
                <Center>
                <Font face=tahoma>
                <H3>Filename:
    <?php
    echo "$CURRENT_FILE .";
    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="compressfile1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    $ERROR_FUNCTION = false;
    CLEARSTATCACHE();
    if (IS_FILE($REAL_FILE)) {
        $ZIP_FILE = DIRname($REAL_FILE) . DIRECTORY_SEPARATOR . SUBSTR(BASEname($REAL_FILE), 0, STRRPOS(BASEname($REAL_FILE), ".")) . ".zip";
        if ($ZIP_FILE != $REAL_FILE) {
            if (IS_FILE($ZIP_FILE)) {
                UNLINK($ZIP_FILE);
            }
            $FILE_TO_ZIP[1] = $REAL_FILE;
            //include_once("dirtreezip1.inc.php");
            $ziper = new zipfile();
            $ziper->addFiles($FILE_TO_ZIP);
            $ziper->output($ZIP_FILE);
            if (IS_FILE($ZIP_FILE)) {
                Echo "<h3>The File has been compressed successfully</h3>";
                UNLINK($REAL_FILE);
                $ERROR_FUNCTION = false;
            } else {
                Echo "<h3>Error: Filename can't be compressed</h3>";
                $ERROR_FUNCTION = true;
            }
        } else {
            Echo "<h3>Error: Filename not exist</h3>";
            $ERROR_FUNCTION = true;
        }
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
    <?php if ($ERROR_FUNCTION) {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="compressfileform3a" value="Cancel"></td>
    <?php } else {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="compressfileform3b" value="Accept"></td>
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("YELLOW", "BLACK");
    exit;
} //end function

Function REnameFILE($NODE) {
/*
This function is the first phase of the Rename File process, the client write
the new name for the selected file with a form
*/
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "REname FILE", "TEAL", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                >
                <font face="tahoma">
                <h3>Current Filename :
    <?php
    $CURRENT_FILE = BUILD_PATH($NODE);
    Echo $CURRENT_FILE;
    ?>
            </h3></td>
        </tr>
        <tr>
            <td>
                
                <font face="tahoma">
                <h3> Choose a new name for this file</h3>
                <form name="renamefile" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class="td">
                        <tr>
                            <td valign="baseline"><h3>New name for this File :<h3></td>
                            <td valign="top"><input type="text" name="REname_FILE" size="50" value="<?php echo $_SESSION['Folder_name'] [$NODE];?>"></td>
                        </tr>
                        <input type="hidden" name='FILE_EXTENSION' value="<?php echo $_SESSION['File_Extension'];?>">
                        <input type="hidden" name='NODE' VALUE="<?php echo $NODE;?>">
                        <input type="hidden" name='ACTION' value="renamefile1">
                        <tr>
                            <td><input type="button" value="Cancel" onclick="javascript:history.back()"></td>
                            
                            <td><input type="Submit" name="RenameFileform2" value="Accept"></td>
                        </tr>
                        <tr>
                            <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("TEAL", "WHITE");
    exit;
} //end function

Function REnameFILE1($REname_FILE) {
/*
This function is the second phase of the Rename File process, this is the
checking phase.
*/
    $CURRENT_FILE = BUILD_PATH($_SESSION['Node']);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE; // the real full filename of the directory
    PAGE_HEADER("FILE MANAGER - DIRTREEVIEW", "REname FILE", "TEAL", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Old Filename:</h3>
    <?php
    echo $_SESSION['Folder_name'] [$_SESSION['Node']];
    ?>
                <h3>New Filename:</h3>
    <?php
    echo $REname_FILE;
    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="renamefile1" method="post" enctype="multipart/form-data" actoon="<?php echo $_SERVER['PHP_SELF'];
    ?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    $ERROR_FUNCTION = false;
    if ($REname_FILE != "") {
        CLEARSTATCACHE();
        if (IS_FILE($REAL_FILE)) {
            if (!IS_FILE(str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($REAL_FILE)) . DIRECTORY_SEPARATOR . $REname_FILE)) {
                if (REname($REAL_FILE, str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($REAL_FILE)) . DIRECTORY_SEPARATOR . $REname_FILE)) {
                    echo "<h3>The File has been renamed successfully</h3>";
                    $_SESSION['Folder_name'] [$_SESSION['Node']] = $REname_FILE;
                    $ERROR_FUNCTION = false;
                } else {
                    echo "<h3>Error: The Old Filename can't be renamed</h3>";
                    $ERROR_FUNCTION = true;
                }
            }
        } else {
            echo "<h3>Error: Old Filename not exist</h3>";
            $ERROR_FUNCTION = true;
        }
    } else {
        echo "<h3>Error: You have not introduced any new name for the this file</h3>";
        $ERROR_FUNCTION = true;
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" value="<?php echo $_SESSION['Node'];?>">
    <?php if ($ERROR_FUNCTION) {
        ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="Renamefileform3a" value="Cancel"></td>
    <?php } else {
        ?>
                    <input type="hidden" name="ACTION" value="renamefile2">
                    <tr>
                        <td><input type="Submit" name="Renamefileform3b" value="Accept"></td>
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("TEAL", "WHITE");
    exit;
} //end function

Function EMAILFILE($NODE) {
/*
This function is the first phase of the Email File process from a NODE of the
tree structure, with previous compression or not), previously to use this
function the SMTP parameter and  the sendmail_From parameter in PHP.INI must be
set acordingly to your ISPs smtp mail server.(your_ip_server could be anything).
sample extracted from PHP.INI
[mail function]
; For Win32 only.
SMTP = smtp.your_ip_server.com
smtp_port = 25
; For Win32 only.
sendmail_from = address@your_ip_server.com
*/
    $FILE_name = $_SESSION['Folder_name'] [$NODE];
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REALFILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE;
    $borde = 0;
    $bordercolor = "BLACK";
    $bodycolor = "CYAN";
    PAGEMAIL_HEADER("FILE MANAGER - DIRTREEVIEW", "EMAIL FUNCTION", "BLUE", "WHITE");
    ?>
<table widht="100%" class="td">
  <tr>
    <td>
        
        
        <h3>SEND THE ARCHIVE
            <?php echo STRTOUPPER($CURRENT_FILE);?> 
            AS EMAIL ATTACHED FILE
        </h3>
        
        <h3> Please fill the fields correctly</h3>

    </td>
  </tr>
  <tr>
    <td>
  <form name="emailform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <table widht="100%" border="<?php echo $borde;?>"  class="td">
           <tr>
            <td valign="top">
            Compress File before Send 
            <input type="radio" value="compress" name="ACTIONEMAIL"> YES 
            <input type="radio" value="nocompress" name="ACTIONEMAIL"> NO
            </td>

               
            </tr>
            <tr>
                
                <td>
                 <input type="text" name="your_name" size="61" value="Your name" onfocus="switchvalue(this,'Your name');" 
                 onblur="switchvalue(this,'Your name');">
                </td>
                >
            </tr>
            <tr>
             
                <td >
                    <input type="text" name="your_email" size="61" value="Your E-mail" onfocus="switchvalue(this,'Your E-mail');" onblur="switchvalue(this,'Your E-mail');">
                </td>
          
            </tr>
            <tr>
               
                <td >
                   <input type="text" name="target_name" size="61" value="Target name" onfocus="switchvalue(this,'Target name');" onblur="switchvalue(this,'Target name');">
                </td>
         
            </tr>
            <tr>
                
                <td >
                   <input type="text" name="target_email" size="61" value="Target E-mail" onfocus="switchvalue(this,'Target E-mail');" onblur="switchvalue(this,'Target E-mail');">
                </td>
               
            </tr>
         
                <td>
                    <center><input type="text" name="matter" size="61" value="the matter of the message" onfocus="switchvalue(this,'the matter of the message');" onblur="switchvalue(this,'the matter of the message');">
                </td>
                     <tr>
               
            </tr>
            <tr>
               
                <td valign="top" rowspan="5">
                    <textarea name="email_message" cols="46" rows="7" onfocus="switchvalue(this,'Your Message');" 
                    onblur="switchvalue(this,'Your Message');">Your Message</textarea>
                </td>
       
            </tr>
            <input type="hidden" name="attached" value="<?php echo $REALFILE?>">
            <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
            <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
            <input type="hidden" name="ACTION" value="emailfile1">
           
            <tr>
                <td>
                <input type="button" value="Cancel" onclick="javascript:history.back()">
                </td>
               
                <td ><input type="Submit" name="send" value="SEND"></td>
            </tr>
            <tr>
                <td><script>Use your BACK ARROW button of your BROWSER NAVIGATOR to GO BACK</script></td>
            </tr>
            <tr>
                
            </tr>
        </table>
    </form>
    </center>
    </td>
  </tr>
</table>
<?php
    PAGE_FOOTER("BLUE", "WHITE");
    exit;
} //END FUNCTION

function IS_VALID_EMAIL($mail) {
/*
This function validate the characters in the contents of the email message.
*/
    return eregi('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' . '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $mail);
} //END FUNCTION

Function EMAILFILE1($NODE) {
/*
This function is the second phase of the EMAIL File process, this is the
checking phase and mail process, previously to use this function the SMTP
variable in PHP.INI must be set acordingly to your ISPs smtp mail server, an
smtp server something like "smtp.your_ip_server.com"
(you_ip_server could be anything).
*/
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE; // the real full filename of the directory
    PAGEMAIL_HEADER("FILE MANAGER - DIRTREEVIEW", "EMAIL FUNCTION", "BLUE", "WHITE");
    ?>
   <table widht="100%" border="0" cellpadding="8" cellspacing="0" class="td">
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h3>Filename:</h3>
    <?php
    echo $CURRENT_FILE;
    ?>
            </td>
        </tr>
        <tr>
            <td>
            <form name="emailfile1" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
               <table align="center" border="0" class="td">
                    <tr>
    <?php
    $ERROR_FUNCTION = false;
    if (EMPTY($_POST['attached'])) {
        echo "<h3>No file to send</h3>";
        $ERROR_FUNCTION = true;
    } else {
        CLEARSTATCACHE();
        if (!IS_FILE($_POST['attached'])) {
            echo "<h3>The File has been erased or moved</h3>";
            $ERROR_FUNCTION = true;
        } else {
            if (!IS_VALID_EMAIL($_POST['target_email'])) {
                echo "<h3>The data fields has errors</h3>";
                $ERROR_FUNCTION = true;
            } else {
                $prioridad = "3";
                $headers = "From: ";
                $headers .= $_POST['your_name'];
                $headers .= " <";
                $headers .= $_POST['your_email'];
                $headers .= ">\n";
                $headers .= "Reply-To: ";
                $headers .= $_POST['your_name'];
                $headers .= " <";
                $headers .= $_POST['your_email'];
                $headers .= ">\n";
                $headers .= "MIME-Version: 1.0\n";
                $headers .= "Content-type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\n";
                $headers .= "X-Sender: DIRTREEVIEW 1.0 <";
                $headers .= $_POST['your_email'];
                $headers .= ">\n";
                $headers .= "X-Mailer: DIRTREEVIEW 1.0\n";
                $headers .= "X-Priority: ";
                $headers .= $prioridad;
                $headers .= "\n";
                $headers .= " return-Path: <";
                $headers .= $_POST['target_email'];
                $headers .= ">\n";
                $headers .= "This is a multi-part message in MIME format.\n";
                $FILE_TO_EMAIL = $_POST['attached'];
                if ($_POST['ACTIONEMAIL'] == "compress") {
                    CLEARSTATCACHE();
                    if (IS_FILE($REAL_FILE)) {
                        $ZIP_FILE = DIRname($REAL_FILE) . DIRECTORY_SEPARATOR . SUBSTR(BASEname($REAL_FILE), 0, STRRPOS(BASEname($REAL_FILE), ".")) . ".zip";
                        if ($ZIP_FILE != $REAL_FILE) {
                            if (IS_FILE($ZIP_FILE)) {
                                UNLINK($ZIP_FILE);
                            }
                            $FILE_TO_ZIP[1] = $REAL_FILE;
                            //include_once("dirtreezip1.inc.php");
                            $ziper = new zipfile();
                            $ziper->addFiles($FILE_TO_ZIP);
                            $ziper->output($ZIP_FILE);
                            if (IS_FILE($ZIP_FILE)) {
                                $FILE_TO_EMAIL = $ZIP_FILE;
                            }
                        }
                    }
                }
                $fp = fopen($FILE_TO_EMAIL, "r");
                $str = fread($fp, filesize($FILE_TO_EMAIL));
                $str = chunk_split(base64_encode($str));
                $fp = fclose($fp);
                $blankline=" \r\n";
                $tmp_message = "Hi " . $_POST['target_name'];
                $tmp_message .= $blankline;
                $tmp_message .= $blankline;
                $tmp_message .= "I'am " . $_POST['your_name'] . " and I send you this message and the attached file";
                $tmp_message .= $blankline;
                $tmp_message .= $blankline;
                $tmp_message .= "Message";
                $tmp_message .= $blankline;
                $tmp_message .= $blankline;
                $tmp_message .= "=======";
                $tmp_message .= $blankline;
                $tmp_message .= $blankline;
                $tmp_message .= $_POST['email_message'];

                $message = "--MIME_BOUNDRY\n";
                $message .= "Content-type: text/plain; charset=\"iso-8859-1\"\n";
                $message .= "Content-Transfer-Encoding: quoted-printable\n";
                $message .= "\n";
                $message .= $tmp_message;
                $message .= "\n";

                $message .= "--MIME_BOUNDRY\n";
                $message .= "Content-type: application/octet-stream; name=";
                $message .= basename($FILE_TO_EMAIL);
                $message .= "\n";
                $message .= "Content-disposition: attachment\n";
                $message .= "Content-Transfer-Encoding: base64\n";
                $message .= "\n";
                $message .= $str;
                $message .= "\n";
                $message .= "\n";
                $message .= "--MIME_BOUNDRY--\n";

                if (!mail($_POST['target_email'], $_POST['matter'], $message, $headers)) {
                    echo "<h3>ERROR : email has not been sent, please try it again</h3>";
                    $ERROR_FUNCTION = true;
                } else {
                    echo "<h3>The file " . $FILE_TO_EMAIL . " has been sent succesfully</h3>";
                }
                if ($_POST['ACTIONEMAIL'] == "compress") {
                    if ($ZIP_FILE != $REAL_FILE) {
                        if (IS_FILE($ZIP_FILE)) {
                            UNLINK($ZIP_FILE);
                        }
                    }
                    unset($_POST['ACTIONEMAIL']);
                }
            }
        }
    }
    ?>
                    </tr>
                    <input type="hidden" name="FILE_EXTENSION" value="<?php echo $_SESSION['File_Extension'];?>">
                    <input type="hidden" name="NODE" value="<?php echo $NODE;?>">
    <?php if ($ERROR_FUNCTION) {
    ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="emailfileform3a" value="Cancel"></td>
    <?php 
   } 
   else {
    ?>
                    <input type="hidden" name="ACTION" value="">
                    <tr>
                        <td><input type="Submit" name="emailfileform3b" value="Accept"></td>
    <?php }
    ?>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("BLUE", "WHITE");
    exit;
} //end function

/*
Zip file creation CLASS.
Makes zip files.
Last Modification and Extension By :
Hasin Hayder
HomePage : www.hasinme.info
Email : td@gmail.com
Originally Based on :
http://www.zend.com/codex.php?id=535&single=1
By Eric Mueller <eric@themepark.com>
http://www.zend.com/codex.php?id=470&single=1
by Denis125 <webmaster@atlant.ru>
a patch from Peter Listiak <mlady@users.sourceforge.net> for last modified date
and time of the compressed file.
Official ZIP file format: http://www.pkware.com/appnote.txt
@access  public
*/
class zipfile{
    var $datasec      = array();
    var $ctrl_dir     = array();
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    var $old_offset   = 0;
    /**
     * Converts an Unix timestamp to a four byte DOS date and time format (date
     * in high two bytes, time in low two bytes allowing magnitude comparison).
     * @param  integer  the current Unix timestamp
     *  return integer  the current date in a four byte DOS format
     * @access private
     */
    function unix2DosTime($unixtime = 0) {
        $timearray = ($unixtime == 0) ? td() : td($unixtime);
        if ($timearray['year'] < 1980) {
        	$timearray['year']    = 1980;
        	$timearray['mon']     = 1;
        	$timearray['mday']    = 1;
        	$timearray['hours']   = 0;
        	$timearray['minutes'] = 0;
        	$timearray['seconds'] = 0;
        } // end if
        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    } // end of the 'unix2DosTime()' method
    /**
     * Adds "file" to archive
     * @param  string   file contents
     * @param  string   name of the file in the archive (may contains the path)
     * @param  integer  the current timestamp
     * @access public
     */
    function addFile($data, $name, $time = 0){
        //$name     = str_replace(chr(92).chr(92), DIRECTORY_SEPARATOR, $name);
        $dtime    = dechex($this->unix2DosTime($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
                  . '\x' . $dtime[4] . $dtime[5]
                  . '\x' . $dtime[2] . $dtime[3]
                  . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');
        $fr   = "\x50\x4b\x03\x04";
        $fr   .= "\x14\x00";            // ver needed to extract
        $fr   .= "\x00\x00";            // gen purpose bit flag
        $fr   .= "\x08\x00";            // compression method
        $fr   .= $hexdtime;             // last mod time and date
        // "local file header" segment
        $unc_len = strlen($data);
        $crc     = crc32($data);
        $zdata   = gzcompress($data);
        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $c_len   = strlen($zdata);
        $fr      .= pack('V', $crc);             // crc32
        $fr      .= pack('V', $c_len);           // compressed filesize
        $fr      .= pack('V', $unc_len);         // uncompressed filesize
        $fr      .= pack('v', strlen($name));    // length of filename
        $fr      .= pack('v', 0);                // extra field length
        $fr      .= $name;
        // "file data" segment
        $fr .= $zdata;
        // "data descriptor" segment (optional but necessary if archive is not
        // served as file)
        $fr .= pack('V', $crc);                 // crc32
        $fr .= pack('V', $c_len);               // compressed filesize
        $fr .= pack('V', $unc_len);             // uncompressed filesize
        // add this entry to array
        $this -> datasec[] = $fr;
        // now add to central directory record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";                // version made by
        $cdrec .= "\x14\x00";                // version needed to extract
        $cdrec .= "\x00\x00";                // gen purpose bit flag
        $cdrec .= "\x08\x00";                // compression method
        $cdrec .= $hexdtime;                 // last mod time & date
        $cdrec .= pack('V', $crc);           // crc32
        $cdrec .= pack('V', $c_len);         // compressed filesize
        $cdrec .= pack('V', $unc_len);       // uncompressed filesize
        $cdrec .= pack('v', strlen($name) ); // length of filename
        $cdrec .= pack('v', 0 );             // extra field length
        $cdrec .= pack('v', 0 );             // file comment length
        $cdrec .= pack('v', 0 );             // disk number start
        $cdrec .= pack('v', 0 );             // internal file attributes
        $cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set
        $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
        $this -> old_offset += strlen($fr);
        $cdrec .= $name;
        // optional extra field, file comment goes here
        // save to central directory
        $this -> ctrl_dir[] = $cdrec;
    } // end of the 'addFile()' method
    /**
     * Dumps out file
     *  return  string  the zipped file
     * @access public
     */
    function file(){
        $data    = implode('', $this -> datasec);
        $ctrldir = implode('', $this -> ctrl_dir);
        return
            $data .
            $ctrldir .
            $this -> eof_ctrl_dir .
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
            pack('V', strlen($ctrldir)) .           // size of central dir
            pack('V', strlen($data)) .              // offset to start of central dir
            "\x00\x00";                             // .zip file comment length
    } // end of the 'file()' method
    /**
     * A Wrapper of original addFile Function
     * Created By Hasin Hayder at 29th Jan, 1:29 AM
     * @param array An Array of files with relative/absolute path to be added in Zip File
     * @access public
     */
    function addFiles($files /*Only Pass Array*/) {
        foreach($files as $file){
		      if (is_file($file)) {
			     $data = implode("",file($file));
	             $this->addFile($data,basename($file));
              }
        }
    }
    /**
     * A Wrapper of original file Function
     * Created By Hasin Hayder at 29th Jan, 1:29 AM
     * @param string Output file name
     * @access public
     */
    function output($file) {
        $fp=fopen($file,"w");
        fwrite($fp,$this->file());
        fclose($fp);
    }

} // end of the 'zipfile' class

// the next two functions are based on
/**
* zip.php
*    begin                : Saturday', Mar 08', 2003
*    copyright            : ('C) 2002-03 Bugada Andrea
*    email                : phpATM@free.fr
*    $Id: zip.php, v1.12 2003/03/09 11:53:50 bugada Exp $
*/
function MSDOS_TIME_TO_UNIX($DOSdate, $DOStime) {
    $year = (($DOSdate &65024) >> 9) + 1980;
    $month = ($DOSdate &480) >> 5;
    $day = ($DOSdate &31);
    $hours = ($DOStime &63488) >> 11;
    $minutes = ($DOStime &2016) >> 5;
    $seconds = ($DOStime &31) * 2;
    return mktime($hours, $minutes, $seconds, $month, $day, $year);
} //end function

function LIST_ZIP($NODE) {
global $encabezado;
    $CURRENT_FILE = BUILD_PATH($NODE);
    $REAL_FILE = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, DIRname($_SESSION['Server_Path'])) . DIRECTORY_SEPARATOR . $CURRENT_FILE; // the real full filename of the directory
    if (EMPTY($BGC)) {
        $BGC = "BLACK";
    }
    if (EMPTY($FGC)) {
        $FGC = "YELLOW";
    }
    $fp = @fopen($REAL_FILE, 'rb');
    if (!$fp) {
        return;
    }
    fseek($fp, -22, SEEK_END);
    // Get central directory field values
    $headersignature = 0;
    do {
        // Search header
        $data = fread($fp, 22);
        list($headersignature, $numberentries, $centraldirsize, $centraldiroffset) =
        array_values(unpack('Vheadersignature/x6/vnumberentries/Vcentraldirsize/Vcentraldiroffset', $data));
        fseek($fp, -23, SEEK_CUR);
    } while (($headersignature != 0x06054b50) && (ftell($fp) > 0));
    // Go to start of central directory
    fseek($fp, $centraldiroffset, SEEK_SET);
    // Read central dir entries
    PAGE_HEADER("FILE FUNCTIONS - DIRTREEVIEW", "LIST ZIP CONTENTS OF " . STRTOUPPER(basename($REAL_FILE)), $BGC, $FGC);
    echo "<p><CENTER><table border='' bgcolor=$BGC cellpadding='0' cellspacing='0' class=td>";
    echo "
    <tr>
       
       <td>
          <h3Filename</h3>
       </td>
       <td>
          <h3>Size</h3>
       </td>
      
       <td>
          <h3>Date:Time</h3>
       </td>
        
    </tr>";
    for ($i = 1; $i <= $numberentries; $i++) {
        // Read central dir entry
        $data = fread($fp, 46);
        list($arcfiletime, $arcfiledate, $arcfilesize, $arcfilenamelen, $arcfileattr) =
        array_values(unpack("x12/varcfiletime/varcfiledate/x8/Varcfilesize/Varcfilenamelen/x6/varcfileattr", $data));
        $filenamelen = fread($fp, $arcfilenamelen);
        $arcfiledatetime = MSDOS_TIME_TO_UNIX($arcfiledate, $arcfiletime);
        
        // Print Filename
        if ($arcfileattr == 16) {
            echo "<td>";
            echo  $filenamelen ;
            echo "</td>";
        } else {
            echo "<td>";
            echo "...." . basename($filenamelen);
            echo "</td>";
        }
        // Print FileSize column
        if ($arcfileattr == 16) {
            echo "<td>";
            echo "Folder";
        } else {
          
            if ($arcfilesize > 0) {
                if (!ISSET($_SESSION['Display_Size'])) {
                    $_SESSION['Display_Size'] = "";
                }
                switch ($_SESSION['Display_Size']) {
                    case 2:
                        echo number_format(($arcfilesize / (1024 * 1024)), 2, ',', '.');
                        $DES_BYTES = "mb";
                        break;
                    case 1:
                         echo number_format(($arcfilesize / 1024), 1, ',', '.') ;
                        $DES_BYTES = "kb";
                        break;
                    default:
                         echo number_format($arcfilesize, 0, ',', '.');
                        $DES_BYTES = "bytes";
                } // switch
            } else {
                
                $DES_BYTES = "";
            }
            echo "</td>";
            
            echo "<td>$DES_BYTES</Td>";
            
        }
        echo "</td>";
        // Print FileDate column
        
        if ($arcfileattr == 16) {
            echo date("d/m/y H:i", $arcfiledatetime) ;
        } else {
            echo date("d/m/y H:i", $arcfiledatetime);
        }
        echo "</td>";
        
        echo "</tr>";
    }
    echo "
    <tr>
        <td valign=middle colspan=8>
            <a href=" . $_SERVER['PHP_SELF'] . "?ACTION=expand&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado></a>
            <h3>BACK</h3>
           
        </td>
    </tr>";
    echo "</table></p>";
    fclose($fp);
    PAGE_FOOTER($BGC, $FGC);
    exit;
} //END FUNCTION

Function PAGEMAIL_HEADER($TITLE, $SUBTITLE, $BGC, $FGC) {
global $encabezado;
/*
This function write the header of the email form web html page.
(little javascript is included).
*/
    if (EMPTY($BGC)) {
        $BGC = "BLUE";
    }
    if (EMPTY($FGC)) {
        $FGC = "WHITE";
    }
    ?>
    <!DOCtype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
        <head>
            <title>
                <?php echo $TITLE;?>
            </title>
            <script type="text/javascript">
                function switchvalue(one,two) {
                    if (one.value==two) {
                        one.value='';
                    }else{
                        if (one.value=='') {
                            one.value=two;
                        }
                    }
                }
            </script>
            <meta http-equiv="Content-type" content="text/html; charset=iso-8859-1">
            <meta name="author" content="Dirtreeview - www.euskalnet.net/aitor-solozabal">
        </head>
        <body>
            <table widht="100%" border="0" cellspacing="0" cellpadding="0" class="td">
                <tr align="center" class=td>
                    <td height="30" valign="middle">
                    <font face="tahoma" > <?php echo $SUBTITLE;?></font>
                    </Td>
                </tr>
            </table>
     <?php
} //end function

Function PAGE_HEADER($TITLE, $SUBTITLE, $BGC, $FGC) {
global $arrHttp,$msgstr;
/*
This function write the header of the any form web html page.
*/
    if (EMPTY($BGC)) {
        $BGC = "BLUE";
    }
    if (EMPTY($FGC)) {
        $FGC = "WHITE";
    }
 //   EncabezadoPagina()
    ?>
  <!DOCtype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
        <head>
            <title>
                <?php echo $TITLE;?>
            </title>
            <meta http-equiv="Content-type" content="text/html; charset=iso-8859-1">
            <meta name="author" content="Dirtreeview - www.euskalnet.net/aitor-solozabal">
        </head>
        <body> -->

            <Table widht="100%" class="td">
                <tr align="center">
                    <td height="30" valign="middle">
                    <font face="tahoma"> <?php echo $SUBTITLE;?></font>
                    </td>
                </tr>
            </table>
     <?php
} //end function

Function PAGEDIR_HEADER($TITLE, $SUBTITLE, $BGC, $FGC) {

/*
This function write the header of the dirtreeview web html page.
*/
    if (EMPTY($BGC)) {
        $BGC = "BLUE";
    }
    if (EMPTY($FGC)) {
        $FGC = "WHITE";
    }
//EncabezadoPagina();
} //end function

Function TABLEDIR_HEADER($BGC, $FGC) {
global $encabezado;
/*
This function write the header of the dirtreeview table inside the web html page
*/
    if (EMPTY($BGC)) {
        $BGC = "WHITE";
    }
    if (EMPTY($FGC)) {
        $FGC = "BLACK";
    }
    ?>

    <table widht="100%" align="center" cellpadding="0" cellspacing="0"  class="td">
        <tr>
            <td><img src="img/dirtree/tree_space.gif"></td>
            <td valign="middle">
                <img src="img/dirtree/tree_space.gif">
                <img src="img/dirtree/tree_space.gif" alt="Developed with PHP under Apache Server">
                <img src="img/dirtree/tree_space.gif">
            </td>
            <td><img src="img/dirtree/tree_space.gif"></td>
            <td align="center" > Display <?php echo "<br>".$_SESSION['widht'] . "x" . $_SESSION['Height'];?></td>
            <td><img src="img/dirtree/tree_space.gif"></td>
            <td align="center">Max File Size<?php echo NUMBER_FORMAT($_SESSION['Maxfilesize'], 0, ',', '.');?></td>
            <td><img src="img/dirtree/tree_space.gif"></td>
            <td>
                <font size="2"> DIRECTORY TREEVIEW </font>
                
                <font> User: <?php echo $_SESSION['login'];?> --> Privileges: <?= $_SESSION['privileges'];?></font>
            </td>
            <td valign="middle">
              <!--  <A HREF=<?=$_SERVER['PHP_SELF'];?>?ACTION=logout>
                <Img SRC="img/dirtree/tree_refresh1.gif" alt="exit - Logout" border="0" height="40">
                <font color="<?=$FGC;?>"> LOGOUT </A>] -->
            </td>
            <td><img src="img/dirtree/tree_space.gif"></td>
        </tr>
    </table>
    <table align="center" class="td">
        <tr>
            <td valign="top"> <font>
                <a href = "<?php echo $_SERVER['PHP_SELF'];?>" action="refresh&NODE= <?php echo $_SESSION['Last_Node'];?>&FILE_EXTENSION= <?php echo $_SESSION['File_Extension'].$encabezado;?>">
                <img src="img/dirtree/tree_refresh0.gif" alt="Rebuild the Treeview">
                <font size="1"> 
                Refresh
                <a href= "<?php echo $_SERVER['PHP_SELF'];?>" action="filter&NODE= <?php echo $_SESSION['Last_Node'];?>&FILE_EXTENSION= <?php echo $_SESSION['File_Extension'].$encabezado;?>">
                <img src="img/dirtree/tree_filter.gif" alt="File Filter Extension Criteria">
                <font size="1" >">
                File Filter 
                </font></a>
    <?php
    if ($_SESSION['File_Extension'] == "") {
        echo "*.*";
    } else {
        echo $_SESSION['File_Extension'];
    }
    ?>
            </td>
        </tr>
     </table>
    <?php
} //end function

Function TABLEDIR_SUBHEADER($BGC, $FGC) {
global $encabezado;
/*
This function write the subheader of the dirtreeview table in the web html page.
*/
    if (EMPTY($BGC)) {
        $BGC = "MAROON";
    }
    if (EMPTY($FGC)) {
        $FGC = "WHITE";
    }
    ?>
    <table align="center" class="td" >
        <tr align="center" valign="top">
            <td><font>FOLDERS</td>
            
            <td><Font>FILES</td>
            <td><Font>FILEnameS</td>
            <td colspan="4">
            <a alt="Show Bytes, Kbytes, Mbytes" href="<?php echo $_SERVER['PHP_SELF'].$encabezado;?>" action="size">
            <font>SIZE</a></td>
            
            <td>
            <font>LAST DATE</td>
           
        </tr>
      
    <?php
} //end function

Function COLUMN_FOLDER($NODE, $BGC, $FGC) {
/*
This function write the data in the column folder of the dirtreeview table.
*/
    if (EMPTY($BGC)) {
        $BGC = "ANTIQUEWHITE";
    }
    if (EMPTY($FGC)) {
        $FGC = "RED";
    }
    ?>
    <tr>
        <td align="right" valign="top">
        <font>
    <?php
    if ($_SESSION['Children_Subdirs'][$NODE] != 0) {
        // number of subdirs
        echo $_SESSION['Children_Subdirs'][$NODE];
    } 
    ?>
</td>
        
    ?>
 </td>
    <?php
} //end function

Function COLUMN_FILES($NODE, $BGC, $FGC) {
/*
This function write the data in the column files of the dirtreeview table.
*/
    if (EMPTY($BGC)) {
        $BGC = "PALEGREEN";
    }
    if (EMPTY($FGC)) {
        $FGC = "BLUE";
    }
    ?>
   <td align="right" valign="top">

   <?php
    if ($_SESSION['Children_Files'] [$NODE] != 0) {
        // number of files
        echo $_SESSION['Children_Files'] [$NODE];
    } 
    ?>
   </td>
  
   <?php
} //end function

Function COLUMN_FILEnameS_ROOT($BGC, $FGC) {
global $encabezado;
/*
This function write the data in the column filenames exclusively for the first
row of the dirtreeview table. (root dir).
*/
    if (empty($BGC)) {
        $BGC = "LIGHTSKYBLUE";
    }
    if (empty($FGC)) {
        $FGC = "BLUE";
    }
    ?>
    <td> 
    
    <a href= "<?php echo $_SERVER['PHP_SELF'];?>" action="collapseall&NODE=0&FILE_EXTENSION"=<?php echo $_SESSION['File_Extension'].$encabezado;?>"> <img src="img/dirtree/tree_minus_end.gif" alt="Collapse All" height="18" widht="18" align="left">
    </a>

    <a href="<?php echo $_SERVER['PHP_SELF'];?>" action="dirfunction&NODE=0&FILE_EXTENSION=<?php echo $_SESSION['File_Extension'].$encabezado;?>">" <img src="img/dirtree/tree_upload.gif" alt="Directory Functions" height="18" widht="18" align="left">
    </a>

    <a href="<?php echo $_SERVER['PHP_SELF'];?>" action="expandall&NODE=0&FILE_EXTENSION=<?= $_SESSION['File_Extension'].$encabezado;?>"> <img src="img/dirtree/tree_root.gif" alt="Expand All" height="20" widht="20" align="left">

    <?php echo BASEname($_SESSION['Server_Path']);?> </a>

    </td>

   
    <?php
} //end function

Function COLUMN_FILEnameS($NODE, $BGC, $FGC) {
global $encabezado;
/*
This function write the data in the column filenames of the dirtreeview table.
*/
    if (empty($BGC)) {
        $BGC = "LIGHTCYAN";
    }
    if (empty($FGC)) {
        $FGC = "BLUE";
    }
    $_SESSION['Last_Node'] = $NODE;
    echo "<td>";
    echo "<img src='img/dirtree/tree_space.gif' height='18' widht='18' align='left'>";
    for ($I = 0;$I <= $_SESSION['Level_Tree'][$NODE];$I++) {
        $LEVEL_NODE[$I] = 0; //initialization of the needed levels
    }
    $J = $NODE;
    $I = $NODE;
    do {
        while (($I < $_SESSION['Numfile']) && ($_SESSION['Level_Tree'][$I] >= $_SESSION['Level_Tree'][$J])) {
            $I++;
        }
        $DIFERENCIA = ($_SESSION['Level_Tree'][$J] - ($_SESSION['Level_Tree'][$I] + 1));
        for ($K = 1;$K <= $DIFERENCIA;$K++) {
            $LEVEL_NODE[$_SESSION['Level_Tree'][$J] - $K] = 1;
        }
        $J = $I;
        $I = $I + 1;
    } while (($I <= $_SESSION['Numfile']) && ($_SESSION['Level_Tree'][$I-1] > 1));
    for ($I = 1;$I < $_SESSION['Level_Tree'][$NODE];$I++) {
        if (($LEVEL_NODE[$I] == 1) || ($NODE > $_SESSION['Last_Level_Node'][$I])) {
            echo "<img src='img/dirtree/tree_space.gif' height='18' align='left'>";
        } else {
            echo "<img src='img/dirtree/tree_vertline.gif' height='18' align='left'>";
        }
    }
    if ($_SESSION['Folder_type'] [$NODE] == "File") { // it is a file
        // add an ACTION to download the file
        echo "<a href=" . $_SERVER['PHP_SELF'] . ">  action=filefunction&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>";
        if ($NODE == $_SESSION['Numfile']) {
            echo "<img src='img/dirtree/tree_end.gif' height='18' widht='18' align='left'>";
        } else {
            if ($_SESSION['Level_Tree'][$NODE + 1] < $_SESSION['Level_Tree'][$NODE]) {
                echo "<img src='img/dirtree/tree_end.gif' height='18' widht='18' align='left'>";
            } else {
                echo "<img src='img/dirtree/tree_split.gif' height='18' widht='18' align='left'>";
            }
        }
        echo "<img src='img/dirtree/tree_download.gif' alt=" . "File Functions" . " height='18' widht='18' align='left'>";
        //agregado


    } else { // it is not a file then it is a directory
        if ($NODE == $_SESSION['Numfile']) {
            echo "<img src='img/dirtree/tree_end.gif' height='18' widht='18'  align='left'>";
            echo "</a>";
            echo "<a href=" . $_SERVER['PHP_SELF'] . "> action=dirfunction&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>";
            echo "<img src='img/dirtree/tree_upload.gif' alt=" . "Dir Functions" . " height='18' widht='18' align='left'>";
        } else {
            if ($_SESSION['Father'] [$NODE + 1] == $NODE) { // has childs
                if ($_SESSION['Opened_Folder'][$NODE] == 0) { // closed NODE (folder)
                    echo "<a href=" . $_SERVER['PHP_SELF'] . "> action=expand&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>";
                    if ($NODE == $_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$NODE]]) {
                        echo "<img src='img/dirtree/tree_plus_end.gif' alt='Expand' height='18' widht='18' align='left'>";
                    } else {
                        $I = $NODE + 1;
                        while ($I <= $_SESSION['Numfile']) {
                            if ($_SESSION['Level_Tree'][$I] < $_SESSION['Level_Tree'][$NODE]) {
                                echo "<img src='img/dirtree/tree_plus_end.gif' alt='Expand' height='18' widht='18' align='left'>";
                                break;
                            } else {
                                if ($_SESSION['Level_Tree'][$I] == $_SESSION['Level_Tree'][$NODE]) {
                                    echo "<img SRC='img/dirtree/tree_plus.gif' alt='Expand' height='18' widht='18' align='left'>";
                                    break;
                                }
                            }
                            $I++;
                        }
                    }
                    if ($_SESSION['Children_Files'] [$NODE] != 0) {
                        echo "<img src='img/dirtree/tree_haschild.gif' height='18' widht='18' align='left'>";
                    } else {
                        echo "<img src='img/dirtree/tree_closed.gif' height='18' widht='18' align='left'>";
                    }
                } else { // opened NODE (folder)
                    echo "<a href=" . $_SERVER['PHP_SELF'] . "> action=collapse&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . ">";
                    if ($NODE == $_SESSION['Last_Level_Node'][$_SESSION['Level_Tree'][$NODE]]) {
                        echo "<img src='img/dirtree/tree_minus_end.gif' alt='Collapse' height='18' widht='18' align='left'>";
                    } else {
                        $I = $NODE + 1;
                        while ($I <= $_SESSION['Numfile']) {
                            if ($_SESSION['Level_Tree'][$I] < $_SESSION['Level_Tree'][$NODE]) {
                                echo "<img src='img/dirtree/tree_minus_end.gif' alt='Collapse' height='18' widht='18' align='left'>";
                                break;
                            } else {
                                if ($_SESSION['Level_Tree'][$I] == $_SESSION['Level_Tree'][$NODE]) {
                                    echo "<img src='img/dirtree/tree_minus.gif' alt='Collapse' height='18' widht='18' align='left'>";
                                    break;
                                }
                            }
                            $I++;
                        }
                    }
                    echo "</a>";
                    echo "<a href=" . $_SERVER['PHP_SELF'] . "> action=\"dirfunction&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>\"";
                    echo "<img src='img/dirtree/tree_upload.gif' alt=" . "Dir Functions" . " height='18' widht='18'  align='left'>";
                }
            } else { // has no childs
                if ($_SESSION['Level_Tree'][$NODE + 1] < $_SESSION['Level_Tree'][$NODE]) {
                    echo "<img src='img/dirtree/tree_end.gif' height='18' widht='18' align='left'>";
                } else {
                    echo "<img src='img/dirtree/tree_split.gif' height='18' widht='18' align='left'>";
                }
                echo "</a>";
                echo "<a href=" . $_SERVER['PHP_SELF'] . "> action=dirfunction&NODE=" . $NODE . "&FILE_EXTENSION=" . $_SESSION['File_Extension'] . "$encabezado>";
                echo "<img src='img/dirtree/tree_upload.gif' alt=" . "Dir Functions" . " height='18' widht='18' align='left'>";
            }
        }
    }
    // after the levels were indented , continue with the current node name
    echo "</a>";
    if ($_SESSION['Folder_type'] [$NODE] == "File") {
    	$nombre=$_SESSION['Folder_name'] [$NODE];
    	$found=false;
    	$comp=array(".cnt",".mst",".xrf",".ifp",".n01",".n02",".l01",".l02",".php","isisuc.tab","isisac.tab",".exe",".gif",".zip");
    	foreach ($comp as $value){
    		if (strpos(strtoupper($nombre),strtoupper($value))===false){
    		}else{
    			$found=true;
    		}
    	}
    	if ($nombre=="mx") $found=true;
    	if (!$found) {
    		$CURRENT_FILE = BUILD_PATH($NODE);
    		/*echo $CURRENT_FILE;
    		$c=explode(DIRECTORY_SEPARATOR,$CURRENT_FILE);
    		$CURRENT_FILE="";
    		for ($icf=1;$icf<count($c);$icf++)
    			if ($CURRENT_FILE=="")
    				$CURRENT_FILE=$c[$icf];
    			else
    				$CURRENT_FILE.='/'.$c[$icf]; */
    		if ($_SESSION['Father'][$NODE]>0) $nombre=$_SESSION['Folder_name'] [$_SESSION['Father'][$NODE]]."/".$nombre;
			echo "<a href=\"javascript:VerArchivo('".urlencode(str_replace("\\","/",$CURRENT_FILE))."')\"><img SRC='img/dirtree/preview.gif' alt=preview  align='left'></a>";
		}else{
			echo "<img src='img/dirtree/n_preview.gif'  align='left'>";
		}
	}
	echo "<img SRC='img/dirtree/tree_space.gif' height='18' widht='9'  align='left'>";
    echo $_SESSION['Folder_name'] [$NODE];
    echo "</td>";

} //end function

Function COLUMN_SIZE($NODE, $BGC, $FGC) {
global $encabezado;
/*
This function write the data in the column size of the dirtreeview table.
There are 5 modes of visualization depends on the value of the SUPERGLOBAL
variable $_SESSION['Display_Size']
*/
    
    ?>
 
   <?php
    // filesize
    $DATO=$_SESSION['Numbytes'][$NODE];
    if ($DATO > 0) {
        switch ($_SESSION['Display_Size']) {
            case 4:
                 if ($_SESSION['Folder_type'][$NODE]=="File"){
                    if ($_SESSION['Maxfilesize']>0){
                        $Y = (($DATO * 100) / $_SESSION['Numbytes'][0]);
                        
                    }
                }else{
                    if ($_SESSION['Maxfoldersize']>0){
                        $Y = (($DATO * 100) / $_SESSION['Numbytes'][0]);
                        
                    }
                }
                echo number_format($Y, 4, ',', '.')."";
                $DES_BYTES = "%";
                break;
            case 3:
                if ($_SESSION['Folder_type'][$NODE]=="File"){
                    if ($_SESSION['Maxfilesize']>0){
                        $Y = intval((($DATO * ((800/$_SESSION['widht'])*40)) / $_SESSION['Maxfilesize']));
                      
                    }
                }else{
                    if ($_SESSION['Maxfoldersize']>0){
                        $Y = intval((($DATO * ((800/$_SESSION['widht'])*40)) / $_SESSION['Maxfoldersize']));
                        
                    }
                }
                if ($NODE!=0){
                    echo str_repeat(chr(124), $Y)."";
                }
                $DES_BYTES = "";
                break;
            case 2:
                echo number_format(($DATO / (1024 * 1024)), 2, ',', '.');
                $DES_BYTES = "mb";
                break;
            case 1:
                echo number_format(($DATO / 1024), 1, ',', '.') ;
                $DES_BYTES = "kb";
                break;
            default:
                echo number_format($DATO, 0, ',', '.');
                $DES_BYTES = "bytes";
        } // switch
    } else {
      
        $DES_BYTES = "";
    }
    ?>
   </td>

    ?>
   <Td> <?php echo $DES_BYTES;?> </Td>
  
   <?php
} //end function

Function COLUMN_DATE($NODE, $BGC, $FGC) {
/*
This function write the data in the column filedate of the dirtreeview table.
*/
    if (empty($BGC)) {
        $BGC = "ANTIQUEWHITE";
    }
    if (empty($FGC)) {
        $FGC = "RED";
    }
    ?>
        
   <?php
    if ($NODE != 0 ) {
        //file date
       if (is_long($_SESSION['File_Date'][$NODE]))
	   	echo (DATE("d/m/y H:i", $_SESSION['File_Date'][$NODE])) ;
    } 
    ?>
        </td>
</tr>
   <?php
} //end function

Function TABLEDIR_FOOTER($BGC, $FGC) {
/*
This function write the data in the footer of the dirtreeview table.
*/
    if (empty($BGC)) {
        $BGC = "MAROON";
    }
    if (empty($FGC)) {
        $FGC = "WHITE";
    }
    ?>
      
            <td colspan="14">
            This page has been created in <?php echo number_format($_SESSION['Total_Time'], 2, ',', '.');?> seconds</td>
        </tr>
  
    <?php
} //end function

Function PAGE_FOOTER($BGC, $FGC) {
//Modificado Guilda Ascencio
global $arrHttp;

/*
This function write the data in the footer of any web html page.
*/

    if (empty($BGC)) {
        $BGC = "BLUE";
    }
    if (empty($FGC)) {
        $FGC = "WHITE";
    }
//Modificado Guilda Ascencio
	echo "</div></div>\n";
	if (isset($arrHttp["encabezado"])) 
    include("../common/footer.php");
	echo "
    </body>
</html>";
} //end function

Function LOGIN($TEXTO) {
/*
This function build the form to capture the data for the user access.
*/
    PAGE_HEADER("DIRTREEVIEW", "LOGIN ACCESS PROCESS", "#006699", "WHITE");
    ?>

   <table widht="100%" border="0" cellpading="0" cellspacing="0" bgcolor="olive" class=td>
        <tr>
            <td valign="top" align="left">
                <center>
                <font face="tahoma">
                <h3><?php echo  $TEXTO ;?></h3>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" border="0" class=td>
                        <tr>
                            <td align="left" valign="baseline"><h3>Username :</h3></td>
                            <td align="left" valign="top">
                            <input type="text" name="username" size="50" value=""></td>
                        </tr>
                         <tr>
                            <td align="left" valign="baseline"><h3>Password :</h3></Td>
                            <td align="left" valign="top">
                            <input type="password" name="password" size="50" value=""></td>
                        </tr>
                        <tr>
                            <td>
                            <input type="Submit" name="login" value="LOGIN"></td>
                        </tr>
                    </table>
                </form>
                </font>
         
            </td>
        </tr>
   </table>
   <?php

    PAGE_FOOTER("#006699", "WHITE");
    exit;
} //end function
Function LOGOUT() {
/*
This function build the form to logout of the session "autentified".
*/

    PAGE_HEADER("LOGOUT - DIRTREEVIEW", "START A NEW SESSION", "", "");
    ?>
   <table widht="100%" border="0" cellpadign="0" cellspacing="0" class=td>
        <tr>
            <td valign="top" align="left">
                
                <font face="tahoma">
                <h2> ! IMPORTANT ¡</h2>
                <h3> The current session has been Cancelled </h3>
                <h3> Click on the LOGOUT button to CONTINUE and start a new session.</h3>
                <form name="LOGOUT" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <?php //SESSION_DESTROY();?>
                    <table align="center" class="td">
                         <tr></tr>
                        <tr>
                             <td><input type="Submit" name="LOGOUT" value="LOGOUT"></td>
                        </tr>
                    </table>
                </form>
                </font>
                
            </Td>
        </Tr>
   </table>
   <?php
    PAGE_FOOTER("", "");
    exit;
} //end function

Function INIT_DIR() {
/*
This function build the form to choose the root dir for the treeview process.
*/
    PAGE_HEADER("DIRTREEVIEW", "SET THE SERVER DIRECTORY TO USE", "#006699", "WHITE");
    ?>
   <table widht="100%" border="0" class="td">
        <tr>
            <td valign="top" align="left">
                <font face="tahoma">
                <h3> Set the name of the server Directory</h3>
                <form method="post" action="dirtree.php">
                <input type="hidden" name="base" value="<?php echo $_REQUEST["base"];?>">
                    <table align="center" class="td">
                        <tr>
                            <td align="left" valign="baseline"> <h3>Directory :</h3></td>
                            <td align="left" valign="top"> <input type="text" name="Server_Path" SIZE="50" 
                            value="$db_path."bases"></td>
                        </tr>
                        <tr>
                         <Td>
                         <input type="Submit" name="INIDIR" value="Accept"></td>
                        </tr>
                    </table>
                </form>
                </font>
            </td>
        </tr>
   </table>
   <?php
    PAGE_FOOTER("#006699", "WHITE");
    exit;
} //end function

Function INIT_VARIABLES() {
/*
This function if for initialitation of SUPERGLOBALS variables.
*/
    Unset($_SESSION['Numfile']); // variable number with the total number of NODEs in the structure
    Unset($_SESSION['Last_Node']); // variable number with the last NODE displayed
    Unset($_SESSION['Levels_Fixed_Path']); // variable number for the levels involved in the server path
    Unset($_SESSION['File_Extension']); // variable string for file filter criteria
    Unset($_SESSION['Total_Time']); // variable number for the totaltime benchmark
    Unset($_SESSION['MaxFileSize']); // variable number for the maximum file size
    Unset($_SESSION['Maxfoldersize']); // variable number for the maximum file folder
    Unset($_SESSION['Size_Bytes']);//variable number of maximum file size to upload
    Unset($_SESSION['Father']); // array of numbers for the parents of the every NODE
    Unset($_SESSION['Children_Files']); // array of numbers with the amount of children files of every folder NODE
    Unset($_SESSION['Children_Subdirs']); // array of numbers with the amount of children subdirs of every folder NODE
    Unset($_SESSION['Level_Tree']); // array of numbers with the level in the estructure of every NODE
    Unset($_SESSION['Last_Level_Node']); // array of numbers for the last NODE in every level
    Unset($_SESSION['Folder_name']); // array of strings for the name of every NODE
    Unset($_SESSION['Folder_type']); // array of strings for the type of every NODE
    Unset($_SESSION['Opened_Folder']); // array for status of the every NODE in the structure
    Unset($_SESSION['File_Date']); // array of last dates of every file NODE
    Unset($_SESSION['Numbytes']); // array of numbers for the filesize in bytes of every NODE
} //end function

Function SCREEN_RESOLUTION() {
global $arrHttp,$db_path;
/*
This function build a hidden web html page with javascript included to obtain
the display screen resolution in pixels.
*/
    if (isset($_GET['ACTION'])) {
      $ACTION=$_GET['ACTION'];
    }
    if (!isset($ACTION)) $ACTION="";
    if ((!isset($_SESSION['widht'])) && (!isset($_SESSION['Height'])) || ($ACTION=="refresh")) {
        if ($ACTION=="refresh"){
          unset($_SESSION['widht']);
          unset($_SESSION['Height']);
        }
        if ((isset($_GET['widht'])) && (isset($_GET['height']))) {
            // output the geometry variables
            // echo "screen widht is: ". $_GET['widht'] ."<br />\n";
            // echo "screen height is: ". $_GET['height'] ."<br />\n";
            // echo "Browser: ". $_SERVER['HTTP_USER_AGENT'] . "<br />";
            $_SESSION['widht'] = $_GET['widht'];
            $_SESSION['Height'] = $_GET['height'];
        } else {
            // pass the geometry variables
            // (preserve the original query string
            // -- post variables will need to handled differently)
            ?>
            <html>
            <tittle>client screen resolution</tittle>
            <head>
            <?php
            $encabezado="";
            if (isset($arrHttp["encabezado"])) $encabezado="&encabezado=s";
            echo "<script language='javascript'>";
            echo "location.href=\"${_SERVER['PHP_SELF']}?widht=\" + screen.widht + \"&height=\" + screen.height+ \"".$encabezado."\"";
            echo "</script>";
            ?>
            </head>
            </html>
            <?php
            exit;
        }
    }
} //end function

/*
 +------------------------------------------------------------------------+
 |                                                                        |
 |                   *****************************                        |
 |                   *  M A I N   P R O G R A M  *                        |
 |                   *****************************                        |
 |         see flow diagram at the beginning of this code list            |
 +------------------------------------------------------------------------+
*/
// ELIMINADO POR MI
//SESSION_START();

/*if (Isset($_POST['username'])) {
    // credentials must be checked the first time
    // can be developed a version with a MySql user database with permissions etc
    if ((($_POST['username'] == 'username') && ($_POST['password'] == 'userpassword')) || (($_POST['username'] == 'administrator') && ($_POST['password'] == 'adminpassword'))) {
        $_SESSION['autentified'] = true;
        $_SESSION['user'] = $_POST['username'];
        if ($_SESSION['user'] == 'username') {
//            $_SESSION['privileges'] = 'restricted';
			$_SESSION['privileges'] = 'all';
        } else {
            $_SESSION['privileges'] = 'all';
        }
    } else {
        LOGIN ("Username or Password incorrect, try it again...");
        exit;
    }
}
*/
//MODIFICADO POR MI
$_SESSION['privileges'] = 'all';
if (!isset($_SESSION['autentified'])) {
   LOGIN("Set the username and password to login");
    exit;
} else {
    SCREEN_RESOLUTION();
}
if (isset($_GET['ACTION'])) {
    $ACTION = $_GET['ACTION'];
} else {
    if (isset($_POST['ACTION'])) {
        $ACTION = $_POST['ACTION'];
    } else {
        $ACTION = "";
    }
}
//when $ACTION=="refresh" the function SCREEN_RESOLUTION must be executed to check the display resolution and after that the variable $ACTION is empty again
if (($ACTION == "") || ($ACTION == "filter2") || ($ACTION == "renamefile2") || ($ACTION == "renamedir2") || ($ACTION == "makedir2") || ($ACTION == "makeroot1") || ($ACTION == "upload3")) {
    // RELOAD THE DIR STRUCTURE AGAIN
    // ************************************************************************************************

	if (!isset($_SESSION['Server_Path'])) {
        if (!isset($_POST['Server_Path'])) {
            if ($_SESSION['privileges'] == 'all') {
                //administrator
                INIT_DIR();
                exit();
            } else {
                //normal user
                $_SESSION['Server_Path'] = dirname(realpath($_SERVER['SCRIPT_FILEname']));
            }
        } else {
            $_SESSION['Server_Path'] = $_POST['Server_Path'];
        }
    } else {
        if ($ACTION == "makeroot1") {
            if ($_POST['NODE'] != 0) {
                $I = $_POST['NODE'];
                $DIR_PATH = $_SESSION['Folder_name'][$I];
                while ($_SESSION['Father'][$I] != 0) {
                    $DIR_PATH = $_SESSION['Folder_name'][$_SESSION['Father'][$I]] . DIRECTORY_SEPARATOR . $DIR_PATH;
                    $I = $_SESSION['Folder_name'][$I];
                }
                $DIR_PATH = $_SESSION['Folder_name'][0] . DIRECTORY_SEPARATOR . $DIR_PATH;
            } else {
                $DIR_PATH = $_SESSION['Folder_name'][0];
            }
            $_SESSION['Server_Path'] = DIRname($_SESSION['Server_Path']) . DIRECTORY_SEPARATOR . $DIR_PATH;
        }
    }
    $_SESSION['Server_Path'] = STR_REPLACE (CHR(92), DIRECTORY_SEPARATOR, $_SESSION['Server_Path']); //slash unix/windows
    $_SESSION['Server_Path'] = STR_REPLACE (CHR(47), DIRECTORY_SEPARATOR, $_SESSION['Server_Path']);
    $_SESSION['Server_Path'] = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $_SESSION['Server_Path']);
    // ************************************************************************************************
    if (!IS_DIR($_SESSION['Server_Path'])) {
        PAGE_HEADER("DIRECTORY TREEVIEW", "ERROR BUSCANDO DIRECTORIO", "", "");
        ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
                    <table align="center" class="td">
                    
                        <tr>
                            <td><h3>
                            Server Path Directory (' <?php echo $_SESSION['Server_Path'];Unset($_SESSION['Server_Path']);?> ') not exist or not valid, please check </td>
                        </tr>
                        <tr>
                            <input type="hidden" name="ACTION" value="">
                            <td><input type="Submit" name="INIDIR" value="Accept"></td>
                        </tr>
                    </table>
                </form>
      <?php
        PAGE_FOOTER("", "");
       // SESSION_DESTROY();
        exit;
    }
    $_SESSION['autentified'] = "refresh"; // set the variable with some value to consecutive calls to dirtree program
    // set variables to measure the time for benchmarck
    // start program chronometer
    $MTIME = MICROTIME();
    $MTIME = EXPLODE(" ", $MTIME);
    $MTIME = $MTIME[1] + $MTIME[0];
    $STARTTIME = $MTIME;
    INIT_VARIABLES();
    // initial values for main variables
    $_SESSION['Numfile'] = -1;
    $_SESSION['Last_Node'] = 0;
    $_SESSION['Levels_Fixed_Path'] = 0;
    $_SESSION['Levels_Fixed_Path'] = SUBSTR_COUNT($_SESSION['Server_Path'], DIRECTORY_SEPARATOR); //level number of the server path
    if (($ACTION == "filter2") || ($ACTION == "makeroot1")) {
        $ACTION = "";
        $_SESSION['Node'] = 0;
    }
    // when come back again recursively from
    // the subroutines (filefilter y upload) with the POST method
    // or the links in the screen with the GET method
    if (isset($_POST['FILE_EXTENSION'])) {
        $_SESSION['File_Extension'] = STRTOUPPER($_POST['FILE_EXTENSION']);
    } else {
        if (isset($_GET['FILE_EXTENSION'])) {
            $_SESSION['File_Extension'] = STRTOUPPER($_GET['FILE_EXTENSION']);
        } else {
            $_SESSION['File_Extension'] = "";
        }
    }
    // procedure to reading the structure of the hard disc directory using DIR PHP command
    // (could be a DataBase Table using a Query in the next version todo list)
    BUILD_DIRECTORY_TREE($_SESSION['Server_Path'], 0);
    // initialization of php file system functions
    CLEARSTATCACHE();
    // procedure to calculate the total bytes of a folder
    // while checking the $_SESSION['File_Extension'] variable
    DIR_SIZES();
    //STORE_SUPERGLOBAL
    $_SESSION['Display_Size'] = 0; // 0=bytes, 1=kb, 2=mb
    //
} else {
    // set variables to measure the time for benchmarck
    // start program chronometer
    $MTIME = MICROTIME();
    $MTIME = EXPLODE(" ", $MTIME);
    $MTIME = $MTIME[1] + $MTIME[0];
    $STARTTIME = $MTIME;
    if (!IS_DIR($_SESSION['Server_Path'])) {
        Unset($_SESSION['Server_Path']);
        PAGE_HEADER("DIRECTORY TREEVIEW", "ERROR BUSCANDO DIRECTORIO", "", "");
        ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <table align="center" class="td">
                      
                        <tr>
                            <td>
                            <h3>Server Path Directory ("<?php echo $_SESSION['Server_Path'];?> ") not exist or not valid, please check </td>
                        </tr>
                        <tr>
                            <td>
                            <input type="Submit" name="INIDIR" value="Accept"></td>
                        </tr>
                    </table>
                </form>
      <?php
        PAGE_FOOTER("", "");
        exit;
    }
    if (isset($_POST['NODE'])) {
        $_SESSION['Last_Node'] = $_POST['NODE'];
        $_SESSION['Node'] = $_POST['NODE'];
    } else {
        if (isset($_GET['NODE'])) {
            $_SESSION['Last_Node'] = $_GET['NODE'];
            $_SESSION['Node'] = $_GET['NODE'];
        } else {
            $_SESSION['Last_Node'] = 0;
            $_SESSION['Node'] = 0;
        }
    }
}
/*
 =============================================================================
                                    Action CARROUSEL
 =============================================================================
*/
if ($ACTION!="downloadfile1"){
?>
<script>
function VerArchivo(Arch){
	msgwin=window.open("leerarchivotxt.php?archivo="+Arch,"","")
	msgwin.focus()
}
</script>
}
<?


switch ($ACTION) {
    case "expand"       : expand($_SESSION['Node']); break;
    case "expandall"    : for ($I = 1 ; ($I <= $_SESSION['Numfile']) ; $I++) {$_SESSION['Opened_Folder'][$I] = 1;} break;
    case "collapse"     : collapse($_SESSION['Node']); break;
    case "collapseall"  : for ($I = 1;$I <= $_SESSION['Numfile'];$I++) {$_SESSION['Opened_Folder'][$I] = 0;} break;
    case "downloadfile" : $ACTION = ""; DOWNLOAD($_SESSION['Node']); break;
    case "downloadfile1": $ACTION = ""; DOWNLOAD1($_SESSION['Node']); break;
    case "dirfunction"  : $ACTION = ""; DIR_FUNCTIONS($_SESSION['Node']); break; //select a Dir Function to process
    case "filefunction" : $ACTION = ""; FILE_FUNCTIONS($_SESSION['Node']); break; //select a File Function to process
    case "makedir"      : $ACTION = ""; MAKEDIR($_SESSION['Node']); break; //write the name of the new directory
    case "makedir1"     : $ACTION = ""; if (Isset($_POST['MAKE_DIR'])) {MAKEDIR1($_POST['MAKE_DIR']);} break; //to create the new directory
    case "makedir2"     : $ACTION = ""; EXPAND($_SESSION['Node']); break; // if the full process gone well
    case "renamedir"    : $ACTION = ""; REnameDIR($_SESSION['Node']); break; //write the name of the new directory
    case "renamedir1"   : $ACTION = ""; if (Isset($_POST['REname_DIR'])) {REnameDIR1($_POST['REname_DIR']);} break; //to rename the directory
    case "renamedir2"   : $ACTION = ""; EXPAND($_SESSION['Node']); break; // if the full process gone well
    case "removedir"    : $ACTION = ""; REMOVEDIR($_SESSION['Node']); break; // confirm the operation
    case "removedir1"   : $ACTION = ""; REMOVEDIR1(); break; // erase dir and subdirs
    case "renamefile"   : $ACTION = ""; REnameFILE($_SESSION['Node']); break; // write the new filename
    case "renamefile1"  : $ACTION = ""; if (Isset($_POST['REname_FILE'])) {REnameFILE1($_POST['REname_FILE']);} break; //to rename the file
    case "renamefile2"  : $ACTION = ""; EXPAND($_SESSION['Node']); break; // if the full process gone well
    case "upload"       : $ACTION = ""; $_SESSION['Size_Bytes'] = 15000000; UPLOAD($_SESSION['Node']); break; // first: choose the file to upload
    case "upload2"      : $ACTION = ""; $_SESSION['Size_Bytes'] = 15000000; UPLOAD2($_SESSION['Node']); break; // SECOND: CHECK the full process
    case "upload3"      : $ACTION = ""; EXPAND($_SESSION['Node']); break; // third : has been uploaded successfully
    case "filter"       : $ACTION = ""; FILTERFILE(); break; // choose the file filter extension criteria
    case "logout"       : $ACTION = ""; LOGOUT(); break; // to exit and start session with other user
    case "emailfile"    : $ACTION = ""; EMAILFILE($_SESSION['Node']); break;
    case "emailfile1"   : $ACTION = ""; EMAILFILE1($_SESSION['Node']); break;
    case "erasefile"    : $ACTION = ""; ERASEFILE($_SESSION['Node']); break;
    case "erasefile1"   : $ACTION = ""; ERASEFILE1($_SESSION['Node']); break;
    case "compressfile" : $ACTION = ""; COMPRESSFILE($_SESSION['Node']); break;
    case "compressfile1": $ACTION = ""; COMPRESSFILE1($_SESSION['Node']); break;
    case "makeroot"     : $ACTION = ""; CHANGE_ROOTDIR($_SESSION['Node']); break;
    case "listzipfile"  : $ACTION = ""; LIST_ZIP($_SESSION['Node']); break;
    case "size"         : $ACTION = ""; $_SESSION['Display_Size']++; if ($_SESSION['Display_Size'] > 4) {$_SESSION['Display_Size'] = 0;}  break;
    Default             : $_SESSION['Node'] = 0; $_SESSION['Opened_Folder'][0] = 1;
}

//BUILDING THE FINAL RESULT - THE WEB HTML PAGE
PAGEDIR_HEADER("DIRTREEVIEW", "DIRTREEVIEW - FILE MANAGER", "", ""); // finally create html code to build the page
TABLEDIR_HEADER("#cccccc", ""); // table above the treeview
TABLEDIR_SUBHEADER("", ""); // building the table to show the treeview
// following lines to display the first row only for the root
COLUMN_FOLDER(0, "", "");
COLUMN_FILES(0, "", "");
COLUMN_FILEnameS_ROOT("", "");
COLUMN_SIZE(0, "", "");
COLUMN_DATE(0, "", "");
DISPLAY_TREE(); // calling the function to show the dir treeview.
// finish the benchmark
$MTIME = MICROTIME();
$MTIME = EXPLODE(" ", $MTIME);
$MTIME = $MTIME[1] + $MTIME[0];
$ENDTIME = $MTIME;
$_SESSION['Total_Time'] = ($ENDTIME - $STARTTIME);
TABLEDIR_FOOTER("black", "yellow"); //table below the treeview
PAGE_FOOTER("", ""); //finish the web page
