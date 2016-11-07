<?php
/**
 * Classe que defini os metodos e acoes para gerenciamento dos
 * Fasciculos de uma colecao
 *
 * @author domingos.teruel
 * @package defaultPackage
 */
class facicOperations extends facic {
	var $obMask = null;
	/**
	 * Array da Mascara usada no faciculo corrente
	 * @var array
	 */
	var $maskList = null;
	/**
	 * Id do Titulo (campo v30)
	 * em faciculo
	 * @var integer
	 */
	var $titleID = 0;
	var $lastFacicData = array();
	/**
	 * Variavel que defini se o volume
	 * Finito(0) ou Infinito(1)
	 * @var bool
	 */	
	var $volumeType = "";
	/**
	 * Variavel que defini se o numero e
	 * finito(0) ou Infinito(1)
	 * @var bool
	 */
	var $numberType = "";
	var $startYear = 0;
	var $endYear = 0;
	var $startNumber =0;
	var $endNumber = 0;
	var $startVolume = 0;
	var $endVolume = 0;
	var $titleSituation = "";
	var $currentMask = "";
	var $field920 = null;
	/**
	 * Recebera o registro com otodos os campos
	 * e valores para serem inseridos
	 * @var array
	 */
	var $newRecord = array();
	function __construct(){
		$this->registro = new Record();
		
		if(isset($_GET["title"]) && $_GET["title"] != "") {
			$this->setTitleId($_GET['title']);
		}else {
			$this->setTitleId(165);
		}

		$this->maskUsed();
		$this->dataTitle();

                if($this->isFiniteOrInfinte() == "IF") {
			$this->infiniteFinitePP();
		}elseif ($this->isFiniteOrInfinte() == "FI") {
			$this->finiteInfinitePP();
		}elseif ($this->isFiniteOrInfinte() == "FF") {
			$this->finiteFinitePP();
		}else {
			$this->infiniteInfinitePP();
		}
		if(isset($_GET['action']) || isset($_GET['edit'])) {
			$this->defineField920();
			$this->setNewRecord();
		}
	}
	function getDataRecordNew(){
		return $this->getNewRecord();		
	}
	/**
	 * Recovery the last facic record
	 * 
	 */
	function getLastFacic(){
		$data = $this->lastFacicData();		
		//$data = $this->searchRecord("TIT",$this->getTitleID());	
		$this->lastFacicData = $data;
	}
	function setTitleId($titleID) {
		$this->titleID = $titleID;
	}
	function getTitleID() {
		return $this->titleID;
	}
	function setVolumeType($volumeType) {
		$this->volumeType = $volumeType;
	}
	function setNumberType($numberType) {
		$this->numberType = $numberType;
	}
	function setStartYear($startYear) {
		$this->startYear = $startYear;
	}
	function setStartNumber($startNumber){
		$this->startNumber = $startNumber;
	}
	function setEndNumber($endNumber){
		$this->endNumber = $endNumber;
	}
	function setEndVolume($endVolume){
		$this->endVolume = $endVolume;
	}
	function setStartVolume($startVolume){
		$this->startVolume = $startVolume;
	}
	function setEndYear($endYear){
		$this->endYear = $endYear;
	}
	function setTitleSituation($titleSituation){
		$this->titleSituation = $titleSituation;
	}
	function getTitleSituation(){
		return $this->titleSituation;
	}
	/**
	 * Recovery the mask used at facic
	 *
	 */
	function maskUsed()
	{
		$this->getLastFacic();
		/**
		 * Se houverem fasciculos recuperar a mascara a partir do ultimo fasciculo
		 * Caso contrario, recuperar a mascara do usuario
		 */
		if($this->hasFacic()) {
			$data = $this->searchMask("NOM_",$this->lastFacicData[910]);
		}else {
			$data = $this->searchMask("NOM_",$_POST["field"]["maskname"]);
		}

		$this->maskList = $data[0];
		$this->setVolumeType($data[0][841][0]);
		$this->setNumberType($data[0][841][1]);
	}

	function dataTitle() {
		$data = $this->searchTitle("I",$this->getTitleID());

		$this->setStartYear($data[0][301]);
		$this->setEndYear($data[0][304]);
		$this->setStartVolume($data[0][302]);
		$this->setEndVolume($data[0][305]);
		$this->setStartNumber($data[0][303]);
		$this->setEndNumber($data[0][306]);
		$this->setTitleSituation($data[0][50]);
	}

	function searchTitle($field,$value)
	{
		global $BVS_CONF, $configurator, $isisBroker, $TITLE_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Title()."</database>\n";
		//Used when has search
		$xmlparameters .= "<search>$field=$value</search>\n";

		if(isset($_GET['startIndex']) && $_GET['startIndex'] > 0) {
			$xmlparameters .= "<from>{$_GET['startIndex']}</from>\n";
		}else{
			$xmlparameters .= "<from>1</from>\n";
		}
		if(isset($_GET["results"]) && $_GET["results"] != "") {
			$xmlparameters .= "<count>{$_GET['results']}</count>\n";
		}else{
			$xmlparameters .= "<count>1</count>\n";
		}

		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>On</reverse>\n";
		$xmlparameters .= "</parameters>\n";
		$rawxml = $isisBroker->search($xmlparameters);
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

		$recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);
			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;
			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");
		}

		return $recordList;
	}

	function searchMask($field,$value)
	{
		global $BVS_CONF, $configurator, $isisBroker, $MASK_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Mask()."</database>\n";
		//Used when has search
		/**
		$xmlparameters .= "<search>$field=$value</search>\n";

		if(isset($_GET['startIndex']) && $_GET['startIndex'] > 0) {
			$xmlparameters .= "<from>{$_GET['startIndex']}</from>\n";
		}else{
			$xmlparameters .= "<from>1</from>\n";
		}
		if(isset($_GET["results"]) && $_GET["results"] != "") {
			$xmlparameters .= "<count>{$_GET['results']}</count>\n";
		}else{
			$xmlparameters .= "<count>1</count>\n";
		} **/
		$xmlparameters .= "<from>$field=$value</from>\n";
		$xmlparameters .= "<count>1</count>\n";
		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>On</reverse>\n";
		$xmlparameters .= "</parameters>\n";
		$rawxml = $isisBroker->keyrange_mfnrange($xmlparameters);
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

		$recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);
			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;
			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");
		}
		return $recordList;
	}

function hasFacic(){
		global $BVS_CONF, $configurator, $isisBroker, $FACIC_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Facic()."</database>\n";
		//Used when has search
		$_ID = $this->getTitleID();
		$xmlparameters .= "<search>TIT=$_ID</search>\n";

		if(isset($_GET['startIndex']) && $_GET['startIndex'] > 0) {
			$xmlparameters .= "<from>{$_GET['startIndex']}</from>\n";
		}else{
			$xmlparameters .= "<from>1</from>\n";
		}
		if(isset($_GET["results"]) && $_GET["results"] != "") {
			$xmlparameters .= "<count>{$_GET['results']}</count>\n";
		}else{
			$xmlparameters .= "<count>1</count>\n";
		}

		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>On</reverse>\n";
		$xmlparameters .= "</parameters>\n";

		$rawxml = $isisBroker->search($xmlparameters);

		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

		$recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);

			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;

			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");
		}

		if($recordList[0][1002]) {
			return true;
		}else {
			return false;
		}
	}

	function listByExpression(){
		global $BVS_CONF, $configurator, $isisBroker, $FACIC_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Facic()."</database>\n";
		$xmlparameters .= "<search>Q4V1F+</search>\n";
		$xmlparameters .= "<count>1</count>\n";
		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>On</reverse>\n";
		$xmlparameters .= "</parameters>\n";
		$rawxml = $isisBroker->keyrange_mfnrange($xmlparameters);
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

        $recordList = null;
		$i=0;

        while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);

			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;

			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");
		}
		return $recordList;
	}
    
	function isFiniteOrInfinte() {
		if($this->volumeType == 0 && $this->numberType == 0) {
			return "II";
		}elseif ($this->volumeType == 1 && $this->numberType == 0){
			return  "FI";
		}elseif ($this->volumeType == 1 && $this->numberType == 1){
			return "FF";
		}else{
			return "IF";
		}
	}

	function isHalfRoundMaskVolume(){
		$maskVolume = $this->maskList[860];
		$control = false;
		
		if ($maskVolume[0] != $maskVolume[1]){
			$control=true;
		}
		return $control;
	}

	/**
	 * Se VOLUME e NUMERO presente em MASK
	 * Verificar se e a ultima ocorrencia de VOLUME/NUMERO em MASK
	 * 	Se for ultimo
	 * 		Retorna 1ª Ocorrencia de VOLUME em MASK
	 * 		Incrementando +1 em NUMERO e ANO
	 * 	Se nao for ultimo
	 * 		Retorna Proxima ocorrencia de VOLUME/NUMERO em MASK
	 * 		Mantem o ANO
	 * 
	 * Case Z10F/Q4FMP/M12FME
	 */
	function finiteFinitePP()
	{
		//Recuperamos os NUMEROS e VOLUMES definidos para a Mascara corrente do Fasciculo
		$maskVolume = $this->maskList[860];
		$maskNumber = $this->maskList[880];
		//Recuperamos o NUMERO e VOLUME correntes do Fasciculo
		$currentVolume = $this->lastFacicData[912];
		$currentNumber = $this->lastFacicData[913];
		//Verifica se o VOLUME e NUMERO corrente estao presente na MASCARA
		$hasVolume = (@in_array($currentVolume,$maskVolume)) ? true : false;
		$hasNumber = (@in_array($currentNumber,$maskNumber)) ? true: false;
		if($hasVolume && $hasNumber) {
			if($currentNumber == end($maskNumber) && $currentVolume == end($maskVolume)) {
				$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				$this->newRecord["volume"] = $maskVolume[0];
				$this->newRecord["number"] = $maskNumber[0];
			}else{
				$this->newRecord["year"] = (int) $this->lastFacicData[911];
				$this->newRecord["volume"] = $maskVolume[array_search($currentVolume,$maskVolume)+1];
				$this->newRecord["number"] = $maskNumber[array_search($currentNumber,$maskNumber)+1];
			}
		}else {
			$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
			$this->newRecord["volume"] = $currentVolume;
			$this->newRecord["number"] = $currentNumber;
		}
	}
	/**
	 * Se VOLUME e NUMERO presente em MASK
	 * Verificar se e a ultima ocorrencia de VOLUME/NUMERO em MASK
	 * 	Se for ultimo
	 * 		Retorna 1ª Ocorrencia de VOLUME em MASK
	 * 		Incrementando +1 em NUMERO e ANO
	 * 	Se nao for ultimo
	 * 		Retorna Proxima ocorrencia de VOLUME/NUMERO em MASK
	 * 		Mantem o ANO
	 * Se nao tiver NUMERO presente em MASK
	 * 	Verifica se o VOLUME e a ultima ocorrencia em MASK
	 * 		Se for ultimo
	 * 			Retorna a 1ª Ocorrencia em MASK do VOLUME
	 * 			Incrementa +1 em NUMERO
	 * 			Dividir NUMERO CORRENTE pelo ultima ocorrencia em MASK
	 * 				Se Resultado 0
	 * 					Incrementa +1 em ANO
	 * 				Se nao
	 * 					Matem o ANO
	 * 		Se nao for ultimo VOLUME em MASK
	 * 			Retornar proxima ocorrecia em MASK
	 * 			Incrementa +1 em NUMERO
	 * 			Matem ANO
	 * 
	 * Case Q4V1F
	 */
	function finiteInfinitePP()
	{
		//Recuperamos os NUMEROS e VOLUMES definidos para a Mascara corrente do Fasciculo
		$maskVolume = $this->maskList[860];
		$maskNumber = $this->maskList[880];
		//Recuperamos o NUMERO e VOLUME correntes do Fasciculo		
		$currentVolume = $this->lastFacicData[912];
		$currentNumber = $this->lastFacicData[913];		
		//Verifica se o VOLUME e NUMERO corrente estao presente na MASCARA
		$hasVolume = @(in_array($currentVolume,$maskVolume)) ? true : false;
		$hasNumber = @(in_array($currentNumber,$maskNumber)) ? true: false;
		//echo "$currentNumber => $currentVolume";
		if($hasVolume && $hasNumber) {
			if($currentNumber == end($maskNumber) && $currentVolume == end($maskVolume)) {
				$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				$this->newRecord["volume"] = $maskVolume[0];
				$this->newRecord["number"] = (int) $currentNumber + 1;
			}else{
				$this->newRecord["year"] = (int) $this->lastFacicData[911];
				$this->newRecord["volume"] = $maskVolume[array_search($currentVolume,$maskVolume)+1];
				$this->newRecord["number"] = $maskNumber[array_search($currentNumber,$maskNumber)+1];
			}
		}elseif ($hasVolume && !$hasNumber) {
			if($currentVolume == end($maskVolume)) {
				$this->newRecord["volume"] =  $maskVolume[0];
				$this->newRecord["number"] = (int) $currentNumber + 1;
				$_rest = ($currentNumber % end($maskNumber));
				if($_rest){
					$this->newRecord["year"] = (int) $this->lastFacicData[911];
				}else {
					$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				}
			}else {
				$this->newRecord["volume"] = $maskVolume[array_search($currentVolume,$maskVolume)+1];
				$this->newRecord["number"] = (int) $currentNumber + 1;
				$this->newRecord["year"] = (int) $this->lastFacicData[911];
			}
		}elseif (!$hasVolume && !$hasNumber) {
			$this->newRecord["volume"] = $maskVolume[0];
			$this->newRecord["number"] = (int) $currentNumber + 1;
			$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
		}else {
			$this->newRecord["volume"] = "";
			$this->newRecord["number"] = "";
			$this->newRecord["year"] = (int) $_GET["yearFacic"];
		}
	}
	
	/**
	 * Se VOLUME e NUMERO presente em MASK
	 * 	Verificar se e a ultima ocorrencia de VOLUME/NUMERO em MASK
	 * 	Se for ultimo
	 * 		Incrementando +1 em VOLUME, NUMERO e ANO
	 * 	Se nao for ultimo
	 * 		Retorna Proxima ocorrencia de VOLUME/NUMERO em MASK
	 * 		Mantem o ANO
	 * Se nao tiver VOLUME e NUMERO presente em MASK
	 * 	Incrementando +1 em NUMERO
	 *  Se o volume for constante  
	 *	  Divide NUMERO por ultima ocorrencia de NUMERO MASK
	 * 		Se resto = 0
	 *		 	Incrementando +1 em VOLUME
	 *			Divide VOLUME por ultima ocorrencia de VOLUME MASK
	 *		 		Se resto = 0
	 *		 			Incrementa +1 em ANO
	 * 				Se nao
	 * 					Matem o ANO
	 *		Se nao
	 * 			Matem o VOLUME
	 * 			Matem o ANO
	 * 	Se nao for constante
	 * 			Incrementa +1 em VOLUME
	 * 			Matem ANO
	 *			Divide VOLUME por ultima ocorrencia de VOLUME MASK
	 *		 		Se resto = 0
	 *		 			Incrementa +1 em ANO
	 * 				Se nao
	 * 					Matem o ANO
	 * 
	 * Case Q1V4F+/A1V1F+/B1V6F+
	 */
	function infiniteInfinitePP()
	{
		//Recuperamos os NUMEROS e VOLUMES definidos para a Mascara corrente do Fasciculo
		$maskVolume = $this->maskList[860];
		$maskNumber = $this->maskList[880];
		//Recuperamos o NUMERO e VOLUME correntes do Fasciculo
		$currentVolume = $this->lastFacicData[912];
		$currentNumber = $this->lastFacicData[913];
		//Verifica se o VOLUME e NUMERO corrente estao presente na MASCARA
		$hasVolume = (@in_array($currentVolume,$maskVolume)) ? true : false;
		$hasNumber = (@in_array($currentNumber,$maskNumber)) ? true: false;
		if($hasVolume && $hasNumber) {
			if($currentNumber == end($maskNumber) && $currentVolume == end($maskVolume)) {
				$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				$this->newRecord["volume"] = (int) $currentVolume + 1;
				$this->newRecord["number"] = (int) $currentNumber + 1;
			}else{
				if ($this->isHalfRoundMaskVolume()){
					$this->newRecord["year"] = (int) $this->lastFacicData[911];
					$this->newRecord["volume"] = (int) $maskVolume + 1;
					$this->newRecord["number"] = (int) $currentNumber + 1;
				}else{
					$this->newRecord["year"] = (int) $this->lastFacicData[911];
					$this->newRecord["volume"] = $currentVolume;
					$this->newRecord["number"] = (int) $currentNumber + 1;
				}
			}
		}else {
			$this->newRecord["number"] = (int) $currentNumber + 1;
			if ($this->isHalfRoundMaskVolume()){
			/*    Se o VOLUME MASK nao for constante */
				$this->newRecord["volume"] = (int) $currentVolume + 1;
				$_restVolume = @($currentVolume % end($maskVolume));
				if($_restVolume){
					/* resto == 0 */
					$this->newRecord["year"] = $this->lastFacicData[911];
				}else {
					/* resto != 0 */
					$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				}
			}else{
				/* Se o VOLUME MASK for constante */
				$_restNumber = @($currentNumber % end($maskNumber));
				if($_restNumber){
				/* resto == 0 */
					$this->newRecord["volume"] = $currentVolume;
					$this->newRecord["year"] = (int) $this->lastFacicData[911];
				}else {
				/* resto != 0 */
					$this->newRecord["volume"] = (int) $currentVolume + 1;
					$_restVolume = @($currentVolume % end($maskVolume));
					if($_restVolume){
					/* resto == 0 */
						$this->newRecord["year"] = (int) $this->lastFacicData[911];
					}else {
					/* resto != 0 */
						$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
					}
				}
			}
		}
	}
	/**
	 * Se o Volume/Numero FACIC e o mesmo em MASK
	 * 	Se for a ultima ocorrencia
	 * 		Retorna a primeira ocorrencia do Numero em MASK
	 * 		Incrementa +1 ao Volume e ao Ano
	 * 	se não
	 * 		Retorna o proximo Volume/Numero em MASK
	 * 		Mantem ANO de FACIC
	 * Se nao for o mesmo em MASK
	 * 	Se Numero FACIC for igual a ultima ocorrencia em MASK
	 * 		Retorna a primeira ocorrencia Numero em MASK
	 * 		Incrementa +1 ao Ano
	 * 		Dividir o Volume FACIC por Volume MASK
	 * 		Se resto igual a 0
	 * 			Incrementa +1 ao ANO
	 * 		Se nao
	 * 			Matem Ano
	 * 	Se nao for ultima ocorrencia
	 * 		Retorna proximo Numero de MASK
	 * 		Mantem Volume e Ano
	 * 		
	 * Case: Z15V3F
	 */

	function infiniteFinitePP() {
		$maskVolume = $this->maskList[860];
		$maskNumber = $this->maskList[880];

		$currentNumber = $this->lastFacicData[913];
		$currentVolume = $this->lastFacicData[912];
		/**
		/*Checa se presente Volume e Numero na Mask
		*/
		$hasVolume = (in_array($currentVolume,$maskVolume)) ? true : false;
		$hasNumber = (in_array($currentNumber,$maskNumber)) ? true: false;
		//Se presente o Volume e o Numero
		if($hasVolume && $hasNumber) {
			//Se for o ultimo volume/numero da mascara
			//echo "$currentNumber == " . end($maskNumber) ." ". $currentVolume ."==". end($maskVolume) . "\n";
			if($currentNumber == end($maskNumber) && $currentVolume == end($maskVolume)) {
				$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				$this->newRecord["volume"] = (int) $currentVolume + 1;
				$this->newRecord["number"] = $maskNumber[0];
			}elseif($currentNumber == end($maskNumber) && $currentVolume != end($maskVolume)) {
				$this->newRecord["year"] = $this->lastFacicData[911];
				$this->newRecord["volume"] = (int) $currentVolume +1;
				$this->newRecord["number"] = $maskNumber[array_search($currentNumber,$maskNumber)+1];
			}else {
				$this->newRecord["year"] = $this->lastFacicData[911];
				$this->newRecord["volume"] = $maskVolume[array_search($currentVolume,$maskVolume)+1];
				$this->newRecord["number"] = $maskNumber[array_search($currentNumber,$maskNumber)+1];
			}
		}elseif(!$hasVolume && $hasNumber) {
			if($currentNumber == end($maskNumber)) {
				$_res = ($currentVolume % end($maskVolume));
				if($_res) {
					$this->newRecord["year"] = (int) $this->lastFacicData[911];
				}else {
					$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				}
				$this->newRecord["volume"] = (int) $currentVolume + 1;
				$this->newRecord["number"] = $maskNumber[0];
			}elseif(!$hasVolume && !$hasNumber) {
				$this->newRecord["year"] = (int) $this->lastFacicData[911] + 1;
				$this->newRecord["volume"] = $currentVolume;
				$this->newRecord["number"] = $currentNumber;
			}else {
				$this->newRecord["year"] = $this->lastFacicData[911];
				$this->newRecord["volume"] = $currentVolume;
				$this->newRecord["number"] = $maskNumber[array_search($currentNumber,$maskNumber)+1];
			}
		}
	}
	function setNewRecord() {
		/**
		 * [10] => BR541.1
	    	[30] => 165
		    [910] => M2V6F
		    [911] => 1986
		    [912] => 89
		    [913] => 6
		    [914] => P
		    [915] => 1
		    [920] => 0000426000
		    [940] => 19940504
		    [941] => 19940504
		    		"database" => "1",
					"literatureType" => "5",
					"treatmentLevel" => "6",
					"centerCode" => "10",
					"titleCode" => "30",
					"notes" => "900",
					"codeNameMask" => "910",
					"year" => "911",
					"volume" => "912",
					"issue" => "913",
					"status" => "914",
					"quantity" => "915",
					"publicationType" => "916",
					"sequentialNumber" => "920",
					"creationDate" => "940",
					"changeDate" => "941",
					"documentalistCreation" => "950",
					"documentalistChange" => "951",
		 */
		
		$this->newRecord['database'] = "FACIC";
		$this->newRecord['literatureType'] = "S";
		$this->newRecord['treatmentLevel'] = "f";
		$this->newRecord['centerCode'] = $this->lastFacicData[10];
		$this->newRecord['titleCode'] = $this->lastFacicData[30];
		$this->newRecord['notes'] = $this->lastFacicData[900];
		$this->newRecord['codeNameMask'] = $this->lastFacicData[910];
		/**
		if($this->newRecord['year'] != "") {
			$this->newRecord['year'] = $this->lastFacicData[911];
		}
		$this->newRecord['issue'] = $this->lastFacicData[913];
		*/
		$this->newRecord['status'] = $this->lastFacicData[914];
		$this->newRecord['quantity'] = $this->lastFacicData[915];
		$this->newRecord['publicationType'] = $this->lastFacicData[916];
		$this->newRecord['sequentialNumber'] = $this->getField920();
		$this->newRecord['creationDate'] = $this->lastFacicData[940];
		$this->newRecord['changeDate'] = date("Ymd");
		$this->newRecord['documentalistCreation'] = $this->lastFacicData[950];
		$this->newRecord['documentalistChange'] = "ME";
		//print_r($this->newRecord);
	}
	function getNewRecord(){		
		//sort($this->newRecord);
		return $this->newRecord;
	}
	/**
	 * Verifica se numero ou volume de Fasciculo
	 * e ausente ou presente na mascara
	 * 
	 * Retorna verdadeiro para presente e falso para ausente
	 *
	 */
	function isPresentOrFault(){
		$maskVolume = $this->maskList[860];
		$maskNumber = $this->maskList[880];
		$faultVolume = false;
		$faultNumber = false;
		if(is_array($maskVolume)) {
			reset($maskVolume);
			while (list($key, $val) = each($maskVolume)) {
				if($val == "" || $val == 0) {
					$faultVolume = true;
				}
			}
		}
		if(is_array($maskNumber)) {
			reset($maskNumber);
			while (list($key, $val) = each($maskNumber)) {
				if($val == "" || $val == 0) {
					$faultNumber = true;
				}
			}
		}
	}
	/**
	 * Recuperar o ultimo faciculo
	 * setar o valor do campo 920
     * Setar a posicao do Faciculo na colecao
     * Se posicao for igual a ultima
	 *   Incrementar +1000
	 *   retornar novo valor
     * Caso contrario
	 *   Somar Anterior com corrente e dividir por 2
	 *   retornar resultado
	 *
	 */
	function setField920Value($currentValue,$previousValue,$isLastValue)
	{
		/**
		$currentValue = 3;
		$previousValue = 2;
		$isLastValue = false;
		*/
		$newValue = null;		
		
		if($isLastValue && $previousValue == "") {
			$newValue = (int) $currentValue + 1000;
		}else {
			$newValue = (((int) $currentValue + (int) $previousValue) / 2);			
		}		
		$newValue = (int) $newValue;
		if($newValue >= $previousValue || $newValue <= $currentValue) {
			$this->field920 = $newValue;			
		}else {
			user_error("Não compativel com a Mascara",E_USER_ERROR);
		}		
	}
    
	function defineField920() {
		$tempList = $this->sequenceNumber();
		sort($tempList);
		if(isset($_GET['facicCurrent']) && isset($_GET['facicPrevious'])) {
			$currentValue = $_GET['facicCurrent'];
			$previousValue = $_GET['facicPrevious'];
			if($_GET['facicPrevious'] == 0){
				$isLastValue = false;	
			}else {
				if($currentValue == $tempList[0]) {
					$isLastValue = false;
				}elseif ($currentValue == end($tempList)) {
					$isLastValue = true;
				}else {
					$isLastValue = false;
				}
			}
		}else {
			if(isset($_GET['facicCurrent'])) {
				$currentValue = $_GET['facicCurrent'];
				$previousValue = null;
				$isLastValue = true;
			}else {
				$currentValue = $this->lastFacicData[920];
				$previousValue = null;
				$isLastValue = true;
			}
		}			
		
		$this->setField920Value($currentValue,$previousValue,$isLastValue);
	}

	function getField920(){
		return $this->field920;
	}

	function sequenceNumber() {
		global $BVS_CONF, $configurator, $isisBroker, $FACIC_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Facic()."</database>\n";
		//Used when has search
		if(isset($_REQUEST["title"]) && $_REQUEST["title"] != "") {
			$xmlparameters .= "<search>TIT={$_REQUEST["title"]}</search>\n";
		}
		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>Off</reverse>\n";
		$xmlparameters .= "</parameters>\n";		

        if(isset($_REQUEST["title"]) && $_REQUEST["title"] != "") {
			$rawxml = $isisBroker->search($xmlparameters);
		}else{
			user_error("Pesquisa Invalida, Sem Título para pesquisar");
		}
		
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

        $recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);
			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;
			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");

		}
		$tempList = array();
		while (list($key,$val) = each($recordList)) {			
			$tempList[] = $recordList[$key][920];			
		}

		return $tempList;
	}
    
	function lastFacicData() {
		global $BVS_CONF, $configurator, $isisBroker, $FACIC_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Facic()."</database>\n";
		//Used when has search
		if(isset($_REQUEST["title"]) && $_REQUEST["title"] != "") {
			$xmlparameters .= "<from>{$_REQUEST["title"]}=</from>\n";
		}
		$xmlparameters .= "<fieldsort>920</fieldsort>\n";
		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>Off</reverse>\n";
		$xmlparameters .= "</parameters>\n";		

        if(isset($_REQUEST["title"]) && $_REQUEST["title"] != "") {
			$rawxml = $isisBroker->keyrange_mfnrange($xmlparameters);
		}else{
			user_error("Pesquisa Invalida, Sem Título para pesquisar");
		}
		
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");
		$recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);
			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;
			$tempRecord = array();

			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}

            $tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");

		}
		$tempList = array();
		$lastRecord = array();		
		while (list($key,$val) = each($recordList)) {
			if($recordList[$key][920] >= prev($recordList[$key][920])) {
				$lastRecord = $recordList[$key];
			}
		}
		return $lastRecord;
	}

	function previousField920($currentValue)
	{
		$tempList = $this->sequenceNumber();
		sort($tempList);
		//print_r($tempList);
		$previous = 0;
		foreach ($tempList as $key => $value) {
    		if($value == $currentValue) {
    			$previous = $tempList[$key-1];
    		}
		}
		
		return $previous;
	}

	function getIssues($titleId, $sort)
	{	
		$dataCollection = $this->setRecodsByTitle($titleId, 'all');
		if(is_array($dataCollection)) {
			reset($dataCollection);
			
			$data = array();
			while (list($key, $val) = each($dataCollection)) {

				$issue = new facicData();
				$issue->set_mfn($dataCollection[$key]["mfn"]);
				$issue->set_year($dataCollection[$key]["911"]);
				$issue->set_vol($dataCollection[$key]["912"]);
				$issue->set_num($dataCollection[$key]["913"]);
				$issue->set_type($dataCollection[$key]["916"]);
				$issue->set_status($dataCollection[$key]["914"]);
				$issue->setData('sequentialNumber', $dataCollection[$key]["920"]);
				
				$issue->set_qtd($dataCollection[$key]["915"]);
				$issue->set_mask($dataCollection[$key]["910"]);
				$issue->set_note(makeShorterText($dataCollection[$key]["900"],30));
				$issue->set_idmfn($dataCollection[$key]["mfn"]);

				$data[]=$issue;
			}
		}
		
		return $data;
	}
	function sortIssues($issues, $separateSpecials = true){
		if ($separateSpecials){
			usort($issues, "issue_cmp_spec");
		} else {
			usort($issues, "issue_cmp");
		}
		
		return $issues;
	}
	/*
	function getNextSeqNumber($lastSeqNumber){
		if (strlen($lastSeqNumber)==0)  $lastSeqNumber = 0;
		return $lastSeqNumber + 100;
	}
	
	function getFutureIssues($initialYear, $year, $vol, $num, $maskSample,$maskID, $lastSeqNumber){
		$today = date("Y");

		if ($vol == 'null' || !$vol){
			$vol = 1;
		}
		if ($num == 'null' || !$num){
			$num = 1;
		}
		if ($year == 'null' || !$year){
			$year = 1;
		}
		$vfreq = $maskSample[count($maskSample)-1][860];
		for ($i = $num; $i < count($maskSample); $i++){
			
			if ($maskSample[$i][860]!=" " && $maskSample[$i][880]!=" "){
				var_dump($i);
				$newIssue = new facicData();
				$newIssue->set_year($year);

				if ($maskSample[$i][860]!=" "){
					$newIssue->set_vol((($year - $initialYear) * $vfreq) + $maskSample[$i][860]);
				} else {
					$newIssue->set_vol("");
				}

				if ($maskSample[$i][880] == " "){
					$newIssue->set_num("");
				} else {
					$newIssue->set_num($maskSample[$i][880]);
				}
				
				$newIssue->set_mask($maskID);
				$newIssue->set_type("");
				$newIssue->set_status("P");
				$newIssue->set_qtd("1");
				$newIssue->set_note("");
				$newIssue->set_mfn("New");
				$newIssue->set_idmfn("New");
				$lastSeqNumber = $this->getNextSeqNumber($lastSeqNumber);

				//var_dump($lastSeqNumber);
				$newIssue->setData("sequentialNumber",$lastSeqNumber);

				$issues[] = $newIssue;
			}
		}	
		
		for ($y = ($year + 1); $y <= $today; $y++){
			for ($i = 0; $i < count($maskSample); $i++){
				$newIssue = new facicData();
				$newIssue->set_year($y);

				if ($maskSample[$i][860]!=" "){
					$newIssue->set_vol((($y - $initialYear) * $vfreq) + $maskSample[$i][860]);
				} else {
					$newIssue->set_vol("");
				}
				if ($maskSample[$i][880] == " "){
					$newIssue->set_num("");
				} else {
					$newIssue->set_num($maskSample[$i][880]);
				}
				$newIssue->set_mask($maskID);
				$newIssue->set_type("");
				$newIssue->set_status("P");
				$newIssue->set_qtd("1");
				$newIssue->set_note("");
				$newIssue->set_mfn("New");
				$newIssue->set_idmfn("New");
				$lastSeqNumber = $this->getNextSeqNumber($lastSeqNumber);
				//var_dump($lastSeqNumber);
				$newIssue->setData("sequentialNumber",$lastSeqNumber);
				$issues[] = $newIssue;
			}
		}	


			
		return $issues;
	}
	*/
	function getRealHolding($title) {
		global $BVS_CONF, $configurator, $isisBroker, $FACIC_TAG_NAME;

		$xmlparameters = "<parameters>\n";
		$xmlparameters .= "<database>".$configurator->getPath2Holdings()."</database>\n";
		$xmlparameters .= "<search>TIT=".$title."</search>\n";
		$xmlparameters .= "<gizmo>GIZMO_XML</gizmo>\n";
		$xmlparameters .= "<xml_header>yes</xml_header>\n";
		$xmlparameters .= "<reverse>Off</reverse>\n";
		$xmlparameters .= "</parameters>\n";		
		$rawxml = $isisBroker->search($xmlparameters);
		$posicion1 = strpos($rawxml,"<record");
		$posicion2 = strpos($rawxml,"</record>");

        $recordList = null;
		$i=0;
		while ($posicion1>0)
		{
			$elemento = substr($rawxml,$posicion1,$posicion2-$posicion1+9);
			$record = new Record();
			$record->unserializeFromString($elemento);
			$tempField = $record->campos;
			$tempRecord = array();
			while (list($key,$val) = each($tempField)) {
				if(array_key_exists($tempField[$key]->tag,$tempRecord)) {
					$varTemp = $tempRecord[$tempField[$key]->tag];
					$tempRecord[$tempField[$key]->tag] = array_merge($varTemp,$tempField[$key]->contenido);
				}else {
					$tempRecord += array($tempField[$key]->tag => $tempField[$key]->contenido);
				}
			}
			$tempRecord += array("mfn" => $record->getMfn());
			ksort($tempRecord);
			$recordList[] = $tempRecord;
			$tempRecord = null;
			$rawxml = substr($rawxml,$posicion2+1);
			$posicion1 = strpos($rawxml,"<record");
			$posicion2 = strpos($rawxml,"</record>");

		}
		while (list($key,$val) = each($recordList)) {			
			$r .= '<p/>'.$recordList[$key][970];			
		}

		return $r;
	}

}
function issue_cmp_spec($a, $b)
{
	$t_a = $a->get_type()!='' ? 'S' : 'R';
	$t_b = $b->get_type()!='' ? 'S' : 'R';
	if ($t_a.$a->getData('sequentialNumber') == $t_b.$b->getData('sequentialNumber')) {
		return 0;
	}
	return ($t_a.$a->getData('sequentialNumber') < $t_b.$b->getData('sequentialNumber')) ? -1 : 1;
}
function issue_cmp($a, $b)
{
	if ($a->getData('sequentialNumber') == $b->getData('sequentialNumber')) {
		return 0;
	}
	return ($a->getData('sequentialNumber') < $b->getData('sequentialNumber')) ? -1 : 1;
}


?>