<?php
$wxis_host = $_SERVER['HTTP_HOST'];
$wxis_action = "/cgi-bin/wxis.exe/bvs-lib/common/scripts/wxis/";
function wxis_document_post( $url, $content = "" )
{ 
	$content = str_replace("\\\"","\"",$content);
	$content = str_replace("\n","",$content);
	$content = str_replace("\r","",$content);
	$content = str_replace("\\\\","\\",$content);
	
	// Strip URL  
	$url_parts = parse_url($url);
	$host = $url_parts["host"];
	$port = ($url_parts["port"]) ? $url_parts["port"] : 80;
	$path = $url_parts["path"];
	$query = $url_parts["query"];
	if ( $content != "" )
	{
		$query .= "&content=" . urlencode($content);
	}
	$timeout = 10;
	$contentLength = strlen($query);
	
	// Generate the request header 
    $ReqHeader =  
      "POST $path HTTP/1.0\n". 
      "Host: $host\n". 
      "User-Agent: PostIt\n". 
      "Content-Type: application/x-www-form-urlencoded\n". 
      "Content-Length: $contentLength\n\n". 
      "$query\n"; 
		
	// Open the connection to the host 
	$fp = fsockopen($host, $port, $errno, $errstr, $timeout);
	
	fputs( $fp, $ReqHeader ); 
	if ($fp) {
		while (!feof($fp)){
			$result .= fgets($fp, 4096);
		}
	}
    
    return strstr($result,"<"); 
}

function wxis_url ( $IsisScript, $param )
{
	global $wxis_host;
	global $wxis_action;

	$request = "http://" . $wxis_host . $wxis_action . "?" . "IsisScript=" . $IsisScript;
	$param = str_replace("<parameters>", "", $param);
	$param = str_replace("</parameters>", "", $param);
	$param = str_replace(">", "=", $param);
	$paramSplited = split("<",$param);
	reset($paramSplited);
	while ( list($key, $value) = each($paramSplited) )
	{
		if ( trim($value) != "" && substr($value,0,1) != "/" )
		{
			$request .= "&" . $value;
		}
	}

	return $request;

}

function wxis_list ( $param )
{
	return wxis_document_post(wxis_url("list.xis",$param));
}

function wxis_search ( $param )
{
	return wxis_document_post(wxis_url("search.xis",$param));
}

function wxis_index ( $param )
{
	return wxis_document_post(wxis_url("index.xis",$param));
}

function wxis_edit ( $param )
{
	return wxis_document_post(wxis_url("edit.xis",$param));
}

function wxis_write ( $param, $content )
{
	return wxis_document_post(wxis_url("write.xis",$param),$content);
}

function wxis_delete ( $param )
{
	return wxis_document_post(wxis_url("delete.xis",$param));
}

function wxis_control ( $param )
{
	return wxis_document_post(wxis_url("control.xis",$param));
}
?>