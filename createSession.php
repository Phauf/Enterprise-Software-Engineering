<?php
$page="createSession.php";
if ($username==NULL || $password==NULL)
{
	log_error($_SERVER['REMOTE_ADDR'],"NULL SID","Null credentioninformation",$_SERVER['REQUEST_URI'],$page);
	header('Content-Type: application/json');
	header('HTTP/1.1 200 OK');
	$output[]="Status: ERROR";
	$output[]="MSG: Username or password cannot be null";
	$output[]="Action: Please try again.";
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{
	$dblink=db_connect("cs4743");
	$now=date("Y-m-d H:i:s");
	$salt=microtime();
	$username=addslashes(strtolower($username));
	$sql="Select * from `users` where `username`='$username'";
	$rst=$dblink->query($sql) or
		log_error($_SERVER['REMOTE_ADDR'],"NULL SID","Could not execute query for $username/$password pair",$sql,$page);
	$info=$rst->fetch_array(MYSQLI_ASSOC);
	$dbSession=sha1($info['username'].info['password'].$salt);
}