<?php
include("functions.php");
$url=$_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$pathComponents = explode("/", trim($path, "/"));
$endPoint=$pathComponents[1];
switch($endPoint)
{
	case "create_session":
		$username=$_REQUEST['username'];
		$password=$_REQUEST['password'];
		include("createSession.php");
		break;
	case "close_session":
		$sid=$_REQUEST['sid'];
		include("closeSession.php");
		break;
	case "query_files":
		$sid=$_REQUEST['sid'];
		$uid=$_REQUEST['uid'];
		include("queryFiles.php");
		break;
	case "request_file":
		$sid=$_REQUEST['sid'];
		$uid=$_REQUEST['uid'];
		$fid=$_REQUEST['fid'];
		include("requestFile.php");
		break;
	default:
		header('Content-Type: application/json');
		header('HTTP/1.1 200 OK');
}