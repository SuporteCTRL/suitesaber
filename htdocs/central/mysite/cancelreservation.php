<?php

  require_once ("../config.php");
  require_once('../nusoap/nusoap.php');

 session_start();
 $lang=$_SESSION["lang"];
 require_once ("../lang/mysite.php");
 require_once("../lang/lang.php");

 if (!isset($_SESSION["permiso"])) die;



      $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
      $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
      $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
      $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
      $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
      $client = new nusoap_client($empwebservicetranslocation, false,
          						$proxyhost, $proxyport, $proxyusername, $proxypassword);


      $waitId = new soapval('id','',$_REQUEST["waitid"]);
      $obs = new soapval('obs','',$_REQUEST["observations"]);
      $operatorId = new soapval('operatorId','','mySite');


      $param1 = array ( "name" => "operatorLocation" );
      $myparam1 = new soapval ('param','',$_REQUEST["library"],'http://kalio.net/empweb/engine/trans/v1',false,$param1);


      //$param1 = array ( "name" => "desktopOrWS" );
      //$myparam3 = new soapval ('param','','ws','http://kalio.net/empweb/engine/trans/v1',false,$param1);

      $myparams = new soapval ('params','',array($myparam1));
      $myextension = new soapval ('transactionExtras','',$myparams,'','');


      $params = array ($waitId,$obs,$operatorExtension,$myextension);

      //print_r($params);
      // Ac� obtengo los datos generales
      $result = $client->call('cancelWaitSingle', $params, 'http://kalio.net/empweb/engine/trans/v1' , '');

      //echo $client->request;

      //print_r($result);

      $myresult=$msgstr["failed_operation"];
      $legend="";
      $image="mysite/img/flag.png";
      $success = false;

      //print_r($result);

      for ($i=0;$i<sizeof($result['transactionResult']['processResult']);$i++)
      {
        if (sizeof($result['transactionResult']['processResult'][$i]['msg'])>0)
        {
           //Aca hay que internacionalizar
           //print_r($result['transactionResult']['processResult'][$i]['msg']['text'][1]);
           $intermedio = $result['transactionResult']['processResult'][$i]['msg']['text'];
           if (is_array($intermedio))
           {
             $auxi="";
             foreach ($intermedio as $mensaje)
             {
               if ($mensaje["!lang"]==$lang)
                 $legend=$mensaje["!"];
               else if ($mensaje["!lang"]=="en")
                 $auxi=$mensaje["!"];
             }

             if (strlen($legend)==0)
                $legend=$auxi;
           }
           else
            $legend=$intermedio;
        }


        if (sizeof($result['transactionResult']['processResult'][$i]['result'])>0)
        {
          //print_r($result['transactionResult']['processResult'][$i]['result']);
          //Aca esta el resultado definitivo de la transaccion
          $success=true;

        }
      }




      $resultbuff=serialize($result);
      include_once("legends.php");


      if ($success)
      {
            $myresult=$msgstr["success_operation"];
            if (strlen($legend)>0)
                $image="mysite/img/important.png";
            else
                $image="mysite/img/clean.png";

      }


      echo "<div><table><tr rowspan=2>";
      echo "<td><img src='".$image."' /></td>";
      echo "<td><h2>".utf8_encode($myresult)."</h2></td></tr><tr><td><h3>".utf8_encode($legend)."</h3></td>";
      echo "</tr></table></div>";


?>
